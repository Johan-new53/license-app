<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Models\Department;
use App\Models\Hu_reksumber;

use App\Models\Payableto;
use App\Models\Rektujuan;
use App\Models\Bank;
use App\Models\Matauang;
use App\Models\Ppn;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Services\DocNoCheckService;

class SoftcopyController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:softcopy-list|softcopy-create|softcopy-edit|softcopy-delete', ['only' => ['index','show']]);
        $this->middleware('permission:softcopy-create', ['only' => ['create','store']]);
        $this->middleware('permission:softcopy-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:softcopy-delete', ['only' => ['destroy']]);
        $this->middleware('permission:softcopy-export', ['only' => ['export']]);
    }

    public function index(Request $request): View
    {
        $statusOptions = Finance::query()
            ->where('user_entry', auth()->id())
            ->where('type', 'softcopy')
            ->whereNotNull('status')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        $payabletos = Payableto::where('valid', 1)
            ->where('type', 'softcopy')
            ->orderBy('nama')
            ->get();

        $query = Finance::query()
            ->where('user_entry', auth()->id())
            ->where('type', 'softcopy');

        // filter tanggal invoice_date
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Filter payable To
        if ($request->filled('id_payable')) {
            $query->where('id_payable', $request->id_payable);
        }

        // filter doc_no
        if ($request->filled('doc_no')) {
            $query->where('doc_no', 'like', '%' . $request->doc_no . '%');
        }

        // filter deskripsi
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $softcopys = $query
            ->with('payableto')
            ->orderBy('id', 'desc')
            ->paginate(6)
            ->appends($request->query());

        return view('softcopys.index', compact('softcopys', 'statusOptions', 'payabletos'))
            ->with('i', ($softcopys->currentPage() - 1) * $softcopys->perPage());
    }

    public function create()
    {

        $departments = Department::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $hu_rek_sumbers = Hu_reksumber::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $payabletos = Payableto::where('valid', 1)
        ->where('type', 'softcopy')
        ->orderBy('nama')
        ->get();
         $rek_tujuans= Rektujuan::where('valid', 1)
        ->orderBy('nama')
        ->get();
         $banks= Bank::where('valid', 1)
        ->orderBy('nama')
        ->get();

         $currencys= Matauang::where('valid', 1)
        ->orderBy('nama')
        ->get();

        $ppns= Ppn::where('valid', 1)
        ->orderBy('id')
        ->get();


        return view('softcopys.create', compact('departments','hu_rek_sumbers','payabletos','rek_tujuans','banks','currencys','ppns'));
    }

    public function store(Request $request): RedirectResponse
    {

        request()->validate([
          'id_dept' => 'required',
          'id_rek_sumber' => 'required',
          'id_payable' => 'required',
          'id_rek_tujuan' => 'required',
          'doc_no' => 'required',
          'description' => 'required',
          'id_currency' => 'required',
          'dpp' => 'required',
          'file_softcopy' => 'mimes:pdf|max:204800',

        ]);

        $docNoCheckService = new DocNoCheckService();
        $check = $docNoCheckService->check($request->doc_no, 'softcopy');
        if (!empty($check['exists'])) {
            return back()
                ->withInput()
                ->withErrors(['doc_no' => 'Doc No sudah terpakai: '.implode(', ', $check['exists'])]);
        }

        if ($request->hasFile('file_softcopy')) {
            $file = $request->file('file_softcopy');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('softcopy_files', $filename, 'public');
        }


        $data = $request->all();
        $data['user_entry'] = auth()->id();
        $data['type'] = 'softcopy';
        $data['status'] = 'request';
        $data['input_file'] = $path ?? null;

        Finance::create($data);

        return redirect()->route('softcopys.index')
                ->with('success', 'Ap Softcopy created successfully.');
        }

        public function show($id): View
        {
            $finance = \DB::table('finances')
                ->leftjoin('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->leftjoin('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->leftjoin('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')

                ->leftjoin('m_currency', 'finances.id_currency', '=', 'm_currency.id')
                ->leftjoin('m_rek_tujuan', 'finances.id_rek_tujuan', '=', 'm_rek_tujuan.id')
                ->leftjoin('m_ppn', 'finances.id_ppn', '=', 'm_ppn.id')
                ->select(
                    'finances.*',
                    'm_dept.nama as nama_dept',
                    'm_hu_rek_sumber.nama as nama_rek_sumber',
                    'm_payableto.nama as nama_payable',
                    'm_currency.nama as nama_currency',
                    'm_rek_tujuan.nama as nama_rek_tujuan',
                    'm_ppn.nama as nama_ppn'
                )
                ->where('finances.id', $id)
                ->firstOrFail();

            return view('softcopys.show', compact('finance'));
        }

        public function edit($id): View
        {
             $finance = \DB::table('finances')
                ->leftjoin('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->leftjoin('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->leftjoin('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')

                ->leftjoin('m_currency', 'finances.id_currency', '=', 'm_currency.id')
                ->select(
                    'finances.*',
                    'm_dept.nama as nama_dept',
                    'm_hu_rek_sumber.nama as nama_rek_sumber',
                    'm_payableto.nama as nama_payable',

                    'm_currency.nama as nama_currency'
                )
                ->where('finances.id', $id)
                ->first();

            $departments = Department::where('valid', 1)
            ->orderBy('nama')
            ->get();
            $hu_rek_sumbers = Hu_reksumber::where('valid', 1)
            ->orderBy('nama')
            ->get();
            $payabletos = Payableto::where('valid', 1)
            ->where('type', 'softcopy')
            ->orderBy('nama')
            ->get();
            $rek_tujuans= Rektujuan::where('valid', 1)
            ->orderBy('nama')
            ->get();

            $currencys= Matauang::where('valid', 1)
            ->orderBy('nama')
            ->get();

            $ppns= Ppn::where('valid', 1)
            ->orderBy('id')
            ->get();

            return view('softcopys.edit', compact('finance','departments','hu_rek_sumbers','payabletos','rek_tujuans','currencys','ppns'));

        }

    public function update(Request $request, $id)
{
    $finance = Finance::findOrFail($id);

    $validated = $request->validate([
        'id_dept' => 'required',
        'id_rek_sumber' => 'required',
        'id_payable' => 'required',
        'id_rek_tujuan' => 'required',
        'doc_no' => 'required',
        'description' => 'required',
        'id_currency' => 'required',
        'dpp' => 'required',
        'file_softcopy' => 'mimes:pdf|max:204800',
    ]);

    $docNoCheckService = new DocNoCheckService();
    $check = $docNoCheckService->check($request->doc_no, 'softcopy', $finance->id);
    if (!empty($check['exists'])) {
        return back()
            ->withInput()
            ->withErrors(['doc_no' => 'Doc No sudah terpakai: '.implode(', ', $check['exists'])]);
    }

    $data = $request->all();
    $data['status'] = 'requested';

    if ($request->hasFile('file_softcopy')) {
        // hapus file lama
        if ($finance->input_file && Storage::disk('public')->exists($finance->input_file)) {
            Storage::disk('public')->delete($finance->input_file);
        }

        // upload file baru
        $file = $request->file('file_softcopy');
        $path = $file->store('softcopy_files', 'public');

        $data['input_file'] = $path;
    }

    $finance->update($data);

    return redirect()->route('softcopys.index')
        ->with('success', 'Softcopy berhasil diupdate.');
    }



    public function destroy($id): RedirectResponse
    {
        $finance = Finance::findOrFail($id);
        $finance->delete();

        return redirect()->route('softcopys.index')
            ->with('success', 'Soft Copy deleted successfully');
    }

}
