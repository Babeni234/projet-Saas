<?php

namespace App\Services\Legal\Countries;

use App\Services\Legal\CountryInterface;

class Generic implements CountryInterface
{
    public function getName(): string { return 'Autre pays'; }
    public function getCurrency(): string { return 'EUR'; }
    public function getLocale(): string { return 'fr'; }
    public function getTimezone(): string { return 'UTC'; }

    public function getLeaseDefaultDuration(): string { return 'Durée librement convenue entre les parties.'; }
    public function getNoticePeriodMonths(): int { return 1; }
    public function getDepositMaxMonths(): int { return 2; }

    public function getRentIncreaseRules(): array
    {
        return ['summary' => 'Libre selon les conditions du contrat.', 'max_increase_per_year' => 'Variable', 'notice_required' => 'À définir', 'rent_control_zones' => 'Non spécifié'];
    }

    public function getRequiredDocuments(): array
    {
        return ['Contrat de bail écrit', 'État des lieux', 'Pièce d\'identité'];
    }

    public function getApplicableLaws(): array
    {
        return ['Législation locale en vigueur.'];
    }

    public function getTaxRules(): array
    {
        return ['income_tax' => 'Selon la législation fiscale locale.', 'social_charges' => 'Variable', 'tvA' => 'Variable'];
    }

    public function getEvictionGrounds(): array
    {
        return ['Non-paiement du loyer', 'Non-respect des obligations contractuelles'];
    }

    public function getTerminationNoticeTenant(): string { return 'Préavis selon la loi locale.'; }
    public function getTerminationNoticeLandlord(): string { return 'Préavis selon la loi locale.'; }

    public function getDiagnosticsRequired(): array
    {
        return ['Voir la réglementation locale.'];
    }

    public function hasRentControl(): bool { return false; }
    public function getLegalFeesResponsibility(): string { return 'Variable selon les usages locaux.'; }
    public function isInventoryRequired(): bool { return true; }
    public function isGuarantorAllowed(): bool { return true; }
    public function getSecurityDepositRules(): string { return 'Dépôt de garantie limité selon la loi locale.'; }
    public function getRenewalConditions(): string { return 'Tacite reconduction ou renouvellement express selon le contrat.'; }
}
