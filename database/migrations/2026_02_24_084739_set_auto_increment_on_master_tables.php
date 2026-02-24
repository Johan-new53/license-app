<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nonaktifkan strict mode sementara
        DB::statement("SET SESSION sql_mode = ''");

        DB::statement("ALTER TABLE m_bank MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
        DB::statement("ALTER TABLE m_dept MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
        DB::statement("ALTER TABLE m_hu_rek_sumber MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
        DB::statement("ALTER TABLE m_currency MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
        DB::statement("ALTER TABLE m_rek_tujuan MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE m_bank MODIFY id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE m_dept MODIFY id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE m_hu_rek_sumber MODIFY id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE m_currency MODIFY id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE m_rek_tujuan MODIFY id BIGINT UNSIGNED NOT NULL");
    }
};
