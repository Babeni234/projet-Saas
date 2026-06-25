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
}
