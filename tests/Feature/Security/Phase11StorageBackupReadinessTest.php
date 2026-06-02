<?php

namespace Tests\Feature\Security;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Phase11StorageBackupReadinessTest extends TestCase
{
    #[Test]
    public function public_disk_is_configured(): void
    {
        $disk = config('filesystems.disks.public');
        $this->assertNotNull($disk);
        $this->assertEquals('local', $disk['driver']);
        $this->assertStringContainsString('app/public', $disk['root']);
    }

    #[Test]
    public function storage_path_exists(): void
    {
        $this->assertTrue(is_dir(storage_path('app/public')));
        $this->assertTrue(is_dir(storage_path('logs')));
        $this->assertTrue(is_dir(storage_path('framework/cache')));
    }

    #[Test]
    public function security_headers_still_present_on_public_page(): void
    {
        $response = $this->get('/');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    #[Test]
    public function deployment_documentation_file_exists(): void
    {
        $this->assertFileExists(base_path('DEPLOYMENT.md'));
    }

    #[Test]
    public function deployment_documentation_mentions_storage_link(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('storage:link', $content);
    }

    #[Test]
    public function deployment_documentation_mentions_database_backup(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('Backup database', $content);
    }

    #[Test]
    public function deployment_documentation_mentions_bootstrap_cache_permissions(): void
    {
        $content = file_get_contents(base_path('DEPLOYMENT.md'));
        $this->assertStringContainsString('bootstrap/cache', $content);
    }
}
