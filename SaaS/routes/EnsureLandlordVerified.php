<?php

namespace Nangue\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureLandlordVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Assurez-vous que l'utilisateur est authentifié
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérification du statut. Si la colonne n'existe pas encore en DB, 
        // l'accès sera refusé et redirigé vers la vérification.
        // Note: Assurez-vous que la colonne 'is_landlord_verified' existe dans votre table users.
        if (!isset(Auth::user()->is_landlord_verified) || !Auth::user()->is_landlord_verified) {
            return redirect()->route('landlord.verification.create');
        }

        return $next($request);
    }
}
