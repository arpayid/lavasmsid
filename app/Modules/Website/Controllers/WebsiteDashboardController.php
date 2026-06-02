<?php

namespace App\Modules\Website\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use App\Modules\Website\Models\Achievement;
use App\Modules\Website\Models\CmsPage;
use App\Modules\Website\Models\Event;
use App\Modules\Website\Models\Facility;
use App\Modules\Website\Models\Gallery;
use App\Modules\Website\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class WebsiteDashboardController extends Controller
{
    /**
     * Display the Website CMS dashboard.
     */
    public function index(Request $request): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }

        $settings = SchoolSetting::firstOrCreateDefault();

        $stats = [
            'news_count' => Schema::hasTable('news') ? News::count() : 0,
            'events_count' => Schema::hasTable('events') ? Event::count() : 0,
            'galleries_count' => Schema::hasTable('galleries') ? Gallery::count() : 0,
            'achievements_count' => Schema::hasTable('achievements') ? Achievement::count() : 0,
            'facilities_count' => Schema::hasTable('facilities') ? Facility::count() : 0,
            'pages_count' => Schema::hasTable('cms_pages') ? CmsPage::count() : 0,
        ];

        return view('modules.website.dashboard', compact('stats', 'settings'));
    }
}
