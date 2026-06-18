<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LocataireDashboardController extends Controller
{
    /**
     * Affiche le dashboard du locataire connecté avec toutes ses données réelles.
     */
    public function index(): Response
    {
        $user = Auth::user();

        // Charger le locataire avec toutes ses relations
        $locataire = $user->locataire?->load([
            'agency',
            'affectations' => function ($q) {
                $q->where('deleted', false)
                  ->where('statut', 'Actif')
                  ->with(['logement.batiment', 'logement.categorie', 'typeContrat']);
            },
            'contrats' => function ($q) {
                $q->where('deleted', false)
                  ->where('statut', 'actif')
                  ->with(['logement.batiment', 'typeContrat'])
                  ->latest();
            },
        ]);

        // Récupérer la compagnie propriétaire pour afficher son logo
        $company = CompanyProfile::find($user->company_profile_id);

        // Factures du locataire (les 24 dernières)
        $factures = [];
        if ($locataire) {
            $factures = Facture::where('locataire_id', $locataire->id)
                ->where('deleted', false)
                ->with(['typeFacture'])
                ->latest('date_emission')
                ->take(24)
                ->get()
                ->map(fn($f) => $this->formatFacture($f))
                ->toArray();
        }

        // Construire les données contrats / affectations pour le frontend
        $contracts = [];
        if ($locataire && $locataire->affectations->isNotEmpty()) {
            foreach ($locataire->affectations as $aff) {
                $logement = $aff->logement;
                $contrat  = $locataire->contrats
                    ->where('logement_id', $logement?->id)
                    ->first();

                $contracts[] = [
                    'id'         => $aff->id,
                    'reference'  => $aff->reference,
                    'type'       => $aff->typeContrat?->nom ?? $aff->type_bail ?? 'Bail',
                    'start_date' => $aff->date_debut?->toDateString(),
                    'end_date'   => $aff->date_fin?->toDateString(),
                    'rent'       => (float) $aff->loyer,
                    'deposit'    => (float) $aff->caution,
                    'charges'    => 0,
                    'revision_clause' => 'Annuelle (IRL)',
                    'statut'     => $aff->statut,
                    'documents'  => $locataire->documentations
                        ? collect($locataire->documentations)->map(fn($d, $i) => [
                            'id'          => $i,
                            'name'        => $d['name'] ?? 'Justificatif',
                            'filename'    => $d['filename'] ?? '',
                            'status'      => 'uploaded',
                            'uploaded_at' => null,
                            'url'         => isset($d['path']) ? '/storage/' . $d['path'] : null,
                        ])->toArray()
                        : [],
                    'property' => [
                        'name'    => $logement?->reference ?? 'Logement',
                        'address' => $logement?->batiment?->adresse ?? '',
                        'type'    => $logement?->categorie?->nom ?? 'Logement',
                        'photo'   => null,
                        'specs'   => [
                            ['label' => 'Surface',  'value' => ($logement?->surface ?? '—') . ' m²'],
                            ['label' => 'Étage',    'value' => $logement?->etage !== null ? 'Étage ' . $logement->etage : '—'],
                            ['label' => 'Référence','value' => $logement?->reference ?? '—'],
                            ['label' => 'Bâtiment', 'value' => $logement?->batiment?->nom ?? '—'],
                        ],
                        'equipment' => [],
                    ],
                ];
            }
        }

        return Inertia::render('Locataire/dashboard-loc', [
            'auth' => [
                'user' => [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'avatar'     => $locataire?->profil ? '/storage/' . $locataire->profil : null,
                    'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                    'last_name'  => implode(' ', array_slice(explode(' ', $user->name), 1)) ?: '',
                    'phone'      => $locataire?->telephone ?? '',
                ],
            ],
            'company' => $company ? [
                'name'     => $company->legal_name,
                'logo_url' => $company->logo_path ? '/storage/' . $company->logo_path : null,
            ] : null,
            'agency' => $locataire?->agency ? [
                'id'       => $locataire->agency->id,
                'name'     => $locataire->agency->name,
                'logo_url' => null, // Agency n'a pas de champ logo actuellement
            ] : null,
            'locataire' => $locataire ? [
                'id'       => $locataire->id,
                'statut'   => $locataire->statut,
                'telephone'=> $locataire->telephone,
                'profil_url' => $locataire->profil ? '/storage/' . $locataire->profil : null,
            ] : null,
            'contracts' => $contracts,
            'invoices'  => $factures,
            'tickets'   => [], // Phase 2 — module tickets pas encore créé
        ]);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    private function formatFacture(Facture $f): array
    {
        $statut = match($f->statut) {
            'payée', 'payee', 'réglée', 'reglee' => 'paid',
            'en_attente', 'pending'               => 'pending',
            'en_retard', 'retard', 'impayée'      => 'late',
            default                               => strtolower($f->statut),
        };

        return [
            'id'        => $f->id,
            'reference' => $f->numero,
            'type'      => $f->typeFacture?->nom ?? 'Loyer',
            'period'    => $f->periode ?? $f->date_emission?->format('M Y'),
            'amount'    => (float) $f->total,
            'status'    => $statut,
            'consumption' => null,
            'paid_at'   => $f->statut === 'payée' ? $f->updated_at?->toDateString() : null,
        ];
    }
}
