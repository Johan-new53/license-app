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
use App\Models\Category;

use App\Http\Controllers\Controller;
use App\Models\History_approval;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendGraphMail;


class Approval1Controller extends Controller
{


    function __construct()
    {
        $this->middleware('permission:approval-list|approval-create|approval-edit|approval-delete', ['only' => ['index','show']]);
        $this->middleware('permission:approval-create', ['only' => ['create','store']]);
        $this->middleware('permission:approval-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:approval-delete', ['only' => ['destroy']]);
        $this->middleware('permission:approval-export', ['only' => ['export']]);
    }



    public function index(Request $request): View
    {
        $statusOptions = Finance::query()
            ->whereNotNull('status')
            ->where('status', '!=', '')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        if ($statusOptions->isEmpty()) {
            $statusOptions = collect(['requested', 'approved 1', 'approved 2', 'rejected 1', 'rejected 2', 'paid']);
        }

        $query = Finance::query();

        // filter tanggal invoice_date
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Filter type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter payable To (pencarian nama payable)
        if ($request->filled('payable_to')) {
            $query->whereHas('payableto', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->payable_to . '%');
            });
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
            $statuses = (array) $request->status;
            $query->whereIn('status', $statuses);
        }

        $approvals = $query
            ->with('payableto')
            ->whereDate('invoice_date', '>=', '2026-05-01')
            ->orderBy('id', 'desc')
            ->paginate(6)
            ->appends($request->query());

        return view('approvals.index', compact('approvals', 'statusOptions'))
            ->with('i', ($approvals->currentPage() - 1) * $approvals->perPage());

        // $approvals = Finance::orderBy('id', 'desc')
        // ->paginate(6);

        // return view('approvals.index', compact('approvals'))
        //     ->with('i', (request()->input('page', 1) - 1) * 6);
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
           
            $categorys= Category::where('valid', 1)
            ->orderBy('nama')
            ->get();

            return view('approvals.show', compact('finance','histories','categorys'));
        }

        public function edit($id): View
        {
             $approval1s = \DB::table('finances')
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


            return view('approvals.edit', compact('approvals','departments','hu_rek_sumbers','payabletos','rek_tujuans','currencys'));

        }


      public function update(Request $request, $id)
        {
            $finance = Finance::findOrFail($id);

            $validated = $request->validate([
                'keterangan' => 'required'                
            ]);
            
            $email = $finance->user->email ?? null;
            

            if ($request->status == 'approved' and $request->level == 1) {
                $finance->status = 'approved 1';}
            elseif ($request->status == 'rejected' and $request->level ==1) {
                $finance->status = 'rejected 1';}
            elseif ($request->status == 'approved' and $request->level == 2) {
                $finance->status = 'approved 2';}
            elseif ($request->status == 'rejected' and $request->level == 2) {
                $finance->status = 'rejected 2';}

            $oldJournalNo = trim((string)$finance->journal_no);
            $newJournalNo = trim((string)$request->journal_no);

            $finance->due_date = $request->due_date;
            $finance->payment_term = $request->payment_term;
            $finance->po_no = $request->po_no;
            $finance->id_category = $request->id_category;
            $finance->journal_no = $newJournalNo !== '' ? $newJournalNo : null;

            $keteranganHistory = $request->keterangan;
            if ($newJournalNo !== '' && $newJournalNo !== $oldJournalNo) {
                if ($oldJournalNo === '') {
                    $changeNote = "Journal No diisi: " . $newJournalNo;
                } else {
                    $changeNote = "Journal No diubah: " . $oldJournalNo . " -> " . $newJournalNo;
                }
                $keteranganHistory .= " | " . $changeNote;
            }

            $keterangan = $request->keterangan;
            DB::transaction(function () use ($finance, $keteranganHistory) {

                $finance->save();

                History_approval::create([
                    'id_finance' => $finance->id,
                    'status' => $finance->status,
                    'keterangan' => $keteranganHistory,
                    'user_entry' => auth()->id(),
                ]);
                
            });
           

            if ($finance->status <> 'approved 1') {
                $subject = auth()->user()->name . " " . $request->status . " your request";
               
                SendGraphMail::dispatchSync(
                    $email,
                    $subject,
                    view('emails.status_prf', compact('finance','keterangan'))->render(),
                    auth()->user()->graph_tenant, // tenant
                    auth()->user()->email // sender
                );

            }

            return redirect()->route('approvals.index')
                ->with('success', 'Approval berhasil diproses');
        }




}
