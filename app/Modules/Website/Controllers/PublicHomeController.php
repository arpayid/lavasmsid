<?php

namespace App\Modules\Website\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function index(): View
    {
        return view('public.home');
    }
}
