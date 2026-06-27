<?php

namespace App\Http\Middleware;

use App\Models\ImmotokClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImmotokTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token d\'authentification manquant.'
            ], 401);
        }

        $client = ImmotokClient::where('api_token', $token)->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Token d\'authentification invalide.'
            ], 401);
        }

        $request->attributes->set('immotok_client', $client);

        return $next($request);
    }
}
