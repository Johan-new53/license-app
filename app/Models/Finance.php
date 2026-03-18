<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
   
    protected $casts = [
    'invoice_date' => 'datetime',
    'due_date' => 'date',
    ];
    protected $table = 'finances';

    protected $fillable = [
    'type','po_no','id_category','form_submission_time','final_validation_time','email','top_hari','due_date',
    'id_dept','id_rek_sumber','id_payable','id_rek_tujuan','nama_rekening_tujuan',	'id_bank',
    'no_rek_tujuan','invoice_date','doc_no','description','activity_code','id_currency','dpp',
    'id_ppn','persen_ppn','nilai_ppn','pph','total_amount','input_file',	'send_email',
    'payment_term','journal_no','status','payment_date','user_payment_entry',
    'payment_entry','user_entry','created_at','updated_at'

    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_entry');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_entry');
    }

    public function payableto()
    {
        return $this->belongsTo(Payableto::class, 'id_payable');
    }

    public function histories()
    {
        return $this->hasMany(History_approval::class,'id_finance');
    }
}


