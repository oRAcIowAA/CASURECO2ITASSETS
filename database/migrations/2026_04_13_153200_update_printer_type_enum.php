<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum type for printers table
        DB::statement("ALTER TABLE printers MODIFY COLUMN type ENUM('PRINTER', 'SCANNER', 'PORTABLE PRINTER') NOT NULL DEFAULT 'PRINTER'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        // Note: This might fail if there are records with 'PORTABLE PRINTER' type
        DB::statement("ALTER TABLE printers MODIFY COLUMN type ENUM('PRINTER', 'SCANNER') NOT NULL DEFAULT 'PRINTER'");
    }
};
