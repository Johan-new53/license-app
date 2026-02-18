<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CsvImportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-import', ['only' => ['import']]);
    }

    public function import(Request $request)
    {
        // Validate the uploaded CSV file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Truncate the table
        DB::table('products')->truncate();

        // Get the uploaded file
        $path = $request->file('csv_file')->getRealPath();

        // Open the file and read data
        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle); // Get the headers from first row

            while (($data = fgetcsv($handle)) !== false) {
                $row = array_combine($header, $data);

                //$start_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['start_date'])->format('Y-m-d');
                 if ($row['start_date'] =="")
                {
                    $start_date="1900-01-01";
                }
                else {    
                    $start_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['start_date'])->format('Y-m-d');
                }
                //$end_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['end_date'])->format('Y-m-d');
                if ($row['end_date'] =="")
                {
                    $request_date="1900-01-01";
                }
                else {    
                    $end_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['end_date'])->format('Y-m-d');
                }
                //$next_bidding = \Carbon\Carbon::createFromFormat('m/d/Y', $row['next_bidding'])->format('Y-m-d');
                if ($row['next_bidding'] =="")
                {
                    $next_bidding="1900-01-01";
                }
                else {    
                    $next_bidding = \Carbon\Carbon::createFromFormat('m/d/Y', $row['next_bidding'])->format('Y-m-d');
                }
                //$renewal_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['renewal_date'])->format('Y-m-d');
                if ($row['renewal_date'] =="")
                {
                    $renewal_date="1900-01-01";
                }
                else {    
                    $renewal_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['renewal_date'])->format('Y-m-d');
                }
                //$tgl_email1 = \Carbon\Carbon::createFromFormat('m/d/Y', $row['tgl_email1'])->format('Y-m-d');
                if ($row['tgl_email1'] =="")
                {
                    $tgl_email1="1900-01-01";
                }
                else {    
                    $tgl_email1 = \Carbon\Carbon::createFromFormat('m/d/Y', $row['tgl_email1'])->format('Y-m-d');
                }
                //$tgl_email2 = \Carbon\Carbon::createFromFormat('m/d/Y', $row['tgl_email2'])->format('Y-m-d');
                if ($row['tgl_email2'] =="")
                {
                    $tgl_email2="1900-01-01";
                }
                else {    
                    $tgl_email2 = \Carbon\Carbon::createFromFormat('m/d/Y', $row['tgl_email2'])->format('Y-m-d');
                }
                //$tgl_email3 = \Carbon\Carbon::createFromFormat('m/d/Y', $row['tgl_email3'])->format('Y-m-d');
                if ($row['tgl_email3'] =="")
                {
                    $tgl_email3="1900-01-01";
                }
                else {    
                    $tgl_email3 = \Carbon\Carbon::createFromFormat('m/d/Y', $row['tgl_email3'])->format('Y-m-d');
                }

                if ($row['request_date'] =="")
                {
                    $request_date="1900-01-01";
                }
                else {    
                    $request_date = \Carbon\Carbon::createFromFormat('m/d/Y', $row['request_date'])->format('Y-m-d');
                }
                //$date_update_status = \Carbon\Carbon::createFromFormat('m/d/Y', $row['date_update_status'])->format('Y-m-d');
                if ($row['date_update_status'] =="")
                {
                    $date_update_status="1900-01-01";
                }
                else {    
                    $date_update_status = \Carbon\Carbon::createFromFormat('m/d/Y', $row['date_update_status'])->format('Y-m-d');
                }
                //$created_at = \Carbon\Carbon::createFromFormat('m/d/Y H:i', $row['created_at'])->format('Y-m-d H:i');
                if ($row['created_at'] =="")
                {
                    $created_at="1900-01-01";
                }
                else {    
                    $created_at = \Carbon\Carbon::createFromFormat('m/d/Y H:i', $row['created_at'])->format('Y-m-d H:i');
                }
                //$updated_at = \Carbon\Carbon::createFromFormat('m/d/Y H:i', $row['updated_at'])->format('Y-m-d H:i');
                if ($row['updated_at'] =="")
                {
                    $updated_at="1900-01-01";
                }
                else {    
                    $updated_at = \Carbon\Carbon::createFromFormat('m/d/Y H:i', $row['updated_at'])->format('Y-m-d H:i');
                }

                if ($row['qty'] =="")
                {
                    $qty=0;
                }
                else {    
                    $qty = str_replace([','], '', $row['qty']);
                    
                }

                if ($row['used'] =="")
                {
                    $used=0;
                }
                else {    
                    $used = str_replace([','], '', $row['used']);
                    
                }

                if ($row['remaining'] =="")
                {
                    $remaining=0;
                }
                else {                        
                    $remaining = str_replace([','], '', $row['remaining']);
                }
         

                 if ($row['last_bidding'] =="")
                {
                    $last_bidding="1900-01-01";
                }
                else {    
                    $last_bidding = \Carbon\Carbon::createFromFormat('m/d/Y', $row['last_bidding'])->format('Y-m-d');
                }
                
                if ($row['amount_excl_vat'] =="")
                {
                    $amount_excl_vat=0;
                }
                else {    
                    $amount_excl_vat = str_replace([','], '', $row['amount_excl_vat']);                    
                }

                Product::create([                    
                    'id'=> $row['id'],
                    'item'=> $row['item'],
                    'category'=> $row['category'],
                    'description'=> $row['description'],
                    'qty'=> $qty,
                    'used'=> $used,
                    'remaining'=> $remaining,
                    'start_date'=> $start_date,
                    'end_date'=> $end_date,
                    'last_bidding'=> $last_bidding,
                    'next_bidding'=> $next_bidding,
                    'renewal_date'=> $renewal_date,
                    'tgl_email1'=> $tgl_email1,
                    'tgl_email2'=> $tgl_email2,
                    'tgl_email3'=> $tgl_email3,
                    'request_date'=> $request_date,
                    'vendor'=> $row['vendor'],
                    'mata_uang'=> $row['mata_uang'],
                    'amount_excl_vat'=> $amount_excl_vat,
                    'pr'=> $row['pr'],
                    'po'=> $row['po'],
                    'pic'=> $row['pic'],
                    'mail_pic'=> $row['mail_pic'],
                    'hp_pic'=> $row['hp_pic'],
                    'status'=> $row['status'],
                    'date_update_status'=> $date_update_status,
                    'no_tiket'=> $row['no_tiket'],
                    'remark'=> $row['remark'],
                    'nama_admin'=> $row['nama_admin'],
                    'email_admin'=> $row['email_admin'],
                    'hp_admin'=> $row['hp_admin'],
                    'created_at'=> $created_at,
                    'updated_at'=> $updated_at,
                ]);
                // Insert into database
                // DB::table('products')->insert($row);
            }

            fclose($handle);
        }
       
        
        return redirect()->route('products.index')
            ->with('success', 'CSV imported successfully.');

    }
}
