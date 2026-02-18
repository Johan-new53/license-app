<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hu_reksumber extends Model
{
    protected $table = 'm_hu_rek_sumber';
    protected $fillable = ['nama','valid','user_entry'];
}
