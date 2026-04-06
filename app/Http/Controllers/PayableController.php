<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Payableto;
use App\Models\Sync_log;
use App\Services\VendorSyncService;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayableController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payable-list', ['only' => ['index']]);
        $this->middleware('permission:payable-create', ['only' => ['create','store']]);
        $this->middleware('permission:payable-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $lastSync = Sync_log::orderByDesc('last_sync_at')->first();
        $type = $request->type ?? 'hardcopy';

        $query = Payableto::where('type', $type);

        if ($request->has('nama') && $request->nama != '') {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('vendor_account') && $request->vendor_account != '') {
            $query->where('vendor_account', 'LIKE', '%' . $request->vendor_account . '%');
        }

        if ($request->has('hari') && $request->hari != '') {
            $query->where('hari', $request->hari);
        }

        if ($request->has('valid') && $request->valid != '') {
            $query->where('valid', $request->valid);
        }

        $payable = $query->orderBy('nama', 'ASC')->paginate(10)->appends($request->query());

        return view('masterdata.payable.index', compact('payable', 'type', 'lastSync'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(): View
    {
        $payable = Payableto::get();
        return view('masterdata.payable.create', compact('payable'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
            'hari' => 'required',
        ]);

        Payableto::create([
            'nama' => $request->input('nama'),
            'vendor_account' => $request->input('vendor_account'),
            'hari' => $request->input('hari'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('payable.index')
            ->with('success', 'Payable created successfully');
    }

    public function edit($id): View
    {
        $payable = Payableto::find($id);
        return view('masterdata.payable.edit', compact('payable'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
            'hari' => 'required',
        ]);

        $payable = Payableto::find($id);
        $payable->nama = $request->input('nama');
        $payable->vendor_account = $request->input('vendor_account');
        $payable->hari = $request->input('hari');
        $payable->valid = $request->input('valid');
        $payable->user_entry = auth()->user()->name;
        $payable->save();

        return redirect()->route('payable.index')
            ->with('success', 'Payable updated successfully');
    }

    public function sync(VendorSyncService $vendorSyncService)
    {
        $result = $vendorSyncService->sync(auth()->user()->name);

        return redirect()->route('payable.index', ['type' => 'hardcopy'])
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}
