<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ppn;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PpnController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ppn-list', ['only' => ['index']]);
        $this->middleware('permission:ppn-create', ['only' => ['create','store']]);
        $this->middleware('permission:ppn-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $ppn = Ppn::orderBy('nama', 'ASC')->paginate(5);
        return view('masterdata.ppn.index', compact('ppn'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        $ppn = Ppn::get();
        return view('masterdata.ppn.create', compact('ppn'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
            'ppn' => 'required',
        ]);

        Ppn::create([
            'nama' => $request->input('nama'),
            'ppn' => $request->input('ppn'),
            'flag_ubah' => $request->input('flag_ubah'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('ppn.index')
            ->with('success', 'PPN created successfully');
    }

    public function edit($id): View
    {
        $ppn = Ppn::find($id);
        return view('masterdata.ppn.edit', compact('ppn'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $ppn = Ppn::find($id);
        $ppn->nama = $request->input('nama');
        $ppn->ppn = $request->input('ppn');
        $ppn->flag_ubah = $request->input('flag_ubah');
        $ppn->valid = $request->input('valid');
        $ppn->user_entry = auth()->user()->name;
        $ppn->save();

        return redirect()->route('ppn.index')
            ->with('success', 'PPN updated successfully');
    }
}
