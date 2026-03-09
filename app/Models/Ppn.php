<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ppn extends Model
{    
    protected $table = 'm_ppn';
    protected $fillable = ['nama','ppn','flag_ubah','valid','user_entry'];
}
