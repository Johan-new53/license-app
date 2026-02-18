<?php
// app/Http/Controllers/InvoiceController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Vendor;




class InvoiceController extends Controller
{

    public function invoice()
    {
        $vendors = Vendor::orderBy('name')->get();
        return view('invoice', compact('vendors'));
        
    }

    public function cekInvoice(Request $request)
    {
        

        $request->validate([
            'no_invoice' => 'required',
            'id_vendor' => 'required',
        ]);

       
        $exists = Invoice::where('no_invoice', $request->no_invoice)
                        ->where('id_vendor', $request->id_vendor)
                        ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }
}
