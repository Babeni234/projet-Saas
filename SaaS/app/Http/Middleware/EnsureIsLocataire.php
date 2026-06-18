<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsLocataire
{
    /**
     * Handle an incoming request.
     * Bloque l'accès si l'utilisateur connecté n'est pas un locataire.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->account_type !== 'Locataire') {
            // Rediriger vers le bon espace selon le type de compte
            if ($user->employee && $user->employee->agency_id !== null) {
                return redirect()->route('agence.dashboard');
            }
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
