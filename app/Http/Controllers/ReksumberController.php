<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Hu_reksumber;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReksumberController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:reksumber-list', ['only' => ['index']]);
        $this->middleware('permission:reksumber-create', ['only' => ['create','store']]);
        $this->middleware('permission:reksumber-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $reksumber = Hu_reksumber::orderBy('nama', 'ASC')->paginate(5);
        return view('masterdata.reksumber.index', compact('reksumber'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        $reksumber = Hu_reksumber::get();
        return view('masterdata.reksumber.create', compact('reksumber'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        Hu_reksumber::create([
            'nama' => $request->input('nama'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('reksumber.index')
            ->with('success', 'Rekening Sumber created successfully');
    }

    public function edit($id): View
    {
        $reksumber = Hu_reksumber::find($id);
        return view('masterdata.reksumber.edit', compact('reksumber'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $reksumber = Hu_reksumber::find($id);
        $reksumber->nama = $request->input('nama');
        $reksumber->valid = $request->input('valid');
        $reksumber->user_entry = auth()->user()->name;
        $reksumber->save();

        return redirect()->route('reksumber.index')
            ->with('success', 'Rekening Sumber updated successfully');
    }
}
