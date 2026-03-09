<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('history_approval', function (Blueprint $table) {
            $table->id();
            $table->integer('id_finance');
            $table->string('status');
            $table->string('keterangan');
            $table->string('user_entry');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_approval');
    }
};
