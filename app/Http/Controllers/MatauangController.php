<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Matauang;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MatauangController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:matauang-list', ['only' => ['index']]);
        $this->middleware('permission:matauang-create', ['only' => ['create','store']]);
        $this->middleware('permission:matauang-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $matauang = Matauang::orderBy('nama', 'ASC')->paginate(5);
        return view('masterdata.matauang.index', compact('matauang'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        $matauang = Matauang::get();
        return view('masterdata.matauang.create', compact('matauang'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        Matauang::create([
            'nama' => $request->input('nama'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('matauang.index')
            ->with('success', 'Mata Uang created successfully');
    }

    public function edit($id): View
    {
        $matauang = Matauang::find($id);
        return view('masterdata.matauang.edit', compact('matauang'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $matauang = Matauang::find($id);
        $matauang->nama = $request->input('nama');
        $matauang->valid = $request->input('valid');
        $matauang->user_entry = auth()->user()->name;
        $matauang->save();

        return redirect()->route('matauang.index')
            ->with('success', 'Mata Uang updated successfully');
    }
}
