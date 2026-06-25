<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\IncidentComment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncidentController extends Controller
{
    public function index()
    {
        $tenant = auth('tenant')->user()->tenant;
        $incidents = $tenant->incidents()->with('property')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Tenant/Incidents/Index', ['incidents' => $incidents]);
    }

    public function create()
    {
        $tenantUser = auth('tenant')->user();
        $properties = $tenantUser->tenant->contracts()
            ->with('property')
            ->get()
            ->pluck('property')
            ->filter()
            ->unique('id')
            ->values();

        return Inertia::render('Tenant/Incidents/Create', ['properties' => $properties]);
    }

    public function store(Request $request)
    {
        $tenantUser = auth('tenant')->user();
        $tenant = $tenantUser->tenant;

        $data = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'urgency' => 'required|string',
        ]);

        $contract = $tenant->contracts()
            ->where('property_id', $data['property_id'])
            ->where('status', 'active')
            ->first();

        $data['contract_id'] = $contract?->id;
        $data['tenant_id'] = $tenant->id;
        $data['landlord_id'] = $tenant->user_id;
        $data['status'] = 'reported';

        Incident::create($data);

        return redirect()->route('tenant.incidents.index')
            ->with('success', 'Votre demande a été envoyée.');
    }

    public function show(Incident $incident)
    {
        $tenant = auth('tenant')->user()->tenant;
        if ($incident->tenant_id !== $tenant->id) abort(403);

        $incident->load(['property', 'comments.user']);
        return Inertia::render('Tenant/Incidents/Show', ['incident' => $incident]);
    }

    public function comment(Request $request, Incident $incident)
    {
        $tenant = auth('tenant')->user()->tenant;
        if ($incident->tenant_id !== $tenant->id) abort(403);

        $data = $request->validate(['content' => 'required|string']);
        IncidentComment::create([
            'incident_id' => $incident->id,
            'user_id' => auth('tenant')->user()->tenant->user_id,
            'content' => $data['content'],
        ]);

        return back()->with('success', 'Commentaire ajouté.');
    }
}
