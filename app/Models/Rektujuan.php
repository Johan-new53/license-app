<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rektujuan extends Model
{
    protected $table = 'm_rek_tujuan';
    protected $fillable = ['nama','norek','bank','valid','user_entry'];
}
