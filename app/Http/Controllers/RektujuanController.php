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
        $query = Rektujuan::query();

        if ($request->has('nama') && $request->nama != '') {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('norek') && $request->norek != '') {
            $query->where('norek', 'LIKE', '%' . $request->norek . '%');
        }

        if ($request->has('bank') && $request->bank != '') {
            $query->where('bank', 'LIKE', '%' . $request->bank . '%');
        }

        if ($request->has('valid') && $request->valid != '') {
            $query->where('valid', $request->valid);
        }

        $rektujuan = $query->orderBy('id', 'desc')->paginate(10);
        return view('masterdata.rektujuan.index', compact('rektujuan'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
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

        $namaInput = trim($request->input('nama'));
        $norek = trim($request->input('norek') ?? '');
        $bank = trim($request->input('bank') ?? '');

        // Ambil nama bersih (jika user memasukkan nama gabungan secara manual, ambil bagian pertama saja)
        $parts = explode(' - ', $namaInput);
        $cleanNama = trim($parts[0]);

        // Buat nama gabungan otomatis
        $fullName = $cleanNama;
        if ($norek !== '') {
            $fullName .= ' - ' . $norek;
        }
        if ($bank !== '') {
            $fullName .= ' - ' . $bank;
        }

        Rektujuan::create([
            'nama' => $fullName,
            'norek' => $norek !== '' ? $norek : null,
            'bank' => $bank !== '' ? $bank : null,
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('rektujuan.index')
            ->with('success', 'Rekening Tujuan created successfully');
    }

    public function edit($id): View
    {
        $rektujuan = Rektujuan::find($id);
        
        // Pisahkan nama gabungan untuk ditampilkan sebagai nama asli di form edit
        $parts = explode(' - ', $rektujuan->nama);
        $rektujuan->nama = trim($parts[0]);
        
        return view('masterdata.rektujuan.edit', compact('rektujuan'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $rektujuan = Rektujuan::find($id);
        
        $namaInput = trim($request->input('nama'));
        $norek = trim($request->input('norek') ?? '');
        $bank = trim($request->input('bank') ?? '');

        // Ambil nama bersih (jika user memasukkan nama gabungan secara manual, ambil bagian pertama saja)
        $parts = explode(' - ', $namaInput);
        $cleanNama = trim($parts[0]);

        // Buat nama gabungan otomatis
        $fullName = $cleanNama;
        if ($norek !== '') {
            $fullName .= ' - ' . $norek;
        }
        if ($bank !== '') {
            $fullName .= ' - ' . $bank;
        }

        $rektujuan->nama = $fullName;
        $rektujuan->norek = $norek !== '' ? $norek : null;
        $rektujuan->bank = $bank !== '' ? $bank : null;
        $rektujuan->valid = $request->input('valid');
        $rektujuan->user_entry = auth()->user()->name;
        $rektujuan->save();

        return redirect()->route('rektujuan.index')
            ->with('success', 'Rekening Tujuan updated successfully');
    }
}
