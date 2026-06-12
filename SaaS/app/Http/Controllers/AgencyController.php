<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
     * Get eligible managers (employees with manage_agencies or * permission)
     */
    private function getEligibleManagers(?int $companyProfileId, ?int $currentAgencyId = null)
    {
        if ($companyProfileId === null) {
            return collect();
        }

        // Get IDs of users who are already chefs of other agencies
        $otherChefsQuery = Agency::where('company_profile_id', $companyProfileId)
            ->whereNotNull('chef_id');
        
        if ($currentAgencyId !== null) {
            $otherChefsQuery->where('id', '!=', $currentAgencyId);
        }
        
        $otherChefUserIds = $otherChefsQuery->pluck('chef_id')->toArray();

        return Employee::with(['user.role'])
            ->where('company_profile_id', $companyProfileId)
            ->whereHas('user.role', function ($query) {
                $query->where('slug', 'chef_agence');
            })
            ->whereHas('user', function ($query) use ($otherChefUserIds) {
                $query->whereNotIn('id', $otherChefUserIds);
            })
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->user->id, // Use user ID because chef_id references users.id
                    'name' => $employee->user->name,
                    'email' => $employee->user->email,
                    'phone' => $employee->phone ?? $employee->user->phone ?? '',
                ];
            });
    }

    /**
     * Apply agency scope filter for the logged-in user if they are an employee.
     */
    private function applyAgencyScope($query)
    {
        $user = auth()->user();
        if ($user->employee && $user->employee->agency_id !== null) {
            $query->where('id', $user->employee->agency_id);
        }
        return $query;
    }

    /**
     * Check if the logged-in user is authorized to access the specified agency.
     */
    private function checkAgencyAccess(Agency $agency)
    {
        $user = auth()->user();
        if ($agency->company_profile_id !== $user->company_profile_id) {
            abort(403);
        }
        if ($user->employee && $user->employee->agency_id !== null && $agency->id !== $user->employee->agency_id) {
            abort(403);
        }
    }

    /**
     * Display a listing of the agencies.
     */
    public function index(Request $request)
    {
        $companyProfileId = auth()->user()->company_profile_id;
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');
        $status = $request->query('status', '');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');

        $query = Agency::where('company_profile_id', $companyProfileId);
        $query = $this->applyAgencyScope($query);

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

        $statsQuery = Agency::where('company_profile_id', $companyProfileId);
        $statsQuery = $this->applyAgencyScope($statsQuery);

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->active()->count(),
            'inactive' => (clone $statsQuery)->inactive()->count(),
            'suspended' => (clone $statsQuery)->where('status', 'suspended')->count(),
            'total_employees' => (int) (clone $statsQuery)->sum('employee_count'),
            'needs_attention' => (clone $statsQuery)->where(function($q) {
                $q->whereNull('manager_name')
                  ->orWhere('manager_name', '')
                  ->orWhere('status', 'suspended');
            })->count(),
        ];

        $filters = [
            'search' => $search,
            'status' => $status,
            'per_page' => $perPage,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];

        $eligibleManagers = $this->getEligibleManagers($companyProfileId);

        if ($request->wantsJson() || $request->headers->get('Accept') === 'application/json') {
            return response()->json([
                'agencies' => $agencies,
                'filters' => $filters,
                'stats' => $stats,
                'eligibleManagers' => $eligibleManagers,
            ]);
        }

        return $this->enterpriseDashboard('immobilier/agencies', [
            'agencies' => $agencies,
            'filters' => $filters,
            'stats' => $stats,
            'eligibleManagers' => $eligibleManagers,
        ]);
    }

    /**
     * Show the form for creating a new agency.
     */
    public function create()
    {
        $companyProfileId = auth()->user()->company_profile_id;
        $eligibleManagers = $this->getEligibleManagers($companyProfileId);

        return $this->enterpriseDashboard('immobilier/agencies/create', [
            'agency' => null,
            'title' => 'Créer une nouvelle agence',
            'eligibleManagers' => $eligibleManagers,
        ]);
    }

    /**
     * Store a newly created agency in storage.
     */
    public function store(Request $request)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('agencies')->where('company_profile_id', $companyProfileId)
            ],
            'code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('agencies')->where('company_profile_id', $companyProfileId)
            ],
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
            'chef_id' => 'nullable|integer|exists:users,id',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'employee_count' => 'nullable|integer|min:0',
            'establishment_date' => 'nullable|date',
        ]);

        $validated['company_profile_id'] = $companyProfileId;

        if (empty($validated['code'])) {
            unset($validated['code']);
        }

        $agency = Agency::create($validated);

        if ($agency->chef_id) {
            $user = User::find($agency->chef_id);
            if ($user && $user->employee) {
                $user->employee->update(['agency_id' => $agency->id]);
            }
        }

        if ($request->wantsJson() || $request->headers->get('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Agence créée avec succès.',
                'agency' => $agency
            ], 201);
        }

        return redirect()->route('agencies.index')
            ->with('success', 'Agence créée avec succès.');
    }

    /**
     * Display the specified agency.
     */
    public function show(Agency $agency)
    {
        $this->checkAgencyAccess($agency);

        return $this->enterpriseDashboard("immobilier/agencies/{$agency->id}", [
            'agency' => $agency,
        ]);
    }

    /**
     * Show the form for editing the specified agency.
     */
    public function edit(Agency $agency)
    {
        $this->checkAgencyAccess($agency);
        $companyProfileId = auth()->user()->company_profile_id;

        $eligibleManagers = $this->getEligibleManagers($companyProfileId, $agency->id);

        return $this->enterpriseDashboard("immobilier/agencies/{$agency->id}/edit", [
            'agency' => $agency,
            'title' => 'Modifier l\'agence: ' . $agency->name,
            'eligibleManagers' => $eligibleManagers,
        ]);
    }

    /**
     * Update the specified agency in storage.
     */
    public function update(Request $request, Agency $agency)
    {
        $this->checkAgencyAccess($agency);
        $companyProfileId = auth()->user()->company_profile_id;

        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('agencies')->ignore($agency->id)->where('company_profile_id', $companyProfileId)
            ],
            'code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('agencies')->ignore($agency->id)->where('company_profile_id', $companyProfileId)
            ],
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
            'chef_id' => 'nullable|integer|exists:users,id',
            'manager_name' => 'nullable|string|max:255',
            'manager_email' => 'nullable|email|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
            'employee_count' => 'nullable|integer|min:0',
            'establishment_date' => 'nullable|date',
        ]);

        if (empty($validated['code'])) {
            unset($validated['code']);
        }

        $oldChefId = $agency->chef_id;
        $newChefId = $request->input('chef_id');

        $agency->update($validated);

        if ($oldChefId != $newChefId) {
            // Clear old chef's agency affectation
            if ($oldChefId) {
                $oldUser = User::find($oldChefId);
                if ($oldUser && $oldUser->employee) {
                    $oldUser->employee->update(['agency_id' => null]);
                }
            }

            // Set new chef's agency affectation
            if ($newChefId) {
                $newUser = User::find($newChefId);
                if ($newUser && $newUser->employee) {
                    $newUser->employee->update(['agency_id' => $agency->id]);
                }
            }
        }

        if ($request->wantsJson() || $request->headers->get('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Agence mise à jour avec succès.',
                'agency' => $agency
            ]);
        }

        return redirect()->route('agencies.index')
            ->with('success', 'Agence mise à jour avec succès.');
    }

    /**
     * Remove the specified agency from storage.
     */
    public function destroy(Request $request, Agency $agency)
    {
        $this->checkAgencyAccess($agency);

        $agency->delete();

        if ($request->wantsJson() || $request->headers->get('Accept') === 'application/json') {
            return response()->json([
                'success' => true,
                'message' => 'Agence supprimée avec succès.'
            ]);
        }

        return redirect()->route('agencies.index')
            ->with('success', 'Agence supprimée avec succès.');
    }

    /**
     * Get agencies statistics
     */
    public function getStatistics()
    {
        $companyProfileId = auth()->user()->company_profile_id;
        $query = Agency::where('company_profile_id', $companyProfileId);
        $query = $this->applyAgencyScope($query);

        return response()->json([
            'total' => (clone $query)->count(),
            'active' => (clone $query)->active()->count(),
            'inactive' => (clone $query)->inactive()->count(),
            'suspended' => (clone $query)->where('status', 'suspended')->count(),
            'total_employees' => (clone $query)->sum('employee_count'),
        ]);
    }

    /**
     * Get agencies for map view
     */
    public function getAgenciesForMap()
    {
        $companyProfileId = auth()->user()->company_profile_id;
        $query = Agency::where('company_profile_id', $companyProfileId);
        $query = $this->applyAgencyScope($query);

        return response()->json(
            $query->where('latitude', '!=', null)
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
        $companyProfileId = auth()->user()->company_profile_id;

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => [
                'integer',
                Rule::exists('agencies', 'id')->where('company_profile_id', $companyProfileId)
            ],
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user = auth()->user();
        if ($user->employee && $user->employee->agency_id !== null) {
            foreach ($validated['ids'] as $id) {
                if ($id !== $user->employee->agency_id) {
                    abort(403);
                }
            }
        }

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
        $companyProfileId = auth()->user()->company_profile_id;

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => [
                'integer',
                Rule::exists('agencies', 'id')->where('company_profile_id', $companyProfileId)
            ],
        ]);

        $user = auth()->user();
        if ($user->employee && $user->employee->agency_id !== null) {
            foreach ($validated['ids'] as $id) {
                if ($id !== $user->employee->agency_id) {
                    abort(403);
                }
            }
        }

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
        $companyProfileId = auth()->user()->company_profile_id;
        $query = Agency::where('company_profile_id', $companyProfileId);
        $query = $this->applyAgencyScope($query);
        $agencies = $query->get();

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
