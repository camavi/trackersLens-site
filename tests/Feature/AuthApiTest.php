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
            ->assertJsonMissingPath('user.plan');

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

    public function test_account_mutations_require_authentication(): void
    {
        $this->patchJson('/api/user', [])->assertUnauthorized();
        $this->putJson('/api/user/password', [])->assertUnauthorized();
    }

    public function test_profile_changes_require_current_password_and_unique_email(): void
    {
        $user = User::factory()->create(['password' => 'old-password', 'email_verified_at' => now()]);
        $other = User::factory()->create();
        $this->actingAs($user)->fromFrontend();
        $data = ['name' => 'Updated name', 'email' => 'updated@example.com', 'current_password' => 'wrong'];
        $this->patchJson('/api/user', $data)->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->assertNotEquals('Updated name', $user->fresh()->name);
        $data['current_password'] = 'old-password';
        $this->patchJson('/api/user', [...$data, 'email' => $other->email])->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->patchJson('/api/user', $data)->assertOk()->assertJsonPath('email', 'updated@example.com')->assertJsonPath('email_verified_at', null)->assertJsonMissingPath('password');
        $this->assertEquals('Updated name', $user->fresh()->name);
        $this->assertEquals($other->email, $other->fresh()->email);
    }

    public function test_password_change_validates_current_password_and_confirmation(): void
    {
        $user = User::factory()->create(['password' => 'old-password']);
        $this->actingAs($user)->fromFrontend();
        $data = ['current_password' => 'wrong', 'password' => 'new-password', 'password_confirmation' => 'new-password'];
        $this->putJson('/api/user/password', $data)->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $data['current_password'] = 'old-password';
        $this->putJson('/api/user/password', [...$data, 'password_confirmation' => 'different'])->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->putJson('/api/user/password', $data)->assertNoContent();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('new-password', $user->fresh()->password));
        $this->assertFalse(\Illuminate\Support\Facades\Hash::check('old-password', $user->fresh()->password));
    }

    private function fromFrontend(): self
    {
        return $this->withHeader('Referer', 'http://127.0.0.1:8000');
    }
}
