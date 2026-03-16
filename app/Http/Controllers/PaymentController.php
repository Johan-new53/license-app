<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\View\View;


use App\Models\Department;
use App\Models\Hu_reksumber;

use App\Models\Payableto;
use App\Models\Rektujuan;

use App\Models\Matauang;

use App\Http\Controllers\Controller;
use App\Models\History_approval;
use Illuminate\Support\Facades\DB;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;



class PaymentController extends Controller
{


    function __construct()
    {
        $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete', ['only' => ['index','show']]);
        $this->middleware('permission:payment-create', ['only' => ['create','store']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:payment-export', ['only' => ['export']]);
    }


    public function index(Request $request): View
    {
        if (!$request->filled('payment_date')) {
        $payments = collect(); // kosong
        return view('payments.index', compact('payments'))->with('i', 0);
        }

        
        $query = Finance::query()
        ->join('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')
        ->where('finances.status', 'approved 2')
        ->select('finances.*', 'm_payableto.nama as nama_payable');            

        if ($request->filled('payment_date')) {
            $query->whereRaw(
                "finances.due_date <= ?",                
                [$request->payment_date]
            );
        }

        $payments = $query
            ->orderBy('finances.id', 'desc')
            ->paginate(6)
            ->withQueryString();

        return view('payments.index', compact('payments'))
            ->with('i', ($payments->currentPage() - 1) * $payments->perPage());
    } 


    public function show($id): View
        {
            $finance = \DB::table('finances')
                ->leftjoin('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->leftjoin('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->leftjoin('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')

                ->leftjoin('m_currency', 'finances.id_currency', '=', 'm_currency.id')
                ->leftjoin('m_rek_tujuan', 'finances.id_rek_tujuan', '=', 'm_rek_tujuan.id')
                ->select(
                    'finances.*',
                    'm_dept.nama as nama_dept',
                    'm_hu_rek_sumber.nama as nama_rek_sumber',
                    'm_payableto.nama as nama_payable',
                    'm_currency.nama as nama_currency',
                    'm_rek_tujuan.nama as nama_rek_tujuan'
                )
                ->where('finances.id', $id)
                ->firstOrFail();

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

            return view('payments.show', compact('finance','histories'));
        }

        public function edit($id): View
        {
             $payments = \DB::table('finances')
                ->join('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->join('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->join('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')

                ->join('m_currency', 'finances.id_currency', '=', 'm_currency.id')
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


            return view('payments.edit', compact('payments','departments','hu_rek_sumbers','payabletos','rek_tujuans','currencys'));

        }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_date' => 'required',           
        ]);

        $query = Finance::query()
            ->join('m_payableto', 'finances.id_payable', '=', 'm_payableto.id')
            ->where('finances.status', 'approved 2');

        if ($request->filled('payment_date')) {
            $query->whereRaw(
                "finances.due_date <= ?",                
                [$request->payment_date]
            );
        }

        $finances = $query->select('finances.id')->get();

        DB::transaction(function () use ($finances, $request) {

            foreach ($finances as $finance) {

                Finance::where('id', $finance->id)->update([
                    'status' => 'paid',
                    'payment_date' => $request->payment_date ?? now(),
                    'user_payment_entry' => auth()->id(),
                    'payment_entry'=>now(),
                ]);

                History_approval::create([
                    'id_finance' => $finance->id,
                    'status' => 'paid',
                    'keterangan' => 'paid',
                    'user_entry' => auth()->id(),
                ]);
            }

        });

        return redirect()->route('payments.index')
            ->with('success', 'Status payment berhasil diupdate menjadi Paid');
    }

 
   
    
    public function export($payment_date = null)
    {
        // jika payment_date kosong, redirect kembali dengan error
        if (!$payment_date) {
            return redirect()->back()->with('error', 'Payment Date wajib diisi!');
        }

        return Excel::download(
            new PaymentsExport($payment_date),
            'payments_'.$payment_date.'.xlsx'
        );
    }
}
