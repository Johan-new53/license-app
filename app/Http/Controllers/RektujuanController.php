<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Rektujuan;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RektujuanController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:rektujuan-list', ['only' => ['index']]);
        $this->middleware('permission:rektujuan-create', ['only' => ['create','store']]);
        $this->middleware('permission:rektujuan-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $rektujuan = Rektujuan::orderBy('nama', 'ASC')->paginate(5);
        return view('masterdata.rektujuan.index', compact('rektujuan'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        $rektujuan = Rektujuan::get();
        return view('masterdata.rektujuan.create', compact('rektujuan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        Rektujuan::create([
            'nama' => $request->input('nama'),
            'norek' => $request->input('norek'),
            'bank' => $request->input('bank'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('rektujuan.index')
            ->with('success', 'Rekening Tujuan created successfully');
    }

    public function edit($id): View
    {
        $rektujuan = Rektujuan::find($id);
        return view('masterdata.rektujuan.edit', compact('rektujuan'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $rektujuan = Rektujuan::find($id);
        $rektujuan->nama = $request->input('nama');
        $rektujuan->norek = $request->input('norek');
        $rektujuan->bank = $request->input('bank');
        $rektujuan->valid = $request->input('valid');
        $rektujuan->user_entry = auth()->user()->name;
        $rektujuan->save();

        return redirect()->route('rektujuan.index')
            ->with('success', 'Rekening Tujuan updated successfully');
    }
}
