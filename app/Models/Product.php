<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'item','category','description','qty','used','remaining',
    'start_date','end_date','last_bidding','next_bidding','renewal_date',
    'tgl_email1','tgl_email2','tgl_email3','request_date',
    'vendor','mata_uang','amount_excl_vat','pr','po',
    'pic','mail_pic','hp_pic',
    'status','date_update_status','no_tiket','remark','nama_admin','email_admin','hp_admin'
    ];
}