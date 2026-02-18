<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payableto_h extends Model
{  
    protected $table = 'm_payableto_h';
    protected $fillable = ['nama','valid','user_entry'];
}
