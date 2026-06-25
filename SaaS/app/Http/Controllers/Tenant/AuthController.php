<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantUser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Tenant/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (auth('tenant')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = auth('tenant')->user();
            $user->update(['last_login_at' => now()]);
            return redirect()->intended(route('tenant.dashboard'));
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.']);
    }

    public function logout(Request $request)
    {
        auth('tenant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('tenant.login');
    }
}
