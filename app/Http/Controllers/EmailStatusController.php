<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailStatusController extends Controller
{
    public function send()
    {
        // /var/www/your-project/scripts/send_status.php
        // /var/www/html/license-app/email
        
        $path = base_path('email/sendstatus.php');
        
        if (!file_exists($path)) {
            return back()->with('error', 'File sendstatus.php tidak ditemukan');
            
        }

        include $path;

        return back()->with('success', 'Email status berhasil dikirim');
    }
}
