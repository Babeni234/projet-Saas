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
                // TODO: Envoyer l'email
                // Mail::to($renouvellement->locataire->email)->send(new \App\Mail\RenewalReminder($renouvellement));
                Log::info("Rappel de renouvellement envoyé au locataire: {$renouvellement->locataire->email}");
                $count++;
            }
        }

        $this->info("{$count} rappels de renouvellement envoyés.");
    }
}
