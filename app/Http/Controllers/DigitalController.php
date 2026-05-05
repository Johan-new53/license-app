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


class DigitalController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:digital-list|digital-create|digital-edit|digital-delete', ['only' => ['index','show']]);
        $this->middleware('permission:digital-create', ['only' => ['create','store']]);
        $this->middleware('permission:digital-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:digital-delete', ['only' => ['destroy']]);
        $this->middleware('permission:digital-export', ['only' => ['export']]);
    }



    public function index(Request $request): View
    {
        $statusOptions = Finance::query()
            ->where('user_entry', auth()->id())
            ->where('type', 'digital')
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
            ->where('type', 'digital');

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

        $digitals = $query
            ->with('payableto')
            ->orderBy('id', 'desc')
            ->paginate(6)
            ->appends($request->query());

        return view('digitals.index', compact('digitals', 'statusOptions', 'payabletos'))
            ->with('i', ($digitals->currentPage() - 1) * $digitals->perPage());
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



        return view('digitals.create', compact('categorys','departments','hu_rek_sumbers','payabletos','rek_tujuans','banks','currencys','ppns'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (auth()->user()->level == 0) {
            $request->validate([
                'journal_no' => 'required',
            ]);
        } else {
            $request->validate([
              'payment_term' => 'required',
              'po_no' => 'nullable',
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
              'journal_no' => 'required',
              'dpp' => 'required',
            ]);
        }

        $docNoCheckService = new DocNoCheckService();
        if ($request->filled('doc_no')) {
            $check = $docNoCheckService->check($request->doc_no, 'digital');
            if (!empty($check['exists'])) {
                return back()
                    ->withInput()
                    ->withErrors(['doc_no' => 'Doc No sudah terpakai: '.implode(', ', $check['exists'])]);
            }
        }

        $data = $request->all();

        if ($request->filled('id_payable')) {
            $hari = Payableto::where('id', $request->id_payable)->value('hari');
            $data['top_hari'] = $hari;
            $data['due_date'] = now()->addDays($hari);
        } else {
            $data['top_hari'] = 0;
            $data['due_date'] = now();
        }

        if (auth()->user()->level == 0 && empty($data['description'])) {
            $data['description'] = 'Journal Number : '.$data['journal_no'];
        }


        $data['user_entry'] = auth()->id();
        $data['type'] = 'digital';
        $data['status'] = 'requested';



        DB::transaction(function () use ($data) {
        $finance = Finance::create($data);
            History_approval::create([
                'id_finance' => $finance->id,
                'status' => 'requested',
                'keterangan' => 'request prf digital',
                'user_entry' => auth()->id(),
            ]);

        });

        return redirect()->route('digitals.index')
                ->with('success', 'Ap Digital created successfully.');
        }

    public function show($id): View
    {
    $finance = \DB::table('finances')
        ->leftJoin('m_dept', 'finances.id_dept', '=', 'm_dept.id')
        ->leftJoin('m_category', 'finances.id_category', '=', 'm_category.id')
        ->leftJoin('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')
        ->leftJoin('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')
        ->leftJoin('m_bank', 'finances.id_bank', '=', 'm_bank.id')
        ->leftJoin('m_currency', 'finances.id_currency', '=', 'm_currency.id')
        ->leftJoin('m_ppn', 'finances.id_ppn', '=', 'm_ppn.id')
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

    return view('digitals.show', compact('finance','histories'));
    }

    public function edit($id): View
    {
        $finance = Finance::findOrFail($id);

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


            return view('digitals.edit', compact('categorys','finance','departments','hu_rek_sumbers','payabletos','rek_tujuans','banks','currencys','ppns'));

        }


    public function update(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);

        if (auth()->user()->level == 0) {
            $request->validate([
                'journal_no' => 'required',
                'alasan' => 'required',
            ]);

            DB::transaction(function () use ($finance, $request) {
                $finance->update([
                    'journal_no' => $request->journal_no,
                    'description' => 'Journal Number : '.$request->journal_no,
                ]);

                History_approval::create([
                    'id_finance' => $finance->id,
                    'status' => $finance->status,
                    'keterangan' => $request->alasan,
                    'user_entry' => auth()->id(),
                ]);
            });

            if ($request->source == 'approval_index') {
                return redirect()->route('approvals.index')->with('success', 'Journal Number updated successfully');
            } elseif ($request->source == 'approval_show') {
                return redirect()->route('approvals.show', $finance->id)->with('success', 'Journal Number updated successfully');
            }

            return redirect()->route('digitals.index')
                ->with('success', 'Journal Number updated successfully');
        }

        $request->validate([
            'payment_term' => 'required',
            'po_no' => 'nullable',
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
            'journal_no' => 'required',
            'dpp' => 'required',
        ]);

        $docNoCheckService = new DocNoCheckService();
        $check = $docNoCheckService->check($request->doc_no, 'digital', $finance->id);
        if (!empty($check['exists'])) {
            return back()
                ->withInput()
                ->withErrors(['doc_no' => 'Doc No sudah terpakai: '.implode(', ', $check['exists'])]);
        }


        $data = $request->all();
        $data['status'] = 'requested';
        $data['type'] = 'digital';

        DB::transaction(function () use ($data, $finance) {
            $finance->update($data);

            History_approval::create([
                'id_finance' => $finance->id,
                'status' => 'requested',
                'keterangan' => $data['alasan'],
                'user_entry' => auth()->id(),
            ]);
        });


        if ($request->source == 'approval_index') {
            return redirect()->route('approvals.index')->with('success', 'Digital updated successfully');
        } elseif ($request->source == 'approval_show') {
            return redirect()->route('approvals.show', $finance->id)->with('success', 'Digital updated successfully');
        }

        return redirect()->route('digitals.index')
            ->with('success', 'Digital updated successfully');
    }


    public function destroy($id): RedirectResponse
    {
        DB::transaction(function () use ($id) {

            History_approval::where('id_finance', $id)->delete();

            $finance = Finance::findOrFail($id);
            $finance->delete();
        });

        return redirect()->route('digitals.index')
            ->with('success', 'Digital deleted successfully');
    }

}
