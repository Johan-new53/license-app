<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Models\Department;
use App\Models\Hu_reksumber;
use App\Models\Payableto_h;
use App\Models\Rektujuan;
use App\Models\Bank;
use App\Models\Matauang;
use App\Models\Vendor;
use App\Http\Controllers\Controller;

class SubmissionController extends Controller
{
    

    function __construct()
    {
        $this->middleware('permission:hardcopy-list|hardcopy-create|hardcopy-edit|hardcopy-delete', ['only' => ['index','show']]);
        $this->middleware('permission:hardcopy-create', ['only' => ['create','store']]);
        $this->middleware('permission:hardcopy-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:hardcopy-delete', ['only' => ['destroy']]);
        $this->middleware('permission:hardcopy-export', ['only' => ['export']]);
    }

    

    public function index(): View
    {
        
        $hardcopys = Finance::where('user_entry', auth()->id())
        ->orderBy('id', 'desc')
        ->paginate(6);

        return view('hardcopys.index', compact('hardcopys'))
            ->with('i', (request()->input('page', 1) - 1) * 6);
    }

    public function create()
    {
       
        $departments = Department::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $hu_rek_sumbers = Hu_reksumber::where('valid', 1)
        ->orderBy('nama')
        ->get();
        $payableto_hs = Payableto_h::where('valid', 1)
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

        $vendors= Vendor::where('valid', 1)
        ->orderBy('name')
        ->get();

        return view('hardcopys.create', compact('departments','hu_rek_sumbers','payableto_hs','rek_tujuans','banks','currencys','vendors'));
    }

      public function store(Request $request): RedirectResponse
    {
        request()->validate([
          'id_dept' => 'required',
          'id_rek_sumber' => 'required',
          'id_payable_h' => 'required',
          'nama_rekening_tujuan' => 'required',
          'id_bank' => 'required',
          'no_rek_tujuan' => 'required',
          'doc_no' => 'required',
          'description' => 'required',
          'id_currency' => 'required',
          'dpp' => 'required',                 
           
        ]);

        $data = $request->all();
        $data['user_entry'] = auth()->id();
        $data['type'] = 'hardcopy';
        $data['status'] = 'request';
        Finance::create($data);

        return redirect()->route('hardcopys.index')
                ->with('success', 'Ap Hardcopy created successfully.');
        }

      public function show($id): View
        {
            $finance = \DB::table('finances')
                ->join('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->join('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->join('m_payableto_h', 'finances.id_payable_h', '=', 'm_payableto_h.id')
                ->join('m_bank', 'finances.id_bank', '=', 'm_bank.id')                
                ->join('m_currency', 'finances.id_currency', '=', 'm_currency.id')
                ->select(
                    'finances.*',
                    'm_dept.nama as nama_dept',
                    'm_hu_rek_sumber.nama as nama_rek_sumber',
                    'm_payableto_h.nama as nama_payable',
                    'm_bank.nama as nama_bank',
                    'm_currency.nama as nama_currency'
                )
                ->where('finances.id', $id)
                ->first();

            return view('hardcopys.show', compact('finance'));
        }

     public function edit($id): View
        {
             $finance = \DB::table('finances')
                ->join('m_dept', 'finances.id_dept', '=', 'm_dept.id')
                ->join('m_hu_rek_sumber', 'finances.id_rek_sumber', '=', 'm_hu_rek_sumber.id')

                ->join('m_payableto_h', 'finances.id_payable_h', '=', 'm_payableto_h.id')
                ->join('m_bank', 'finances.id_bank', '=', 'm_bank.id')                
                ->join('m_currency', 'finances.id_currency', '=', 'm_currency.id')
                ->select(
                    'finances.*',
                    'm_dept.nama as nama_dept',
                    'm_hu_rek_sumber.nama as nama_rek_sumber',
                    'm_payableto_h.nama as nama_payable',
                    'm_bank.nama as nama_bank',
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
            $payableto_hs = Payableto_h::where('valid', 1)
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

            $vendors= Vendor::where('valid', 1)
            ->orderBy('name')
            ->get();

            return view('hardcopys.edit', compact('finance','departments','hu_rek_sumbers','payableto_hs','rek_tujuans','banks','currencys','vendors'));
            
        }

       
    public function update(Request $request, $id)
    {
        $finance = Finance::findOrFail($id);

        $validated = $request->validate([
            'id_dept' => 'required',
            'id_rek_sumber' => 'required',
            'id_payable_h' => 'required',
            'nama_rekening_tujuan' => 'required',
            'id_bank' => 'required',
            'no_rek_tujuan' => 'required',
            'doc_no' => 'required',
            'description' => 'required',
            'id_currency' => 'required',
            'dpp' => 'required',
        ]);

        $data = $request->all();
        $finance->update($data);

        return redirect()->route('hardcopys.index')
            ->with('success', 'Hard Copy updated successfully');
    }


    
    public function destroy($id): RedirectResponse
    {
        $finance = Finance::findOrFail($id);
        $finance->delete();

        return redirect()->route('hardcopys.index')
            ->with('success', 'Hard Copy deleted successfully');
    }

}