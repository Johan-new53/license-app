<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->unsignedBigInteger('id_rek_tujuan')->after('id_payable');            
         
        });
    }

    public function down()
    {
        Schema::table('finances', function (Blueprint $table) {
           
            $table->dropColumn('id_rek_tujuan');
        });
    }
};
