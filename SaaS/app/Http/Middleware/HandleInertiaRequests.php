<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $agencies = [];
        $companyName = 'Property AI';
        $companyLogo = asset('icons/property-ai-logo.svg');
        $allPermissions = [];

        if ($user) {
            $user->load(['company', 'role', 'employee.agency', 'planRelation']);

            if ($user->company_profile_id) {
                $agencies = \App\Models\Agency::where('company_profile_id', $user->company_profile_id)->get();
            }

            $company = $user->company;
            if ($company) {
                if ($company->legal_name) {
                    $companyName = $company->legal_name;
                }
                if ($company->logo_path) {
                    $companyLogo = asset('storage/' . $company->logo_path);
                }
            }

            // Calcul des permissions fines
            $permissionKeys = [
                'locataires.view', 'locataires.create', 'locataires.edit', 'locataires.delete',
                'batiments.view', 'batiments.create', 'batiments.edit', 'batiments.delete',
                'logements.view', 'logements.create', 'logements.edit', 'logements.delete',
                'contrats.view', 'contrats.create', 'contrats.edit', 'contrats.delete',
                'factures.view', 'factures.create', 'factures.delete',
                'paiements.view', 'paiements.create',
                'depenses.view', 'depenses.create', 'depenses.delete',
                'entrees.view', 'entrees.create',
                'maintenance.view', 'maintenance.create', 'maintenance.edit', 'maintenance.delete',
                'employees.view', 'employees.create', 'employees.edit', 'employees.delete',
                'reports.view'
            ];
            foreach ($permissionKeys as $key) {
                $allPermissions[$key] = $user->hasPermission($key);
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? array_merge($user->toArray(), [
                    'all_permissions' => $allPermissions,
                    'employee' => $user->employee ? array_merge($user->employee->toArray(), [
                        'agency' => $user->employee->agency ? $user->employee->agency->toArray() : null
                    ]) : null,
                    'role' => $user->role ? $user->role->toArray() : null
                ]) : null,
            ],
            'branding' => [
                'name' => $companyName,
                'logo' => $companyLogo,
            ],
            'agencies' => $agencies,
            'vapidPublicKey' => env('VAPID_PUBLIC_KEY'),
        ];
    }
}

