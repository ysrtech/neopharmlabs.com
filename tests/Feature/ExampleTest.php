<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('neopharm:import')->assertSuccessful();
    }

    public function test_recovered_pages_render_in_both_languages(): void
    {
        $paths = [
            '/', '/about', '/careers', '/contact', '/legal',
            '/services', '/services/stability-testing', '/services/wet-chemistry',
            '/blog', '/blog/p2', '/blog/category/newsroom', '/blog/the-impact-of-mocra-on-cosmetic-industry-regulations',
            '/resources', '/resources/webinars', '/resources/epsa-assay-101',
            '/about/team/yvan-cote', '/careers/jobs', '/careers/jobs/20260302001', '/careers/apply/20260302001', '/careers/spontaneous',
            '/search?q=stability', '/sitemap.xml',
            '/fr', '/fr/a-propos', '/fr/contactez-nous', '/fr/services', '/fr/blogue', '/fr/ressources', '/fr/carrieres/emplois',
        ];
        foreach ($paths as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_old_urls_redirect_and_unknown_urls_404(): void
    {
        $this->get('/news/fraud-alert')->assertRedirect('/blog/category/newsroom');
        $this->get('/stability-services')->assertRedirect('/services/stability-testing');
        $this->get('/does-not-exist')->assertNotFound();
    }

    public function test_contact_form_is_stored(): void
    {
        $this->post('/forms/contact', [
            'locale' => 'en', 'first_name' => 'Ada', 'last_name' => 'Lovelace',
            'email' => 'ada@example.com', 'phone' => '123', 'message' => 'Hello',
        ])->assertRedirect();

        $this->assertSame(1, Submission::query()->where('form', 'contact')->count());
    }
}
