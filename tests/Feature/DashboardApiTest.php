<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_endpoints_require_authentication(): void
    {
        $this->getJson('/api/dashboard/summary')->assertUnauthorized();
    }

    public function test_authenticated_user_can_read_dashboard_summary(): void
    {
        $this->actingAs(User::factory()->create());

        $this->getJson('/api/dashboard/summary')
            ->assertOk()
            ->assertJsonStructure([
                'kpis' => [
                    '*' => ['label', 'value', 'delta', 'trend'],
                ],
                'box_segments' => [
                    '*' => ['label', 'value'],
                ],
            ]);
    }
}
