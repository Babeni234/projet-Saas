<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Landlord/Tenants/Index', [
            'tenants' => $tenants,
        ]);
    }

    public function create()
    {
        return Inertia::render('Landlord/Tenants/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $data['user_id'] = auth()->id();

        Tenant::create($data);

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Locataire ajouté avec succès.');
    }

    public function show(Tenant $tenant)
    {
        if ($tenant->user_id !== auth()->id()) abort(403);
        return Inertia::render('Landlord/Tenants/Show', ['tenant' => $tenant]);
    }

    public function edit(Tenant $tenant)
    {
        if ($tenant->user_id !== auth()->id()) abort(403);
        return Inertia::render('Landlord/Tenants/Edit', ['tenant' => $tenant]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        if ($tenant->user_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        $tenant->update($data);

        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Locataire mis à jour.');
    }

    public function destroy(Tenant $tenant)
    {
        if ($tenant->user_id !== auth()->id()) abort(403);
        $tenant->delete();
        return redirect()->route('landlord.tenants.index')
            ->with('success', 'Locataire supprimé.');
    }
}
