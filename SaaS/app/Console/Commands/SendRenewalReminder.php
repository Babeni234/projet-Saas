<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Renouvellement;
use App\Models\Affectation;
use Illuminate\Support\Facades\Log;

class SendRenewalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-renewal-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie un rappel 2 jours avant la fin du contrat aux locataires ayant un renouvellement "A venir"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = now()->addDays(2)->toDateString();
        
        $renouvellements = Renouvellement::with(['locataire', 'contrat'])
            ->where('statut', 'A venir')
            ->get();

        $count = 0;
        foreach ($renouvellements as $renouvellement) {
            $affectation = Affectation::where('locataire_id', $renouvellement->locataire_id)
                ->where('logement_id', $renouvellement->contrat->logement_id)
                ->where('statut', 'Actif')
                ->whereDate('date_fin', $targetDate)
                ->first();

            if ($affectation) {
                try {
                    $tenantEmail = $renouvellement->locataire && $renouvellement->locataire->user ? $renouvellement->locataire->user->email : null;
                    if ($tenantEmail) {
                        \Illuminate\Support\Facades\Mail::to($tenantEmail)->send(new \App\Mail\RenewalReminder($renouvellement));
                        Log::info("Rappel de renouvellement envoyé au locataire: {$tenantEmail}");
                        $count++;
                    }
                } catch (\Exception $e) {
                    Log::error("Erreur lors de l'envoi de rappel de renouvellement : " . $e->getMessage());
                }
            }
        }

        $this->info("{$count} rappels de renouvellement envoyés.");
    }
}
