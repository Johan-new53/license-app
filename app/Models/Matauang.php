<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matauang extends Model
{    
    protected $table = 'm_currency';
    protected $fillable = ['nama','valid','user_entry'];
}
