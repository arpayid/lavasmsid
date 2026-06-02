<?php

namespace Tests\Feature\Admin;

use App\Models\SchoolSetting;
use App\Models\User;
use App\Modules\Website\Models\Event;
use App\Modules\Website\Models\News;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class Phase8WebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected $superAdmin;

    protected $websiteAdmin;

    protected $unauthorizedUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

        // Ensure permissions exist
        $permissions = ['website.view', 'website.create', 'website.update', 'website.delete'];
        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Super Admin
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('Super Admin');

        // Website Admin
        $this->websiteAdmin = User::factory()->create();
        $role = Role::firstOrCreate(['name' => 'Admin Website', 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
        $this->websiteAdmin->assignRole($role);

        // Unauthorized User
        $this->unauthorizedUser = User::factory()->create();
    }

    // ===================== Step 1: Dashboard =====================

    #[Test]
    public function guest_cannot_access_website_cms_dashboard(): void
    {
        $response = $this->get(route('admin.website.dashboard'));
        $response->assertRedirect('/login');
    }

    #[Test]
    public function user_without_website_view_cannot_access_website_cms_dashboard(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $response = $this->get(route('admin.website.dashboard'));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_website_view_can_access_website_cms_dashboard(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.dashboard'));
        $response->assertStatus(200);
        $response->assertSeeText('Website & CMS Dashboard');
    }

    // ===================== Step 2: CMS CRUD =====================

    // --- News/Posts ---
    #[Test]
    public function user_with_website_view_can_access_news_index(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.news.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_website_create_can_create_news(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->post(route('admin.website.news.store'), [
            'title' => 'Test News Title',
            'content' => 'Test content for the news post.',
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.website.news.index'));
        $this->assertDatabaseHas('news', ['title' => 'Test News Title']);
    }

    #[Test]
    public function validation_fails_when_news_title_missing(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->post(route('admin.website.news.store'), [
            'title' => '',
            'content' => 'Some content',
        ]);
        $response->assertSessionHasErrors('title');
    }

    #[Test]
    public function user_with_website_update_can_update_news(): void
    {
        $news = News::create([
            'title' => 'Old Title',
            'slug' => 'old-title',
            'content' => 'Old content',
        ]);

        $this->actingAs($this->websiteAdmin);
        $response = $this->put(route('admin.website.news.update', $news), [
            'title' => 'New Updated Title',
            'content' => 'New updated content',
        ]);

        $response->assertRedirect(route('admin.website.news.index'));
        $this->assertDatabaseHas('news', ['id' => $news->id, 'title' => 'New Updated Title']);
    }

    // --- Pages ---
    #[Test]
    public function user_with_website_view_can_access_pages_index(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.pages.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_website_update_can_update_page(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->put(route('admin.website.pages.update', 'about'), [
            'title' => 'About Our School',
            'content' => 'Detailed history and profile.',
        ]);

        $this->assertDatabaseHas('cms_pages', ['slug' => 'about', 'title' => 'About Our School']);
    }

    // --- Events ---
    #[Test]
    public function user_with_website_view_can_access_events_index(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.events.index'));
        $response->assertStatus(200);
    }

    #[Test]
    public function user_with_website_create_can_create_event(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->post(route('admin.website.events.store'), [
            'title' => 'School Anniversary',
            'start_date' => now()->addDays(7)->format('Y-m-d'),
            'location' => 'School Hall',
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.website.events.index'));
        $this->assertDatabaseHas('events', ['title' => 'School Anniversary']);
    }

    #[Test]
    public function dashboard_totals_update_after_creating_cms_content(): void
    {
        News::create(['title' => 'N1', 'slug' => 'n1', 'content' => 'C1']);
        Event::create(['title' => 'E1', 'slug' => 'e1', 'start_date' => now()]);

        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.dashboard'));
        $response->assertSeeText('1'); // Both news and events should show '1'
    }

    #[Test]
    public function website_cms_crud_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.website.news.index'));
        $this->assertTrue(Route::has('admin.website.pages.index'));
        $this->assertTrue(Route::has('admin.website.events.index'));
    }

    // ===================== Step 3: Settings =====================

    #[Test]
    public function user_with_website_view_can_access_website_settings_page(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.settings.edit'));
        $response->assertStatus(200);
        $response->assertSeeText('Pengaturan Profil & Website');
    }

    #[Test]
    public function user_without_website_view_cannot_access_website_settings_page(): void
    {
        $this->actingAs($this->unauthorizedUser);
        $response = $this->get(route('admin.website.settings.edit'));
        $response->assertStatus(403);
    }

    #[Test]
    public function user_with_website_update_can_update_school_identity(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->put(route('admin.website.settings.update'), [
            'school_name' => 'SMK Bina Karya',
            'tagline' => 'Excellent School',
            'school_email' => 'info@binakarya.sch.id',
            'website_url' => 'https://binakarya.sch.id',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('school_settings', [
            'school_name' => 'SMK Bina Karya',
            'tagline' => 'Excellent School',
            'school_email' => 'info@binakarya.sch.id',
        ]);
    }

    #[Test]
    public function validation_fails_when_email_is_invalid(): void
    {
        $this->actingAs($this->websiteAdmin);
        $response = $this->put(route('admin.website.settings.update'), [
            'school_name' => 'SMK OK',
            'school_email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors('school_email');
    }

    #[Test]
    public function website_settings_routes_exist(): void
    {
        $this->assertTrue(Route::has('admin.website.settings.edit'));
        $this->assertTrue(Route::has('admin.website.settings.update'));
    }

    #[Test]
    public function website_dashboard_displays_configured_school_name(): void
    {
        SchoolSetting::firstOrCreateDefault()->update(['school_name' => 'Sekolah Percobaan']);

        $this->actingAs($this->websiteAdmin);
        $response = $this->get(route('admin.website.dashboard'));

        $response->assertSeeText('Sekolah Percobaan');
    }
}
