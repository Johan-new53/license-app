<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    

    function __construct()
    {
        $this->middleware('permission:product-list|product-create|product-edit|product-delete', ['only' => ['index','show']]);
        $this->middleware('permission:product-create', ['only' => ['create','store']]);
        $this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
        $this->middleware('permission:product-export', ['only' => ['export']]);
    }

    public function index(): View
    {
        $products = Product::orderBy('item')->paginate(6);
        return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 6);
    }

    public function searchitem(Request $request)
    {
	    $search = $request->get('searchitem');
        if ($search=='')
        {
            $products = Product::orderBy('item')->paginate(6);
            return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 6);
        }
        else 
        {
           
            $products = Product::where('item', 'like', '%' . $search . '%')->paginate(6);
            return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 6);
        }
       
        
    }


    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'item' => 'required',
           
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        request()->validate([
            'item' => 'required',
            
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    

public function export()
{
    $fileName = 'Products.csv';
    $products = Product::all();

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];
    
    $columns = ['id', 'item', 'category',
    'description','qty','used','remaining','start_date','end_date',
    'last_bidding','next_bidding','renewal_date',
    'tgl_email1','tgl_email2','tgl_email3','request_date',
    'vendor','mata_uang','amount_excl_vat','pr','po','pic','mail_pic','hp_pic',
    'status','date_update_status','no_tiket','remark',
    'nama_admin','email_admin','hp_admin',
    'created_at','updated_at'
    ];

    $callback = function () use ($products, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        
        foreach ($products as $product) {
            fputcsv($file,[ $product->id , $product->item, $product->category,                
                $product->description , $product->qty , $product->used , $product->remaining ,
                $product->start_date , $product->end_date , $product->last_bidding , $product->next_bidding , 
                $product->renewal_date ,$product->tgl_email1 ,$product->tgl_email2 , $product->tgl_email3 ,$product->request_date , 
                $product->vendor , $product->mata_uang , $product->amount_excl_vat , $product->pr , $product->po ,
                $product->pic , $product->mail_pic , $product->hp_pic ,
                $product->status , $product->date_update_status , $product->no_tiket , $product->remark,
                $product->nama_admin,$product->email_admin,$product->hp_admin,
                $product->created_at,$product->updated_at
  
        ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
    }


}