<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{    
    protected $table = 'm_bank';
    protected $fillable = ['nama','valid','user_entry'];
}
