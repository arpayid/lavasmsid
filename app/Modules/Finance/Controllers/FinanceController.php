<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FinanceController extends Controller
{
    /**
     * Display the finance dashboard.
     *
     * @return View
     */
    public function dashboard(Request $request)
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $totalInvoices = Invoice::count();
        $totalBilled = Invoice::sum('amount');
        $totalPaid = Invoice::sum('paid_amount');
        $outstanding = $totalBilled - $totalPaid;

        $data = [
            'totalInvoices' => $totalInvoices,
            'totalBilled' => $totalBilled,
            'totalPaid' => $totalPaid,
            'outstanding' => $outstanding,
        ];

        return view('modules.finance.dashboard', compact('data'));
    }
}
