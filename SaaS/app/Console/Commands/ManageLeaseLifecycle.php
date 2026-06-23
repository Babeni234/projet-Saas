<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contrat;
use App\Models\Affectation;
use App\Mail\LeaseExpiredMail;
use App\Mail\RenewalInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManageLeaseLifecycle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:manage-lease-lifecycle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gère l\'expiration automatique des baux échus et la séquence d\'emails de rappel de renouvellement.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        // ─── PARTIE 1 : EXPIRATION AUTOMATIQUE DES CONTRATS ÉCHUS ───
        $this->info("Début du traitement des expirations de contrats pour la date : {$today}...");

        $expiredContracts = Contrat::where('statut', 'Actif')
            ->whereDate('fin', '<=', $today)
            ->where('deleted', false)
            ->with(['locataire.user', 'logement'])
            ->get();

        $expiredCount = 0;
        foreach ($expiredContracts as $contrat) {
            DB::transaction(function () use ($contrat) {
                // 1. Expire the contract
                $contrat->update(['statut' => 'Expiré']);

                // 2. Terminate corresponding affectation
                $affectation = $contrat->affectation;
                if (!$affectation) {
                    $affectation = Affectation::where('locataire_id', $contrat->locataire_id)
                        ->where('logement_id', $contrat->logement_id)
                        ->whereIn('statut', ['Actif', "En cours d'exécution"])
                        ->first();
                }
                if ($affectation) {
                    $affectation->update([
                        'statut' => 'Terminé',
                        'date_fin' => $contrat->fin ? $contrat->fin->toDateString() : now()->toDateString(),
                    ]);
                }

                // 3. Set logement to 'Libre'
                if ($contrat->logement) {
                    $contrat->logement->update(['statut' => 'Libre']);
                }

                // 4. Set locataire to 'inactif'
                if ($contrat->locataire) {
                    $contrat->locataire->update(['statut' => 'inactif']);
                    $contrat->locataire->user->update(['status' => 'inactive']);
                }
            });

            // 5. Send lease expired email
            $tenantEmail = $contrat->locataire && $contrat->locataire->user ? $contrat->locataire->user->email : null;
            if ($tenantEmail) {
                try {
                    Mail::to($tenantEmail)->send(new LeaseExpiredMail($contrat));
                    Log::info("Mail d'expiration de bail envoyé au locataire: {$tenantEmail} pour le contrat {$contrat->numero}");
                } catch (\Exception $e) {
                    Log::error("Erreur d'envoi du mail d'expiration de bail à {$tenantEmail} : " . $e->getMessage());
                }
            }

            $expiredCount++;
        }

        $this->info("{$expiredCount} contrats ont été automatiquement expirés.");


        // ─── PARTIE 2 : SÉQUENCE DE RAPPEL DE RENOUVELLEMENT (2 MOIS AVANT) ───
        $this->info("Début du traitement de la séquence d'emails de rappel de renouvellement...");

        $remindersConfig = [
            1 => now()->addDays(60)->toDateString(), // Étape 1 : 2 mois (60j) avant
            2 => now()->addDays(53)->toDateString(), // Étape 2 : 1 semaine après (53j)
            3 => now()->addDays(46)->toDateString(), // Étape 3 : 2 semaines après (46j)
            4 => now()->addDays(39)->toDateString(), // Étape 4 : 3 semaines après (39j)
        ];

        $reminderCount = 0;
        foreach ($remindersConfig as $step => $targetDate) {
            $contractsForReminder = Contrat::where('statut', 'Actif')
                ->whereDate('fin', $targetDate)
                ->where('deleted', false)
                ->with(['locataire.user', 'logement', 'company', 'agency'])
                ->get();

            foreach ($contractsForReminder as $contrat) {
                $tenantEmail = $contrat->locataire && $contrat->locataire->user ? $contrat->locataire->user->email : null;
                if ($tenantEmail) {
                    try {
                        Mail::to($tenantEmail)->send(new RenewalInvitationMail($contrat, $step));
                        Log::info("Mail d'invitation au renouvellement (Étape {$step}) envoyé à: {$tenantEmail} pour le contrat {$contrat->numero}");
                        $reminderCount++;
                    } catch (\Exception $e) {
                        Log::error("Erreur lors de l'envoi de mail de renouvellement (Étape {$step}) à {$tenantEmail} : " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("{$reminderCount} e-mails de rappel de renouvellement ont été envoyés.");
    }
}
