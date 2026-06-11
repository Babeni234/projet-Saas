<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Agency;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyProfileId = auth()->user()->company_profile_id;
        $currentUser = auth()->user();

        // Fetch roles for this company and count users within this company
        $roles = Role::where('company_profile_id', $companyProfileId)
            ->withCount(['users' => function ($query) use ($companyProfileId) {
                $query->where('company_profile_id', $companyProfileId);
            }])
            ->get();
        
        // Active and inactive users for this company (only employees)
        $usersQuery = User::where('company_profile_id', $companyProfileId)
            ->whereIn('status', ['active', 'inactive'])
            ->whereHas('employee')
            ->with(['role', 'employee.agency']);

        // Scope to agency if logged-in user is an employee assigned to an agency
        if ($currentUser->employee && $currentUser->employee->agency_id !== null) {
            $usersQuery->whereHas('employee', function ($q) use ($currentUser) {
                $q->where('agency_id', $currentUser->employee->agency_id);
            });
        }
        $users = $usersQuery->get();
            
        // Pending request users for this company
        $pendingUsersQuery = User::where('company_profile_id', $companyProfileId)
            ->where('status', 'pending')
            ->with(['role', 'employee.agency']);

        if ($currentUser->employee && $currentUser->employee->agency_id !== null) {
            $pendingUsersQuery->whereHas('employee', function ($q) use ($currentUser) {
                $q->where('agency_id', $currentUser->employee->agency_id);
            });
        }
        $pendingUsers = $pendingUsersQuery->get();

        // Fetch company agencies
        $agenciesQuery = Agency::where('company_profile_id', $companyProfileId);
        if ($currentUser->employee && $currentUser->employee->agency_id !== null) {
            $agenciesQuery->where('id', $currentUser->employee->agency_id);
        }
        $agencies = $agenciesQuery->get();

        return Inertia::render('entreprise/Dashboard/Dashboard', [
            'initialRoute' => 'roles',
            'roles' => $roles,
            'users' => $users,
            'pendingUsers' => $pendingUsers,
            'agencies' => $agencies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($companyProfileId) {
                    return $query->where('company_profile_id', $companyProfileId);
                })
            ],
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $slug = Str::slug($validated['name']);
        
        // Ensure slug is unique for this company
        $originalSlug = $slug;
        $count = 1;
        while (Role::where('company_profile_id', $companyProfileId)->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Role::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? '',
            'permissions' => $validated['permissions'] ?? ['basic_access'],
            'company_profile_id' => $companyProfileId,
        ]);

        return redirect()->back()->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        if ($role->company_profile_id !== $companyProfileId) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id)->where(function ($query) use ($companyProfileId) {
                    return $query->where('company_profile_id', $companyProfileId);
                })
            ],
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role->update($validated);

        return redirect()->back()->with('success', 'Rôle mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        if ($role->company_profile_id !== $companyProfileId) {
            abort(403);
        }

        if ($role->slug === 'admin') {
            return redirect()->back()->with('error', 'Le rôle Admin ne peut pas être supprimé.');
        }

        // Set role_id to null for users of this role in this company
        User::where('company_profile_id', $companyProfileId)
            ->where('role_id', $role->id)
            ->update(['role_id' => null]);
        
        $role->delete();

        return redirect()->back()->with('success', 'Rôle supprimé avec succès.');
    }

    /**
     * Store a newly created employee (which is also a user).
     */
    public function storeEmployee(Request $request)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) use ($companyProfileId) {
                    return $query->where('company_profile_id', $companyProfileId);
                })
            ],
            'agency_id' => [
                'nullable',
                Rule::exists('agencies', 'id')->where(function ($query) use ($companyProfileId) {
                    return $query->where('company_profile_id', $companyProfileId);
                })
            ],
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        DB::transaction(function () use ($validated, $companyProfileId) {
            // 1. Create the user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'account_type' => 'individual',
                'subscription_plan' => 'starter',
                'role_id' => $validated['role_id'],
                'company_profile_id' => $companyProfileId,
                'status' => 'active',
            ]);

            // 2. Create the employee record
            Employee::create([
                'user_id' => $user->id,
                'company_profile_id' => $companyProfileId,
                'agency_id' => $validated['agency_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'position' => $validated['position'] ?? null,
            ]);
        });

        return redirect()->back()->with('success', 'Collaborateur créé avec succès.');
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        if ($user->company_profile_id !== $companyProfileId) {
            abort(403);
        }

        $validated = $request->validate([
            'role_id' => [
                'nullable',
                Rule::exists('roles', 'id')->where(function ($query) use ($companyProfileId) {
                    return $query->where('company_profile_id', $companyProfileId);
                })
            ],
        ]);

        $user->update([
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->back()->with('success', 'Rôle de l\'utilisateur mis à jour.');
    }

    /**
     * Update user status.
     */
    public function updateUserStatus(Request $request, User $user)
    {
        $companyProfileId = auth()->user()->company_profile_id;

        if ($user->company_profile_id !== $companyProfileId) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:active,inactive,pending',
        ]);

        $user->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Statut de l\'utilisateur mis à jour avec succès.');
    }
}
