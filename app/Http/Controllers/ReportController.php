<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use App\Exports\FinanceExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Finance::with(['category', 'dept', 'rek_sumber', 'bank', 'matauang', 'ppn'])
            ->where(function($q) {
                $q->where('status', 'LIKE', 'approved%')
                  ->orWhere('status', 'paid');
            });

        // Filter seperti di Hardcopy
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }
        if ($request->filled('doc_no')) {
            $query->where('doc_no', 'like', '%' . $request->doc_no . '%');
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', 'like', $request->status . '%');
        }

        $finances = $query->orderBy('invoice_date', 'desc')->paginate(10);

        return view('reports.index', compact('finances'));
    }

    public function export(Request $request)
    {
        return Excel::download(new FinanceExport($request->all()), 'Approved_PRF_Report.xlsx');
    }
}
