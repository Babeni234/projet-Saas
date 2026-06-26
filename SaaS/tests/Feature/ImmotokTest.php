<?php

namespace Tests\Feature;

use App\Models\ImmotokClient;
use App\Models\Illustration;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImmotokTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_registration()
    {
        $response = $this->postJson('/api/immotok/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+22501020304',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Compte créé avec succès.'
                 ]);

        $this->assertDatabaseHas('immotok_clients', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    }

    public function test_client_login()
    {
        $client = ImmotokClient::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/immotok/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Connexion réussie.'
                 ]);
    }

    public function test_get_categories()
    {
        $user = User::create([
            'name' => 'Company Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'account_type' => 'Entreprise',
        ]);

        $company = CompanyProfile::create([
            'user_id' => $user->id,
            'legal_name' => 'Test Company',
            'business_type' => 'Agency',
            'phone' => '123456',
            'registration_number' => 'REG-12345',
            'tax_id' => 'TAX-12345',
            'country' => 'CM',
            'address' => '123 Test Street',
            'city' => 'Douala',
            'postal_code' => '00000',
            'legal_representative_name' => 'John Manager',
            'legal_representative_id_number' => 'ID-12345',
        ]);

        \App\Models\Categorie::create([
            'company_profile_id' => $company->id,
            'nom' => 'Studio',
            'description' => 'Un studio',
        ]);

        $response = $this->getJson('/api/immotok/categories');
        $response->assertStatus(200)
                 ->assertJsonFragment(['Studio']);
    }

    public function test_toggle_subscribe()
    {
        $user = User::create([
            'name' => 'Company Manager 2',
            'email' => 'manager2@test.com',
            'password' => bcrypt('password'),
            'account_type' => 'Entreprise',
        ]);

        $company = CompanyProfile::create([
            'user_id' => $user->id,
            'legal_name' => 'Test Company 2',
            'business_type' => 'Agency',
            'phone' => '1234567',
            'registration_number' => 'REG-12346',
            'tax_id' => 'TAX-12346',
            'country' => 'CM',
            'address' => '456 Test Avenue',
            'city' => 'Yaoundé',
            'postal_code' => '00001',
            'legal_representative_name' => 'Jane Manager',
            'legal_representative_id_number' => 'ID-12346',
        ]);

        $client = ImmotokClient::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
        ]);

        // Access without auth
        $response = $this->postJson("/api/immotok/companies/{$company->id}/subscribe");
        $response->assertStatus(401);

        // With session auth
        $response = $this->withSession(['immotok_client_id' => $client->id])
                         ->postJson("/api/immotok/companies/{$company->id}/subscribe");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'subscribed' => true,
                     'subscribers_count' => 1
                 ]);

        $this->assertDatabaseHas('immotok_subscriptions', [
            'immotok_client_id' => $client->id,
            'company_profile_id' => $company->id
        ]);
    }
}
