<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payableto;
use App\Models\Sync_log;
use App\Services\VendorSyncService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Imports\PayableImport;
use App\Exports\PayableExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Throwable;

class PayableController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payable-list', ['only' => ['index']]);
        $this->middleware('permission:payable-create', ['only' => ['create','store','import','export']]);
        $this->middleware('permission:payable-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $lastSync = Sync_log::orderByDesc('last_sync_at')->first();
        $type = $request->type ?? 'hardcopy';

        $query = Payableto::where('type', $type);

        if ($request->filled('nama')) {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->filled('vendor_account')) {
            $query->where('vendor_account', 'LIKE', '%' . $request->vendor_account . '%');
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        if ($request->filled('valid')) {
            $query->where('valid', $request->valid);
        }

        $payable = $query->orderBy('nama', 'ASC')->paginate(10)->appends($request->query());

        return view('masterdata.payable.index', compact('payable', 'type', 'lastSync'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(Request $request): View
    {
        $type = $request->type ?? 'hardcopy';
        return view('masterdata.payable.create', compact('type'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'hari' => 'required|numeric|min:0',
            'type' => 'required|string|max:50',
            'vendor_account' => 'nullable|string|max:255',
            'valid' => 'nullable|in:0,1',
        ]);

        Payableto::create([
            'nama' => $request->nama,
            'vendor_account' => $request->vendor_account,
            'hari' => $request->hari,
            'valid' => $request->valid ?? 1,
            'type' => $request->type,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('payable.index', ['type' => $request->type])
            ->with('success', 'Data created successfully');
    }

    public function edit(Request $request, $id): View
    {
        $payable = Payableto::findOrFail($id);
        $type = $request->type ?? $payable->type ?? 'hardcopy';

        return view('masterdata.payable.edit', compact('payable', 'type'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'hari' => 'required|numeric|min:0',
            'vendor_account' => 'nullable|string|max:255',
            'valid' => 'nullable|in:0,1',
        ]);

        $payable = Payableto::findOrFail($id);
        $payable->nama = $request->nama;
        $payable->vendor_account = $request->vendor_account;
        $payable->hari = $request->hari;
        $payable->valid = $request->valid ?? 1;
        $payable->user_entry = auth()->user()->name;
        $payable->save();

        return redirect()->route('payable.index', ['type' => $request->type ?? $payable->type ?? 'hardcopy'])
            ->with('success', 'Data updated successfully');
    }

    public function export(Request $request)
    {
        $filename = 'data_' . ($request->type ?? 'all') . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PayableExport($request), $filename);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes' => 'File harus berupa Excel (.xlsx atau .xls).',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        try {
            $file = $request->file('file');

            if (!$file || !$file->isValid()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'File upload gagal.');
            }

            $import = new PayableImport(auth()->user()->name);

            Excel::import($import, $file);

            $message = "Upload data selesai. Insert: {$import->inserted}, Update: {$import->updated}, Skip: {$import->skipped}";

            return redirect()->route('payable.index', [
                'type' => $request->type ?? 'hardcopy'
            ])
            ->with('success', $message)
            ->with('import_errors', $import->errors);

        } catch (\Throwable $e) {
            \Log::error('Payable import error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Upload data gagal: ' . $e->getMessage());
        }
    }

    public function sync(VendorSyncService $vendorSyncService)
    {
        $result = $vendorSyncService->sync(auth()->user()->name);

        return redirect()->route('payable.index', ['type' => 'hardcopy'])
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
