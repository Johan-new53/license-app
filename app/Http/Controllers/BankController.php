<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Bank;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BankController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:bank-list', ['only' => ['index']]);
        $this->middleware('permission:bank-create', ['only' => ['create','store']]);
        $this->middleware('permission:bank-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $query = Bank::query();

        if ($request->has('nama') && $request->nama != '') {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('valid') && $request->valid != '') {
            $query->where('valid', $request->valid);
        }

        $bank = $query->orderBy('nama', 'ASC')->paginate(10);
        return view('masterdata.bank.index', compact('bank'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(): View
    {
        $bank = Bank::get();
        return view('masterdata.bank.create', compact('bank'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        Bank::create([
            'nama' => $request->input('nama'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('bank.index')
            ->with('success', 'Bank created successfully');
    }

    public function edit($id): View
    {
        $bank = Bank::find($id);
        return view('masterdata.bank.edit', compact('bank'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $bank = Bank::find($id);
        $bank->nama = $request->input('nama');
        $bank->valid = $request->input('valid');
        $bank->user_entry = auth()->user()->name;
        $bank->save();

        return redirect()->route('bank.index')
            ->with('success', 'Bank updated successfully');
    }
}
