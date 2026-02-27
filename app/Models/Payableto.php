<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payableto extends Model
{  
    protected $table = 'm_payableto';
    protected $fillable = ['type','nama','vendor_account','party_id','term_payment','hari','valid','user_entry'];
    							

}
