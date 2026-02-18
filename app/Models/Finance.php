<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $casts = [
    'invoice_date' => 'datetime',
    ];
    protected $table = 'finances';
    
    protected $fillable = [   
    'type','po_no','id_category','form_submission_time','final_validation_time','email',
    'id_dept','id_rek_sumber','id_payable_h','id_payable_s','id_payable_a','nama_rekening_tujuan',	'id_bank',
    'no_rek_tujuan','invoice_date','doc_no','description','id_currency','dpp',
    'persen_ppn','nilai_ppn','pph','total_amount','input_file',	'send_email',
    'payment_term','journal_no','status','payment_date','user_payment_entry',
    'payment_entry','user_entry','created_at','updated_at'

    ];
}