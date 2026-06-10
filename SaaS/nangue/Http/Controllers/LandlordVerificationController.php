<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class LandlordVerificationController extends Controller
{
    protected string $testEmail = 'brevelnangue3@gmail.com';

    public function create()
    {
        $user = Auth::user();

        // L'utilisateur test est automatiquement autorisé
        if ($user->email === $this->testEmail) {
            return redirect()->route('immo.bailleur');
        }

        if ($user->landlord_verified) {
            return redirect()->route('immo.bailleur');
        }

        if ($user->landlord_verification_status === 'pending') {
            return redirect()->route('landlord.verification.pending');
        }

        return Inertia::render('Nangue/Landlord/Verification', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_identity' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'document_property' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $paths = [];

        if ($request->hasFile('document_identity')) {
            $paths['document_identity'] = $request->file('document_identity')
                ->store('verifications/' . $user->id . '/identity', 'public');
        }

        if ($request->hasFile('document_property')) {
            $paths['document_property'] = $request->file('document_property')
                ->store('verifications/' . $user->id . '/property', 'public');
        }

        $user->update([
            'verification_documents' => $paths,
            'additional_info' => $request->additional_info,
            'landlord_verification_status' => 'pending',
            'landlord_verified' => false,
        ]);

        return redirect()->route('landlord.verification.pending')
            ->with('success', 'Vos documents ont été soumis pour vérification. Notre équipe les examinera sous peu.');
    }

    public function pending(): Response
    {
        return Inertia::render('Nangue/Landlord/VerificationPending', [
            'user' => Auth::user(),
        ]);
    }
}