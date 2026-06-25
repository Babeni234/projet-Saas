<?php

namespace App\Services\Legal\Countries;

use App\Services\Legal\CountryInterface;

class Canada implements CountryInterface
{
    public function getName(): string { return 'Canada'; }
    public function getCurrency(): string { return 'CAD'; }
    public function getLocale(): string { return 'fr_CA'; }
    public function getTimezone(): string { return 'America/Montreal'; }

    public function getLeaseDefaultDuration(): string
    {
        return 'Bail à durée fixe (souvent 12 mois) ou à durée indéterminée. Résiliation annuelle possible.';
    }

    public function getNoticePeriodMonths(): int
    {
        return 1; // 1 mois pour le locataire (Québec) ; 1-2 mois pour le bailleur
    }

    public function getDepositMaxMonths(): int
    {
        return 1; // Québec : max 1 mois ; Ontario : pas de dépôt autorisé
    }

    public function getRentIncreaseRules(): array
    {
        return [
            'summary' => 'Encadré par la régie du logement (Québec) ou le Residential Tenancies Act (Ontario).',
            'max_increase_per_year' => 'Pourcentage fixé annuellement par le Tribunal administratif du logement (TAL)',
            'notice_required' => 'Préavis de 3 à 6 mois selon la province',
            'rent_control_zones' => 'Oui dans plusieurs provinces (Québec, Ontario, Colombie-Britannique)',
        ];
    }

    public function getRequiredDocuments(): array
    {
        return [
            'Contrat de bail écrit (recommandé - obligatoire au Québec pour résidence principale)',
            'État des lieux (recommandé)',
            'Copie d\'une pièce d\'identité',
            'Preuve de revenus (sur demande)',
            'Attestation d\'assurance responsabilité civile',
        ];
    }

    public function getApplicableLaws(): array
    {
        return [
            'Code civil du Québec (articles 1892 à 2000)',
            'Loi sur la Régie du logement (Québec)',
            'Residential Tenancies Act, 2006 (Ontario)',
            'Residential Tenancy Act (Colombie-Britannique)',
            'Residential Tenancies Act (Alberta)',
        ];
    }

    public function getTaxRules(): array
    {
        return [
            'income_tax' => 'Revenus locatifs imposés au niveau fédéral et provincial',
            'social_charges' => 'Pas de charges sociales spécifiques',
            'micro_foncier' => 'Pas de micro-foncier',
            'tvA' => 'TVA/TPS non applicable sur les loyers résidentiels',
            'taxe_fonciere' => 'Taxe foncière municipale due par le propriétaire',
            'deductions' => 'Déduction possible : intérêts hypothécaires, assurances, entretien, gestion immobilière',
        ];
    }

    public function getEvictionGrounds(): array
    {
        return [
            'Non-paiement du loyer',
            'Sous-location non autorisée',
            'Troubles de voisinage sérieux',
            'Nombre excessif d\'occupants',
            'Reprise pour occupation par le propriétaire ou un proche',
            'Subdivision ou agrandissement important du logement',
            'Démolition du logement',
        ];
    }

    public function getTerminationNoticeTenant(): string
    {
        return 'Préavis de 1 mois (Québec). Variable selon les provinces (Ontario : 60 jours).';
    }

    public function getTerminationNoticeLandlord(): string
    {
        return 'Préavis de 6 mois pour reprise (Québec). 60 jours pour augmentation de loyer refusée (Ontario).';
    }

    public function getDiagnosticsRequired(): array
    {
        return [
            'Pas de diagnostics obligatoires au niveau fédéral',
            'Certification énergétique dans certaines provinces',
            'Détecteur de fumée obligatoire (toutes provinces)',
            'Détecteur de monoxyde de carbone (selon province)',
        ];
    }

    public function hasRentControl(): bool
    {
        return true; // Québec, Ontario, C-B, Île-du-Prince-Édouard
    }

    public function getLegalFeesResponsibility(): string
    {
        return 'Habituellement partagés ou à la charge du propriétaire. Frais de dossier interdits au Québec.';
    }

    public function isInventoryRequired(): bool
    {
        return false; // Recommandé mais pas obligatoire
    }

    public function isGuarantorAllowed(): bool
    {
        return true; // Caution possible
    }

    public function getSecurityDepositRules(): string
    {
        return 'Québec : max 1 mois, pas d\'intérêts. Ontario : dépôt de garantie interdit (sauf clés). Colombie-Britannique : max 1/2 mois de loyer.';
    }

    public function getRenewalConditions(): string
    {
        return 'Québec : tacite reconduction avec ajustement de loyer. Le locataire peut quitter à chaque anniversaire avec préavis de 1 mois.';
    }
}
