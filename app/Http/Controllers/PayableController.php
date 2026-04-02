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
        // $this->middleware('permission:payable-list', ['only' => ['index']]);
        // $this->middleware('permission:payable-create', ['only' => ['create','store']]);
        // $this->middleware('permission:payable-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $lastSync = Sync_log::orderByDesc('last_sync_at')->first();
        $type = $request->type ?? 'hardcopy';
        $payable = Payableto::where('type',$type)->orderBy('nama', 'ASC')->paginate(5)->appends(['type' => $type]);
        return view('masterdata.payable.index', compact('payable', 'type', 'lastSync'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
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

    // public function sync(Request $request): RedirectResponse
    // {
    //     $types = ['hardcopy', 'automate'];

    //     try {
    //         $baseUrl  = env('VENDOR_PRF_API_URL');
    //         $username = env('VENDOR_PRF_API_USERNAME');
    //         $password = env('VENDOR_PRF_API_PASSWORD');

    //         $lastSuccessSync = Sync_log::where('status', 'success')
    //             ->orderByDesc('last_sync_at')
    //             ->first();

    //         $dateFrom = $lastSuccessSync && $lastSuccessSync->last_sync_at
    //             ? Carbon::parse($lastSuccessSync->last_sync_at)->subMinute()->toIso8601String()
    //             : '1970-01-01T00:00:00.000Z';

    //         $syncStartedAt = now();

    //         $loginResponse = Http::timeout(60)->post($baseUrl . '/api/auth/login/v1', [
    //             'username' => $username,
    //             'password' => $password,
    //         ]);

    //         if (!$loginResponse->successful()) {
    //             Sync_log::create([
    //                 'last_sync_at' => $syncStartedAt,
    //                 'status' => 'failed',
    //                 'total_data' => 0,
    //                 'message' => 'Login ke API vendor gagal.',
    //             ]);

    //             return redirect()->route('payable.index', ['type' => 'hardcopy'])
    //                 ->with('error', 'Login ke API vendor gagal.');
    //         }

    //         $token = $loginResponse->json()['data'] ?? null;

    //         if (!$token) {
    //             Sync_log::create([
    //                 'last_sync_at' => $syncStartedAt,
    //                 'status' => 'failed',
    //                 'total_data' => 0,
    //                 'message' => 'Token API tidak ditemukan.',
    //             ]);

    //             return redirect()->route('payable.index', ['type' => 'hardcopy'])
    //                 ->with('error', 'Token API tidak ditemukan.');
    //         }

    //         $vendorResponse = Http::timeout(120)
    //             ->withToken($token)
    //             ->get($baseUrl . "/api/vendor/list/v1/{$dateFrom}");

    //         if (!$vendorResponse->successful()) {
    //             Sync_log::create([
    //                 'last_sync_at' => $syncStartedAt,
    //                 'status' => 'failed',
    //                 'total_data' => 0,
    //                 'message' => 'Gagal mengambil data vendor dari API.',
    //             ]);

    //             return redirect()->route('payable.index', ['type' => 'hardcopy'])
    //                 ->with('error', 'Gagal mengambil data vendor dari API.');
    //         }

    //         $vendors = $vendorResponse->json()['data'] ?? [];

    //         if (!is_array($vendors)) {
    //             Sync_log::create([
    //                 'last_sync_at' => $syncStartedAt,
    //                 'status' => 'failed',
    //                 'total_data' => 0,
    //                 'message' => 'Format data vendor tidak valid.',
    //             ]);

    //             return redirect()->route('payable.index', ['type' => 'hardcopy'])
    //                 ->with('error', 'Format data vendor tidak valid.');
    //         }

    //         $totalSync = 0;

    //         foreach ($vendors as $vendor) {
    //             $nama = trim($vendor['vendor_name'] ?? '');
    //             $vendorAccount = trim($vendor['vendor_account'] ?? '');
    //             $termOfPayment = trim($vendor['term_of_payment'] ?? '');
    //             $status = trim($vendor['status'] ?? '');

    //             if ($nama === '') {
    //                 continue;
    //             }

    //             $valid = strtolower($status) === 'registered' ? 1 : 0;

    //             $top = strtolower($termOfPayment);

    //             if (in_array($top, ['cod', 'dp'])) {
    //                 $hari = 0;
    //             } else {
    //                 preg_match('/\d+/', $top, $matches);
    //                 $hari = isset($matches[0]) ? (int) $matches[0] : 0;
    //             }

    //             foreach ($types as $type) {
    //                 if ($vendorAccount !== '') {
    //                     Payableto::updateOrCreate(
    //                         [
    //                             'nama' => $nama,
    //                             'type' => $type,
    //                             'vendor_account' => $vendorAccount,
    //                         ],
    //                         [
    //                             'term_payment' => $termOfPayment,
    //                             'hari' => $hari,
    //                             'valid' => $valid,
    //                             'user_entry' => auth()->user()->name,
    //                         ]
    //                     );
    //                 } else {
    //                     $existing = Payableto::where('nama', $nama)
    //                         ->where('type', $type)
    //                         ->where(function ($query) {
    //                             $query->whereNull('vendor_account')
    //                                 ->orWhere('vendor_account', '');
    //                         })
    //                         ->first();

    //                     if ($existing) {
    //                         $existing->update([
    //                             'term_payment' => $termOfPayment,
    //                             'hari' => $hari,
    //                             'valid' => $valid,
    //                             'user_entry' => auth()->user()->name,
    //                         ]);
    //                     } else {
    //                         Payableto::create([
    //                             'nama' => $nama,
    //                             'type' => $type,
    //                             'vendor_account' => null,
    //                             'term_payment' => $termOfPayment,
    //                             'hari' => $hari,
    //                             'valid' => $valid,
    //                             'user_entry' => auth()->user()->name,
    //                         ]);
    //                     }
    //                 }
    //             }

    //             $totalSync++;
    //         }

    //         Sync_log::create([
    //             'last_sync_at' => $syncStartedAt,
    //             'status' => 'success',
    //             'total_data' => $totalSync,
    //             'message' => 'Sync berhasil.',
    //         ]);

    //         return redirect()->route('payable.index', ['type' => 'hardcopy'])
    //             ->with('success', "Sync berhasil. Total data vendor API: {$totalSync}");
    //     } catch (\Throwable $e) {
    //         Log::error('Sync payable error: ' . $e->getMessage());

    //         Sync_log::create([
    //             'last_sync_at' => now(),
    //             'status' => 'failed',
    //             'total_data' => 0,
    //             'message' => $e->getMessage(),
    //         ]);

    //         return redirect()->route('payable.index', ['type' => 'hardcopy'])
    //             ->with('error', 'Terjadi error saat sync data vendor.');
    //     }
    // }

}
