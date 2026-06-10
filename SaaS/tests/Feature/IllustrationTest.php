<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\CompanyProfile;
use App\Models\Employee;
use App\Models\Illustration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IllustrationTest extends TestCase
{
    use RefreshDatabase;

    private User $companyUser;
    private CompanyProfile $companyProfile;
    private Agency $agencyA;
    private Agency $agencyB;
    private Role $roleChef;

    protected function setUp(): void
    {
        parent::setUp();

        // Create company and promoter
        $this->companyUser = User::factory()->create();
        $this->companyProfile = CompanyProfile::create([
            'user_id' => $this->companyUser->id,
            'business_type' => 'SAS',
            'legal_name' => 'Immo Corp',
            'registration_number' => '998877665',
            'tax_id' => '11223344',
            'country' => 'FR',
            'address' => '1 Rue Royale',
            'city' => 'Paris',
            'postal_code' => '75001',
            'legal_representative_name' => 'Ad Rep',
            'legal_representative_id_number' => 'ID1234',
            'phone' => '+33100000000',
        ]);
        $this->companyUser->update(['company_profile_id' => $this->companyProfile->id]);

        // Create agencies
        $this->agencyA = Agency::create([
            'name' => 'Agence Paris Centre',
            'code' => 'AG-PAR01',
            'status' => 'active',
            'company_profile_id' => $this->companyProfile->id,
        ]);

        $this->agencyB = Agency::create([
            'name' => 'Agence Lyon Est',
            'code' => 'AG-LYO01',
            'status' => 'active',
            'company_profile_id' => $this->companyProfile->id,
        ]);

        // Role
        $this->roleChef = Role::create([
            'company_profile_id' => $this->companyProfile->id,
            'name' => "Chef d'agence",
            'slug' => 'chef_agence',
        ]);
    }

    public function test_illustrations_index_page_is_accessible(): void
    {
        $response = $this->actingAs($this->companyUser)->get(route('immobilier.illustrations'));

        $response->assertOk();
    }

    public function test_can_upload_illustrations(): void
    {
        Storage::fake('public');

        $imageFile = UploadedFile::fake()->image('apartment1.jpg');
        $videoFile = UploadedFile::fake()->create('walkthrough.mp4', 500, 'video/mp4');

        $payload = [
            'target_type' => 'logement',
            'target_id' => '101',
            'target_name' => 'APT-A101',
            'description' => 'Superbes photos de la pièce principale',
            'photos' => [$imageFile],
            'videos' => [$videoFile]
        ];

        $response = $this->actingAs($this->companyUser)->post(route('illustrations.store'), $payload);

        $response->assertRedirect();
        
        $this->assertDatabaseCount('illustrations', 2);
        $this->assertDatabaseHas('illustrations', [
            'target_type' => 'logement',
            'target_id' => '101',
            'target_name' => 'APT-A101',
            'media_type' => 'image',
            'file_name' => 'apartment1.jpg',
        ]);
        $this->assertDatabaseHas('illustrations', [
            'target_type' => 'logement',
            'target_id' => '101',
            'target_name' => 'APT-A101',
            'media_type' => 'video',
            'file_name' => 'walkthrough.mp4',
        ]);

        $illImage = Illustration::where('file_name', 'apartment1.jpg')->first();
        Storage::disk('public')->assertExists($illImage->file_path);

        $illVideo = Illustration::where('file_name', 'walkthrough.mp4')->first();
        Storage::disk('public')->assertExists($illVideo->file_path);
    }

    public function test_can_update_illustration(): void
    {
        $illustration = Illustration::create([
            'company_profile_id' => $this->companyProfile->id,
            'target_type' => 'batiment',
            'target_id' => '5',
            'target_name' => 'Immeuble C',
            'file_path' => 'illustrations/fake.jpg',
            'file_name' => 'fake.jpg',
            'media_type' => 'image',
        ]);

        $response = $this->actingAs($this->companyUser)->put(route('illustrations.update', $illustration->id), [
            'description' => 'Mise à jour de la description',
            'target_name' => 'Immeuble C Modifié'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('illustrations', [
            'id' => $illustration->id,
            'description' => 'Mise à jour de la description',
            'target_name' => 'Immeuble C Modifié'
        ]);
    }

    public function test_can_delete_illustration(): void
    {
        Storage::fake('public');
        $fakePath = 'illustrations/to_be_deleted.jpg';
        Storage::disk('public')->put($fakePath, 'dummy content');

        $illustration = Illustration::create([
            'company_profile_id' => $this->companyProfile->id,
            'target_type' => 'batiment',
            'target_id' => '5',
            'target_name' => 'Immeuble C',
            'file_path' => $fakePath,
            'file_name' => 'to_be_deleted.jpg',
            'media_type' => 'image',
        ]);

        $response = $this->actingAs($this->companyUser)->delete(route('illustrations.destroy', $illustration->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('illustrations', ['id' => $illustration->id]);
        Storage::disk('public')->assertMissing($fakePath);
    }

    public function test_agency_employee_only_sees_agency_illustrations(): void
    {
        // Setup employee for agencyA
        $employeeUser = User::factory()->create([
            'company_profile_id' => $this->companyProfile->id,
            'role_id' => $this->roleChef->id,
        ]);
        Employee::create([
            'user_id' => $employeeUser->id,
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyA->id,
        ]);

        // Illustration for agencyA
        Illustration::create([
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyA->id,
            'target_type' => 'logement',
            'target_id' => '1',
            'target_name' => 'APT-A101',
            'file_path' => 'illustrations/a.jpg',
            'file_name' => 'a.jpg',
            'media_type' => 'image',
        ]);

        // Illustration for agencyB
        Illustration::create([
            'company_profile_id' => $this->companyProfile->id,
            'agency_id' => $this->agencyB->id,
            'target_type' => 'logement',
            'target_id' => '2',
            'target_name' => 'APT-B202',
            'file_path' => 'illustrations/b.jpg',
            'file_name' => 'b.jpg',
            'media_type' => 'image',
        ]);

        $response = $this->actingAs($employeeUser)->get(route('agence.immobilier.illustrations'));

        $response->assertOk();
        
        $pageData = $response->getOriginalContent()->getData()['page']['props'];
        $viewIllustrations = $pageData['illustrations'];

        $this->assertCount(1, $viewIllustrations);
        $this->assertEquals('APT-A101', $viewIllustrations[0]['target_name']);
    }
}
