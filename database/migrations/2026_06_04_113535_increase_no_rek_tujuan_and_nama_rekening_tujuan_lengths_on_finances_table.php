<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->string('no_rek_tujuan', 500)->nullable()->change();
            $table->string('nama_rekening_tujuan', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->string('no_rek_tujuan', 100)->nullable()->change();
            $table->string('nama_rekening_tujuan', 100)->nullable()->change();
        });
    }
};
