<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgencyController extends Controller
{
    /**
     * Render the enterprise dashboard shell with Vue Router navigation.
     */
    private function enterpriseDashboard(string $initialRoute, array $props = [])
    {
        return Inertia::render('entreprise/Dashboard/Dashboard', array_merge([
            'initialRoute' => $initialRoute,
        ], $props));
    }

    /**
     * Display a listing of the agencies.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');
        $status = $request->query('status', '');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Agency::query();

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Sort
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $agencies = $query->paginate($perPage);

        return $this->enterpriseDashboard('immobilier/agencies', [
            'agencies' => $agencies,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'per_page' => $perPage,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'stats' => [
                'total' => Agency::count(),
                'active' => Agency::active()->count(),
                'inactive' => Agency::inactive()->count(),
                'suspended' => Agency::where('status', 'suspended')->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new agency.
     */
    public function create()
    {
        return $this->enterpriseDashboard('immobilier/agencies/create', [
            'agency' => null,
            'title' => 'Créer une nouvelle agence',
        ]);
    }

    /**
     * Store a newly created agency in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies',
            'code' => 'required|string|max:50|unique:agencies',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'employee_count' => 'nullable|integer|min:0',
            'establishment_date' => 'nullable|date',
        ]);

        Agency::create($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agence créée avec succès.');
    }

    /**
     * Display the specified agency.
     */
    public function show(Agency $agency)
    {
        return $this->enterpriseDashboard("immobilier/agencies/{$agency->id}", [
            'agency' => $agency,
        ]);
    }

    /**
     * Show the form for editing the specified agency.
     */
    public function edit(Agency $agency)
    {
        return $this->enterpriseDashboard("immobilier/agencies/{$agency->id}/edit", [
            'agency' => $agency,
            'title' => 'Modifier l\'agence: ' . $agency->name,
        ]);
    }

    /**
     * Update the specified agency in storage.
     */
    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies,name,' . $agency->id,
            'code' => 'required|string|max:50|unique:agencies,code,' . $agency->id,
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'employee_count' => 'nullable|integer|min:0',
            'establishment_date' => 'nullable|date',
        ]);

        $agency->update($validated);

        return redirect()->route('agencies.index')
            ->with('success', 'Agence mise à jour avec succès.');
    }

    /**
     * Remove the specified agency from storage.
     */
    public function destroy(Agency $agency)
    {
        $agency->delete();

        return redirect()->route('agencies.index')
            ->with('success', 'Agence supprimée avec succès.');
    }

    /**
     * Get agencies statistics
     */
    public function getStatistics()
    {
        return response()->json([
            'total' => Agency::count(),
            'active' => Agency::active()->count(),
            'inactive' => Agency::inactive()->count(),
            'suspended' => Agency::where('status', 'suspended')->count(),
            'total_employees' => Agency::sum('employee_count'),
        ]);
    }

    /**
     * Get agencies for map view
     */
    public function getAgenciesForMap()
    {
        return response()->json(
            Agency::where('latitude', '!=', null)
                ->where('longitude', '!=', null)
                ->select('id', 'name', 'code', 'city', 'latitude', 'longitude', 'status')
                ->get()
        );
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:agencies,id',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        Agency::whereIn('id', $validated['ids'])
            ->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour avec succès pour ' . count($validated['ids']) . ' agence(s).'
        ]);
    }

    /**
     * Bulk delete agencies
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:agencies,id',
        ]);

        $count = Agency::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => $count . ' agence(s) supprimée(s) avec succès.'
        ]);
    }

    /**
     * Export agencies
     */
    public function export(Request $request)
    {
        $agencies = Agency::all();

        $csv = fopen('php://memory', 'r+');
        fputcsv($csv, ['ID', 'Nom', 'Code', 'Email', 'Téléphone', 'Ville', 'Pays', 'Statut', 'Employés', 'Créé le']);

        foreach ($agencies as $agency) {
            fputcsv($csv, [
                $agency->id,
                $agency->name,
                $agency->code,
                $agency->email,
                $agency->phone,
                $agency->city,
                $agency->country,
                $agency->status_label,
                $agency->employee_count,
                $agency->created_at->format('d/m/Y'),
            ]);
        }

        rewind($csv);
        $csv_content = stream_get_contents($csv);
        fclose($csv);

        return response($csv_content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="agencies_' . date('Ymd_His') . '.csv"',
        ]);
    }
}
