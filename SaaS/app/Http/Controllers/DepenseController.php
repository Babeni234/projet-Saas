<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DepenseCreatedMail;
use App\Mail\DepenseRejectedMail;
use App\Models\Tresorerie;

class DepenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $query = Depense::with(['typeDepense', 'agency'])
            ->where('company_profile_id', $user->company_profile_id)
            ->where('deleted', false)
            ->orderBy('date_depense', 'desc');

        if ($user->employee && $user->employee->agency_id) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'type_depense_id' => 'nullable|integer|exists:type_depenses,id',
            'categorie' => 'nullable|string|max:100',
        ]);

        $depense = new Depense($validated);
        $depense->company_profile_id = $user->company_profile_id;
        $depense->statut = 'En attente'; // Force default status to 'En attente'

        if ($user->employee && $user->employee->agency_id) {
            $depense->agency_id = $user->employee->agency_id;
        }

        $depense->save();

        // Send Email Alert on Creation
        $depense->load(['company.user', 'agency']);
        $companyEmail = $depense->company && $depense->company->user ? $depense->company->user->email : null;
        
        if ($depense->agency_id) {
            // Created by Agency: send to Agency & Company
            $agencyEmail = $depense->agency?->email;
            $this->sendMailSafe($agencyEmail, new DepenseCreatedMail($depense));
            $this->sendMailSafe($companyEmail, new DepenseCreatedMail($depense));
        } else {
            // Created by Company: send only to Company
            $this->sendMailSafe($companyEmail, new DepenseCreatedMail($depense));
        }

        return response()->json($depense->load(['typeDepense', 'agency']), 201);
    }

    public function update(Request $request, Depense $depense)
    {
        $user = Auth::user();
        if (!$user || $depense->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // General constraints: cannot update a processed expense
        if ($depense->statut === 'Payé' || $depense->statut === 'Annulé') {
            return response()->json(['error' => 'Impossible de modifier une dépense déjà traitée.'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'type_depense_id' => 'nullable|integer|exists:type_depenses,id',
            'categorie' => 'nullable|string|max:100',
            'statut' => 'nullable|string|in:Payé,En attente,Annulé',
        ]);

        if ($user->employee && $user->employee->agency_id !== null) {
            $validated['statut'] = 'En attente';
        } else {
            if (!isset($validated['statut'])) {
                $validated['statut'] = $depense->statut;
            }
        }

        $oldStatus = $depense->statut;
        $newStatus = $validated['statut'];

        $depense->update($validated);

        $this->handleStatusTransition($depense, $oldStatus, $newStatus, $request->input('message'));

        return response()->json($depense->load(['typeDepense', 'agency']));
    }

    public function updateStatus(Request $request, Depense $depense)
    {
        $user = Auth::user();
        if (!$user || $depense->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Only company can confirm/reject
        if ($user->employee && $user->employee->agency_id !== null) {
            return response()->json(['error' => 'Action non autorisée pour les agences.'], 403);
        }

        $validated = $request->validate([
            'statut' => 'required|string|in:Payé,En attente,Annulé',
            'message' => 'nullable|string',
        ]);

        $oldStatus = $depense->statut;
        $newStatus = $validated['statut'];

        $depense->update([
            'statut' => $newStatus
        ]);

        $this->handleStatusTransition($depense, $oldStatus, $newStatus, $validated['message'] ?? null);

        return response()->json($depense->load(['typeDepense', 'agency']));
    }

    public function destroy(Depense $depense)
    {
        $user = Auth::user();
        if (!$user || $depense->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $depense->update(['deleted' => true]);
        $depense->delete();

        // Sync with Tresorerie: delete any transaction entry
        Tresorerie::where('source_type', Depense::class)
            ->where('source_id', $depense->id)
            ->delete();

        return response()->json(['message' => 'Supprimé avec succès']);
    }

    private function handleStatusTransition(Depense $depense, string $oldStatus, string $newStatus, ?string $rejectionMessage = null)
    {
        if ($newStatus === 'Payé') {
            // Sync to Tresorerie (Outflow, negative amount)
            Tresorerie::enregistrer(
                $depense,
                -1 * (float)$depense->montant,
                "Dépense confirmée : {$depense->titre} (Réf : {$depense->reference})",
                $depense->date_depense ? $depense->date_depense->toDateString() : now()->toDateString()
            );
        } else {
            // Remove from Tresorerie if it was Payé previously or if transitioning to En attente/Annulé
            Tresorerie::where('source_type', Depense::class)
                ->where('source_id', $depense->id)
                ->delete();

            // Send rejection mail if transitioning to Annulé
            if ($newStatus === 'Annulé') {
                $depense->load(['company.user', 'agency']);
                $msg = $rejectionMessage ?: "La demande de dépense a été refusée.";
                if ($depense->agency_id) {
                    $agencyEmail = $depense->agency?->email;
                    if ($agencyEmail) {
                        $this->sendMailSafe($agencyEmail, new DepenseRejectedMail($depense, $msg));
                    }
                } else {
                    $companyEmail = $depense->company && $depense->company->user ? $depense->company->user->email : null;
                    if ($companyEmail) {
                        $this->sendMailSafe($companyEmail, new DepenseRejectedMail($depense, $msg));
                    }
                }
            }
        }
    }

    private function sendMailSafe($to, $mailable)
    {
        if (empty($to)) return;
        try {
            Mail::to($to)->send($mailable);
        } catch (\Exception $e) {
            \Log::error("Erreur d'envoi d'email à {$to} : " . $e->getMessage());
        }
    }
}

