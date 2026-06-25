<?php

namespace App\Services\Legal;

interface CountryInterface
{
    public function getName(): string;
    public function getCurrency(): string;
    public function getLocale(): string;
    public function getTimezone(): string;
    public function getLeaseDefaultDuration(): string;
    public function getNoticePeriodMonths(): int;
    public function getDepositMaxMonths(): int;
    public function getRentIncreaseRules(): array;
    public function getRequiredDocuments(): array;
    public function getApplicableLaws(): array;
    public function getTaxRules(): array;
    public function getEvictionGrounds(): array;
    public function getTerminationNoticeTenant(): string;
    public function getTerminationNoticeLandlord(): string;
    public function getDiagnosticsRequired(): array;
    public function hasRentControl(): bool;
    public function getLegalFeesResponsibility(): string;
    public function isInventoryRequired(): bool;
    public function isGuarantorAllowed(): bool;
    public function getSecurityDepositRules(): string;
    public function getRenewalConditions(): string;
}
