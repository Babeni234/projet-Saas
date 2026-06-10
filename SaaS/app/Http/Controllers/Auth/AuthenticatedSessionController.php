<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|\Inertia\Response
    {
        $request->ensureIsNotRateLimited();

        $credentials = $request->only('email', 'password');
        
        if (! Auth::validate($credentials)) {
            \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        \Illuminate\Support\Facades\RateLimiter::clear($request->throttleKey());

        $user = \App\Models\User::where('email', $request->email)->first();

        // Generate 6-digit code
        $code = sprintf("%06d", mt_rand(0, 999999));

        // Store 2fa info in session
        session([
            '2fa_user_id' => $user->id,
            '2fa_code' => $code,
            '2fa_expires_at' => now()->addMinutes(15),
            '2fa_remember' => $request->boolean('remember'),
        ]);

        // Log the generated 2FA code for easy developer/admin retrieval
        \Illuminate\Support\Facades\Log::info("Generated 2FA code for {$user->email}: {$code}");

        // Send email
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP 2FA send failed: ' . $e->getMessage());
        }

        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'requires2fa' => true,
            'email' => $user->email,
            'status' => 'Un code de vérification a été envoyé à votre adresse e-mail.',
        ]);
    }

    /**
     * Verify the 2FA code.
     */
    public function verify2fa(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('2fa_user_id');
        $storedCode = session('2fa_code');
        $expiresAt = session('2fa_expires_at');
        $remember = session('2fa_remember', false);

        if (!$userId || !$storedCode || !$expiresAt) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée. Veuillez vous reconnecter.']);
        }

        if (now()->gt(\Carbon\Carbon::parse($expiresAt))) {
            return back()->withErrors(['code' => 'Le code a expiré. Veuillez en demander un nouveau.']);
        }

        if (trim($request->code) !== trim($storedCode)) {
            return back()->withErrors(['code' => 'Le code de vérification est incorrect.']);
        }

        // Clear session
        session()->forget(['2fa_user_id', '2fa_code', '2fa_expires_at', '2fa_remember']);

        // Authenticate
        Auth::loginUsingId($userId, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Resend the 2FA code.
     */
    public function resend2fa(Request $request): RedirectResponse
    {
        $userId = session('2fa_user_id');
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée. Veuillez vous reconnecter.']);
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Utilisateur introuvable.']);
        }

        $code = sprintf("%06d", mt_rand(0, 999999));

        session([
            '2fa_code' => $code,
            '2fa_expires_at' => now()->addMinutes(15),
        ]);

        // Log the resent 2FA code
        \Illuminate\Support\Facades\Log::info("Resent 2FA code for {$user->email}: {$code}");

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($code));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('SMTP 2FA resend failed: ' . $e->getMessage());
        }

        return back()->with('status', 'Un nouveau code de vérification a été envoyé à votre adresse e-mail.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
