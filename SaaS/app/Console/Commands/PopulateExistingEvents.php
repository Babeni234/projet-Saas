<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Evenement;
use App\Models\Batiment;
use App\Models\Logement;
use App\Models\Locataire;
use App\Models\Contrat;
use App\Models\Affectation;
use App\Models\Renouvellement;
use App\Models\Engagement;
use App\Models\EtatDesLieux;

class PopulateExistingEvents extends Command
{
    protected $signature = 'events:populate-existing';
    protected $description = 'Populate the evenements table with creation logs for all existing real estate data';

    public function handle()
    {
        $this->info('Starting to populate events for existing data...');

        // 1. Batiment
        $batiments = Batiment::withTrashed()->get();
        foreach ($batiments as $model) {
            $this->createEvent($model, 'Bâtiment', 'Nouveau bâtiment créé', "Bâtiment {$model->nom} créé dans le système");
        }
        $this->info('Bâtiments processed: ' . $batiments->count());

        // 2. Logement
        $logements = Logement::withTrashed()->get();
        foreach ($logements as $model) {
            $ref = $model->reference ?? "ID: {$model->id}";
            $this->createEvent($model, 'Logement', 'Nouveau logement ajouté', "Logement {$ref} créé avec un loyer de {$model->loyer}");
        }
        $this->info('Logements processed: ' . $logements->count());

        // 3. Locataire
        $locataires = Locataire::withTrashed()->get();
        foreach ($locataires as $model) {
            $name = $model->nom ?? "Locataire #{$model->id}";
            $this->createEvent($model, 'Locataire', 'Nouveau locataire ajouté', "Locataire {$name} enregistré");
        }
        $this->info('Locataires processed: ' . $locataires->count());

        // 4. Contrat
        $contrats = Contrat::withTrashed()->get();
        foreach ($contrats as $model) {
            $ref = $model->numero ?? $model->reference ?? "ID: {$model->id}";
            $this->createEvent($model, 'Contrat', 'Nouveau contrat créé', "Contrat {$ref} créé");
        }
        $this->info('Contrats processed: ' . $contrats->count());

        // 5. Affectation
        $affectations = Affectation::withTrashed()->get();
        foreach ($affectations as $model) {
            $logementRef = $model->logement ? $model->logement->reference : 'Inconnu';
            $locataireName = $model->locataire ? $model->locataire->nom : 'Inconnu';
            $this->createEvent($model, 'Logement', 'Nouvelle affectation', "Logement {$logementRef} affecté à {$locataireName}");
        }
        $this->info('Affectations processed: ' . $affectations->count());

        // 6. Renouvellement
        $renouvellements = Renouvellement::withTrashed()->get();
        foreach ($renouvellements as $model) {
            $ref = $model->reference ?? "ID: {$model->id}";
            $locataireName = $model->locataire ? $model->locataire->nom : 'Inconnu';
            $this->createEvent($model, 'Contrat', 'Demande de renouvellement', "Demande de renouvellement {$ref} créée pour {$locataireName}");
        }
        $this->info('Renouvellements processed: ' . $renouvellements->count());

        // 7. Engagement
        $engagements = Engagement::all();
        foreach ($engagements as $model) {
            $ref = $model->reference ?? "ID: {$model->id}";
            $this->createEvent($model, 'Engagement', 'Nouvel engagement signé', "Convention {$ref} signée");
        }
        $this->info('Engagements processed: ' . $engagements->count());

        // 8. EtatDesLieux
        $etats = EtatDesLieux::all();
        foreach ($etats as $model) {
            $ref = $model->reference ?? "ID: {$model->id}";
            $this->createEvent($model, 'Logement', 'État des lieux entrée', "État des lieux {$ref} effectué");
        }
        $this->info('États des lieux processed: ' . $etats->count());

        $this->info('Successfully populated existing events.');
    }

    private function createEvent($model, $categorie, $titre, $description)
    {
        $userId = \App\Models\User::where('company_profile_id', $model->company_profile_id)->value('id');

        $exists = Evenement::where('company_profile_id', $model->company_profile_id)
            ->where('titre', $titre)
            ->where('description', $description)
            ->where('created_at', $model->created_at)
            ->exists();

        if (!$exists) {
            Evenement::create([
                'company_profile_id' => $model->company_profile_id,
                'agency_id' => $model->agency_id ?? null,
                'user_id' => $userId,
                'titre' => $titre,
                'description' => $description,
                'type' => 'Création',
                'categorie' => $categorie,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at ?? $model->created_at,
            ]);
        }
    }
}
