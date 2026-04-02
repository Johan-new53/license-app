<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sync_log extends Model
{
    protected $fillable = [
        'last_sync_at',
        'status',
        'total_data',
        'message',
    ];
}
