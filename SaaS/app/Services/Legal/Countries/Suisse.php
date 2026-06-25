<?php

namespace App\Services\Legal\Countries;

use App\Services\Legal\CountryInterface;

class Suisse implements CountryInterface
{
    public function getName(): string { return 'Suisse'; }
    public function getCurrency(): string { return 'CHF'; }
    public function getLocale(): string { return 'fr_CH'; }
    public function getTimezone(): string { return 'Europe/Zurich'; }

    public function getLeaseDefaultDuration(): string
    {
        return 'Durée librement convenue (le plus souvent 1 an renouvelable). Bail à durée déterminée possible.';
    }

    public function getNoticePeriodMonths(): int
    {
        return 3; // 3 mois pour le bailleur ; délais variables selon l'ancienneté pour le locataire
    }

    public function getDepositMaxMonths(): int
    {
        return 3; // Max 3 mois de loyer (dépôt de garantie sur compte bloqué)
    }

    public function getRentIncreaseRules(): array
    {
        return [
            'summary' => 'Augmentation liée au taux hypothécaire de référence et à l\'indice suisse des prix à la consommation.',
            'max_increase_per_year' => 'Basé sur l\'évolution du taux hypothécaire de référence',
            'notice_required' => 'Préavis d\'au moins 10 jours avant le début du bail',
            'rent_control_zones' => 'Pas d\'encadrement général, mais les loyers abusifs peuvent être contestés (art. 269 CO)',
        ];
    }

    public function getRequiredDocuments(): array
    {
        return [
            'Contrat de bail écrit (recommandé)',
            'Procès-verbal d\'état des lieux',
            'Attestation d\'assurance ménage du locataire',
            'Extrait du registre des poursuites (sur demande)',
            'Droit de gage (dépôt de garantie)',
        ];
    }

    public function getApplicableLaws(): array
    {
        return [
            'Code des obligations suisse (CO) - articles 253 à 274g',
            'Ordonnance sur le bail à loyer (OBLF)',
            'Règlements cantonaux sur la protection des locataires',
        ];
    }

    public function getTaxRules(): array
    {
        return [
            'income_tax' => 'Revenus locatifs imposés au niveau cantonal et communal',
            'social_charges' => 'Pas de charges sociales spécifiques',
            'micro_foncier' => 'Pas de micro-foncier',
            'tvA' => 'TVA non applicable sur les loyers',
            'taxe_fonciere' => 'Impôt foncier selon le canton',
            'deductions' => 'Déduction possible des intérêts hypothécaires et des frais d\'entretien',
        ];
    }

    public function getEvictionGrounds(): array
    {
        return [
            'Non-paiement du loyer',
            'Non-respect des obligations contractuelles',
            'Inconvenients excessifs pour les voisins',
            'Besoin urgent du propriétaire pour lui-même ou sa famille',
            'Transformation ou démolition du bâtiment',
        ];
    }

    public function getTerminationNoticeTenant(): string
    {
        return 'Préavis de 3 mois. Peut être réduit selon la durée de location et les usages cantonaux.';
    }

    public function getTerminationNoticeLandlord(): string
    {
        return 'Préavis de 3 mois (minimum légal). Délais plus longs selon l\'ancienneté du locataire.';
    }

    public function getDiagnosticsRequired(): array
    {
        return [
            'Pas de diagnostics obligatoires au niveau fédéral comme en France',
            'Certificat énergétique cantonal (obligatoire dans certains cantons)',
        ];
    }

    public function hasRentControl(): bool
    {
        return true; // Loyer abusif contestable (art. 269 CO)
    }

    public function getLegalFeesResponsibility(): string
    {
        return 'Les honoraires d\'agence sont généralement à la charge du locataire (dans la limite des usages cantonaux).';
    }

    public function isInventoryRequired(): bool
    {
        return false; // Recommandé mais pas obligatoire
    }

    public function isGuarantorAllowed(): bool
    {
        return true;
    }

    public function getSecurityDepositRules(): string
    {
        return 'Dépôt de garantie max 3 mois de loyer. Versé sur un compte bancaire de garantie (droit de gage). Restitution sous conditions.';
    }

    public function getRenewalConditions(): string
    {
        return 'Tacite reconduction par défaut. Le bailleur peut refuser le renouvellement uniquement pour motifs valables (art. 271 CO).';
    }
}
