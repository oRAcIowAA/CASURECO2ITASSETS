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
        if (DB::getDriverName() === 'sqlite') {
            // SQLite workaround: Recreate the column to update the check constraint
            Schema::table('pc_units', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('pc_units', function (Blueprint $table) {
                $table->enum('status', ['available', 'not_available', 'incoming', 'assigned', 'defective', 'condemned'])->default('available');
            });
        }
        else {
            // MySQL/MariaDB
            DB::statement("ALTER TABLE pc_units MODIFY COLUMN status ENUM('available', 'not_available', 'incoming', 'assigned', 'defective', 'condemned') NOT NULL DEFAULT 'available'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('pc_units', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('pc_units', function (Blueprint $table) {
                $table->enum('status', ['available', 'not_available', 'incoming'])->default('available');
            });
        }
        else {
            DB::statement("ALTER TABLE pc_units MODIFY COLUMN status ENUM('available', 'not_available', 'incoming') NOT NULL DEFAULT 'available'");
        }
    }
};
