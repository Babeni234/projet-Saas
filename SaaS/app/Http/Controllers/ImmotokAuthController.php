<?php

namespace App\Http\Controllers;

use App\Models\ImmotokClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImmotokAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:immotok_clients,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client = ImmotokClient::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = Str::random(60);
        $client->api_token = $token;
        $client->save();

        return response()->json([
            'success' => true,
            'client' => $client,
            'token' => $token,
            'message' => 'Compte créé avec succès.'
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $client = ImmotokClient::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        $token = Str::random(60);
        $client->api_token = $token;
        $client->save();

        return response()->json([
            'success' => true,
            'client' => $client,
            'token' => $token,
            'message' => 'Connexion réussie.'
        ]);
    }

    public function logout(Request $request)
    {
        $client = $request->attributes->get('immotok_client');
        if ($client) {
            $client->api_token = null;
            $client->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ]);
    }

    public function me(Request $request)
    {
        $client = $request->attributes->get('immotok_client');
        if ($client) {
            return response()->json([
                'success' => true,
                'client' => $client
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Non authentifié.'
        ], 401);
    }
}
