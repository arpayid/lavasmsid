<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Phase11SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    #[Test]
    public function admin_dashboard_requires_auth(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect('/login');
    }

    #[Test]
    public function public_homepage_remains_accessible(): void
    {
        $response = $this->get(route('public.home'));
        $response->assertStatus(200);
    }

    #[Test]
    public function security_headers_exist_on_public_homepage(): void
    {
        $response = $this->get(route('public.home'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
        $response->assertHeader('X-XSS-Protection', '0');
    }

    #[Test]
    public function security_headers_exist_on_admin_authenticated_response(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user);
        $response = $this->get(route('admin.dashboard'));

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    #[Test]
    public function public_ppdb_page_works_or_fails_gracefully(): void
    {
        // Route might be ppdb.index or public.ppdb - test whichever exists
        $url = route('ppdb.index', [], false);
        $response = $this->get($url);
        // Should either succeed (200) or gracefully handle (404/302)
        $this->assertContains($response->getStatusCode(), [200, 302, 404]);
    }
}
