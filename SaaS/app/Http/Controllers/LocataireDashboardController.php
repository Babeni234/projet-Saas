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
            'wallet',
            'affectations' => function ($q) {
                $q->where('deleted', false)
                  ->whereIn('statut', [
                      'Actif', 'actif', 'active', 'en_cours',
                      'En cours d\'exécution', 'En cours', 'signé', 'signe'
                  ])
                  ->with([
                      'logement.batiment',
                      'logement.categorie',
                      'typeContrat',
                      'contrat.renouvellements.fraisContrats',
                      'contrat.typeContrat',
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

        // Mois déjà payés (clés de période comme '2026-06')
        $paidMonthsKeys = [];
        if ($locataire) {
            $paidMonthsKeys = \App\Models\MoisPaye::whereHas('paiementLoyer', function ($q) use ($locataire) {
                $q->where('locataire_id', $locataire->id)->where('deleted', false);
            })->pluck('periode')->toArray();
        }

        // Règle de loyer de la compagnie
        $regleLoyer = \App\Models\RegleLoyer::where('company_profile_id', $user->company_profile_id)->first();

        // Anciens contrats
        $oldContracts = [];
        if ($locataire) {
            $oldContracts = $locataire->contrats()
                ->where('deleted', false)
                ->whereIn('statut', ['termine', 'terminé', 'resilie', 'résilié', 'expired', 'expire'])
                ->with(['logement.batiment', 'typeContrat'])
                ->get()
                ->map(function ($c) {
                    $logement = $c->logement;
                    $batiment = $logement?->batiment;
                    $adresseParts = array_filter([
                        $batiment?->adresse,
                        $batiment?->quartier,
                        $batiment?->ville,
                        $batiment?->pays,
                    ]);
                    return [
                        'id' => $c->id,
                        'property_name' => $logement?->reference ?? 'Logement',
                        'address' => implode(', ', $adresseParts) ?: '',
                        'owner' => $c->company?->legal_name ?? 'Propriétaire',
                        'start_date' => $c->debut?->toDateString(),
                        'end_date' => $c->fin?->toDateString(),
                        'rent' => (float) $c->loyer,
                        'deposit' => (float) $c->caution,
                        'duration' => $c->duree ?? '12 mois',
                        'status' => in_array(strtolower($c->statut), ['termine', 'terminé', 'expired', 'expire']) ? 'ended' : 'resigned',
                        'documents' => [],
                    ];
                })
                ->toArray();
        }

        // Reçus / Quittances
        $receipts = [];
        if ($locataire) {
            $rentReceipts = \App\Models\PaiementLoyer::where('locataire_id', $locataire->id)
                ->where('deleted', false)
                ->with(['contrat.logement', 'company'])
                ->get()
                ->map(function ($p) use ($locataire) {
                    return [
                        'id' => 'RCPT-RENT-' . $p->id,
                        'type' => 'rent',
                        'title' => 'Loyer ' . ($p->date_reglement ? $p->date_reglement->format('M Y') : ''),
                        'reference' => $p->reference,
                        'amount' => (float) $p->montant_total,
                        'period' => $p->date_reglement ? $p->date_reglement->format('M Y') : '',
                        'paid_at' => $p->date_reglement ? $p->date_reglement->toDateString() : null,
                        'method' => $p->mode_reglement,
                        'transaction_id' => $p->reference_tx ?? ('TX-' . $p->id),
                        'property' => $p->contrat?->logement?->reference ?? 'Logement',
                        'tenant' => $locataire->nom,
                        'landlord' => $p->company?->legal_name ?? 'Propriétaire',
                    ];
                });

            $utilityReceipts = \App\Models\Facture::where('locataire_id', $locataire->id)
                ->where('deleted', false)
                ->where('statut', 'payée')
                ->whereHas('typeFacture', function ($q) {
                    $q->where('nom', '!=', 'Loyer');
                })
                ->with(['typeFacture', 'company'])
                ->get()
                ->map(function ($f) use ($locataire) {
                    return [
                        'id' => 'RCPT-UTIL-' . $f->id,
                        'type' => strtolower($f->typeFacture?->nom) === 'eau' ? 'water' : 'electricity',
                        'title' => 'Facture ' . ($f->typeFacture?->nom ?? 'Autre'),
                        'reference' => 'QUIT-' . $f->numero,
                        'amount' => (float) $f->total,
                        'period' => $f->periode,
                        'paid_at' => $f->updated_at ? $f->updated_at->toDateString() : ($f->date_emission ? $f->date_emission->toDateString() : null),
                        'method' => 'Wallet',
                        'transaction_id' => 'TX-' . $f->id,
                        'property' => $locataire->affectations->first()?->logement?->reference ?? 'Logement',
                        'tenant' => $locataire->nom,
                        'landlord' => $f->company?->legal_name ?? 'Propriétaire',
                    ];
                });

            $contractFeeReceipts = \App\Models\FraisContrat::whereHas('renouvellement', function ($q) use ($locataire) {
                    $q->where('locataire_id', $locataire->id);
                })
                ->with(['renouvellement.contrat.logement', 'company'])
                ->get()
                ->map(function ($fc) use ($locataire) {
                    return [
                        'id' => 'RCPT-FEE-' . $fc->id,
                        'type' => 'contract_fee',
                        'title' => 'Frais de contrat (Renouvellement)',
                        'reference' => 'FEE-CTR-' . $fc->id,
                        'amount' => (float) $fc->montant,
                        'period' => $fc->date_paiement ? $fc->date_paiement->format('Y') : '',
                        'paid_at' => $fc->date_paiement ? $fc->date_paiement->toDateString() : null,
                        'method' => 'Wallet',
                        'transaction_id' => 'TX-' . $fc->id,
                        'property' => $fc->renouvellement?->contrat?->logement?->reference ?? 'Logement',
                        'tenant' => $locataire->nom,
                        'landlord' => $fc->company?->legal_name ?? 'Propriétaire',
                    ];
                });

            $receipts = $rentReceipts->concat($utilityReceipts)->concat($contractFeeReceipts)->toArray();
        }

        // Construire les données contrats / affectations pour le frontend
        $contracts = [];
        $contractFeesList = []; // frais de contrat structurés pour la section dédiée
        if ($locataire && $locataire->affectations->isNotEmpty()) {
            foreach ($locataire->affectations as $aff) {
                $logement = $aff->logement;
                $batiment = $logement?->batiment;

                // ── Contrat formel via jointure affectation_id (ou fallback par logement_id) ──
                $contrat = $aff->contrat
                    ?? $locataire->contrats->where('logement_id', $logement?->id)->first();

                // ── Renouvellements du contrat ──
                $renouvellements = $contrat?->renouvellements ?? collect();

                // ── Frais initiaux du bail (affectation) ──
                $fraisInitial = (float) ($aff->frais_de_contrat ?? 0);
                if ($fraisInitial > 0) {
                    $contractFeesList[] = [
                        'id'          => 'INIT-' . $aff->id,
                        'type'        => 'initial',
                        'label'       => 'Frais de contrat initial',
                        'amount'      => $fraisInitial,
                        'date'        => $aff->date_debut?->toDateString(),
                        'statut'      => 'payé',
                        'reference'   => $aff->reference,
                    ];
                }

                // ── Frais de renouvellements ──
                foreach ($renouvellements as $rnv) {
                    $fraisRnv = (float) ($rnv->frais_contrat ?? 0);
                    if ($fraisRnv > 0) {
                        $contractFeesList[] = [
                            'id'          => 'RNV-' . $rnv->id,
                            'type'        => 'renouvellement',
                            'label'       => 'Frais renouvellement ' . ($rnv->reference ?? ''),
                            'amount'      => $fraisRnv,
                            'date'        => $rnv->created_at?->toDateString(),
                            'statut'      => strtolower($rnv->statut ?? 'en_attente'),
                            'reference'   => $rnv->reference ?? '',
                        ];
                    }
                    // Frais de contrat enregistrés dans frais_contrats via le renouvellement
                    foreach ($rnv->fraisContrats ?? [] as $fc) {
                        $contractFeesList[] = [
                            'id'          => 'FC-' . $fc->id,
                            'type'        => 'frais_contrat',
                            'label'       => 'Frais contrat - ' . ($rnv->reference ?? ''),
                            'amount'      => (float) $fc->montant,
                            'date'        => $fc->date_paiement?->toDateString(),
                            'statut'      => 'payé',
                            'reference'   => $rnv->reference ?? '',
                        ];
                    }
                }

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

                    // ── Contrat formel (si existant via jointure affectation_id) ──
                    'contrat_numero'  => $contrat?->numero ?? $aff->reference,
                    'contrat_id'      => $contrat?->id,
                    'type'            => $aff->typeContrat?->nom ?? $aff->type_bail ?? ($contrat?->typeContrat?->nom) ?? 'Bail',

                    // ── Dates & montants ──
                    'start_date'      => $aff->date_debut?->toDateString(),
                    'end_date'        => $aff->date_fin?->toDateString(),
                    'rent'            => (float) $aff->loyer,
                    'deposit'         => (float) $aff->caution,
                    'charges'         => 0,
                    'frais_contrat'   => $fraisInitial,
                    'cycle_paiement'  => $aff->cycle_paiement ?? 'mensuel',
                    'duree'           => $aff->duree,
                    'revision_clause' => 'Annuelle (IRL)',

                    // ── Renouvellements ──
                    'renouvellements' => $renouvellements->map(fn($r) => [
                        'id'           => $r->id,
                        'reference'    => $r->reference,
                        'nouveau_loyer'=> (float) $r->nouveau_loyer,
                        'frais_contrat'=> (float) ($r->frais_contrat ?? 0),
                        'statut'       => $r->statut,
                        'duree'        => $r->duree,
                        'cycle'        => $r->cycle_paiement,
                        'created_at'   => $r->created_at?->toDateString(),
                    ])->values()->toArray(),

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
            'contractFees' => $contractFeesList,
            'invoices'  => $factures,
            'tickets'   => [],
            'wallet'    => $locataire?->wallet ? [
                'id'    => $locataire->wallet->id,
                'solde' => (float) $locataire->wallet->solde,
                'transactions' => \App\Models\TransacWallet::where('wallet_id', $locataire->wallet->id)->latest()->get()->map(fn($t) => [
                    'id' => $t->id,
                    'type' => $t->type,
                    'amount' => (float) $t->amount,
                    'description' => $t->description,
                    'reference' => $t->reference_tx,
                    'date' => $t->created_at?->toIso8601String(),
                ])->toArray(),
            ] : null,
            'oldContracts' => $oldContracts,
            'regleLoyer' => $regleLoyer ? [
                'id' => $regleLoyer->id,
                'jour_declenchement' => (int) $regleLoyer->jour_declenchement,
                'taux_penalite' => (float) $regleLoyer->taux_penalite,
                'cycle' => $regleLoyer->cycle,
            ] : null,
            'receipts' => $receipts,
            'paidMonthsKeys' => $paidMonthsKeys,
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
