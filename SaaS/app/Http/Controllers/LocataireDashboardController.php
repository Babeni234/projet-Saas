<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Facture;
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
                  ->with([
                      'logement.batiment',
                      'logement.categorie',
                      'typeContrat',
                  ]);
            },
            'contrats' => function ($q) {
                $q->where('deleted', false)
                  ->whereIn('statut', ['actif', 'Actif', 'en_cours', 'signé', 'signe'])
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
                $batiment = $logement?->batiment;

                // Trouver le contrat formel lié à cet logement si existant
                $contrat = $locataire->contrats
                    ->where('logement_id', $logement?->id)
                    ->first();

                // Adresse complète du bâtiment
                $adresseParts = array_filter([
                    $batiment?->adresse,
                    $batiment?->quartier,
                    $batiment?->ville,
                    $batiment?->pays,
                ]);
                $adresseComplete = implode(', ', $adresseParts) ?: '';

                // Équipements du bâtiment (ascenseur, piscine, parking, générateur)
                $equipements = [];
                if ($batiment) {
                    if ($batiment->ascenseur)    $equipements[] = 'Ascenseur';
                    if ($batiment->piscine)       $equipements[] = 'Piscine';
                    if ($batiment->gardiennage)   $equipements[] = 'Gardiennage';
                    if ($batiment->generatrice)   $equipements[] = 'Groupe électrogène';
                    if ($batiment->nombre_parkings > 0) $equipements[] = 'Parking (' . $batiment->nombre_parkings . ' places)';
                }

                $contracts[] = [
                    // ── Affectation ──
                    'id'              => $aff->id,
                    'reference'       => $aff->reference,
                    'statut'          => $aff->statut,

                    // ── Contrat (si formel existe) ──
                    'contrat_numero'  => $contrat?->numero ?? $aff->reference,
                    'type'            => $aff->typeContrat?->nom ?? $aff->type_bail ?? ($contrat?->typeContrat?->nom) ?? 'Bail',

                    // ── Dates & montants ──
                    'start_date'      => $aff->date_debut?->toDateString(),
                    'end_date'        => $aff->date_fin?->toDateString(),
                    'rent'            => (float) $aff->loyer,
                    'deposit'         => (float) $aff->caution,
                    'charges'         => 0,
                    'frais_contrat'   => (float) ($aff->frais_de_contrat ?? 0),
                    'cycle_paiement'  => $aff->cycle_paiement ?? 'mensuel',
                    'duree'           => $aff->duree,
                    'revision_clause' => 'Annuelle (IRL)',

                    // ── Documents locataire ──
                    'documents' => $locataire->documentations
                        ? collect($locataire->documentations)->map(fn($d, $i) => [
                            'id'          => $i,
                            'name'        => $d['name'] ?? 'Justificatif',
                            'filename'    => $d['filename'] ?? '',
                            'status'      => 'uploaded',
                            'uploaded_at' => null,
                            'url'         => isset($d['path']) ? '/storage/' . $d['path'] : null,
                        ])->toArray()
                        : [],

                    // ── Bien immobilier ──
                    'property' => [
                        'id'           => $logement?->id,
                        'name'         => $logement?->reference ?? 'Logement',
                        'address'      => $adresseComplete,
                        'city'         => $batiment?->ville ?? '',
                        'country'      => $batiment?->pays ?? '',
                        'quartier'     => $batiment?->quartier ?? '',
                        'code_postal'  => $batiment?->code_postal ?? '',
                        'latitude'     => $batiment?->latitude,
                        'longitude'    => $batiment?->longitude,
                        'type'         => $logement?->categorie?->nom ?? 'Logement',
                        'batiment_nom' => $batiment?->nom ?? '',
                        'batiment_ref' => $batiment?->reference ?? '',
                        'type_batiment'=> $batiment?->type_batiment ?? '',
                        'photo'        => null,
                        'specs'        => [
                            ['label' => 'Surface',    'value' => ($logement?->surface ?? '—') . ' m²'],
                            ['label' => 'Étage',      'value' => $logement?->etage !== null ? 'Étage ' . $logement->etage : 'RDC'],
                            ['label' => 'Référence',  'value' => $logement?->reference ?? '—'],
                            ['label' => 'Bâtiment',   'value' => $batiment?->nom ?? '—'],
                            ['label' => 'Ville',      'value' => $batiment?->ville ?? '—'],
                            ['label' => 'Année const.','value' => $batiment?->annee_construction ?? '—'],
                        ],
                        'equipment' => $equipements,
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
                'logo_url' => null,
            ] : null,
            'locataire' => $locataire ? [
                'id'         => $locataire->id,
                'statut'     => $locataire->statut,
                'telephone'  => $locataire->telephone,
                'profil_url' => $locataire->profil ? '/storage/' . $locataire->profil : null,
            ] : null,
            'contracts' => $contracts,
            'invoices'  => $factures,
            'tickets'   => [],
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
            'id'          => $f->id,
            'reference'   => $f->numero,
            'type'        => $f->typeFacture?->nom ?? 'Loyer',
            'period'      => $f->periode ?? $f->date_emission?->format('M Y'),
            'amount'      => (float) $f->total,
            'status'      => $statut,
            'consumption' => null,
            'paid_at'     => in_array($f->statut, ['payée', 'payee', 'réglée', 'reglee'])
                                ? $f->updated_at?->toDateString()
                                : null,
        ];
    }
}
