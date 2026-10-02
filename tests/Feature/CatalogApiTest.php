<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class CatalogApiTest extends TestCase
{
    use RefreshDatabase;

    private function release(string $visibility = 'public'): array
    {
        $records = array_fill_keys(['tl_pages', 'tl_widgets', 'tl_runtime_nodes', 'tl_runtime_dependencies', 'tl_flows', 'tl_channels', 'tl_connections', 'tl_ai_agents', 'tl_agents'], []);
        $records['tl_pages'] = [['id' => 'f', 'content' => ['id' => 'f', 'type' => 'flowmap']]];

        return ['kind' => 'flowmap', 'version' => '1.0.0', 'title' => 'Example flow', 'description' => '', 'license' => 'MIT',
            'visibility' => $visibility, 'bundleJson' => json_encode(['schema' => 'tl-catalog-bundle/v1', 'kind' => 'flowmap', 'rootId' => 'f', 'records' => $records])];
    }

    public function test_auth_ownership_immutability_and_exact_hash(): void
    {
        $this->getJson('/api/catalog?kind=flowmap')->assertUnauthorized();
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $data = $this->release();
        $published = $this->actingAs($owner)->postJson('/api/catalog', $data)->assertCreated()->json();
        $id = $published['artifactId'];
        $this->assertSame(hash('sha256', $data['bundleJson']), $published['sha256']);
        $this->postJson('/api/catalog', [...$data, 'artifactId' => $id])->assertConflict();
        $this->actingAs($other)->postJson('/api/catalog', [...$data, 'artifactId' => $id, 'version' => '2.0.0'])->assertForbidden();
        $this->getJson('/api/catalog/'.$id.'/versions/1.0.0')->assertOk()->assertJsonPath('bundleJson', $data['bundleJson']);
        $this->getJson('/api/catalog?kind=flowmap&query=Example')->assertOk()->assertJsonPath('total', 1)->assertJsonMissingPath('items.0.bundleJson');
    }

    public function test_public_catalog_is_anonymous_and_only_lists_public_metadata(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner)->postJson('/api/catalog', $this->release('public'))->assertCreated();
        $this->postJson('/api/catalog', $this->release('private'))->assertCreated();
        $this->postJson('/api/catalog', $this->release('unlisted'))->assertCreated();
        $this->getJson('/api/public/catalog?kind=flowmap')
            ->assertOk()->assertJsonPath('total', 1)->assertJsonMissingPath('items.0.bundleJson')
            ->assertJsonPath('items.0.visibility', 'public');
    }

    public function test_private_and_unlisted_are_not_discovered_by_other_accounts(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $private = $this->actingAs($owner)->postJson('/api/catalog', $this->release('private'))->assertCreated()->json('artifactId');
        $unlisted = $this->postJson('/api/catalog', $this->release('unlisted'))->assertCreated()->json('artifactId');
        $this->getJson('/api/catalog?kind=flowmap&mine=1')->assertJsonPath('total', 2);
        $this->actingAs($other)->getJson('/api/catalog?kind=flowmap')->assertJsonPath('total', 0);
        $this->getJson('/api/catalog/'.$private.'/versions/1.0.0')->assertNotFound();
        $this->getJson('/api/catalog/'.$unlisted.'/versions/1.0.0')->assertOk();
    }

    public function test_pagination_keeps_all_versions_accessible_and_rejects_bad_types(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);
        $this->actingAs(User::factory()->create());
        $this->postJson('/api/catalog', [...$this->release(), 'kind' => 'node'])->assertUnprocessable();
        for ($i = 0; $i < 22; $i++) {
            $this->postJson('/api/catalog', $this->release())->assertCreated();
        }
        $this->getJson('/api/catalog?kind=flowmap')->assertJsonCount(20, 'items')->assertJsonPath('hasMore', true);
        $this->getJson('/api/catalog?kind=flowmap&page=2')->assertJsonCount(2, 'items')->assertJsonPath('hasMore', false);
    }
}
