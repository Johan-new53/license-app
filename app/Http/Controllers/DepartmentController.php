<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:dept-list', ['only' => ['index']]);
        $this->middleware('permission:dept-create', ['only' => ['create','store']]);
        $this->middleware('permission:dept-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $query = Department::query();

        if ($request->has('nama') && $request->nama != '') {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('valid') && $request->valid != '') {
            $query->where('valid', $request->valid);
        }

        $department = $query->orderBy('nama', 'ASC')->paginate(10);
        return view('masterdata.department.index', compact('department'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(): View
    {
        $department = Department::get();
        return view('masterdata.department.create', compact('department'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        Department::create([
            'nama' => $request->input('nama'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('department.index')
            ->with('success', 'Department created successfully');
    }

    public function edit($id): View
    {
        $department = Department::find($id);
        return view('masterdata.department.edit', compact('department'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $department = Department::find($id);
        $department->nama = $request->input('nama');
        $department->valid = $request->input('valid');
        $department->user_entry = auth()->user()->name;
        $department->save();

        return redirect()->route('department.index')
            ->with('success', 'Department updated successfully');
    }
}
