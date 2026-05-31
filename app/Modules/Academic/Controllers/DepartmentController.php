<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        return view('modules.academic.departments.index', [
            'departments' => DB::table('departments')->orderBy('name')->paginate(15),
        ]);
    }
}
