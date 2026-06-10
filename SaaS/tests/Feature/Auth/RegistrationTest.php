<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'account_type' => 'individual',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertStatus(200);

        // Code should be in the session
        $this->assertTrue(session()->has('2fa_register_code'));
        $code = session('2fa_register_code');

        // Verify the code
        $verifyResponse = $this->post('/register/verify-2fa', [
            'code' => $code,
        ]);

        $this->assertAuthenticated();
        $verifyResponse->assertRedirect(route('subscription', absolute: false));
    }
}
