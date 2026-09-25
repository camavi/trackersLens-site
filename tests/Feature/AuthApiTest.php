<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_session_payload(): void
    {
        $response = $this
            ->fromFrontend()
            ->postJson('/api/register', [
                'name' => 'Thomas Lane',
                'email' => 'thomas@example.com',
                'password' => 'password-secure',
                'password_confirmation' => 'password-secure',
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('user.email', 'thomas@example.com')
            ->assertJsonPath('user.plan', 'pro');

        $this->assertAuthenticated();
    }

    public function test_user_can_login_and_read_current_user(): void
    {
        User::factory()->create([
            'email' => 'thomas@example.com',
            'password' => 'password-secure',
        ]);

        $this
            ->fromFrontend()
            ->postJson('/api/login', [
                'email' => 'thomas@example.com',
                'password' => 'password-secure',
            ])->assertNoContent();

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('email', 'thomas@example.com');
    }

    private function fromFrontend(): self
    {
        return $this->withHeader('Referer', 'http://127.0.0.1:8000');
    }
}
