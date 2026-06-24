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
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
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

