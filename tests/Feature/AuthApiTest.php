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

    public function test_login_and_logout_on_an_alternate_same_origin_port(): void
    {
        User::factory()->create([
            'email' => 'port-test@example.com',
            'password' => 'password-secure',
        ]);

        $this->withHeader('Referer', 'http://127.0.0.1:8001/it/')
            ->postJson('http://127.0.0.1:8001/api/login', [
                'email' => 'port-test@example.com',
                'password' => 'password-secure',
            ])->assertNoContent();
        $this->assertAuthenticated();
        $this->getJson('http://127.0.0.1:8001/api/user')
            ->assertOk()->assertJsonPath('email', 'port-test@example.com');
        $this->postJson('http://127.0.0.1:8001/api/logout')->assertNoContent();
        $this->assertGuest('web');
    }

    public function test_dynamic_stateful_host_does_not_trust_another_origin(): void
    {
        $request = \Illuminate\Http\Request::create('http://127.0.0.1:8001/api/login');
        $request->headers->set('Referer', 'https://untrusted.example/');
        $this->assertFalse(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::fromFrontend($request));
        $request->headers->set('Referer', 'http://127.0.0.1:8002/');
        $this->assertFalse(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::fromFrontend($request));
        $request->headers->set('Referer', 'http://127.0.0.1:8001/it/');
        $this->assertTrue(\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::fromFrontend($request));
    }

    private function fromFrontend(): self
    {
        return $this->withHeader('Referer', 'http://127.0.0.1:8000');
    }
}
