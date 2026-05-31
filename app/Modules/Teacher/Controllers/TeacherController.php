<?php

namespace App\Modules\Teacher\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        return view('modules.teacher.index', ['items' => collect()]);
    }
}
