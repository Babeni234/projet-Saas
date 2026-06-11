<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\CompanyProfile;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgencyPortalTest extends TestCase
{
    use RefreshDatabase;

    private User $companyUser;
    private CompanyProfile $companyProfile;
    private Agency $agencyA;
    private Agency $agencyB;
    private Role $roleAdmin;
    private Role $roleChef;
    private Role $roleAgent;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create company user & profile
        $this->companyUser = User::factory()->create();
        $this->companyProfile = CompanyProfile::create([
            'user_id' => $this->companyUser->id,
            'business_type' => 'SAS',
            'legal_name' => 'Company Agency Test',
            'registration_number' => '112233445',
            'tax_id' => '556677889',
            'country' => 'FR',
            'address' => '10 Rue de Paris',
            'city' => 'Paris',
            'postal_code' => '75002',
            'legal_representative_name' => 'Jean Rep',
            'legal_representative_id_number' => 'ID778899',
            'phone' => '+33123456780',
        ]);
        $this->companyUser->update([
            'company_profile_id' => $this->companyProfile->id,
        ]);

        // 2. Create agencies
        $this->agencyA = Agency::create([
            'name' => 'Agency Paris A',
            'code' => 'AG-A001',
            'status' => 'active',
            'company_profile_id' => $this->companyProfile->id,
        ]);

        $this->agencyB = Agency::create([
            'name' => 'Agency Lyon B',
            'code' => 'AG-B001',
            'status' => 'active',
            'company_profile_id' => $this->companyProfile->id,
        ]);

        // 3. Create company roles
        $this->roleAdmin = Role::create([
            'company_profile_id' => $this->companyProfile->id,
            'name' => 'Administrateur',
            'slug' => 'admin',
        ]);

        $this->roleChef = Role::create([
            'company_profile_id' => $this->companyProfile->id,
            'name' => 'Chef d\'Agence',
            'slug' => 'chef_agence',
        ]);

        $this->roleAgent = Role::create([
            'company_profile_id' => $this->companyProfile->id,
            'name' => 'Agent Immobilier',
            'slug' => 'agent_immobilier',
        ]);
    }

    /**
     * Test redirection for logged-in agency employee to /agence/dashboard.
     */
    public function test_agency_employees_are_redirected_to_agency_dashboard(): void
    {
        $employeeUser = User::factory()->create([
            'company_profile_id' => $this->companyProfile->id,
            'role_id' => $this->roleChef->id,
        ]);

        Employee::create([
            'user_id' => $employeeUser->id,
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyA->id,
            'phone' => '+336112233',
            'position' => 'Chef de bureau',
        ]);

        $response = $this->actingAs($employeeUser)->get('/dashboard');

        $response->assertRedirect(route('agence.dashboard'));
    }

    /**
     * Test that company owners/promoters do not get redirected and see the standard dashboard.
     */
    public function test_company_promoters_see_standard_dashboard(): void
    {
        $response = $this->actingAs($this->companyUser)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('entreprise/Dashboard/Dashboard');
    }

    /**
     * Test that employees index returns only the agency's collaborators and excludes restricted roles.
     */
    public function test_employees_listing_is_scoped_to_agency_and_excludes_restricted_roles(): void
    {
        // Collaborator in Agency A
        $userA = User::factory()->create([
            'company_profile_id' => $this->companyProfile->id,
            'role_id' => $this->roleAgent->id,
            'name' => 'Agent Parisien',
        ]);
        Employee::create([
            'user_id' => $userA->id,
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyA->id,
        ]);

        // Collaborator in Agency B
        $userB = User::factory()->create([
            'company_profile_id' => $this->companyProfile->id,
            'role_id' => $this->roleAgent->id,
            'name' => 'Agent Lyonnais',
        ]);
        Employee::create([
            'user_id' => $userB->id,
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyB->id,
        ]);

        // Authenticate as User A (Agency A)
        $response = $this->actingAs($userA)->getJson(route('agence.employees.data'));

        $response->assertOk();
        
        // Assert User A sees themselves (Agency A) but NOT User B (Agency B)
        $response->assertJsonFragment(['name' => 'Agent Parisien']);
        $response->assertJsonMissing(['name' => 'Agent Lyonnais']);

        // Assert role listing excludes 'admin' and 'chef_agence'
        $response->assertJsonMissing(['slug' => 'admin']);
        $response->assertJsonMissing(['slug' => 'chef_agence']);
        $response->assertJsonFragment(['slug' => 'agent_immobilier']);
    }

    /**
     * Test collaborator creation automatically injects creator's agency_id.
     */
    public function test_agency_employee_creation_auto_injects_agency_id(): void
    {
        $creator = User::factory()->create([
            'company_profile_id' => $this->companyProfile->id,
            'role_id' => $this->roleChef->id,
        ]);
        Employee::create([
            'user_id' => $creator->id,
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyA->id,
        ]);

        $payload = [
            'name' => 'Nouveau Collaborateur A',
            'email' => 'collaborateurA@test.com',
            'password' => 'password123',
            'role_id' => $this->roleAgent->id,
            'position' => 'Consultant junior',
            'phone' => '+337998877',
        ];

        $response = $this->actingAs($creator)->postJson(route('agence.employees.store'), $payload);

        $response->assertOk();
        $response->assertJsonPath('success', true);

        // Verify the database has the new user and employee
        $this->assertDatabaseHas('users', [
            'email' => 'collaborateurA@test.com',
            'company_profile_id' => $this->companyProfile->id,
        ]);

        $newUser = User::where('email', 'collaborateurA@test.com')->first();
        $this->assertNotNull($newUser->employee);
        
        // Assert the agency ID matches the creator's agency ID (agencyA)
        $this->assertEquals($this->agencyA->id, $newUser->employee->agency_id);
        $this->assertEquals('Consultant junior', $newUser->employee->position);
    }

    /**
     * Test collaborator creation fails if attempting to assign restricted roles (e.g. Admin or Chef d'agence).
     */
    public function test_agency_employee_creation_fails_with_restricted_roles(): void
    {
        $creator = User::factory()->create([
            'company_profile_id' => $this->companyProfile->id,
            'role_id' => $this->roleChef->id,
        ]);
        Employee::create([
            'user_id' => $creator->id,
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyA->id,
        ]);

        $payload = [
            'name' => 'Restricted Role Test',
            'email' => 'restricted@test.com',
            'password' => 'password123',
            'role_id' => $this->roleAdmin->id, // Admin role should be rejected
        ];

        $response = $this->actingAs($creator)->postJson(route('agence.employees.store'), $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['role_id']);
    }
}
