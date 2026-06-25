<?php

namespace App\Services\Legal\Countries;

use App\Services\Legal\CountryInterface;

class France implements CountryInterface
{
    public function getName(): string { return 'France'; }
    public function getCurrency(): string { return 'EUR'; }
    public function getLocale(): string { return 'fr_FR'; }
    public function getTimezone(): string { return 'Europe/Paris'; }

    public function getLeaseDefaultDuration(): string
    {
        return 'Bail vide : 3 ans. Bail meublé : 1 an. Bail étudiant : 9 mois.';
    }

    public function getNoticePeriodMonths(): int
    {
        return 3; // 3 mois pour le bailleur, 1 mois pour le locataire en zone tendue
    }

    public function getDepositMaxMonths(): int
    {
        return 1; // 1 mois de loyer hors charges (loi ALUR)
    }

    public function getRentIncreaseRules(): array
    {
        return [
            'summary' => 'Augmentation encadrée par l\'IRL (Indice de Référence des Loyers) publié par l\'INSEE.',
            'max_increase_per_year' => 'IRL annuel',
            'notice_required' => 'Préavis de 3 mois avec justificatif IRL',
            'rent_control_zones' => 'Zones tendues : Paris, Lyon, Bordeaux, etc. (encadrement des loyers)',
        ];
    }

    public function getRequiredDocuments(): array
    {
        return [
            'Bail écrit (loi ALUR)',
            'État des lieux contradictoire',
            'DPE (Diagnostic de Performance Énergétique)',
            'Diagnostic électrique (si installation > 15 ans)',
            'Diagnostic gaz (si installation > 15 ans)',
            'ERP (État des Risques et Pollutions)',
            'Notice d\'information sur les droits et obligations',
            'Règlement de copropriété et diagnostics techniques',
        ];
    }

    public function getApplicableLaws(): array
    {
        return [
            'Loi n° 89-462 du 6 juillet 1989 (loi Mermaz-Malandain)',
            'Loi ALUR n° 2014-366 du 24 mars 2014',
            'Loi ELAN n° 2018-1021 du 23 novembre 2018',
            'Décret n° 2015-981 du 31 juillet 2015 (IRL)',
            'Code civil (articles 1708 à 1778)',
        ];
    }

    public function getTaxRules(): array
    {
        return [
            'income_tax' => 'Revenus fonciers imposables au barème progressif (IR)',
            'social_charges' => '17.2% de prélèvements sociaux (CSG, CRDS)',
            'micro_foncier' => 'Régime micro-foncier possible si revenus < 15 000 €/an (abattement 30%)',
            'tvA' => 'TVA non applicable sur les loyers d\'habitation (sauf logements meublés > 23 000 €)',
            'taxe_fonciere' => 'Taxe foncière due par le propriétaire',
            'flat_tax_option' => 'Option possible pour PFU (Prélèvement Forfaitaire Unique) à 30%',
        ];
    }

    public function getEvictionGrounds(): array
    {
        return [
            'Non-paiement des loyers ou charges',
            'Défaut d\'assurance locative',
            'Troubles de voisinage répétés',
            'Non-respect des obligations locatives',
            'Reprise du logement pour occupation personnelle (préavis 6 mois)',
            'Vente du logement (préavis 6 mois)',
        ];
    }

    public function getTerminationNoticeTenant(): string
    {
        return 'Préavis de 3 mois (réduit à 1 mois en zone tendue, pour mutation professionnelle, perte d\'emploi, ou logement social).';
    }

    public function getTerminationNoticeLandlord(): string
    {
        return 'Préavis de 6 mois pour reprise ou vente. Délai de 2 mois pour motif légitime et sérieux après décision judiciaire.';
    }

    public function getDiagnosticsRequired(): array
    {
        return [
            'DPE (Diagnostic de Performance Énergétique) - obligatoire depuis 2006',
            'Diagnostic amiante (si permis avant juillet 1997)',
            'Diagnostic plomb (si construction avant 1949)',
            'Diagnostic gaz (si installation > 15 ans)',
            'Diagnostic électrique (si installation > 15 ans)',
            'ERP (État des Risques et Pollutions)',
            'Loi Carrez (surface privative en copropriété)',
        ];
    }

    public function hasRentControl(): bool
    {
        return true; // Encadrement des loyers dans 28 zones tendues
    }

    public function getLegalFeesResponsibility(): string
    {
        return 'Les frais d\'agence sont partagés entre bailleur et locataire (loi ALUR). Frais de dossier interdits.';
    }

    public function isInventoryRequired(): bool
    {
        return true; // État des lieux contradictoire obligatoire (loi ALUR)
    }

    public function isGuarantorAllowed(): bool
    {
        return true; // Garantie VISALE possible pour les locataires éligibles
    }

    public function getSecurityDepositRules(): string
    {
        return 'Dépôt de garantie max 1 mois de loyer (hors charges) pour logement vide. Restitution sous 1 mois (2 mois si dégradations).';
    }

    public function getRenewalConditions(): string
    {
        return 'Renouvellement automatique par tacite reconduction pour le bailleur. Le locataire peut donner congé à tout moment avec préavis. Le bailleur ne peut refuser le renouvellement sans motif légitime.';
    }
}
