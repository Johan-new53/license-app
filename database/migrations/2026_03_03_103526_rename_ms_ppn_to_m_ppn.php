<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('ms_ppn', 'm_ppn');
    }

    public function down(): void
    {
        Schema::rename('m_ppn', 'ms_ppn');
    }
};