<?php

namespace Tests\Feature\Public;

use App\Models\SchoolSetting;
use App\Modules\Website\Models\News;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class Phase8PublicWebsiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    #[Test]
    public function public_home_page_loads(): void
    {
        $response = $this->get(route('public.home'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_profile_page_loads(): void
    {
        $response = $this->get(route('public.profile'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_news_index_loads(): void
    {
        $response = $this->get(route('public.news'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_news_detail_loads_for_published_news(): void
    {
        $news = News::create([
            'title' => 'Breaking News',
            'slug' => 'breaking-news',
            'content' => 'Content here',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('public.news.show', $news->slug));
        $response->assertStatus(200);
        $response->assertSeeText('Breaking News');
    }

    #[Test]
    public function unpublished_news_is_not_visible_publicly(): void
    {
        $news = News::create([
            'title' => 'Draft News',
            'slug' => 'draft-news',
            'content' => 'Content here',
            'is_published' => false,
        ]);

        $response = $this->get(route('public.news.show', $news->slug));
        $response->assertStatus(404);
    }

    #[Test]
    public function public_events_page_loads(): void
    {
        $response = $this->get(route('public.events'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_facilities_page_loads(): void
    {
        $response = $this->get(route('public.facilities'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_achievements_page_loads(): void
    {
        $response = $this->get(route('public.achievements'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_contact_page_loads(): void
    {
        $response = $this->get(route('public.contact'));
        $response->assertStatus(200);
    }

    #[Test]
    public function public_layout_displays_school_name_from_settings(): void
    {
        SchoolSetting::firstOrCreateDefault()->update(['school_name' => 'SMK Maju Jaya']);

        $response = $this->get(route('public.home'));
        $response->assertSeeText('SMK Maju Jaya');
    }

    #[Test]
    public function public_homepage_contains_ppdb_link_if_ppdb_route_exists(): void
    {
        if (Route::has('public.ppdb')) {
            $response = $this->get(route('public.home'));
            $response->assertSee(route('public.ppdb'));
        } else {
            $this->markTestSkipped('Route public.ppdb not found.');
        }
    }

    #[Test]
    public function guest_can_access_public_pages(): void
    {
        $this->get(route('public.home'))->assertStatus(200);
        $this->get(route('public.profile'))->assertStatus(200);
    }
}
