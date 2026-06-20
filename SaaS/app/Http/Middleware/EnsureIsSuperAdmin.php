<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsSuperAdmin
{
    /**
     * Handle an incoming request.
     * Bloque l'accès si l'utilisateur connecté n'est pas un super administrateur.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $type = strtolower(str_replace([' ', '_'], '', $user->account_type));

        if ($type !== 'superadmin') {
            // Redirect to appropriate space based on account type
            if ($user->employee && $user->employee->agency_id !== null) {
                return redirect()->route('agence.dashboard');
            }
            if ($user->account_type === 'Locataire') {
                return redirect()->route('locataire.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
