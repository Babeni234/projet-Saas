<?php

namespace App\Services\Legal\Countries;

use App\Services\Legal\CountryInterface;

class Belgique implements CountryInterface
{
    public function getName(): string { return 'Belgique'; }
    public function getCurrency(): string { return 'EUR'; }
    public function getLocale(): string { return 'fr_BE'; }
    public function getTimezone(): string { return 'Europe/Brussels'; }

    public function getLeaseDefaultDuration(): string
    {
        return 'Bail de résidence principale : 3 ans minimum (peut être porté à 6 ou 9 ans). Bail étudiant : 1 an. Bail commercial : 9 ans.';
    }

    public function getNoticePeriodMonths(): int
    {
        return 3; // 3 mois pour le bailleur ; variable selon la durée du bail pour le locataire
    }

    public function getDepositMaxMonths(): int
    {
        return 2; // Max 2 mois de loyer (avec ou sans garantie bancaire)
    }

    public function getRentIncreaseRules(): array
    {
        return [
            'summary' => 'Indexation annuelle liée à l\'indice santé (lissé). Augmentation libre à la relocation.',
            'max_increase_per_year' => 'Indice santé lissé (géré par le SPF Économie)',
            'notice_required' => 'Préavis de 2 mois avant la date anniversaire',
            'rent_control_zones' => 'Pas d\'encadrement général, mais certaines communes ont des règlements spécifiques',
        ];
    }

    public function getRequiredDocuments(): array
    {
        return [
            'Bail écrit obligatoire (toute location)',
            'État des lieux contradictoire (obligatoire)',
            'Attestation PEB (Performance Énergétique du Bâtiment)',
            'Attestation d\'assurance habitation du locataire',
            'Certificat de conformité électrique (si installation > 25 ans)',
            'Attestation d\'assurance du propriétaire (incendie)',
        ];
    }

    public function getApplicableLaws(): array
    {
        return [
            'Code civil belge (articles 1708 à 1762)',
            'Décret bruxellois du 27 mars 2014 (région bruxelloise)',
            'Décret wallon du 15 mars 2018 (région wallonne)',
            'Décret flamand du 9 novembre 2018 (région flamande)',
            'Loi du 30 avril 1950 sur les baux à loyer',
        ];
    }

    public function getTaxRules(): array
    {
        return [
            'income_tax' => 'Revenus cadastraux indexés imposés au Précompte Immobilier',
            'social_charges' => 'Pas de charges sociales spécifiques sur les revenus locatifs',
            'micro_foncier' => 'Pas de régime micro-foncier spécifique',
            'tvA' => 'TVA non applicable sur les loyers d\'habitation',
            'taxe_fonciere' => 'Précompte immobilier dû par le propriétaire',
            'flat_tax_option' => 'Pas de PFU ; imposition au barème progressif',
        ];
    }

    public function getEvictionGrounds(): array
    {
        return [
            'Non-paiement du loyer ou des charges',
            'Inoccupation du logement',
            'Troubles de voisinage graves',
            'Non-respect des obligations locatives',
            'Travaux urgents de rénovation (région wallonne)',
            'Occupation personnelle (selon région avec préavis spécifique)',
        ];
    }

    public function getTerminationNoticeTenant(): string
    {
        return 'Préavis de 3 mois (bail 3 ans). Réduit si logement non-décent ou si mutation professionnelle.';
    }

    public function getTerminationNoticeLandlord(): string
    {
        return 'Préavis de 6 mois pour occupation personnelle. Pas de congé sans motif légitime pendant les 3 premières années.';
    }

    public function getDiagnosticsRequired(): array
    {
        return [
            'PEB (Performance Énergétique du Bâtiment) - obligatoire depuis 2011',
            'Certificat de conformité électrique (obligatoire pour installation > 25 ans)',
            'Certificat de conformité gaz (obligatoire depuis 2018 en Wallonie)',
            'Attestation d\'assurance incendie',
        ];
    }

    public function hasRentControl(): bool
    {
        return false; // Pas d'encadrement général des loyers en Belgique
    }

    public function getLegalFeesResponsibility(): string
    {
        return 'Les honoraires d\'agence sont généralement partagés, mais les frais de dossier sont souvent facturés au locataire.';
    }

    public function isInventoryRequired(): bool
    {
        return true; // État des lieux contradictoire obligatoire
    }

    public function isGuarantorAllowed(): bool
    {
        return true;
    }

    public function getSecurityDepositRules(): string
    {
        return 'Dépôt de garantie max 2 mois de loyer. Restitution sous 2 mois. Bloqué sur un compte bancaire (garantie locative).';
    }

    public function getRenewalConditions(): string
    {
        return 'Tacite reconduction après la période initiale. Le bailleur peut refuser le renouvellement avec un préavis de 6 mois (selon région).';
    }
}
