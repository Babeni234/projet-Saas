<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\User;
use App\Models\Agency;
use App\Mail\LocataireCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocataireController extends Controller
{
    /**
     * Liste des locataires.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Locataire::where('company_profile_id', $companyProfileId)
                          ->where('deleted', false)
                          ->with(['user', 'agency', 'affectations.logement']);

        // Filtrage par agence si l'utilisateur connecté est un agent
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $locataires = $query->get()->map(fn($l) => $this->formatLocataire($l));

        return response()->json($locataires);
    }

    /**
     * Enregistre un nouveau locataire.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        $companyProfileId = $currentUser->company_profile_id;

        $request->validate([
            'nom'            => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'telephone'      => 'nullable|string|max:50',
            'agency_id'      => 'nullable|integer|exists:agencies,id',
            'profil'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'documentations'   => 'nullable|array|max:10',
            'documentations.*' => 'file|mimes:pdf,jpeg,png,jpg,webp,doc,docx|max:10240',
        ]);

        // Déterminer l'agence de rattachement
        $agencyId = $request->input('agency_id');
        $isAgent = $currentUser->employee && $currentUser->employee->agency_id !== null;
        if ($isAgent) {
            $agencyId = $currentUser->employee->agency_id;
        }

        // Valider que l'agence appartient à la compagnie
        if ($agencyId) {
            Agency::where('id', $agencyId)->where('company_profile_id', $companyProfileId)->firstOrFail();
        }

        // Générer mot de passe automatique
        $plainPassword = Str::random(10);

        $locataire = DB::transaction(function () use ($request, $companyProfileId, $agencyId, $plainPassword) {
            // 1. Créer le User
            $user = User::create([
                'name'               => $request->input('nom'),
                'email'              => $request->input('email'),
                'password'           => Hash::make($plainPassword),
                'account_type'       => 'Locataire',
                'status'             => 'inactive',
                'company_profile_id' => $companyProfileId,
            ]);

            // Gérer l'upload de l'image de profil
            $profilPath = null;
            if ($request->hasFile('profil')) {
                $profilPath = $request->file('profil')->store('locataires/profils', 'public');
            }

            // Gérer les documents joints
            $documents = [];
            if ($request->hasFile('documentations')) {
                foreach ($request->file('documentations') as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->store('locataires/documents', 'public');
                    $documents[] = [
                        'name'     => 'Justificatif',
                        'filename' => $filename,
                        'path'     => $path,
                    ];
                }
            }

            // 2. Créer le Locataire
            $loc = Locataire::create([
                'user_id'            => $user->id,
                'company_profile_id' => $companyProfileId,
                'agency_id'          => $agencyId,
                'telephone'          => $request->input('telephone'),
                'profil'             => $profilPath,
                'documentations'     => $documents,
                'statut'             => 'inactif', // inactif par défaut
            ]);

            return $loc;
        });

        // Envoyer le mot de passe par mail
        try {
            Mail::to($locataire->user->email)->send(new LocataireCreatedMail($locataire, $plainPassword));
        } catch (\Exception $e) {
            // Logger l'erreur sans bloquer la réponse de succès
            logger()->error("Erreur lors de l'envoi de mail de création locataire : " . $e->getMessage());
        }

        $locataire->load(['user', 'agency', 'affectations.logement']);

        return response()->json($this->formatLocataire($locataire), 201);
    }

    /**
     * Met à jour un locataire.
     */
    public function update(Request $request, Locataire $locataire)
    {
        $this->authorizeCompany($locataire);
        $currentUser = Auth::user();

        $request->validate([
            'nom'            => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $locataire->user_id,
            'telephone'      => 'nullable|string|max:50',
            'agency_id'      => 'nullable|integer|exists:agencies,id',
            'profil'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'documentations'   => 'nullable|array|max:10',
            'documentations.*' => 'file|mimes:pdf,jpeg,png,jpg,webp,doc,docx|max:10240',
        ]);

        $agencyId = $request->input('agency_id');
        $isAgent = $currentUser->employee && $currentUser->employee->agency_id !== null;
        if ($isAgent) {
            $agencyId = $currentUser->employee->agency_id;
        }

        if ($agencyId) {
            Agency::where('id', $agencyId)->where('company_profile_id', $locataire->company_profile_id)->firstOrFail();
        }

        DB::transaction(function () use ($request, $locataire, $agencyId) {
            // Mettre à jour l'utilisateur
            $locataire->user->update([
                'name'  => $request->input('nom'),
                'email' => $request->input('email'),
            ]);

            // Mettre à jour le profil de l'utilisateur
            if ($request->hasFile('profil')) {
                if ($locataire->profil && Storage::disk('public')->exists($locataire->profil)) {
                    Storage::disk('public')->delete($locataire->profil);
                }
                $locataire->profil = $request->file('profil')->store('locataires/profils', 'public');
            }

            // Mettre à jour les documents (les nouveaux s'ajoutent à la liste)
            $documents = $locataire->documentations ?? [];
            if ($request->hasFile('documentations')) {
                foreach ($request->file('documentations') as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->store('locataires/documents', 'public');
                    $documents[] = [
                        'name'     => 'Justificatif',
                        'filename' => $filename,
                        'path'     => $path,
                    ];
                }
            }

            $locataire->update([
                'telephone'      => $request->input('telephone'),
                'agency_id'      => $agencyId,
                'documentations' => $documents,
            ]);
        });

        $locataire->load(['user', 'agency', 'affectations.logement']);

        return response()->json($this->formatLocataire($locataire));
    }

    /**
     * Supprime un locataire (soft-delete) et son User.
     * Restreint aux utilisateurs Entreprise (non-agents).
     */
    public function destroy(Locataire $locataire)
    {
        $this->authorizeCompany($locataire);

        $currentUser = Auth::user();
        $isAgent = $currentUser->employee && $currentUser->employee->agency_id !== null;
        if ($isAgent) {
            return response()->json(['error' => 'Action non autorisée pour les agences.'], 403);
        }

        DB::transaction(function () use ($locataire) {
            $locataire->update(['deleted' => true]);
            $locataire->delete(); // Soft delete locataire
            
            // Supprimer le user
            $locataire->user->delete();
        });

        return response()->json(['message' => 'Locataire supprimé avec succès.']);
    }

    /**
     * Met à jour le statut du locataire (Actif, Suspendu, Désactivé -> inactif).
     */
    public function updateStatus(Request $request, Locataire $locataire)
    {
        $this->authorizeCompany($locataire);

        $request->validate([
            'statut' => 'required|string|in:actif,inactif,suspendu,affecté',
        ]);

        $statut = $request->input('statut');

        DB::transaction(function () use ($locataire, $statut) {
            $locataire->update(['statut' => $statut]);
            
            // Mettre à jour le statut du user correspondant
            $userStatus = 'inactive';
            if ($statut === 'actif' || $statut === 'affecté') {
                $userStatus = 'active';
            }
            $locataire->user->update(['status' => $userStatus]);
        });

        $locataire->load(['user', 'agency', 'affectations.logement']);

        return response()->json($this->formatLocataire($locataire));
    }

    /**
     * Supprime un document du locataire.
     */
    public function deleteDocument(Request $request, Locataire $locataire, $index)
    {
        $this->authorizeCompany($locataire);

        $docs = $locataire->documentations ?? [];
        $index = (int)$index;

        if (isset($docs[$index])) {
            $doc = $docs[$index];
            if (isset($doc['path']) && Storage::disk('public')->exists($doc['path'])) {
                Storage::disk('public')->delete($doc['path']);
            }
            array_splice($docs, $index, 1);
            $docs = array_values($docs);
            $locataire->update(['documentations' => $docs]);
        }

        $locataire->load(['user', 'agency', 'affectations.logement']);

        return response()->json($this->formatLocataire($locataire));
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function formatLocataire(Locataire $l): array
    {
        $activeAffectation = $l->affectations->where('statut', 'Actif')->first();
        $logementRef = $activeAffectation && $activeAffectation->logement ? $activeAffectation->logement->reference : null;

        return [
            'id'                 => $l->id,
            'uuid'               => $l->uuid,
            'nom'                => $l->user->name,
            'email'              => $l->user->email,
            'telephone'          => $l->telephone,
            'agency_id'          => $l->agency_id,
            'agency_name'        => $l->agency?->name,
            'profil_url'         => $l->profil ? '/storage/' . $l->profil : null,
            'statut'             => ucfirst($l->statut), // Actif, Inactif, Suspendu, Affecté
            'logement'           => $logementRef ?? 'Aucun',
            'garantie'           => (float)($activeAffectation ? $activeAffectation->caution : 0),
            'documents'          => $l->documentations ? collect($l->documentations)->map(fn($d) => [
                'name'     => $d['name'] ?? '',
                'filename' => $d['filename'] ?? '',
                'path'     => $d['path'] ?? '',
                'url'      => isset($d['path']) ? '/storage/' . $d['path'] : null,
            ])->toArray() : [],
            'created_at'         => $l->created_at?->toDateString(),
        ];
    }

    private function authorizeCompany(Locataire $locataire): void
    {
        $user = Auth::user();
        abort_if(
            $locataire->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
