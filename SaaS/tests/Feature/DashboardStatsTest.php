<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Models\Locataire;
use App\Models\Contrat;
use App\Models\Facture;
use App\Models\TypeFacture;
use App\Models\Depense;
use App\Models\TypeDepense;
use App\Models\Tresorerie;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardStatsTest extends TestCase
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

    public function test_enterprise_dashboard_stats_aggregates_all(): void
    {
        // 1. Create a Type de dépense
        $typeDep = TypeDepense::create([
            'company_profile_id' => $this->company->id,
            'nom' => 'Maintenance',
        ]);

        // 2. Create paid expenses
        // Agency 1 paid expense: 1000
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'type_depense_id' => $typeDep->id,
            'titre' => 'Reparation fuite',
            'montant' => 1000.00,
            'date_depense' => now(),
            'statut' => 'Payé',
        ]);

        // Agency 2 paid expense: 500
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'type_depense_id' => $typeDep->id,
            'titre' => 'Achat ampoules',
            'montant' => 500.00,
            'date_depense' => now(),
            'statut' => 'Payé',
        ]);

        // 3. Create positive Tresorerie records (Revenues)
        // Agency 1 revenue: 3000
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 3000.00,
            'date_transaction' => now(),
            'source_type' => 'App\Models\PaiementLoyer',
            'source_id' => 1,
            'motif' => 'Loyer Juin 2026',
        ]);

        // Agency 2 revenue: 2000
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'montant' => 2000.00,
            'date_transaction' => now(),
            'source_type' => 'App\Models\PaiementLoyer',
            'source_id' => 2,
            'motif' => 'Loyer Juin 2026',
        ]);

        // 4. Create unpaid and paid Invoices
        $locataire = Locataire::create([
            'company_profile_id' => $this->company->id,
            'user_id' => User::factory()->create()->id,
            'telephone' => '123456789',
        ]);
        
        $typeFacture = TypeFacture::create([
            'company_profile_id' => $this->company->id,
            'nom' => 'Loyer',
        ]);

        // Unpaid Invoice
        Facture::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'locataire_id' => $locataire->id,
            'type_facture_id' => $typeFacture->id,
            'date_emission' => now(),
            'date_echeance' => now()->addDays(5),
            'items' => [],
            'total' => 800.00,
            'statut' => 'Impayé',
        ]);

        // Paid Invoice (should NOT be returned in unpaid_invoices)
        Facture::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'locataire_id' => $locataire->id,
            'type_facture_id' => $typeFacture->id,
            'date_emission' => now(),
            'date_echeance' => now()->addDays(5),
            'items' => [],
            'total' => 1200.00,
            'statut' => 'Payé',
        ]);

        // Call endpoint acting as the enterprise user
        $response = $this->actingAs($this->user)->getJson(route('dashboard.stats'));

        $response->assertOk();
        
        // We expect total revenue to be 3000 + 2000 = 5000
        $this->assertEquals(5000.00, $response->json('kpis.total_revenue'));

        // We expect total expenses to be 1000 + 500 = 1500
        $this->assertEquals(1500.00, $response->json('kpis.total_expenses'));

        // We expect cashflow_net to be 5000 - 1500 = 3500
        $this->assertEquals(3500.00, $response->json('kpis.cashflow_net'));

        // We expect only 1 unpaid invoice (the one with status 'Impayé')
        $this->assertCount(1, $response->json('unpaid_invoices'));
        $this->assertEquals(800.00, $response->json('unpaid_invoices.0.total'));

        // We expect the breakdown chart by type to contain Maintenance = 1500
        $this->assertEquals(1500.00, $response->json('chart_expenses_by_type.Maintenance'));

        // Check new fields
        $this->assertNotNull($response->json('kpis.revenue_actual'));
        $this->assertNotNull($response->json('kpis.revenue_last_month'));
        $this->assertNotNull($response->json('kpis.revenue_change_percent'));
        $this->assertNotNull($response->json('kpis.expenses_actual'));
        $this->assertNotNull($response->json('kpis.expenses_last_month'));
        $this->assertNotNull($response->json('kpis.expenses_change_percent'));
        $this->assertNotNull($response->json('kpis.profit_actual'));
        $this->assertNotNull($response->json('kpis.profit_margin'));
        $this->assertNotNull($response->json('kpis.unpaid_invoices_total'));
        $this->assertNotNull($response->json('kpis.unpaid_invoices_count'));
        $this->assertNotNull($response->json('kpis.unpaid_invoices_overdue_count'));
        $this->assertNotNull($response->json('kpis.unpaid_rate'));
        $this->assertNotNull($response->json('unpaid_period_data'));
        $this->assertNotNull($response->json('active_contracts'));
    }

    public function test_agency_dashboard_stats_scopes_strictly_to_agency(): void
    {
        // 1. Create a Type de dépense
        $typeDep = TypeDepense::create([
            'company_profile_id' => $this->company->id,
            'nom' => 'Maintenance',
        ]);

        // 2. Create paid expenses
        // Agency 1 paid expense: 1000
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'type_depense_id' => $typeDep->id,
            'titre' => 'Reparation fuite',
            'montant' => 1000.00,
            'date_depense' => now(),
            'statut' => 'Payé',
        ]);

        // Agency 2 paid expense: 500 (should be excluded for Agency 1)
        Depense::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'type_depense_id' => $typeDep->id,
            'titre' => 'Achat ampoules',
            'montant' => 500.00,
            'date_depense' => now(),
            'statut' => 'Payé',
        ]);

        // 3. Create positive Tresorerie records (Revenues)
        // Agency 1 revenue: 3000
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency1->id,
            'montant' => 3000.00,
            'date_transaction' => now(),
            'source_type' => 'App\Models\PaiementLoyer',
            'source_id' => 1,
            'motif' => 'Loyer Juin 2026',
        ]);

        // Agency 2 revenue: 2000 (should be excluded for Agency 1)
        Tresorerie::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency2->id,
            'montant' => 2000.00,
            'date_transaction' => now(),
            'source_type' => 'App\Models\PaiementLoyer',
            'source_id' => 2,
            'motif' => 'Loyer Juin 2026',
        ]);

        // 4. Setup an employee user for Agency 1
        $employeeUser = User::factory()->create();
        $employeeUser->update(['company_profile_id' => $this->company->id]);
        
        Employee::create([
            'company_profile_id' => $this->company->id,
            'user_id' => $employeeUser->id,
            'agency_id' => $this->agency1->id,
            'first_name' => 'Agent',
            'last_name' => 'One',
            'email' => 'agent1@test.com',
            'status' => 'active',
        ]);

        // Call endpoint acting as the Agency 1 employee user
        $response = $this->actingAs($employeeUser)->getJson(route('dashboard.stats'));

        $response->assertOk();

        // Scoped values check:
        // Revenue should be exactly 3000
        $this->assertEquals(3000.00, $response->json('kpis.total_revenue'));

        // Expenses should be exactly 1000
        $this->assertEquals(1000.00, $response->json('kpis.total_expenses'));

        // Net cashflow should be 3000 - 1000 = 2000
        $this->assertEquals(2000.00, $response->json('kpis.cashflow_net'));

        // Breakdown should only count Agency 1's Maintenance expense: 1000
        $this->assertEquals(1000.00, $response->json('chart_expenses_by_type.Maintenance'));
    }
}
