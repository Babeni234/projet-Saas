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

    /**
     * Display list of allowed countries.
     */
    public function countriesIndex()
    {
        $countries = \App\Models\Country::orderBy('name', 'asc')->get();
        return Inertia::render('SuperAdmin/Countries/Index', [
            'countries' => $countries,
        ]);
    }

    /**
     * Store a new country.
     */
    public function countriesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:2|unique:countries,code',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        \App\Models\Country::create($validated);

        return back()->with('success', 'Pays enregistré avec succès.');
    }

    /**
     * Display list of subscription plans.
     */
    public function plansIndex()
    {
        $plans = \App\Models\SubscriptionPlan::orderBy('created_at', 'desc')->get();
        return Inertia::render('SuperAdmin/Plans/Index', [
            'plans' => $plans,
        ]);
    }

    /**
     * Store a new subscription plan.
     */
    public function plansStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug',
            'account_type' => 'required|in:company,individual',
            'price' => 'required|numeric|min:0',
            'max_logements' => 'required|integer',
            'max_locataires' => 'required|integer',
            'max_employees' => 'required|integer',
            'max_agencies' => 'required|integer',
            'max_buildings' => 'required|integer',
            'has_ai' => 'required|boolean',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'popular' => 'boolean',
            'color' => 'nullable|string|max:255',
        ]);

        if (empty($validated['color'])) {
            $validated['color'] = 'from-indigo-500 to-indigo-650';
        }

        \App\Models\SubscriptionPlan::create($validated);

        return back()->with('success', 'Forfait créé avec succès.');
    }

    /**
     * Display form to create a new user account.
     */
    public function createAccount()
    {
        return Inertia::render('SuperAdmin/Accounts/Create');
    }

    /**
     * Create and store a new user account (Company or Individual) directly.
     */
    public function storeAccount(Request $request)
    {
        $accountType = $request->input('account_type', 'individual');

        $rules = [
            'account_type' => 'required|in:individual,company',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:\App\Models\User,email',
            'phone' => 'nullable|string|max:30',
        ];

        if ($accountType === 'company') {
            $rules = array_merge($rules, [
                'business_type' => 'required|in:real_estate,hotel',
                'legal_name' => 'required|string|max:255',
                'registration_number' => 'required|string|max:100',
                'tax_id' => 'required|string|max:100',
                'country' => 'required|string|size:2',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'legal_representative_name' => 'required|string|max:255',
                'legal_representative_id_number' => 'required|string|max:100',
                'company_logo' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:2048',
                'certificate_of_incorporation' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'tax_registration_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'representative_id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'proof_of_address' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            ]);
        }

        $validated = $request->validate($rules);

        // Generate temporary password
        $tempPassword = \Illuminate\Support\Str::random(12);

        $user = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $validated, $accountType, $tempPassword) {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($tempPassword),
                'account_type' => $accountType,
                'subscription_plan' => null, // Will select plan on first login
                'status' => 'active',
                'email_verified_at' => now(), // Auto verify email to bypass verification/2FA flow
            ]);

            if ($accountType !== 'company') {
                $profile = \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'business_type' => 'individual',
                    'legal_name' => $validated['name'],
                    'registration_number' => 'N/A',
                    'tax_id' => 'N/A',
                    'country' => 'CM',
                    'address' => 'N/A',
                    'city' => 'N/A',
                    'postal_code' => 'N/A',
                    'legal_representative_name' => $validated['name'],
                    'legal_representative_id_number' => 'N/A',
                    'phone' => $validated['phone'] ?? 'N/A',
                    'logo_path' => null,
                    'verification_status' => 'approved',
                ]);
            } else {
                $logoPath = null;
                if ($request->hasFile('company_logo')) {
                    $logoPath = $request->file('company_logo')->store('company-logos', 'local');
                }

                $profile = \App\Models\CompanyProfile::create([
                    'user_id' => $user->id,
                    'business_type' => $validated['business_type'],
                    'legal_name' => $validated['legal_name'],
                    'registration_number' => $validated['registration_number'],
                    'tax_id' => $validated['tax_id'],
                    'country' => strtoupper($validated['country']),
                    'address' => $validated['address'],
                    'city' => $validated['city'],
                    'postal_code' => $validated['postal_code'],
                    'legal_representative_name' => $validated['legal_representative_name'],
                    'legal_representative_id_number' => $validated['legal_representative_id_number'],
                    'phone' => $validated['phone'],
                    'logo_path' => $logoPath,
                    'verification_status' => 'approved', // Auto approved when created by super admin
                ]);
            }

            // Seed default roles for this new company
            $roles = [
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'synced' => 0,
                    'name' => 'Admin',
                    'slug' => 'admin',
                    'description' => 'Administrateur avec accès complet à toutes les fonctionnalités.',
                    'permissions' => json_encode(['*']),
                    'company_profile_id' => $profile->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'synced' => 0,
                    'name' => "Chef d'agence",
                    'slug' => 'chef_agence',
                    'description' => "Gestion et supervision d'une agence spécifique.",
                    'permissions' => json_encode(['manage_agencies', 'manage_properties', 'view_reports']),
                    'company_profile_id' => $profile->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'synced' => 0,
                    'name' => 'Gestionnaire',
                    'slug' => 'gestionnaire',
                    'description' => 'Gestion des opérations quotidiennes et des locations.',
                    'permissions' => json_encode(['manage_properties', 'view_reports']),
                    'company_profile_id' => $profile->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'synced' => 0,
                    'name' => 'Comptable',
                    'slug' => 'comptable',
                    'description' => 'Accès et gestion de la comptabilité et des factures.',
                    'permissions' => json_encode(['manage_accounting', 'view_reports']),
                    'company_profile_id' => $profile->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'synced' => 0,
                    'name' => 'Maintenancier',
                    'slug' => 'maintenancier',
                    'description' => 'Gestion des tickets et interventions de maintenance.',
                    'permissions' => json_encode(['manage_maintenance']),
                    'company_profile_id' => $profile->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'synced' => 0,
                    'name' => 'Employé simple',
                    'slug' => 'employer_simple',
                    'description' => 'Employé simple avec accès de base.',
                    'permissions' => json_encode(['basic_access']),
                    'company_profile_id' => $profile->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            \Illuminate\Support\Facades\DB::table('roles')->insert($roles);

            // Fetch newly created admin role to assign it
            $adminRole = \Illuminate\Support\Facades\DB::table('roles')
                ->where('company_profile_id', $profile->id)
                ->where('slug', 'admin')
                ->first();

            // Link user to company profile and assign admin role
            $user->update([
                'company_profile_id' => $profile->id,
                'role_id' => $adminRole ? $adminRole->id : null,
            ]);

            if ($accountType === 'company') {
                $documentTypes = [
                    'certificate_of_incorporation',
                    'tax_registration_document',
                    'representative_id_document',
                    'proof_of_address',
                ];

                foreach ($documentTypes as $documentType) {
                    if (! $request->hasFile($documentType)) {
                        continue;
                    }

                    $file = $request->file($documentType);
                    $path = $file->store("legal-documents/{$profile->id}", 'local');

                    \App\Models\CompanyLegalDocument::create([
                        'company_profile_id' => $profile->id,
                        'document_type' => $documentType,
                        'disk' => 'local',
                        'file_path' => $path,
                        'original_filename' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
                        'file_size' => $file->getSize(),
                        'ai_status' => 'approved', // Auto approved by Super Admin
                    ]);
                }
            }

            return $user;
        });

        // Send confirmation email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SuperAdminUserCreatedMail($user, $tempPassword));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SuperAdmin Account creation email failed: ' . $e->getMessage());
        }

        return redirect()->route('superadmin.accounts.create')
            ->with('success', 'Compte créé avec succès et e-mail envoyé.')
            ->with('temp_password', $tempPassword)
            ->with('created_email', $user->email)
            ->with('created_name', $user->name);
    }

    /**
     * Display Super Admin Profile page.
     */
    public function profile()
    {
        $currentAdmin = auth()->user();
        $admins = User::whereIn('account_type', ['Super Admin', 'super_admin', 'superadmin', 'Super ADMIN'])
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('SuperAdmin/Profile/Index', [
            'currentAdmin' => $currentAdmin,
            'admins' => $admins,
        ]);
    }

    /**
     * Update Super Admin credentials.
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Create a new Super Admin account.
     */
    public function storeSuperAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'account_type' => 'superadmin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Nouveau compte Super Admin créé avec succès.');
    }
}

