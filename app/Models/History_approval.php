<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History_approval extends Model
{
    protected $table = 'history_approval';
    protected $fillable = ['id_finance','status','keterangan','user_entry'];
}
