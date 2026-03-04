<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'm_category';
    protected $fillable = ['nama','valid','user_entry'];
}
