<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Bank;
use App\Models\Department;
use App\Models\Finance;
use App\Models\Hu_reksumber;
use App\Models\Matauang;
use App\Models\Ppn;
use App\Models\Rektujuan;

use App\Services\DocNoCheckService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Payableto;
use App\Models\History_approval;
use Illuminate\Support\Facades\DB;


class HardcopyController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:hardcopy-list|hardcopy-create|hardcopy-edit|hardcopy-delete', ['only' => ['index','show']]);
        $this->middleware('permission:hardcopy-create', ['only' => ['create','store']]);
        $this->middleware('permission:hardcopy-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:hardcopy-delete', ['only' => ['destroy']]);
        $this->middleware('permission:hardcopy-export', ['only' => ['export']]);
    }



    public function index(Request $request): View
    {
        $statusOptions = Finance::query()
            ->where('user_entry', auth()->id())
            ->where('type', 'hardcopy')
            ->whereNotNull('status')
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        $payabletos = Payableto::where('valid', 1)
            ->where('type', 'main')
            ->orderBy('nama')
            ->get();

        $query = Finance::query()
            ->where('user_entry', auth()->id())
            ->where('type', 'hardcopy');

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

        $hardcopys = $query
            ->with('payableto')
            ->orderBy('id', 'desc')
            ->paginate(6)
            ->appends($request->query());

        return view('hardcopys.index', compact('hardcopys', 'statusOptions', 'payabletos'))
            ->with('i', ($hardcopys->currentPage() - 1) * $hardcopys->perPage());
    }

    public function create()
    {

        $categorys = Category::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $departments = Department::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $hu_rek_sumbers = Hu_reksumber::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $payabletos = Payableto::where('valid', 1)
        ->where('type', 'main')
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



        return view('hardcopys.create', compact('categorys','departments','hu_rek_sumbers','payabletos','rek_tujuans','banks','currencys','ppns'));
    }

      public function store(Request $request): RedirectResponse
    {
        request()->validate([
          'payment_term' => 'required',
          'po_no' => 'required',
          'id_category' => 'required',
          'id_dept' => 'required',
          'id_rek_sumber' => 'required',
          'id_payable' => 'required',
          'nama_rekening_tujuan' => 'required',
          'id_bank' => 'required',
          'no_rek_tujuan' => 'required',
          'doc_no' => 'required',
          'description' => 'required',
          'id_currency' => 'required',
          'dpp' => 'required',

        ]);

        $docNoCheckService = new DocNoCheckService();
        $check = $docNoCheckService->check($request->doc_no, 'hardcopy');
        if (!empty($check['exists'])) {
            return back()
                ->withInput()
                ->withErrors(['doc_no' => 'Doc No sudah terpakai: '.implode(', ', $check['exists'])]);
        }

        $data = $request->all();
        $hari = Payableto::where('id', $request->id_payable)->value('hari');
        $data['top_hari'] = $hari;
        $data['due_date'] = now()->addDays($hari);


        $data['user_entry'] = auth()->id();
        $data['type'] = 'hardcopy';
        $data['status'] = 'requested';



        DB::transaction(function () use ($data) {
        $finance = Finance::create($data);
            History_approval::create([
                'id_finance' => $finance->id,
                'status' => 'requested',
                'keterangan' => 'request prf hardcopy',
                'user_entry' => auth()->id(),
            ]);

        });

        return redirect()->route('hardcopys.index')
                ->with('success', 'Ap Hardcopy created successfully.');
        }

    public function show($id): View
    {
    $finance = \DB::table('finances')
        ->join('m_dept', 'finances.id_dept', '=', 'm_dept.id')
        ->join('m_category', 'finances.id_category', '=', 'm_category.id')
        ->join('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')
        ->join('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')
        ->join('m_bank', 'finances.id_bank', '=', 'm_bank.id')
        ->join('m_currency', 'finances.id_currency', '=', 'm_currency.id')
        ->join('m_ppn', 'finances.id_ppn', '=', 'm_ppn.id')
        ->select(
            'finances.*',
            'm_dept.nama as nama_dept',
            'm_category.nama as nama_category',
            'm_hu_rek_sumber.nama as nama_rek_sumber',
            'm_payableto.nama as nama_payable',
            'm_bank.nama as nama_bank',
            'm_currency.nama as nama_currency',
            'm_ppn.nama as nama_ppn'
        )
        ->where('finances.id', $id)
        ->first();

    $histories = DB::table('history_approval')
    ->join('users', 'history_approval.user_entry', '=', 'users.id')
    ->select(
        'history_approval.*',
        'users.name',
        'users.email'
    )
    ->where('history_approval.id_finance', $id)
    ->orderBy('history_approval.id','asc')
    ->get();

    return view('hardcopys.show', compact('finance','histories'));
    }

     public function edit($id): View
        {
             $finance = \DB::table('finances')
                ->join('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->join('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->join('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')
                ->join('m_bank', 'finances.id_bank', '=', 'm_bank.id')
                ->join('m_currency', 'finances.id_currency', '=', 'm_currency.id')
                ->select(
                    'finances.*',
                    'm_dept.nama as nama_dept',
                    'm_hu_rek_sumber.nama as nama_rek_sumber',
                    'm_payableto.nama as nama_payable',
                    'm_bank.nama as nama_bank',
                    'm_currency.nama as nama_currency'
                )
                ->where('finances.id', $id)
                ->first();

            $categorys = Category::where('valid', 1)
            ->orderBy('nama')
            ->get();
            $departments = Department::where('valid', 1)
            ->orderBy('nama')
            ->get();
            $hu_rek_sumbers = Hu_reksumber::where('valid', 1)
            ->orderBy('nama')
            ->get();
            $payabletos = Payableto::where('valid', 1)
            ->where('type', 'main')
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


            return view('hardcopys.edit', compact('categorys','finance','departments','hu_rek_sumbers','payabletos','rek_tujuans','banks','currencys','ppns'));

        }


    public function update(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);

        $validated = $request->validate([
            'payment_term' => 'required',
            'po_no' => 'required',
            'id_category' => 'required',
            'id_dept' => 'required',
            'id_rek_sumber' => 'required',
            'id_payable' => 'required',
            'nama_rekening_tujuan' => 'required',
            'id_bank' => 'required',
            'no_rek_tujuan' => 'required',
            'doc_no' => 'required',
            'description' => 'required',
            'id_currency' => 'required',
            'dpp' => 'required',
        ]);

        $docNoCheckService = new DocNoCheckService();
        $check = $docNoCheckService->check($request->doc_no, 'hardcopy', $finance->id);
        if (!empty($check['exists'])) {
            return back()
                ->withInput()
                ->withErrors(['doc_no' => 'Doc No sudah terpakai: '.implode(', ', $check['exists'])]);
        }


        $data = $request->all();
        $data['status'] = 'requested';
        $data['user_entry'] = auth()->id();
        $data['type'] = 'hardcopy';

        DB::transaction(function () use ($data, $finance) {
            $finance->update($data);

            History_approval::create([
                'id_finance' => $finance->id,
                'status' => 'requested',
                'keterangan' => $data['alasan'],
                'user_entry' => auth()->id(),
            ]);
        });


        return redirect()->route('hardcopys.index')
            ->with('success', 'Hard Copy updated successfully');
    }


    public function destroy($id): RedirectResponse
    {
        DB::transaction(function () use ($id) {

            History_approval::where('id_finance', $id)->delete();

            $finance = Finance::findOrFail($id);
            $finance->delete();
        });

        return redirect()->route('hardcopys.index')
            ->with('success', 'Hardcopy deleted successfully');
    }

}
