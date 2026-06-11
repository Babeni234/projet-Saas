<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertStatus(200);
        $this->assertTrue(session()->has('2fa_code'));
        
        $code = session('2fa_code');

        $response2 = $this->post('/login/verify-2fa', [
            'code' => $code,
        ]);

        $this->assertAuthenticated();
        $response2->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_agency_employees_are_redirected_to_agency_dashboard_after_2fa(): void
    {
        $user = User::factory()->create();
        
        $companyProfile = \App\Models\CompanyProfile::create([
            'user_id' => $user->id,
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
        
        $user->update([
            'company_profile_id' => $companyProfile->id,
        ]);

        $agency = \App\Models\Agency::create([
            'name' => 'Agency Paris A',
            'code' => 'AG-A001',
            'status' => 'active',
            'company_profile_id' => $companyProfile->id,
        ]);

        \App\Models\Employee::create([
            'user_id' => $user->id,
            'company_profile_id' => $companyProfile->id,
            'agency_id' => $agency->id,
            'phone' => '+336112233',
            'position' => 'Chef de bureau',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertStatus(200);
        $this->assertTrue(session()->has('2fa_code'));
        
        $code = session('2fa_code');

        $response2 = $this->post('/login/verify-2fa', [
            'code' => $code,
        ]);

        $this->assertAuthenticated();
        $response2->assertRedirect(route('agence.dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
