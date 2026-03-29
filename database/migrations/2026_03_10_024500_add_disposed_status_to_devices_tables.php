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
        // Changing the ENUM columns to VARCHAR(50) to prevent 'Data truncated for column' errors
        // and safely accommodate uppercase/lowercase variations like 'Disposed', 'Condemned', 'Defective'.
        $tables = ['pc_units', 'printers', 'network_devices'];

        foreach ($tables as $tableName) {
            if (DB::getDriverName() !== 'sqlite') {
                DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'available'");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['pc_units', 'printers', 'network_devices'];

        foreach ($tables as $tableName) {
            if (DB::getDriverName() !== 'sqlite') {
                // Warning: this could fail if there are rows with 'Disposed' before rollback.
                DB::statement("ALTER TABLE {$tableName} MODIFY COLUMN status ENUM('available', 'not_available', 'incoming', 'assigned', 'defective', 'condemned') NOT NULL DEFAULT 'available'");
            }
        }
    }
};
