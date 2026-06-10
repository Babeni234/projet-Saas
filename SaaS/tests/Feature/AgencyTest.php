<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private \App\Models\CompanyProfile $companyProfile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->companyProfile = \App\Models\CompanyProfile::create([
            'user_id' => $this->user->id,
            'business_type' => 'SAS',
            'legal_name' => 'Test Company',
            'registration_number' => '123456789',
            'tax_id' => '987654321',
            'country' => 'FR',
            'address' => '123 Rue de la Paix',
            'city' => 'Paris',
            'postal_code' => '75001',
            'legal_representative_name' => 'Jean Rep',
            'legal_representative_id_number' => 'ID123456',
            'phone' => '+33123456789',
        ]);
        $this->user->update([
            'company_profile_id' => $this->companyProfile->id,
        ]);

        Agency::saving(function ($agency) {
            if (empty($agency->company_profile_id)) {
                $agency->company_profile_id = $this->companyProfile->id;
            }
        });
    }

    public function test_agencies_index_page_requires_auth(): void
    {
        $response = $this->get(route('agencies.index'));
        $response->assertRedirect('/login');
    }

    public function test_agencies_index_page_renders_for_auth_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('agencies.index'));
        $response->assertOk();
    }

    public function test_agencies_index_json_listing(): void
    {
        Agency::create([
            'name' => 'Agence Parisienne',
            'code' => 'AP001',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('agencies.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            'agencies' => [
                'data' => [
                    '*' => ['id', 'name', 'code', 'status', 'created_at']
                ]
            ],
            'filters',
            'stats'
        ]);

        $this->assertEquals('Agence Parisienne', $response->json('agencies.data.0.name'));
    }

    public function test_agency_can_be_stored(): void
    {
        $payload = [
            'name' => 'Nouvelle Agence Centrée',
            'code' => 'NAC44',
            'status' => 'active',
            'email' => 'contact@nac.com',
            'phone' => '+331223344',
            'address' => '12 Rue Principale',
            'city' => 'Paris',
            'country' => 'France',
            'employee_count' => 12,
            'manager_name' => 'Marc Dupont',
            'manager_email' => 'marc@nac.com',
            'manager_phone' => '+336112233',
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('agencies.store'), $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('agency.name', 'Nouvelle Agence Centrée');

        $this->assertDatabaseHas('agencies', [
            'name' => 'Nouvelle Agence Centrée',
            'code' => 'NAC44',
            'manager_name' => 'Marc Dupont',
        ]);
    }

    public function test_agency_store_validation_errors(): void
    {
        // Missing required name, code, status
        $response = $this->actingAs($this->user)
            ->postJson(route('agencies.store'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'code', 'status']);
    }

    public function test_agency_can_be_updated(): void
    {
        $agency = Agency::create([
            'name' => 'Agence Marseille',
            'code' => 'AM002',
            'status' => 'inactive',
        ]);

        $payload = [
            'name' => 'Agence Marseille Modifiee',
            'code' => 'AM002-MOD',
            'status' => 'active',
            'manager_name' => 'Alice Martin',
            'employee_count' => 5,
        ];

        $response = $this->actingAs($this->user)
            ->putJson(route('agencies.update', $agency), $payload);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $this->assertDatabaseHas('agencies', [
            'id' => $agency->id,
            'name' => 'Agence Marseille Modifiee',
            'code' => 'AM002-MOD',
            'status' => 'active',
            'manager_name' => 'Alice Martin',
            'employee_count' => 5,
        ]);
    }

    public function test_agency_update_validation_errors(): void
    {
        $agency1 = Agency::create([
            'name' => 'Agence Alpha',
            'code' => 'A1',
            'status' => 'active',
        ]);

        $agency2 = Agency::create([
            'name' => 'Agence Beta',
            'code' => 'B2',
            'status' => 'active',
        ]);

        // Attempting to change name of agency2 to name of agency1 (which is unique)
        $payload = [
            'name' => 'Agence Alpha',
            'code' => 'B2',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->user)
            ->putJson(route('agencies.update', $agency2), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_agency_can_be_destroyed(): void
    {
        $agency = Agency::create([
            'name' => 'Agence A Supprimer',
            'code' => 'AS003',
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('agencies.destroy', $agency));

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $this->assertSoftDeleted('agencies', [
            'id' => $agency->id,
        ]);
    }

    public function test_agency_statistics_endpoint(): void
    {
        Agency::create(['name' => 'A1', 'code' => 'C1', 'status' => 'active', 'employee_count' => 10]);
        Agency::create(['name' => 'A2', 'code' => 'C2', 'status' => 'inactive', 'employee_count' => 5]);
        Agency::create(['name' => 'A3', 'code' => 'C3', 'status' => 'suspended', 'employee_count' => 2]);

        $response = $this->actingAs($this->user)
            ->getJson(route('agencies.statistics'));

        $response->assertOk();
        $response->assertJson([
            'total' => 3,
            'active' => 1,
            'inactive' => 1,
            'suspended' => 1,
            'total_employees' => 17
        ]);
    }

    public function test_agencies_for_map_endpoint(): void
    {
        Agency::create(['name' => 'Map1', 'code' => 'M1', 'latitude' => 10.0, 'longitude' => 20.0, 'status' => 'active']);
        Agency::create(['name' => 'Map2', 'code' => 'M2', 'latitude' => null, 'longitude' => null, 'status' => 'active']);

        $response = $this->actingAs($this->user)
            ->getJson(route('agencies.map'));

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.name', 'Map1');
    }

    public function test_agencies_bulk_status_update(): void
    {
        $a1 = Agency::create(['name' => 'A1', 'code' => 'C1', 'status' => 'inactive']);
        $a2 = Agency::create(['name' => 'A2', 'code' => 'C2', 'status' => 'suspended']);

        $response = $this->actingAs($this->user)
            ->postJson(route('agencies.bulk-status'), [
                'ids' => [$a1->id, $a2->id],
                'status' => 'active'
            ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $this->assertEquals('active', $a1->fresh()->status);
        $this->assertEquals('active', $a2->fresh()->status);
    }

    public function test_agencies_bulk_delete(): void
    {
        $a1 = Agency::create(['name' => 'A1', 'code' => 'C1', 'status' => 'active']);
        $a2 = Agency::create(['name' => 'A2', 'code' => 'C2', 'status' => 'active']);

        $response = $this->actingAs($this->user)
            ->postJson(route('agencies.bulk-delete'), [
                'ids' => [$a1->id, $a2->id]
            ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        $this->assertSoftDeleted('agencies', ['id' => $a1->id]);
        $this->assertSoftDeleted('agencies', ['id' => $a2->id]);
    }

    public function test_agencies_export_csv(): void
    {
        Agency::create(['name' => 'ExportAgency', 'code' => 'EXP', 'status' => 'active']);

        $response = $this->actingAs($this->user)
            ->get(route('agencies.export'));

        $response->assertOk();
        $response->assertHeaderContains('Content-Type', 'text/csv');
        $this->assertStringContainsString('ExportAgency', $response->getContent());
    }

    public function test_assigning_employee_to_agency_on_create_updates_employee_agency_id(): void
    {
        $managerUser = User::factory()->create([
            'email' => 'manager_test@example.com',
            'company_profile_id' => $this->companyProfile->id,
        ]);
        $employee = \App\Models\Employee::create([
            'user_id' => $managerUser->id,
            'company_profile_id' => $this->companyProfile->id,
            'phone' => '12345678',
            'position' => 'Manager',
        ]);

        $payload = [
            'name' => 'Agency with Employee Manager',
            'code' => 'AWEM1',
            'status' => 'active',
            'manager_name' => $managerUser->name,
            'manager_email' => $managerUser->email,
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('agencies.store'), $payload);

        $response->assertStatus(201);

        $agency = Agency::where('code', 'AWEM1')->first();
        $this->assertNotNull($agency);
        $this->assertEquals($agency->id, $employee->fresh()->agency_id);
    }

    public function test_assigning_employee_to_agency_on_update_updates_employee_agency_id(): void
    {
        $agency = Agency::create([
            'name' => 'Agency for Update Test',
            'code' => 'AUT1',
            'status' => 'active',
            'company_profile_id' => $this->companyProfile->id,
        ]);

        $managerUser = User::factory()->create([
            'email' => 'manager_update@example.com',
            'company_profile_id' => $this->companyProfile->id,
        ]);
        $employee = \App\Models\Employee::create([
            'user_id' => $managerUser->id,
            'company_profile_id' => $this->companyProfile->id,
            'phone' => '12345678',
            'position' => 'Manager',
        ]);

        $payload = [
            'name' => 'Agency for Update Test',
            'code' => 'AUT1',
            'status' => 'active',
            'manager_name' => $managerUser->name,
            'manager_email' => $managerUser->email,
        ];

        $response = $this->actingAs($this->user)
            ->putJson(route('agencies.update', $agency), $payload);

        $response->assertOk();

        $this->assertEquals($agency->id, $employee->fresh()->agency_id);
    }
}
