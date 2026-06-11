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
            ->with('batiments')
            ->orderBy('nom')
            ->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'uuid'          => $p->uuid,
                'type'          => $p->type,
                'nom'           => $p->nom,
                'prenom'        => $p->prenom,
                'raison_sociale'=> $p->raison_sociale,
                'siret'         => $p->siret,
                'numero_contribuable' => $p->numero_contribuable,
                'piece_identite_type' => $p->piece_identite_type,
                'piece_identite_numero' => $p->piece_identite_numero,
                'nom_complet'   => $p->nom_complet,
                'initiales'     => $p->initiales,
                'email'         => $p->email,
                'telephone'     => $p->telephone,
                'telephone_secondaire' => $p->telephone_secondaire,
                'adresse'       => $p->adresse,
                'ville'         => $p->ville,
                'pays'          => $p->pays,
                'code_postal'   => $p->code_postal,
                'banque'        => $p->banque,
                'rib'           => $p->rib,
                'swift'         => $p->swift,
                'statut'        => $p->statut,
                'type_contrat'  => $p->type_contrat,
                'commission_taux' => $p->commission_taux,
                'date_debut_contrat' => $p->date_debut_contrat?->toDateString(),
                'date_fin_contrat' => $p->date_fin_contrat?->toDateString(),
                'notes'         => $p->notes,
                'photo_path'    => $p->photo_path ? Storage::disk('public')->url($p->photo_path) : null,
                'batiments'     => $p->batiments->map(fn($b) => [
                    'id'           => $b->id,
                    'nom'          => $b->nom,
                    'ville'        => $b->ville,
                    'quartier'     => $b->quartier,
                    'adresse'      => $b->adresse,
                    'appartements' => $b->appartements,
                    'etages'       => $b->etages,
                    'statut'       => $b->statut,
                ]),
                'documents'     => $p->documents ? collect($p->documents)->map(fn($d) => [
                    'name' => $d['name'] ?? '',
                    'filename' => $d['filename'] ?? '',
                    'path' => $d['path'] ?? '',
                    'url' => isset($d['path']) ? Storage::disk('public')->url($d['path']) : null,
                ])->toArray() : [],
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

    /**
     * Upload un document légal pour le propriétaire.
     */
    public function uploadDocument(Request $request, Proprietaire $proprietaire)
    {
        $this->authorize_company($proprietaire);

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpeg,png,jpg,webp,doc,docx|max:10240', // 10 MB max
            'name'     => 'required|string|max:100', // e.g. "cni", "kbis", "statuts", "autre"
        ]);

        $file = $request->file('document');
        $filename = $file->getClientOriginalName();
        $path = $file->store('proprietaires/documents', 'public');

        $docs = $proprietaire->documents ?? [];
        $docs[] = [
            'name'     => $request->input('name'),
            'filename' => $filename,
            'path'     => $path,
        ];

        $proprietaire->update(['documents' => $docs]);

        $formattedDocs = collect($docs)->map(fn($d) => [
            'name'     => $d['name'] ?? '',
            'filename' => $d['filename'] ?? '',
            'path'     => $d['path'] ?? '',
            'url'      => isset($d['path']) ? Storage::disk('public')->url($d['path']) : null,
        ])->toArray();

        return response()->json([
            'message'   => 'Document importé avec succès.',
            'documents' => $formattedDocs,
        ]);
    }

    /**
     * Supprime un document légal du propriétaire.
     */
    public function deleteDocument(Request $request, Proprietaire $proprietaire, $index)
    {
        $this->authorize_company($proprietaire);

        $docs = $proprietaire->documents ?? [];
        $index = (int)$index;

        if (isset($docs[$index])) {
            $doc = $docs[$index];
            if (isset($doc['path']) && Storage::disk('public')->exists($doc['path'])) {
                Storage::disk('public')->delete($doc['path']);
            }
            array_splice($docs, $index, 1);
            $docs = array_values($docs);
            $proprietaire->update(['documents' => $docs]);
        }

        $formattedDocs = collect($docs)->map(fn($d) => [
            'name'     => $d['name'] ?? '',
            'filename' => $d['filename'] ?? '',
            'path'     => $d['path'] ?? '',
            'url'      => isset($d['path']) ? Storage::disk('public')->url($d['path']) : null,
        ])->toArray();

        return response()->json([
            'message'   => 'Document supprimé.',
            'documents' => $formattedDocs,
        ]);
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
