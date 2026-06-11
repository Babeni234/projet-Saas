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
        if ($user) {
            $user->load(['company', 'role', 'employee.agency']);
            if ($user->company_profile_id) {
                $agencies = \App\Models\Agency::where('company_profile_id', $user->company_profile_id)->get();
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'immo' => [
                'particulierUrl' => route('immo.particulier', absolute: false),
                'bailleurUrl' => route('immo.bailleur', absolute: false),
            ],
            'agencies' => $agencies,
        ];
    }
}
