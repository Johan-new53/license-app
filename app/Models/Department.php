<?php

// app/Models/Department.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'm_dept';
    protected $fillable = ['nama','valid','user_entry'];
}
