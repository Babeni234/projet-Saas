<?php

namespace App\Services\Legal;

class LegalFrameworkService
{
    protected array $countries = [];

    public function __construct()
    {
        $this->countries = [
            'FR' => new Countries\France(),
            'BE' => new Countries\Belgique(),
            'CH' => new Countries\Suisse(),
            'CA' => new Countries\Canada(),
        ];
    }

    public function getCountry(string $code): CountryInterface
    {
        return $this->countries[$code] ?? new Countries\Generic();
    }

    public function getDefaultCountry(): CountryInterface
    {
        return $this->countries['FR'] ?? new Countries\Generic();
    }

    public function getAvailableCountries(): array
    {
        return [
            'FR' => ['name' => 'France', 'flag' => '🇫🇷', 'regions' => ['metro' => 'France métropolitaine', '971' => 'Guadeloupe', '972' => 'Martinique', '973' => 'Guyane', '974' => 'La Réunion', '976' => 'Mayotte']],
            'BE' => ['name' => 'Belgique', 'flag' => '🇧🇪', 'regions' => ['bruxelles' => 'Région de Bruxelles-Capitale', 'flandre' => 'Région flamande', 'wallonie' => 'Région wallonne']],
            'CH' => ['name' => 'Suisse', 'flag' => '🇨🇭', 'regions' => ['fr' => 'Suisse romande', 'de' => 'Suisse alémanique', 'it' => 'Suisse italienne']],
            'CA' => ['name' => 'Canada', 'flag' => '🇨🇦', 'regions' => ['qc' => 'Québec', 'on' => 'Ontario', 'bc' => 'Colombie-Britannique', 'ab' => 'Alberta']],
            'AF' => ['name' => 'Afghanistan', 'flag' => '🇦🇫'], 'AL' => ['name' => 'Albanie', 'flag' => '🇦🇱'], 'DZ' => ['name' => 'Algérie', 'flag' => '🇩🇿'], 'DE' => ['name' => 'Allemagne', 'flag' => '🇩🇪'], 'AD' => ['name' => 'Andorre', 'flag' => '🇦🇩'], 'AO' => ['name' => 'Angola', 'flag' => '🇦🇴'], 'AG' => ['name' => 'Antigua-et-Barbuda', 'flag' => '🇦🇬'], 'SA' => ['name' => 'Arabie saoudite', 'flag' => '🇸🇦'], 'AR' => ['name' => 'Argentine', 'flag' => '🇦🇷'], 'AM' => ['name' => 'Arménie', 'flag' => '🇦🇲'], 'AU' => ['name' => 'Australie', 'flag' => '🇦🇺'], 'AT' => ['name' => 'Autriche', 'flag' => '🇦🇹'], 'AZ' => ['name' => 'Azerbaïdjan', 'flag' => '🇦🇿'],
            'BS' => ['name' => 'Bahamas', 'flag' => '🇧🇸'], 'BH' => ['name' => 'Bahreïn', 'flag' => '🇧🇭'], 'BD' => ['name' => 'Bangladesh', 'flag' => '🇧🇩'], 'BB' => ['name' => 'Barbade', 'flag' => '🇧🇧'], 'BY' => ['name' => 'Biélorussie', 'flag' => '🇧🇾'], 'MM' => ['name' => 'Birmanie', 'flag' => '🇲🇲'], 'BO' => ['name' => 'Bolivie', 'flag' => '🇧🇴'], 'BA' => ['name' => 'Bosnie-Herzégovine', 'flag' => '🇧🇦'], 'BW' => ['name' => 'Botswana', 'flag' => '🇧🇼'], 'BR' => ['name' => 'Brésil', 'flag' => '🇧🇷'], 'BN' => ['name' => 'Brunei', 'flag' => '🇧🇳'], 'BG' => ['name' => 'Bulgarie', 'flag' => '🇧🇬'], 'BF' => ['name' => 'Burkina Faso', 'flag' => '🇧🇫'], 'BI' => ['name' => 'Burundi', 'flag' => '🇧🇮'],
            'KH' => ['name' => 'Cambodge', 'flag' => '🇰🇭'], 'CM' => ['name' => 'Cameroun', 'flag' => '🇨🇲'], 'CV' => ['name' => 'Cap-Vert', 'flag' => '🇨🇻'], 'CF' => ['name' => 'République centrafricaine', 'flag' => '🇨🇫'], 'CL' => ['name' => 'Chili', 'flag' => '🇨🇱'], 'CN' => ['name' => 'Chine', 'flag' => '🇨🇳'], 'CY' => ['name' => 'Chypre', 'flag' => '🇨🇾'], 'CO' => ['name' => 'Colombie', 'flag' => '🇨🇴'], 'KM' => ['name' => 'Comores', 'flag' => '🇰🇲'], 'CG' => ['name' => 'Congo', 'flag' => '🇨🇬'], 'CD' => ['name' => 'République démocratique du Congo', 'flag' => '🇨🇩'], 'KP' => ['name' => 'Corée du Nord', 'flag' => '🇰🇵'], 'KR' => ['name' => 'Corée du Sud', 'flag' => '🇰🇷'], 'CR' => ['name' => 'Costa Rica', 'flag' => '🇨🇷'], 'CI' => ['name' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'], 'HR' => ['name' => 'Croatie', 'flag' => '🇭🇷'], 'CU' => ['name' => 'Cuba', 'flag' => '🇨🇺'],
            'DK' => ['name' => 'Danemark', 'flag' => '🇩🇰'], 'DJ' => ['name' => 'Djibouti', 'flag' => '🇩🇯'], 'DM' => ['name' => 'Dominique', 'flag' => '🇩🇲'], 'EG' => ['name' => 'Égypte', 'flag' => '🇪🇬'], 'AE' => ['name' => 'Émirats arabes unis', 'flag' => '🇦🇪'], 'EC' => ['name' => 'Équateur', 'flag' => '🇪🇨'], 'ER' => ['name' => 'Érythrée', 'flag' => '🇪🇷'], 'ES' => ['name' => 'Espagne', 'flag' => '🇪🇸'], 'EE' => ['name' => 'Estonie', 'flag' => '🇪🇪'], 'US' => ['name' => 'États-Unis', 'flag' => '🇺🇸'], 'ET' => ['name' => 'Éthiopie', 'flag' => '🇪🇹'],
            'FJ' => ['name' => 'Fidji', 'flag' => '🇫🇯'], 'FI' => ['name' => 'Finlande', 'flag' => '🇫🇮'], 'GA' => ['name' => 'Gabon', 'flag' => '🇬🇦'], 'GM' => ['name' => 'Gambie', 'flag' => '🇬🇲'], 'GE' => ['name' => 'Géorgie', 'flag' => '🇬🇪'], 'GH' => ['name' => 'Ghana', 'flag' => '🇬🇭'], 'GR' => ['name' => 'Grèce', 'flag' => '🇬🇷'], 'GD' => ['name' => 'Grenade', 'flag' => '🇬🇩'], 'GT' => ['name' => 'Guatemala', 'flag' => '🇬🇹'], 'GN' => ['name' => 'Guinée', 'flag' => '🇬🇳'], 'GW' => ['name' => 'Guinée-Bissau', 'flag' => '🇬🇼'], 'GQ' => ['name' => 'Guinée équatoriale', 'flag' => '🇬🇶'], 'GY' => ['name' => 'Guyana', 'flag' => '🇬🇾'],
            'HT' => ['name' => 'Haïti', 'flag' => '🇭🇹'], 'HN' => ['name' => 'Honduras', 'flag' => '🇭🇳'], 'HU' => ['name' => 'Hongrie', 'flag' => '🇭🇺'],
            'IS' => ['name' => 'Islande', 'flag' => '🇮🇸'], 'IN' => ['name' => 'Inde', 'flag' => '🇮🇳'], 'ID' => ['name' => 'Indonésie', 'flag' => '🇮🇩'], 'IR' => ['name' => 'Iran', 'flag' => '🇮🇷'], 'IQ' => ['name' => 'Irak', 'flag' => '🇮🇶'], 'IE' => ['name' => 'Irlande', 'flag' => '🇮🇪'], 'IL' => ['name' => 'Israël', 'flag' => '🇮🇱'], 'IT' => ['name' => 'Italie', 'flag' => '🇮🇹'],
            'JM' => ['name' => 'Jamaïque', 'flag' => '🇯🇲'], 'JP' => ['name' => 'Japon', 'flag' => '🇯🇵'], 'JO' => ['name' => 'Jordanie', 'flag' => '🇯🇴'],
            'KZ' => ['name' => 'Kazakhstan', 'flag' => '🇰🇿'], 'KE' => ['name' => 'Kenya', 'flag' => '🇰🇪'], 'KG' => ['name' => 'Kirghizistan', 'flag' => '🇰🇬'], 'KI' => ['name' => 'Kiribati', 'flag' => '🇰🇮'], 'KW' => ['name' => 'Koweït', 'flag' => '🇰🇼'],
            'LA' => ['name' => 'Laos', 'flag' => '🇱🇦'], 'LS' => ['name' => 'Lesotho', 'flag' => '🇱🇸'], 'LV' => ['name' => 'Lettonie', 'flag' => '🇱🇻'], 'LB' => ['name' => 'Liban', 'flag' => '🇱🇧'], 'LR' => ['name' => 'Libéria', 'flag' => '🇱🇷'], 'LY' => ['name' => 'Libye', 'flag' => '🇱🇾'], 'LI' => ['name' => 'Liechtenstein', 'flag' => '🇱🇮'], 'LT' => ['name' => 'Lituanie', 'flag' => '🇱🇹'], 'LU' => ['name' => 'Luxembourg', 'flag' => '🇱🇺'],
            'MK' => ['name' => 'Macédoine du Nord', 'flag' => '🇲🇰'], 'MG' => ['name' => 'Madagascar', 'flag' => '🇲🇬'], 'MY' => ['name' => 'Malaisie', 'flag' => '🇲🇾'], 'MW' => ['name' => 'Malawi', 'flag' => '🇲🇼'], 'MV' => ['name' => 'Maldives', 'flag' => '🇲🇻'], 'ML' => ['name' => 'Mali', 'flag' => '🇲🇱'], 'MT' => ['name' => 'Malte', 'flag' => '🇲🇹'], 'MA' => ['name' => 'Maroc', 'flag' => '🇲🇦'], 'MH' => ['name' => 'Îles Marshall', 'flag' => '🇲🇭'], 'MR' => ['name' => 'Mauritanie', 'flag' => '🇲🇷'], 'MU' => ['name' => 'Maurice', 'flag' => '🇲🇺'], 'MX' => ['name' => 'Mexique', 'flag' => '🇲🇽'], 'FM' => ['name' => 'Micronésie', 'flag' => '🇫🇲'], 'MD' => ['name' => 'Moldavie', 'flag' => '🇲🇩'], 'MC' => ['name' => 'Monaco', 'flag' => '🇲🇨'], 'MN' => ['name' => 'Mongolie', 'flag' => '🇲🇳'], 'ME' => ['name' => 'Monténégro', 'flag' => '🇲🇪'], 'MZ' => ['name' => 'Mozambique', 'flag' => '🇲🇿'],
            'NA' => ['name' => 'Namibie', 'flag' => '🇳🇦'], 'NR' => ['name' => 'Nauru', 'flag' => '🇳🇷'], 'NP' => ['name' => 'Népal', 'flag' => '🇳🇵'], 'NI' => ['name' => 'Nicaragua', 'flag' => '🇳🇮'], 'NE' => ['name' => 'Niger', 'flag' => '🇳🇪'], 'NG' => ['name' => 'Nigeria', 'flag' => '🇳🇬'], 'NO' => ['name' => 'Norvège', 'flag' => '🇳🇴'], 'NZ' => ['name' => 'Nouvelle-Zélande', 'flag' => '🇳🇿'],
            'OM' => ['name' => 'Oman', 'flag' => '🇴🇲'], 'UG' => ['name' => 'Ouganda', 'flag' => '🇺🇬'], 'UZ' => ['name' => 'Ouzbékistan', 'flag' => '🇺🇿'],
            'PK' => ['name' => 'Pakistan', 'flag' => '🇵🇰'], 'PW' => ['name' => 'Palaos', 'flag' => '🇵🇼'], 'PA' => ['name' => 'Panama', 'flag' => '🇵🇦'], 'PG' => ['name' => 'Papouasie-Nouvelle-Guinée', 'flag' => '🇵🇬'], 'PY' => ['name' => 'Paraguay', 'flag' => '🇵🇾'], 'NL' => ['name' => 'Pays-Bas', 'flag' => '🇳🇱'], 'PE' => ['name' => 'Pérou', 'flag' => '🇵🇪'], 'PH' => ['name' => 'Philippines', 'flag' => '🇵🇭'], 'PL' => ['name' => 'Pologne', 'flag' => '🇵🇱'], 'PT' => ['name' => 'Portugal', 'flag' => '🇵🇹'],
            'QA' => ['name' => 'Qatar', 'flag' => '🇶🇦'],
            'RO' => ['name' => 'Roumanie', 'flag' => '🇷🇴'], 'GB' => ['name' => 'Royaume-Uni', 'flag' => '🇬🇧'], 'RU' => ['name' => 'Russie', 'flag' => '🇷🇺'], 'RW' => ['name' => 'Rwanda', 'flag' => '🇷🇼'],
            'KN' => ['name' => 'Saint-Christophe-et-Niévès', 'flag' => '🇰🇳'], 'SM' => ['name' => 'Saint-Marin', 'flag' => '🇸🇲'], 'VC' => ['name' => 'Saint-Vincent-et-les-Grenadines', 'flag' => '🇻🇨'], 'LC' => ['name' => 'Sainte-Lucie', 'flag' => '🇱🇨'], 'SB' => ['name' => 'Îles Salomon', 'flag' => '🇸🇧'], 'SV' => ['name' => 'Salvador', 'flag' => '🇸🇻'], 'WS' => ['name' => 'Samoa', 'flag' => '🇼🇸'], 'ST' => ['name' => 'Sao Tomé-et-Principe', 'flag' => '🇸🇹'], 'SN' => ['name' => 'Sénégal', 'flag' => '🇸🇳'], 'RS' => ['name' => 'Serbie', 'flag' => '🇷🇸'], 'SC' => ['name' => 'Seychelles', 'flag' => '🇸🇨'], 'SL' => ['name' => 'Sierra Leone', 'flag' => '🇸🇱'], 'SG' => ['name' => 'Singapour', 'flag' => '🇸🇬'], 'SK' => ['name' => 'Slovaquie', 'flag' => '🇸🇰'], 'SI' => ['name' => 'Slovénie', 'flag' => '🇸🇮'], 'SO' => ['name' => 'Somalie', 'flag' => '🇸🇴'], 'SD' => ['name' => 'Soudan', 'flag' => '🇸🇩'], 'SS' => ['name' => 'Soudan du Sud', 'flag' => '🇸🇸'], 'SR' => ['name' => 'Suriname', 'flag' => '🇸🇷'], 'SE' => ['name' => 'Suède', 'flag' => '🇸🇪'], 'SY' => ['name' => 'Syrie', 'flag' => '🇸🇾'],
            'TJ' => ['name' => 'Tadjikistan', 'flag' => '🇹🇯'], 'TZ' => ['name' => 'Tanzanie', 'flag' => '🇹🇿'], 'TD' => ['name' => 'Tchad', 'flag' => '🇹🇩'], 'CZ' => ['name' => 'Tchéquie', 'flag' => '🇨🇿'], 'TH' => ['name' => 'Thaïlande', 'flag' => '🇹🇭'], 'TL' => ['name' => 'Timor oriental', 'flag' => '🇹🇱'], 'TG' => ['name' => 'Togo', 'flag' => '🇹🇬'], 'TO' => ['name' => 'Tonga', 'flag' => '🇹🇴'], 'TT' => ['name' => 'Trinité-et-Tobago', 'flag' => '🇹🇹'], 'TN' => ['name' => 'Tunisie', 'flag' => '🇹🇳'], 'TM' => ['name' => 'Turkménistan', 'flag' => '🇹🇲'], 'TR' => ['name' => 'Turquie', 'flag' => '🇹🇷'], 'TV' => ['name' => 'Tuvalu', 'flag' => '🇹🇻'],
            'UA' => ['name' => 'Ukraine', 'flag' => '🇺🇦'], 'UY' => ['name' => 'Uruguay', 'flag' => '🇺🇾'],
            'VU' => ['name' => 'Vanuatu', 'flag' => '🇻🇺'], 'VA' => ['name' => 'Vatican', 'flag' => '🇻🇦'], 'VE' => ['name' => 'Venezuela', 'flag' => '🇻🇪'], 'VN' => ['name' => 'Viêt Nam', 'flag' => '🇻🇳'],
            'YE' => ['name' => 'Yémen', 'flag' => '🇾🇪'],
            'ZM' => ['name' => 'Zambie', 'flag' => '🇿🇲'], 'ZW' => ['name' => 'Zimbabwe', 'flag' => '🇿🇼'],
        ];
    }

    public function getCountryName(string $code): string
    {
        $list = $this->getAvailableCountries();
        return $list[$code]['name'] ?? 'Autre pays';
    }

    public function getApplicableTexts(string $code, ?string $region = null): array
    {
        $country = $this->getCountry($code);

        return [
            'name' => $country->getName(),
            'currency' => $country->getCurrency(),
            'locale' => $country->getLocale(),
            'timezone' => $country->getTimezone(),
            'lease_default_duration' => $country->getLeaseDefaultDuration(),
            'notice_period_months' => $country->getNoticePeriodMonths(),
            'deposit_max_months' => $country->getDepositMaxMonths(),
            'rent_increase_rules' => $country->getRentIncreaseRules(),
            'required_documents' => $country->getRequiredDocuments(),
            'laws' => $country->getApplicableLaws(),
            'tax_rules' => $country->getTaxRules(),
            'eviction_grounds' => $country->getEvictionGrounds(),
            'termination_notice_tenant' => $country->getTerminationNoticeTenant(),
            'termination_notice_landlord' => $country->getTerminationNoticeLandlord(),
            'diagnostics_required' => $country->getDiagnosticsRequired(),
            'has_rent_control' => $country->hasRentControl(),
            'legal_fees_responsibility' => $country->getLegalFeesResponsibility(),
            'inventory_required' => $country->isInventoryRequired(),
            'guarantor_allowed' => $country->isGuarantorAllowed(),
            'security_deposit_rules' => $country->getSecurityDepositRules(),
            'renewal_conditions' => $country->getRenewalConditions(),
        ];
    }

    public function getForUser(\App\Models\User $user): CountryInterface
    {
        $code = $user->country ?? 'FR';
        return $this->getCountry($code);
    }

    public function getApplicableTextsForUser(\App\Models\User $user): array
    {
        $code = $user->country ?? 'FR';
        $region = $user->region;
        return $this->getApplicableTexts($code, $region);
    }
}
