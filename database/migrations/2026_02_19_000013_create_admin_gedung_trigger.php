<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trigger untuk memastikan admin dan pimpinan tidak bisa memiliki gedung_id
        DB::statement("
            CREATE TRIGGER check_admin_gedung_id_before_insert
            BEFORE INSERT ON admin
            FOR EACH ROW
            BEGIN
                IF (NEW.role = 'admin' OR NEW.role = 'pimpinan') AND NEW.gedung_id IS NOT NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Admin dan Pimpinan tidak boleh memiliki gedung_id';
                END IF;
                IF NEW.role = 'petugas' AND NEW.gedung_id IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Petugas wajib memiliki gedung_id';
                END IF;
            END
        ");

        DB::statement("
            CREATE TRIGGER check_admin_gedung_id_before_update
            BEFORE UPDATE ON admin
            FOR EACH ROW
            BEGIN
                IF (NEW.role = 'admin' OR NEW.role = 'pimpinan') AND NEW.gedung_id IS NOT NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Admin dan Pimpinan tidak boleh memiliki gedung_id';
                END IF;
                IF NEW.role = 'petugas' AND NEW.gedung_id IS NULL THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Petugas wajib memiliki gedung_id';
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS check_admin_gedung_id_before_insert');
        DB::statement('DROP TRIGGER IF EXISTS check_admin_gedung_id_before_update');
    }
};
