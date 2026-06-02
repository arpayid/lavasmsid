<?php

namespace Tests\Feature\Security;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Phase11RuntimeReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    #[Test]
    public function deployment_mentions_config_cache(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('config:cache', $content);
    }

    #[Test]
    public function deployment_mentions_route_cache(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('route:cache', $content);
    }

    #[Test]
    public function deployment_mentions_view_cache(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('view:cache', $content);
    }

    #[Test]
    public function deployment_mentions_queue_worker(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('queue', $content);
    }

    #[Test]
    public function deployment_mentions_scheduler_cron(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('schedule:run', $content);
    }

    #[Test]
    public function jobs_table_migration_exists(): void
    {
        $migrations = glob(database_path('migrations/*_create_jobs_table.php'));
        $this->assertNotEmpty($migrations, 'Jobs table migration not found');
    }

    #[Test]
    public function public_homepage_still_loads(): void
    {
        $this->get('/')->assertStatus(200);
    }

    #[Test]
    public function admin_dashboard_still_requires_auth(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    #[Test]
    public function security_headers_still_exist(): void
    {
        $response = $this->get('/');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }
}
