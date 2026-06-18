<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Models\Tresorerie;
use App\Models\Depense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceStatsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private CompanyProfile $company;
    private Agency $agency1;
    private Agency $agency2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->company = CompanyProfile::create([
            'user_id' => $this->user->id,
            'legal_name' => 'Test Corp',
            'business_type' => 'SAS',
            'registration_number' => '123456789',
            'tax_id' => '987654321',
            'country' => 'FR',
            'address' => '1 Rue Test',
            'city' => 'Paris',
            'postal_code' => '75000',
            'legal_representative_name' => 'Rep Test',
            'legal_representative_id_number' => 'ID9999',
            'phone' => '+33123456789',
        ]);
        $this->user->update(['company_profile_id' => $this->company->id]);

        $this->agency1 = Agency::create([
            'company_profile_id' => $this->company->id,
            'name' => 'Agency Paris',
            'code' => 'AG-PARIS',
            'status' => 'active',
        ]);

        $this->agency2 = Agency::create([
            'company_profile_id' => $this->company->id,
            'name' => 'Agency Lyon',
            'code' => 'AG-LYON',
            'status' => 'active',
        ]);
    }

    public function test_finance_stats_computes_correctly_for_selected_year(): void
    {
        // 1. Create 2026 Inflows (Tresorerie positive)
        // PaiementLoyer (Agency 1) - 3,000,000 XAF
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 3000000.00,
            'date_transaction' => '2026-06-15',
            'source_type' => 'App\Models\PaiementLoyer',
            'source_id' => 1,
            'motif' => 'Loyer A1',
        ]);

        // Facture (Agency 2) - 2,000,000 XAF
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'montant' => 2000000.00,
            'date_transaction' => '2026-06-16',
            'source_type' => 'App\Models\Facture',
            'source_id' => 2,
            'motif' => 'Règlement Facture A2',
        ]);

        // EntreeFonds (Agency 1) - 4,000,000 XAF
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 4000000.00,
            'date_transaction' => '2026-07-01',
            'source_type' => 'App\Models\EntreeFonds',
            'source_id' => 3,
            'motif' => 'Subvention',
        ]);

        // FraisContrat (Headquarters - null agency_id) - 1,000,000 XAF
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => null,
            'montant' => 1000000.00,
            'date_transaction' => '2026-02-10',
            'source_type' => 'App\Models\FraisContrat',
            'source_id' => 4,
            'motif' => 'Frais Contrat HQ',
        ]);

        // 2. Create 2025 Inflows (should be excluded for year 2026)
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 5000000.00,
            'date_transaction' => '2025-06-15',
            'source_type' => 'App\Models\PaiementLoyer',
            'source_id' => 5,
            'motif' => 'Loyer A1 2025',
        ]);

        // 3. Create 2026 Outflows (paid Depenses)
        // Paid expense Agency 1: 2,500,000 XAF
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 2500000.00,
            'date_depense' => '2026-05-10',
            'statut' => 'Payé',
            'titre' => 'Achat Bureau',
        ]);

        // Paid expense Agency 2: 1,500,000 XAF
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'montant' => 1500000.00,
            'date_depense' => '2026-06-15',
            'statut' => 'Payé',
            'titre' => 'Climatisation',
        ]);

        // Paid expense Siège (null): 1,000,000 XAF
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => null,
            'montant' => 1000000.00,
            'date_depense' => '2026-08-20',
            'statut' => 'Payé',
            'titre' => 'Honoraires Avocat',
        ]);

        // Unpaid expense 2026 (should be excluded)
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 800000.00,
            'date_depense' => '2026-05-10',
            'statut' => 'En attente',
            'titre' => 'Facture électricité',
        ]);

        // 4. Create 2025 Outflows (should be excluded for year 2026)
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 3000000.00,
            'date_depense' => '2025-06-15',
            'statut' => 'Payé',
            'titre' => 'Frais 2025',
        ]);

        // --- Execute request for 2026 ---
        $response = $this->actingAs($this->user)->getJson(route('finance.stats', ['year' => 2026]));
        $response->assertOk();

        // 10M total revenue, 5M total expenses
        $this->assertEquals(2026, $response->json('year'));
        $this->assertEquals(10000000.00, $response->json('kpis.revenue'));
        $this->assertEquals(5000000.00, $response->json('kpis.expenses'));
        $this->assertEquals(5000000.00, $response->json('kpis.netCash'));
        $this->assertEquals(50.0, $response->json('kpis.profitMargin'));

        // YoY compared to 2025 (5M rev, 3M exp, 2M netCash)
        $this->assertEquals(100.0, $response->json('kpis.revenue_change'));
        $this->assertEquals(66.7, $response->json('kpis.expenses_change'));
        $this->assertEquals(150.0, $response->json('kpis.net_cash_change'));

        // Check monthly cashflows
        // Inflows: Jan (0), Feb (1000000), Mar (0), Apr (0), May (0), Jun (3000000 + 2000000 = 5000000), Jul (4000000)...
        $this->assertEquals(1000000.00, $response->json('chart_monthly.inflows.1')); // Feb
        $this->assertEquals(5000000.00, $response->json('chart_monthly.inflows.5')); // Jun
        $this->assertEquals(4000000.00, $response->json('chart_monthly.inflows.6')); // Jul

        // Check structure
        $this->assertEquals(3000000.00, $response->json('chart_structure.loyers'));
        $this->assertEquals(2000000.00, $response->json('chart_structure.factures'));
        $this->assertEquals(4000000.00, $response->json('chart_structure.entrees_fonds'));
        $this->assertEquals(1000000.00, $response->json('chart_structure.frais_contrats'));

        // Check entity performance breakdown
        $entities = $response->json('entities');
        $this->assertCount(3, $entities);

        // First entity is Siège Social
        $this->assertEquals('Siège Social', $entities[0]['nom']);
        $this->assertEquals('Siège', $entities[0]['type']);
        $this->assertEquals(0.00, $entities[0]['loyers']);
        $this->assertEquals(1000000.00, $entities[0]['divers']);
        $this->assertEquals(1000000.00, $entities[0]['depenses']);
        $this->assertEquals(0.00, $entities[0]['solde']);

        // Second entity is Agency Paris (agency1)
        $this->assertEquals('Agency Paris', $entities[1]['nom']);
        $this->assertEquals('Agence', $entities[1]['type']);
        $this->assertEquals(3000000.00, $entities[1]['loyers']);
        $this->assertEquals(4000000.00, $entities[1]['divers']);
        $this->assertEquals(2500000.00, $entities[1]['depenses']);
        $this->assertEquals(4500000.00, $entities[1]['solde']);

        // Third entity is Agency Lyon (agency2)
        $this->assertEquals('Agency Lyon', $entities[2]['nom']);
        $this->assertEquals('Agence', $entities[2]['type']);
        $this->assertEquals(2000000.00, $entities[2]['loyers']);
        $this->assertEquals(0.00, $entities[2]['divers']);
        $this->assertEquals(1500000.00, $entities[2]['depenses']);
        $this->assertEquals(500000.00, $entities[2]['solde']);

        // --- Execute request for 2025 ---
        $response2 = $this->actingAs($this->user)->getJson(route('finance.stats', ['year' => 2025]));
        $response2->assertOk();

        // 5M revenue, 3M expenses
        $this->assertEquals(2025, $response2->json('year'));
        $this->assertEquals(5000000.00, $response2->json('kpis.revenue'));
        $this->assertEquals(3000000.00, $response2->json('kpis.expenses'));
        $this->assertEquals(2000000.00, $response2->json('kpis.netCash'));
        $this->assertEquals(40.0, $response2->json('kpis.profitMargin'));
    }
}
