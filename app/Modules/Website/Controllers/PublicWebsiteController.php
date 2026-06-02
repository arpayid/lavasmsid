<?php

namespace App\Modules\Website\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Modules\Academic\Models\Department;
use App\Modules\Website\Models\Achievement;
use App\Modules\Website\Models\CmsPage;
use App\Modules\Website\Models\Event;
use App\Modules\Website\Models\Facility;
use App\Modules\Website\Models\Gallery;
use App\Modules\Website\Models\News;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PublicWebsiteController extends Controller
{
    protected function getSettings()
    {
        if (Schema::hasTable('school_settings')) {
            return SchoolSetting::firstOrCreateDefault();
        }

        return (object) [
            'school_name' => 'LavaSMSID School',
            'tagline' => 'Sistem Manajemen Sekolah Modern',
            'school_email' => 'info@sekolah.sch.id',
            'school_phone' => '-',
            'school_address' => '-',
            'principal_name' => '-',
            'principal_message' => '-',
            'history' => '-',
            'vision' => '-',
            'mission' => '-',
        ];
    }

    public function home(): View
    {
        $settings = $this->getSettings();

        $news = [];
        if (Schema::hasTable('news')) {
            $news = News::published()->latest('created_at')->take(3)->get();
        }

        $events = [];
        if (Schema::hasTable('events')) {
            $events = Event::published()->orderBy('start_date', 'desc')->take(3)->get();
        }

        $facilities = [];
        if (Schema::hasTable('facilities')) {
            $facilities = Facility::active()->orderBy('sort_order')->take(6)->get();
        }

        $achievements = [];
        if (Schema::hasTable('achievements')) {
            $achievements = Achievement::orderByDesc('year')->take(3)->get();
        }

        return view('public.home', compact('settings', 'news', 'events', 'facilities', 'achievements'));
    }

    public function profile(): View
    {
        $settings = $this->getSettings();
        $about = Schema::hasTable('cms_pages') ? CmsPage::where('slug', 'about')->first() : null;

        return view('public.profile', compact('settings', 'about'));
    }

    public function departments(): View
    {
        $settings = $this->getSettings();
        $departments = Schema::hasTable('departments') ? Department::orderBy('name')->get() : [];

        return view('public.departments', compact('settings', 'departments'));
    }

    public function news(): View
    {
        $settings = $this->getSettings();
        $articles = Schema::hasTable('news') ? News::published()->latest('created_at')->paginate(10) : collect();

        return view('public.news.index', compact('settings', 'articles'));
    }

    public function newsShow(string $slug): View
    {
        $settings = $this->getSettings();
        $article = Schema::hasTable('news') ? News::published()->where('slug', $slug)->firstOrFail() : abort(404);

        return view('public.news.show', compact('settings', 'article'));
    }

    public function events(): View
    {
        $settings = $this->getSettings();
        $events = Schema::hasTable('events') ? Event::published()->orderByDesc('start_date')->paginate(10) : collect();

        return view('public.events.index', compact('settings', 'events'));
    }

    public function gallery(): View
    {
        $settings = $this->getSettings();
        $galleries = Schema::hasTable('galleries') ? Gallery::orderBy('sort_order')->paginate(12) : collect();

        return view('public.gallery.index', compact('settings', 'galleries'));
    }

    public function achievements(): View
    {
        $settings = $this->getSettings();
        $achievements = Schema::hasTable('achievements') ? Achievement::orderByDesc('year')->paginate(15) : collect();

        return view('public.achievements.index', compact('settings', 'achievements'));
    }

    public function facilities(): View
    {
        $settings = $this->getSettings();
        $facilities = Schema::hasTable('facilities') ? Facility::active()->orderBy('sort_order')->get() : collect();

        return view('public.facilities.index', compact('settings', 'facilities'));
    }

    public function contact(): View
    {
        $settings = $this->getSettings();

        return view('public.contact', compact('settings'));
    }

    public function page(string $slug): View
    {
        $settings = $this->getSettings();
        $page = Schema::hasTable('cms_pages') ? CmsPage::where('slug', $slug)->firstOrFail() : abort(404);

        return view('public.page', compact('settings', 'page'));
    }
}
