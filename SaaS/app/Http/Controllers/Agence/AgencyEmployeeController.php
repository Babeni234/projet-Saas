<?php

namespace App\Http\Controllers\Agence;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AgencyEmployeeController extends Controller
{
    /**
     * Get employees and roles within Espace Agence.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user->employee || $user->employee->agency_id === null) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $agencyId = $user->employee->agency_id;
        $companyProfileId = $user->company_profile_id;

        // Fetch company roles, excluding Admin and Chef d'agence
        $roles = Role::where('company_profile_id', $companyProfileId)
            ->whereNotIn('slug', ['admin', 'chef_agence'])
            ->get();

        // Fetch employees in this agency
        $employees = User::where('company_profile_id', $companyProfileId)
            ->whereHas('employee', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            })
            ->with(['role', 'employee.agency'])
            ->get();

        return response()->json([
            'roles' => $roles,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created agency employee.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->employee || $user->employee->agency_id === null) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $agencyId = $user->employee->agency_id;
        $companyProfileId = $user->company_profile_id;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) use ($companyProfileId) {
                    return $query->where('company_profile_id', $companyProfileId)
                                 ->whereNotIn('slug', ['admin', 'chef_agence']);
                })
            ],
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        DB::transaction(function () use ($validated, $companyProfileId, $agencyId) {
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

            Employee::create([
                'user_id' => $user->id,
                'company_profile_id' => $companyProfileId,
                'agency_id' => $agencyId,
                'phone' => $validated['phone'] ?? null,
                'position' => $validated['position'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Collaborateur créé avec succès pour votre agence.',
        ]);
    }
}
