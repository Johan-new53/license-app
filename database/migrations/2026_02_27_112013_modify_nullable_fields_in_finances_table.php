<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rek_tujuan')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->string('activity_code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rek_tujuan')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->string('activity_code')->nullable(false)->change();
        });
    }
};
