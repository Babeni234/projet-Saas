<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Agency;
use App\Models\Locataire;
use App\Models\Logement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    /**
     * Display the Super Admin Dashboard with metrics and dynamic maps.
     */
    public function dashboard()
    {
        $stats = [
            'total_companies' => CompanyProfile::count(),
            'total_users' => User::whereNotIn('account_type', ['Super Admin', 'super_admin', 'superadmin', 'Super ADMIN'])->count(),
            'total_agencies' => Agency::count(),
            'total_tenants' => Locataire::count(),
            'total_logements' => Logement::count(),
        ];

        // Subscription plans distribution
        $plansDistribution = [
            'starter' => User::where('account_type', 'company')->where('subscription_plan', 'starter')->count(),
            'professional' => User::where('account_type', 'company')->where('subscription_plan', 'professional')->count(),
            'enterprise' => User::where('account_type', 'company')->where('subscription_plan', 'enterprise')->count(),
        ];

        // Recent companies
        $recentCompanies = CompanyProfile::with('user:id,name,email,subscription_plan')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'legal_name' => $company->legal_name,
                    'city' => $company->city,
                    'business_type' => $company->business_type,
                    'verification_status' => $company->verification_status,
                    'owner_email' => $company->user->email ?? '—',
                    'plan' => $company->user->subscription_plan ?? 'starter',
                    'created_at' => $company->created_at->format('d/m/Y'),
                ];
            });

        // Recent users
        $recentUsers = User::with('company:id,legal_name')
            ->whereNotIn('account_type', ['Super Admin', 'super_admin', 'superadmin', 'Super ADMIN'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'account_type' => $u->account_type,
                    'company_name' => $u->company->legal_name ?? 'Property AI',
                    'created_at' => $u->created_at->format('d/m/Y'),
                ];
            });

        // Geographic mapping data
        $geoData = CompanyProfile::select('city', \DB::raw('count(*) as count'))
            ->groupBy('city')
            ->get()
            ->map(function ($item) {
                // Approximate coordinates for seeded cities
                $coordinates = [
                    'paris' => ['lat' => 48.8566, 'lng' => 2.3522],
                    'lyon' => ['lat' => 45.7640, 'lng' => 4.8357],
                    'marseille' => ['lat' => 43.2965, 'lng' => 5.3698],
                    'cannes' => ['lat' => 43.5528, 'lng' => 7.0174],
                    'bordeaux' => ['lat' => 44.8378, 'lng' => -0.5792],
                ];
                $cityKey = strtolower($item->city);
                $coords = $coordinates[$cityKey] ?? ['lat' => 46.2276, 'lng' => 2.2137]; // fallback center of France

                return [
                    'city' => $item->city,
                    'count' => $item->count,
                    'lat' => $coords['lat'],
                    'lng' => $coords['lng'],
                ];
            });

        return Inertia::render('SuperAdmin/Dashboard', [
            'stats' => $stats,
            'plansDistribution' => $plansDistribution,
            'recentCompanies' => $recentCompanies,
            'recentUsers' => $recentUsers,
            'geoData' => $geoData,
        ]);
    }

    /**
     * Display list of all registered companies.
     */
    public function companies()
    {
        $companies = CompanyProfile::with('user:id,name,email,subscription_plan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($company) {
                return [
                    'id' => $company->id,
                    'legal_name' => $company->legal_name,
                    'business_type' => $company->business_type,
                    'city' => $company->city,
                    'country' => $company->country,
                    'address' => $company->address,
                    'phone' => $company->phone,
                    'logo_url' => $company->logo_path ? asset('storage/' . $company->logo_path) : null,
                    'verification_status' => $company->verification_status,
                    'owner' => [
                        'id' => $company->user->id ?? null,
                        'name' => $company->user->name ?? '—',
                        'email' => $company->user->email ?? '—',
                        'subscription_plan' => $company->user->subscription_plan ?? 'starter',
                    ],
                    'agencies_count' => Agency::where('company_profile_id', $company->id)->count(),
                    'logements_count' => Logement::where('company_profile_id', $company->id)->count(),
                    'created_at' => $company->created_at->format('d/m/Y H:i'),
                ];
            });

        return Inertia::render('SuperAdmin/Companies/Index', [
            'companies' => $companies,
        ]);
    }

    /**
     * Update verification status of a company profile.
     */
    public function verifyCompany(Request $request, CompanyProfile $company)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,suspended,rejected',
        ]);

        $company->update([
            'verification_status' => $validated['status'],
        ]);

        return back()->with('success', 'Statut de vérification mis à jour avec succès.');
    }

    /**
     * Update subscription plan tier for a company.
     */
    public function updateCompanyPlan(Request $request, CompanyProfile $company)
    {
        $validated = $request->validate([
            'plan' => 'required|in:starter,professional,enterprise',
        ]);

        $owner = $company->user;
        if ($owner) {
            $owner->update([
                'subscription_plan' => $validated['plan'],
            ]);
            return back()->with('success', "Abonnement de l'entreprise mis à jour.");
        }

        return back()->withErrors(['error' => 'Propriétaire introuvable pour cette entreprise.']);
    }

    /**
     * Display directory of all users in the system.
     */
    public function users()
    {
        $users = User::with('company:id,legal_name')
            ->whereNotIn('account_type', ['Super Admin', 'super_admin', 'superadmin', 'Super ADMIN'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'account_type' => $u->account_type,
                    'company_name' => $u->company->legal_name ?? 'Property AI',
                    'status' => $u->status ?? 'active',
                    'is_connected' => (bool)$u->is_connected,
                    'created_at' => $u->created_at->format('d/m/Y H:i'),
                ];
            });

        return Inertia::render('SuperAdmin/Users/Index', [
            'users' => $users,
        ]);
    }

    /**
     * Update user account status (e.g. block or activate).
     */
    public function updateUserStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended',
        ]);

        $user->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Statut de l'utilisateur mis à jour.");
    }

    /**
     * Safely delete a user account from the system.
     */
    public function deleteUser(User $user)
    {
        // Don't allow self-deletion
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }

        // Cascade delete safeguards can go here if needed.
        $user->delete();

        return back()->with('success', "L'utilisateur a été supprimé.");
    }
}
