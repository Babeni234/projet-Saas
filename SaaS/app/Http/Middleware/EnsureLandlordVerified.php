<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLandlordVerified
{
    protected string $testEmail = 'brevelnangue3@gmail.com';

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // L'utilisateur test a accès aux deux dashboards sans vérification
        if ($user->email === $this->testEmail) {
            return $next($request);
        }

        if ($user->landlord_verified) {
            return $next($request);
        }

        if ($user->landlord_verification_status === 'pending') {
            return redirect()->route('landlord.verification.pending');
        }

        return redirect()->route('landlord.verification.create')
            ->with('error', 'Vous devez être vérifié en tant que bailleur pour accéder à cette page.');
    }
}