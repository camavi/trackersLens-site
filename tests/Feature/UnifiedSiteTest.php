<?php

namespace Tests\Feature;

use Tests\TestCase;

class UnifiedSiteTest extends TestCase
{
    public function test_landing_languages_and_pages_render_in_the_same_process(): void
    {
        foreach (['it', 'en', 'es'] as $locale) {
            foreach (['', 'features', 'pricing', 'roadmap', 'changelog', 'docs', 'privacy', 'terms', 'blog', 'about'] as $page) {
                $this->get('/'.$locale.'/'.$page)->assertOk()
                    ->assertSee('lang="'.$locale.'"', false)
                    ->assertSee('apiBaseUrl: ""', false)
                    ->assertSee('appBaseUrl: "/app"', false)
                    ->assertDontSee('https://api.trackerslens.com', false)
                    ->assertDontSee('https://app.trackerslens.com', false);
            }
        }
    }

    public function test_dashboard_deep_links_and_documentation_are_available(): void
    {
        foreach (['/app', '/app/settings', '/app/cloud-assets'] as $path) {
            $response = $this->get($path)->assertOk();
            $this->assertSame(public_path('build/dashboard/index.html'), $response->baseResponse->getFile()->getPathname());
        }
        $this->get('/docs/api-contract')->assertOk()->assertSee('API Contract');
        $this->get('/api/user')->assertUnauthorized();
        $this->get('/it/missing')->assertNotFound();
        $this->get('/.env')->assertNotFound();
    }
}
