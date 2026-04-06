<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:category-list', ['only' => ['index']]);
        $this->middleware('permission:category-create', ['only' => ['create','store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
    }

    public function index(Request $request): View
    {
        $query = Category::query();

        if ($request->has('nama') && $request->nama != '') {
            $query->where('nama', 'LIKE', '%' . $request->nama . '%');
        }

        if ($request->has('valid') && $request->valid != '') {
            $query->where('valid', $request->valid);
        }

        $category = $query->orderBy('nama', 'ASC')->paginate(10);
        return view('masterdata.category.index', compact('category'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(): View
    {
        $category = Category::get();
        return view('masterdata.category.create', compact('category'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        Category::create([
            'nama' => $request->input('nama'),
            'valid' => 1,
            'user_entry' => auth()->user()->name,
        ]);

        return redirect()->route('category.index')
            ->with('success', 'Mata Uang created successfully');
    }

    public function edit($id): View
    {
        $category = Category::find($id);
        return view('masterdata.category.edit', compact('category'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama' => 'required',
        ]);

        $category = Category::find($id);
        $category->nama = $request->input('nama');
        $category->valid = $request->input('valid');
        $category->user_entry = auth()->user()->name;
        $category->save();

        return redirect()->route('category.index')
            ->with('success', 'Mata Uang updated successfully');
    }
}
