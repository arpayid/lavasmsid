<?php

namespace App\Modules\Website\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Website\Models\News;
use App\Modules\Website\Models\Event;
use App\Modules\Website\Models\Gallery;
use App\Modules\Website\Models\Achievement;
use App\Modules\Website\Models\Facility;
use App\Modules\Website\Models\CmsPage;
use App\Modules\Academic\Models\Department;
use Illuminate\View\View;

class PublicWebsiteController extends Controller
{
    public function home(): View
    {
        $news = News::where('is_published', true)->latest('published_at')->take(3)->get();
        $events = Event::where('is_published', true)->where('start_date', '>=', now())->orderBy('start_date')->take(3)->get();
        $facilities = Facility::where('is_active', true)->orderBy('sort_order')->take(6)->get();
        $achievements = Achievement::orderByDesc('year')->take(3)->get();

        return view('public.home', compact('news', 'events', 'facilities', 'achievements'));
    }

    public function profile(): View
    {
        $about = CmsPage::where('slug', 'about')->first();
        $vision = CmsPage::where('slug', 'vision')->first();
        $mission = CmsPage::where('slug', 'mission')->first();
        $history = CmsPage::where('slug', 'history')->first();

        return view('public.profile', compact('about', 'vision', 'mission', 'history'));
    }

    public function departments(): View
    {
        $departments = Department::where('is_active', true)->with(['competencies'])->get();
        return view('public.departments', compact('departments'));
    }

    public function news(): View
    {
        $articles = News::where('is_published', true)->orderByDesc('published_at')->paginate(10);
        return view('public.news.index', compact('articles'));
    }

    public function newsShow(string $slug): View
    {
        $article = News::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('public.news.show', compact('article'));
    }

    public function events(): View
    {
        $events = Event::where('is_published', true)->orderBy('start_date')->paginate(10);
        return view('public.events.index', compact('events'));
    }

    public function gallery(): View
    {
        $galleries = Gallery::orderBy('sort_order')->paginate(12);
        return view('public.gallery.index', compact('galleries'));
    }

    public function achievements(): View
    {
        $achievements = Achievement::orderByDesc('year')->paginate(15);
        return view('public.achievements.index', compact('achievements'));
    }

    public function facilities(): View
    {
        $facilities = Facility::where('is_active', true)->orderBy('sort_order')->get();
        return view('public.facilities.index', compact('facilities'));
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function page(string $slug): View
    {
        $page = CmsPage::where('slug', $slug)->firstOrFail();
        return view('public.page', compact('page'));
    }
}