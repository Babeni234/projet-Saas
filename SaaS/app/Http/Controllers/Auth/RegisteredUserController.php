<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CompanyLegalDocument;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    private const DOCUMENT_TYPES = [
        'certificate_of_incorporation',
        'tax_registration_document',
        'representative_id_document',
        'proof_of_address',
    ];

    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $accountType = $request->input('account_type', 'individual');

        $rules = [
            'account_type' => 'required|in:individual,company',
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($accountType === 'company') {
            $rules = array_merge($rules, [
                'business_type' => 'required|in:real_estate,hotel',
                'legal_name' => 'required|string|max:255',
                'registration_number' => 'required|string|max:100',
                'tax_id' => 'required|string|max:100',
                'country' => 'required|string|size:2',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'legal_representative_name' => 'required|string|max:255',
                'legal_representative_id_number' => 'required|string|max:100',
                'phone' => 'required|string|max:30',
                'certificate_of_incorporation' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'tax_registration_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'representative_id_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'proof_of_address' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'company_logo' => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:2048',
            ]);
        }

        $validated = $request->validate($rules);

        $user = DB::transaction(function () use ($request, $validated, $accountType) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'account_type' => $accountType,
            ]);

            if ($accountType !== 'company') {
                return $user;
            }

            $logoPath = null;
            if ($request->hasFile('company_logo')) {
                $logoPath = $request->file('company_logo')->store('company-logos', 'local');
            }

            $profile = CompanyProfile::create([
                'user_id' => $user->id,
                'business_type' => $validated['business_type'],
                'legal_name' => $validated['legal_name'],
                'registration_number' => $validated['registration_number'],
                'tax_id' => $validated['tax_id'],
                'country' => strtoupper($validated['country']),
                'address' => $validated['address'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
                'legal_representative_name' => $validated['legal_representative_name'],
                'legal_representative_id_number' => $validated['legal_representative_id_number'],
                'phone' => $validated['phone'],
                'logo_path' => $logoPath,
                'verification_status' => 'pending',
            ]);

            foreach (self::DOCUMENT_TYPES as $documentType) {
                if (! $request->hasFile($documentType)) {
                    continue;
                }

                $file = $request->file($documentType);
                $path = $file->store("legal-documents/{$profile->id}", 'local');

                CompanyLegalDocument::create([
                    'company_profile_id' => $profile->id,
                    'document_type' => $documentType,
                    'disk' => 'local',
                    'file_path' => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
                    'file_size' => $file->getSize(),
                    'ai_status' => 'pending',
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
