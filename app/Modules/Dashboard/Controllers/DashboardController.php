<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = Cache::remember('dashboard.stats', 300, function () {
            return [
                'students' => $this->tableCount('students'),
                'teachers' => $this->tableCount('teachers'),
                'departments' => $this->tableCount('departments'),
                'classrooms' => $this->tableCount('classrooms'),
                'ppdb' => $this->tableCount('ppdb_registrations'),
                'payments_today' => $this->tableSum('invoices', 'paid_amount', ['created_at', date('Y-m-d')]),
                'attendance_today' => $this->tableCount('attendances', ['attendance_date', date('Y-m-d')]),
                'users' => User::count(),
            ];
        });

        return view('admin.dashboard', ['stats' => $stats]);
    }

    protected function tableCount(string $table, ?array $where = null): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $query = \DB::table($table);
        if ($where) {
            $query->where($where[0], $where[1] ?? null);
        }

        return $query->count();
    }

    protected function tableSum(string $table, string $column, ?array $where = null): float
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $query = \DB::table($table);
        if ($where) {
            $query->where($where[0], $where[1] ?? null);
        }

        return (float) $query->sum($column);
    }
}
