<?php

namespace App\Http\Controllers;

use App\Models\Proprietaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProprietaireController extends Controller
{
    /**
     * Retourne la liste des propriétaires de la compagnie connectée (JSON API).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $proprietaires = Proprietaire::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->orderBy('nom')
            ->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'uuid'          => $p->uuid,
                'type'          => $p->type,
                'nom'           => $p->nom,
                'prenom'        => $p->prenom,
                'raison_sociale'=> $p->raison_sociale,
                'nom_complet'   => $p->nom_complet,
                'initiales'     => $p->initiales,
                'email'         => $p->email,
                'telephone'     => $p->telephone,
                'ville'         => $p->ville,
                'pays'          => $p->pays,
                'statut'        => $p->statut,
                'type_contrat'  => $p->type_contrat,
                'commission_taux' => $p->commission_taux,
                'photo_path'    => $p->photo_path ? Storage::disk('public')->url($p->photo_path) : null,
            ]);

        return response()->json($proprietaires);
    }

    /**
     * Crée un nouveau propriétaire.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'type'                  => 'required|in:personne_physique,personne_morale',
            'nom'                   => 'required|string|max:255',
            'prenom'                => 'nullable|string|max:255',
            'raison_sociale'        => 'nullable|string|max:255',
            'siret'                 => 'nullable|string|max:100',
            'numero_contribuable'   => 'nullable|string|max:100',
            'piece_identite_type'   => 'nullable|string|max:100',
            'piece_identite_numero' => 'nullable|string|max:100',
            'email'                 => 'nullable|email|max:255',
            'telephone'             => 'nullable|string|max:50',
            'telephone_secondaire'  => 'nullable|string|max:50',
            'adresse'               => 'nullable|string',
            'ville'                 => 'nullable|string|max:100',
            'pays'                  => 'nullable|string|max:10',
            'code_postal'           => 'nullable|string|max:20',
            'banque'                => 'nullable|string|max:100',
            'rib'                   => 'nullable|string|max:50',
            'swift'                 => 'nullable|string|max:20',
            'statut'                => 'nullable|string|in:actif,inactif,suspendu',
            'date_debut_contrat'    => 'nullable|date',
            'date_fin_contrat'      => 'nullable|date|after_or_equal:date_debut_contrat',
            'commission_taux'       => 'nullable|numeric|min:0|max:100',
            'type_contrat'          => 'nullable|string|max:100',
            'notes'                 => 'nullable|string',
        ]);

        $proprietaire = Proprietaire::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($proprietaire->append(['nom_complet', 'initiales']), 201);
    }

    /**
     * Met à jour un propriétaire.
     */
    public function update(Request $request, Proprietaire $proprietaire)
    {
        $this->authorize_company($proprietaire);

        $validated = $request->validate([
            'type'                  => 'sometimes|in:personne_physique,personne_morale',
            'nom'                   => 'sometimes|required|string|max:255',
            'prenom'                => 'nullable|string|max:255',
            'raison_sociale'        => 'nullable|string|max:255',
            'siret'                 => 'nullable|string|max:100',
            'numero_contribuable'   => 'nullable|string|max:100',
            'piece_identite_type'   => 'nullable|string|max:100',
            'piece_identite_numero' => 'nullable|string|max:100',
            'email'                 => 'nullable|email|max:255',
            'telephone'             => 'nullable|string|max:50',
            'telephone_secondaire'  => 'nullable|string|max:50',
            'adresse'               => 'nullable|string',
            'ville'                 => 'nullable|string|max:100',
            'pays'                  => 'nullable|string|max:10',
            'code_postal'           => 'nullable|string|max:20',
            'banque'                => 'nullable|string|max:100',
            'rib'                   => 'nullable|string|max:50',
            'swift'                 => 'nullable|string|max:20',
            'statut'                => 'nullable|string|in:actif,inactif,suspendu',
            'date_debut_contrat'    => 'nullable|date',
            'date_fin_contrat'      => 'nullable|date',
            'commission_taux'       => 'nullable|numeric|min:0|max:100',
            'type_contrat'          => 'nullable|string|max:100',
            'notes'                 => 'nullable|string',
        ]);

        $proprietaire->update($validated);

        return response()->json($proprietaire->fresh()->append(['nom_complet', 'initiales']));
    }

    /**
     * Supprime (soft-delete) un propriétaire.
     */
    public function destroy(Proprietaire $proprietaire)
    {
        $this->authorize_company($proprietaire);

        $proprietaire->update(['deleted' => true]);
        $proprietaire->delete(); // Soft delete

        return response()->json(['message' => 'Propriétaire supprimé avec succès.']);
    }

    /**
     * Upload/remplace la photo de profil d'un propriétaire.
     */
    public function uploadPhoto(Request $request, Proprietaire $proprietaire)
    {
        $this->authorize_company($proprietaire);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5 MB max
        ]);

        // Supprime l'ancienne photo si elle existe
        if ($proprietaire->photo_path && Storage::disk('public')->exists($proprietaire->photo_path)) {
            Storage::disk('public')->delete($proprietaire->photo_path);
        }

        $path = $request->file('photo')->store('proprietaires/photos', 'public');
        $proprietaire->update(['photo_path' => $path]);

        return response()->json([
            'photo_url' => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Supprime la photo de profil.
     */
    public function deletePhoto(Proprietaire $proprietaire)
    {
        $this->authorize_company($proprietaire);

        if ($proprietaire->photo_path && Storage::disk('public')->exists($proprietaire->photo_path)) {
            Storage::disk('public')->delete($proprietaire->photo_path);
        }

        $proprietaire->update(['photo_path' => null]);

        return response()->json(['message' => 'Photo supprimée.']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function authorize_company(Proprietaire $proprietaire): void
    {
        $user = Auth::user();
        abort_if(
            $proprietaire->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
