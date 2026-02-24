<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payableto_s extends Model
{  
    protected $table = 'm_payableto_s';
    protected $fillable = ['nama','valid','user_entry'];
}
