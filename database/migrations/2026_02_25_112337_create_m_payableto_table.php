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
        Schema::create('m_payableto', function (Blueprint $table) {
           
            $table->id();
            $table->string('type', 50);
            $table->string('nama', 150);
            $table->string('vendor_account', 100)->nullable();
            $table->unsignedBigInteger('party_id')->nullable();
            $table->string('term_payment', 50);            
            $table->integer('hari')->nullable();
            $table->string('user_entry', 100)->nullable();
            $table->timestamps(); // created_at & updated_at



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_payableto');
    }
};
