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
        $query = Finance::with(['category', 'dept', 'rek_sumber', 'bank', 'matauang', 'ppn', 'payableto', 'rektujuan'])
            ->select('finances.*')
            ->selectRaw('COALESCE(finances.form_submission_time, DATE(finances.created_at)) as submission_date')
            ->selectRaw("(SELECT created_at FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 1' ORDER BY id DESC LIMIT 1) as approved1_date")
            ->selectRaw("COALESCE((SELECT created_at FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 2' ORDER BY id DESC LIMIT 1), finances.final_validation_time) as approved2_date");

        // Filter Submission Date
        if ($request->filled('submission_date_from')) {
            $query->whereRaw('COALESCE(finances.form_submission_time, DATE(finances.created_at)) >= ?', [$request->submission_date_from]);
        }
        if ($request->filled('submission_date_to')) {
            $query->whereRaw('COALESCE(finances.form_submission_time, DATE(finances.created_at)) <= ?', [$request->submission_date_to]);
        }

        // Filter Approved 2 Date
        if ($request->filled('approved2_date_from')) {
            $query->whereRaw("COALESCE((SELECT DATE(created_at) FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 2' ORDER BY id DESC LIMIT 1), finances.final_validation_time) >= ?", [$request->approved2_date_from]);
        }
        if ($request->filled('approved2_date_to')) {
            $query->whereRaw("COALESCE((SELECT DATE(created_at) FROM history_approval WHERE history_approval.id_finance = finances.id AND history_approval.status = 'approved 2' ORDER BY id DESC LIMIT 1), finances.final_validation_time) <= ?", [$request->approved2_date_to]);
        }

        // Filter Invoice Date
        if ($request->filled('date_from')) {
            $query->whereDate('finances.invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('finances.invoice_date', '<=', $request->date_to);
        }
        if ($request->filled('payable_to')) {
            $query->whereHas('payableto', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->payable_to . '%');
            });
        }
        if ($request->filled('doc_no')) {
            $query->where('finances.doc_no', 'like', '%' . $request->doc_no . '%');
        }
        if ($request->filled('description')) {
            $query->where('finances.description', 'like', '%' . $request->description . '%');
        }
        if ($request->filled('type')) {
            $query->where('finances.type', $request->type);
        }
        if ($request->filled('status')) {
            $statuses = (array) $request->status;
            $query->whereIn('finances.status', $statuses);
        }

        $finances = $query->orderBy('finances.invoice_date', 'desc')->paginate(10)->withQueryString();

        return view('reports.index', compact('finances'));
    }

    public function export(Request $request)
    {
        return Excel::download(new FinanceExport($request->all()), 'Approved_PRF_Report.xlsx');
    }
}
