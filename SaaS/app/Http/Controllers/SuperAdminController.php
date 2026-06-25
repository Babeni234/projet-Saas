<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Agency;
use App\Models\Locataire;
use App\Models\Logement;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    /**
     * Display the Super Admin Dashboard with metrics and dynamic maps.
     */
    /**
     * Get country name from country code
     */
    private function getCountryName($code)
    {
        $countries = [
            'FR' => 'France',
            'US' => 'États-Unis',
            'GB' => 'Royaume-Uni',
            'DE' => 'Allemagne',
            'ES' => 'Espagne',
            'IT' => 'Italie',
            'CA' => 'Canada',
            'CH' => 'Suisse',
            'BE' => 'Belgique',
            'NL' => 'Pays-Bas',
        ];
        return $countries[strtoupper($code)] ?? $code;
    }

    public function dashboard()
    {
        // Use single query with subqueries for better performance
        $stats = [
            'total_companies' => CompanyProfile::count(),
            'total_users' => User::whereNotIn('account_type', ['Super Admin', 'super_admin', 'superadmin', 'Super ADMIN'])->count(),
            'total_agencies' => Agency::count(),
            'total_tenants' => Locataire::count(),
            'total_logements' => Logement::count(),
        ];

        // Subscription plans from database grouped by account_type
        $subscriptionPlans = SubscriptionPlan::all()->groupBy('account_type');
        
        // Get user counts per plan
        $planCounts = User::select('subscription_plan', \DB::raw('count(*) as count'))
            ->whereNotNull('subscription_plan')
            ->groupBy('subscription_plan')
            ->pluck('count', 'subscription_plan')
            ->toArray();
        
        // Format plans with counts
        $formattedPlans = [
            'individual' => [],
            'company' => []
        ];
        
        foreach ($subscriptionPlans as $accountType => $plans) {
            foreach ($plans as $plan) {
                $formattedPlans[$accountType][] = [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'price' => $plan->price,
                    'account_type' => $plan->account_type,
                    'color' => $plan->color,
                    'popular' => $plan->popular,
                    'user_count' => $planCounts[$plan->slug] ?? 0,
                    'features' => $plan->features,
                ];
            }
        }

        // Recent companies - limit to 5 with eager loading
        $recentCompanies = CompanyProfile::with('user:id,name,email,subscription_plan')
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

        // Recent users - limit to 5 with eager loading
        $recentUsers = User::with('company:id,legal_name')
            ->whereNotIn('account_type', ['Super Admin', 'super_admin', 'superadmin', 'Super ADMIN'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

        // Geographic mapping data - grouped by country with companies
        $geoData = CompanyProfile::select('country', \DB::raw('count(*) as count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->get()
            ->map(function ($item) {
                // Get companies for this country
                $countryCompanies = CompanyProfile::where('country', $item->country)
                    ->with(['user:id,name,email,subscription_plan'])
                    ->withCount(['agencies', 'logements'])
                    ->get()
                    ->map(function ($company) {
                        return [
                            'id' => $company->id,
                            'legal_name' => $company->legal_name,
                            'city' => $company->city,
                            'country' => $company->country,
                            'verification_status' => $company->verification_status,
                            'owner' => [
                                'name' => $company->user->name ?? '—',
                                'email' => $company->user->email ?? '—',
                                'subscription_plan' => $company->user->subscription_plan ?? 'starter',
                            ],
                            'agencies_count' => $company->agencies_count ?? 0,
                            'employees_count' => $company->employees_count ?? 0,
                            'locataires_count' => $company->locataires_count ?? 0,
                        ];
                    });

                // Retrieve from database table via code
                $countryRecord = \App\Models\Country::where('code', strtoupper($item->country))->first();
                if ($countryRecord) {
                    $lat = (float) $countryRecord->latitude;
                    $lng = (float) $countryRecord->longitude;
                    $countryName = $countryRecord->name;
                } else {
                    // Approximate coordinates for countries fallback
                    $coordinates = [
                        'FR' => ['lat' => 46.2276, 'lng' => 2.2137, 'name' => 'France'],
                        'US' => ['lat' => 37.0902, 'lng' => -95.7129, 'name' => 'États-Unis'],
                        'GB' => ['lat' => 55.3781, 'lng' => -3.4360, 'name' => 'Royaume-Uni'],
                        'DE' => ['lat' => 51.1657, 'lng' => 10.4515, 'name' => 'Allemagne'],
                        'ES' => ['lat' => 40.4637, 'lng' => -3.7492, 'name' => 'Espagne'],
                        'IT' => ['lat' => 41.8719, 'lng' => 12.5674, 'name' => 'Italie'],
                        'CA' => ['lat' => 56.1304, 'lng' => -106.3468, 'name' => 'Canada'],
                        'CH' => ['lat' => 46.8182, 'lng' => 8.2275, 'name' => 'Suisse'],
                        'BE' => ['lat' => 50.5039, 'lng' => 4.4699, 'name' => 'Belgique'],
                        'NL' => ['lat' => 52.1326, 'lng' => 5.2913, 'name' => 'Pays-Bas'],
                    ];
                    $countryKey = strtoupper($item->country);
                    $fallback = $coordinates[$countryKey] ?? ['lat' => 46.2276, 'lng' => 2.2137, 'name' => $this->getCountryName($item->country)];
                    $lat = $fallback['lat'];
                    $lng = $fallback['lng'];
                    $countryName = $fallback['name'];
                }

                return [
                    'country' => $item->country,
                    'country_name' => $countryName,
                    'count' => $item->count,
                    'lat' => $lat,
                    'lng' => $lng,
                    'companies' => $countryCompanies,
                ];
            });

        return Inertia::render('SuperAdmin/Dashboard', [
            'stats' => $stats,
            'subscriptionPlans' => $formattedPlans,
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
        $companies = CompanyProfile::with(['user:id,name,email,subscription_plan'])
            ->withCount(['agencies', 'logements'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($company) {
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
                    'agencies_count' => $company->agencies_count ?? 0,
                    'logements_count' => $company->logements_count ?? 0,
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
            ->paginate(50)
            ->through(function ($u) {
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

    public function globalMap()
    {
        // Get statistics for the map
        $stats = [
            'active_users' => User::where('is_connected', true)->count(),
            'countries_count' => CompanyProfile::whereNotNull('country')->distinct('country')->count('country'),
            'companies_count' => CompanyProfile::count(),
        ];

        // Get user location (simulated - in production use IP geolocation)
        $userLocation = [
            'lat' => 48.8566, // Paris default
            'lng' => 2.3522,
        ];

        // Fetch real companies with coordinates and metrics
        $coordsCount = [];
        $companies = CompanyProfile::with(['user', 'agencies', 'employees', 'countryRelation', 'locataires'])
            ->get()
            ->map(function ($company) use (&$coordsCount) {
                $lat = $company->countryRelation->latitude ?? 48.8566;
                $lng = $company->countryRelation->longitude ?? 2.3522;
                
                // Coordinate key to count matches
                $key = round($lat, 2) . '_' . round($lng, 2);
                if (!isset($coordsCount[$key])) {
                    $coordsCount[$key] = 0;
                } else {
                    $coordsCount[$key]++;
                }
                
                // Apply a small random offset if coordinates overlap
                if ($coordsCount[$key] > 0) {
                    $lat += (mt_rand(-100, 100) / 100) * 0.8;
                    $lng += (mt_rand(-100, 100) / 100) * 0.8;
                }

                return [
                    'id' => $company->id,
                    'name' => $company->legal_name,
                    'promoter' => $company->legal_representative_name ?? ($company->user->name ?? '—'),
                    'logo' => $company->logo_path ? asset('storage/' . $company->logo_path) : null,
                    'address' => $company->address,
                    'city' => $company->city,
                    'country_name' => $company->countryRelation->name ?? $company->country,
                    'country_code' => $company->country,
                    'agencies_count' => $company->agencies->count(),
                    'employees_count' => $company->employees->count(),
                    'locataires_count' => $company->locataires->count(),
                    'latitude' => (float)$lat,
                    'longitude' => (float)$lng,
                ];
            });

        return Inertia::render('SuperAdmin/GlobalMap', [
            'stats' => $stats,
            'userLocation' => $userLocation,
            'companies' => $companies,
        ]);
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

    /**
     * Display transactions and trial settings panel.
     */
    public function transactionsIndex()
    {
        $transactions = \App\Models\Transaction::with(['user', 'company', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($t) {
                return [
                    'id' => $t->id,
                    'user_name' => $t->user->name ?? '—',
                    'user_email' => $t->user->email ?? '—',
                    'company_name' => $t->company->legal_name ?? '—',
                    'plan_name' => $t->plan->name ?? $t->plan_slug,
                    'amount' => $t->amount,
                    'billing_cycle' => $t->billing_cycle,
                    'payment_method' => $t->payment_method,
                    'payment_ref' => $t->payment_ref,
                    'phone_number' => $t->phone_number,
                    'status' => $t->status,
                    'created_at' => $t->created_at->format('d/m/Y H:i'),
                ];
            });

        $blockedFeatures = \App\Models\TrialSetting::getValue('blocked_features', ['hotel', 'accounting', 'maintenance']);
        $trialDurationDays = (int) \App\Models\TrialSetting::getValue('trial_duration_days', 14);

        return Inertia::render('SuperAdmin/PaymentControl', [
            'transactions' => $transactions,
            'blockedFeatures' => $blockedFeatures,
            'trialDurationDays' => $trialDurationDays,
        ]);
    }

    /**
     * Manually validate a pending transaction and activate user's subscription.
     */
    public function manualValidateTransaction(Request $request, \App\Models\Transaction $transaction)
    {
        if ($transaction->status === 'success') {
            return back()->with('success', 'Cette transaction est déjà validée.');
        }

        $user = $transaction->user;
        if (!$user) {
            return back()->withErrors(['error' => 'Utilisateur associé à la transaction introuvable.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $transaction) {
            // Update transaction status
            $transaction->status = 'success';
            $transaction->save();

            // Deactivate previous active subscriptions for this user
            \App\Models\UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'ends_at' => now(),
                ]);

            // Create new active subscription record
            \App\Models\UserSubscription::create([
                'user_id' => $user->id,
                'plan_slug' => $transaction->plan_slug,
                'price' => $transaction->amount,
                'starts_at' => now(),
                'ends_at' => $transaction->billing_cycle === 'yearly' ? now()->addYear() : now()->addMonth(),
                'status' => 'active',
            ]);

            // Update user subscription plan
            $user->subscription_plan = $transaction->plan_slug;
            $user->trial_ends_at = null; // Clear trial expiration if active
            $user->save();
        });

        return back()->with('success', 'La transaction a été validée manuellement et l\'abonnement activé.');
    }

    /**
     * Save trial setting (blocked modules).
     */
    public function saveTrialSettings(Request $request)
    {
        $validated = $request->validate([
            'blocked_features' => 'required|array',
            'trial_duration_days' => 'required|integer|min:1|max:365',
        ]);

        \App\Models\TrialSetting::updateOrCreate(
            ['key' => 'blocked_features'],
            ['value' => $validated['blocked_features']]
        );

        \App\Models\TrialSetting::updateOrCreate(
            ['key' => 'trial_duration_days'],
            ['value' => (int) $validated['trial_duration_days']]
        );

        return back()->with('success', 'Paramètres de la période d\'essai mis à jour avec succès.');
    }

    /**
     * Display the Finance management page with KPIs and year filtering.
     */
    public function financeIndex(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $users = \App\Models\User::whereNotNull('subscription_plan')
            ->whereIn('account_type', ['company', 'individual'])
            ->with(['company', 'planRelation', 'activeSubscription'])
            ->get()
            ->map(function ($u) use ($year) {
                // Find last successful transaction for billing cycle
                $lastTx = \App\Models\Transaction::where('user_id', $u->id)
                    ->where('status', 'success')
                    ->orderBy('created_at', 'desc')
                    ->first();

                $billingCycle = $lastTx ? $lastTx->billing_cycle : 'monthly';

                // Get plan prices
                $plan = $u->planRelation;
                $monthlyPrice = $plan ? $plan->price : 0.0;
                $yearlyPrice = $plan ? ($plan->price_yearly ?? ($monthlyPrice * 12 * 0.8)) : 0.0;

                $priceToPay = $billingCycle === 'yearly' ? $yearlyPrice : $monthlyPrice;
                $annualTarget = $billingCycle === 'yearly' ? $yearlyPrice : ($monthlyPrice * 12);

                // Amount paid this year
                $paidThisYear = \App\Models\Transaction::where('user_id', $u->id)
                    ->where('status', 'success')
                    ->whereYear('created_at', $year)
                    ->sum('amount');

                // Determine status and next due date
                $status = 'unpaid'; // trial, paid, unpaid
                $dueDate = null;
                $paidInSelectedYear = \App\Models\Transaction::where('user_id', $u->id)
                    ->where('status', 'success')
                    ->whereYear('created_at', $year)
                    ->exists();

                if ($year == date('Y')) {
                    if ($u->isTrialActive()) {
                        $status = 'trial';
                        $dueDate = $u->trial_ends_at;
                    } elseif ($u->hasActiveSubscription()) {
                        $status = 'paid';
                        $dueDate = $u->activeSubscription->ends_at;
                    } else {
                        $status = 'unpaid';
                        $dueDate = $u->trial_ends_at ?? $u->created_at;
                    }
                } else {
                    if ($paidInSelectedYear) {
                        $status = 'paid';
                        $lastTxInYear = \App\Models\Transaction::where('user_id', $u->id)
                            ->where('status', 'success')
                            ->whereYear('created_at', $year)
                            ->orderBy('created_at', 'desc')
                            ->first();
                        $dueDate = $lastTxInYear ? $lastTxInYear->created_at->addMonth() : null;
                    } else {
                        $status = 'unpaid';
                        $dueDate = null;
                    }
                }

                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'account_type' => $u->account_type,
                    'company_name' => $u->company->legal_name ?? '—',
                    'plan_name' => $plan ? $plan->name : 'starter',
                    'plan_slug' => $u->subscription_plan,
                    'price' => $priceToPay,
                    'billing_cycle' => $billingCycle,
                    'due_date' => $dueDate ? $dueDate->format('d/m/Y') : '—',
                    'status' => $status,
                    'is_blocked' => $u->isTrialExpired(),
                    'paid_this_year' => $paidThisYear,
                    'annual_target' => $annualTarget,
                ];
            });

        // Calculate KPIs
        $totalCollected = (double) \App\Models\Transaction::where('status', 'success')
            ->whereYear('created_at', $year)
            ->sum('amount');

        $totalToReceive = 0.0;
        foreach ($users as $u) {
            $outstanding = $u['annual_target'] - $u['paid_this_year'];
            if ($outstanding > 0) {
                $totalToReceive += $outstanding;
            }
        }

        // Available years
        $txYears = \App\Models\Transaction::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
        $availableYears = array_unique(array_merge([date('Y') - 1, date('Y'), date('Y') + 1], $txYears));
        sort($availableYears);

        return Inertia::render('SuperAdmin/Finance', [
            'users' => $users,
            'totalCollected' => $totalCollected,
            'totalToReceive' => $totalToReceive,
            'selectedYear' => (int) $year,
            'availableYears' => array_values($availableYears),
        ]);
    }

    /**
     * Record a manual payment for a user and renew/activate subscription.
     */
    public function recordUserPayment(Request $request, User $user)
    {
        $validated = $request->validate([
            'billing_cycle' => 'required|in:monthly,yearly',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $validated) {
            // Create successful transaction
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'company_profile_id' => $user->company_profile_id ?? 0,
                'plan_slug' => $user->subscription_plan ?? 'starter',
                'amount' => $validated['amount'],
                'billing_cycle' => $validated['billing_cycle'],
                'payment_method' => $validated['payment_method'],
                'payment_ref' => 'MANUAL_' . time() . '_' . rand(100, 999),
                'status' => 'success',
            ]);

            // Deactivate previous active subscriptions for this user
            \App\Models\UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'ends_at' => now(),
                ]);

            // Create new active subscription record
            \App\Models\UserSubscription::create([
                'user_id' => $user->id,
                'plan_slug' => $user->subscription_plan ?? 'starter',
                'price' => $validated['amount'],
                'starts_at' => now(),
                'ends_at' => $validated['billing_cycle'] === 'yearly' ? now()->addYear() : now()->addMonth(),
                'status' => 'active',
            ]);

            // Clear trial expiration
            $user->trial_ends_at = null;
            $user->save();
        });

        return back()->with('success', 'Le paiement a été enregistré avec succès et l\'accès réactivé.');
    }
}

