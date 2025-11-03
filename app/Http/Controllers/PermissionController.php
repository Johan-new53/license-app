<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index','store']]);
        $this->middleware('permission:permission-create', ['only' => ['create','store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $permissions = Permission::orderBy('name', 'ASC')->paginate(5);
        return view('permissions.index', compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        $permission = Permission::get();
        return view('permissions.create', compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',            
        ]);

        
       
        Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'web',
        ]);
                

        return redirect()->route('permissions.index')
            ->with('success', 'Permissions created successfully');
    }

    
    
    public function show($id): View
    {
        $permission = Permission::find($id);
        return view('permissions.show', compact('permission'));
    }

   public function edit($id): View
    {
        $permission = Permission::find($id);
        return view('permissions.edit', compact('permission'));
    }

    
    

     public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
           
        ]);

        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->guard_name='web';
        $permission->save();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }


    public function destroy($id): RedirectResponse
    {
        Permission::find($id)->delete();
        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}
