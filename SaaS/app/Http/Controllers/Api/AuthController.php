<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle mobile login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'required|string',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Debug logging
        \Log::info('Login attempt', [
            'email' => $request->email,
            'user_found' => $user !== null,
            'account_type' => $user ? $user->account_type : null,
        ]);

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Aucun utilisateur trouvé avec cet email.'],
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            \Log::warning('Password mismatch', ['email' => $request->email]);
            throw ValidationException::withMessages([
                'email' => ['Le mot de passe est incorrect.'],
            ]);
        }

        // Check if user is a locataire
        if ($user->account_type !== 'Locataire') {
            \Log::warning('Invalid account type', ['email' => $request->email, 'type' => $user->account_type]);
            throw ValidationException::withMessages([
                'email' => ['Seuls les locataires peuvent se connecter via l\'application mobile. Type de compte: ' . $user->account_type],
            ]);
        }

        // Delete old tokens for this device
        $user->tokens()->where('name', $request->device_name)->delete();

        // Create new token
        $token = $user->createToken($request->device_name)->plainTextToken;

        // Update user connection status
        $user->update(['is_connected' => true, 'last_login_at' => now()]);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'account_type' => $user->account_type,
            ],
        ]);
    }

    /**
     * Handle mobile logout request.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $request->user()->update(['is_connected' => false]);

        return response()->json(['message' => 'Déconnexion réussie.']);
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
