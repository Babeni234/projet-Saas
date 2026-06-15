<?php

namespace App\Traits;

use App\Helpers\EventLogger;

trait LogsActivity
{
    /**
     * Boot the trait and register model event handlers.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'Création');
        });

        static::updated(function ($model) {
            // Check if soft deleted or custom deleted attribute is set
            if ($model->wasChanged('deleted') && $model->deleted) {
                self::logActivity($model, 'Suppression');
            } else {
                self::logActivity($model, 'Modification');
            }
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'Suppression');
        });
    }

    /**
     * Trigger the EventLogger with mapped values.
     */
    protected static function logActivity($model, $type)
    {
        $info = self::getActivityLogInfo($model, $type);
        if (!$info) {
            return;
        }

        EventLogger::log(
            $info['titre'],
            $info['description'],
            $info['type'] ?? $type,
            $info['categorie'],
            $model->agency_id ?? null,
            $model->company_profile_id ?? null
        );
    }

    /**
     * Map model specific titles, descriptions and categories.
     */
    protected static function getActivityLogInfo($model, $type)
    {
        if (method_exists($model, 'customActivityLogInfo')) {
            return $model->customActivityLogInfo($type);
        }

        $className = class_basename($model);
        
        switch ($className) {
            case 'Batiment':
                $categorie = 'Bâtiment';
                $name = $model->nom;
                $titre = $type === 'Création' ? 'Nouveau bâtiment créé' : ($type === 'Suppression' ? 'Suppression de bâtiment' : 'Modification du bâtiment');
                $description = $type === 'Création' ? "Bâtiment {$name} créé dans le système" : ($type === 'Suppression' ? "Bâtiment {$name} supprimé du système" : "Bâtiment {$name} mis à jour");
                break;

            case 'Logement':
                $categorie = 'Logement';
                $ref = $model->reference ?? "ID: {$model->id}";
                $titre = $type === 'Création' ? 'Nouveau logement ajouté' : ($type === 'Suppression' ? 'Suppression du logement' : 'Modification du logement');
                $description = $type === 'Création' ? "Logement {$ref} créé avec un loyer de {$model->loyer}" : ($type === 'Suppression' ? "Logement {$ref} supprimé" : "Logement {$ref} mis à jour");
                break;

            case 'Locataire':
                $categorie = 'Locataire';
                $name = $model->nom_complet ?? "Locataire #{$model->id}";
                $titre = $type === 'Création' ? 'Nouveau locataire ajouté' : ($type === 'Suppression' ? 'Suppression du locataire' : 'Modification du locataire');
                $description = $type === 'Création' ? "Locataire {$name} enregistré" : ($type === 'Suppression' ? "Locataire {$name} supprimé" : "Locataire {$name} mis à jour");
                break;

            case 'Contrat':
                $categorie = 'Contrat';
                $ref = $model->numero ?? $model->reference ?? "ID: {$model->id}";
                $titre = $type === 'Création' ? 'Nouveau contrat créé' : ($type === 'Suppression' ? 'Suppression de contrat' : 'Modification de contrat');
                $description = $type === 'Création' ? "Contrat {$ref} créé" : ($type === 'Suppression' ? "Contrat {$ref} supprimé" : "Contrat {$ref} mis à jour");
                break;

            case 'Affectation':
                $categorie = 'Logement';
                $ref = $model->reference ?? "ID: {$model->id}";
                $logementRef = $model->logement ? $model->logement->reference : 'Inconnu';
                $locataireName = $model->locataire ? $model->locataire->nom_complet : 'Inconnu';
                $titre = $type === 'Création' ? 'Nouvelle affectation' : ($type === 'Suppression' ? "Fin d'affectation" : "Modification d'affectation");
                $description = $type === 'Création' ? "Logement {$logementRef} affecté à {$locataireName}" : ($type === 'Suppression' ? "Affectation {$ref} de {$logementRef} pour {$locataireName} supprimée" : "Affectation {$ref} mise à jour");
                break;

            case 'Renouvellement':
                $categorie = 'Contrat';
                $ref = $model->reference ?? "ID: {$model->id}";
                $locataireName = $model->locataire ? $model->locataire->nom_complet : 'Inconnu';
                $titre = $type === 'Création' ? 'Demande de renouvellement' : ($type === 'Suppression' ? 'Suppression de renouvellement' : 'Renouvellement de contrat');
                $description = $type === 'Création' ? "Demande de renouvellement {$ref} créée pour {$locataireName}" : ($type === 'Suppression' ? "Demande de renouvellement {$ref} supprimée" : "Demande de renouvellement {$ref} mise à jour");
                break;

            case 'Engagement':
                $categorie = 'Engagement';
                $ref = $model->reference ?? "ID: {$model->id}";
                $titre = $type === 'Création' ? 'Nouvel engagement signé' : ($type === 'Suppression' ? 'Suppression de l\'engagement' : 'Modification de l\'engagement');
                $description = $type === 'Création' ? "Convention {$ref} signée" : ($type === 'Suppression' ? "Engagement {$ref} supprimé" : "Engagement {$ref} mis à jour");
                break;

            case 'EtatDesLieux':
                $categorie = 'Logement';
                $ref = $model->reference ?? "ID: {$model->id}";
                $titre = $type === 'Création' ? 'État des lieux entrée' : ($type === 'Suppression' ? 'Suppression d\'état des lieux' : 'Modification d\'état des lieux');
                $description = $type === 'Création' ? "État des lieux {$ref} effectué" : ($type === 'Suppression' ? "État des lieux {$ref} supprimé" : "État des lieux {$ref} mis à jour");
                break;

            default:
                return null;
        }

        return [
            'titre' => $titre,
            'description' => $description,
            'categorie' => $categorie,
        ];
    }
}
