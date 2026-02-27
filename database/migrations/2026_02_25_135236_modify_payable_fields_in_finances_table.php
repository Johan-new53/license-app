<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn([
                'id_payable_h',
                'id_payable_s',
                'id_payable_a'
            ]);

            // Tambah kolom baru
            $table->unsignedBigInteger('id_payable')->nullable()->after('id_rek_sumber');
        });
    }

    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->unsignedBigInteger('id_payable_h')->nullable();
            $table->unsignedBigInteger('id_payable_s')->nullable();
            $table->unsignedBigInteger('id_payable_a')->nullable();

            // Hapus kolom baru
            $table->dropColumn('id_payable');
        });
    }
};