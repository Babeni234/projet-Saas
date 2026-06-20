<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Mail\PartnerContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    /**
     * Send a contact email to a partner company.
     */
    public function contact(Request $request)
    {
        $validated = $request->validate([
            'partner_id' => 'required|exists:company_profiles,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $partner = CompanyProfile::with('user')->findOrFail($validated['partner_id']);

        $recipientEmail = $partner->user->email ?? null;

        if (!$recipientEmail) {
            return response()->json([
                'message' => "Cette entreprise n'a pas configuré d'adresse e-mail de réception."
            ], 422);
        }

        try {
            Mail::to($recipientEmail)->send(new PartnerContactMail(
                $validated['name'],
                $validated['email'],
                $validated['message'],
                $partner->legal_name
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Partner contact email failed to send: ' . $e->getMessage());
            return response()->json([
                'message' => "Une erreur est survenue lors de l'envoi de l'e-mail. Veuillez réessayer plus tard."
            ], 500);
        }

        return response()->json([
            'message' => 'Votre message a été transmis avec succès à ' . $partner->legal_name . ' !'
        ]);
    }

    /**
     * Send a general support inquiry.
     */
    public function generalContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        try {
            Mail::to('support@propertyai.com')->send(new PartnerContactMail(
                $validated['name'],
                $validated['email'],
                $validated['message'],
                'Property AI Support'
            ));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('General contact email failed: ' . $e->getMessage());
            return response()->json([
                'message' => "Une erreur est survenue lors de l'envoi de l'e-mail. Veuillez réessayer plus tard."
            ], 500);
        }

        return response()->json([
            'message' => 'Votre message a été transmis avec succès à l\'équipe de Property AI !'
        ]);
    }
}
