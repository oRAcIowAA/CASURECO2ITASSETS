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
            Schema::table('pc_histories', function (Blueprint $table) {
                $table->dropColumn('action');
            });
            Schema::table('pc_histories', function (Blueprint $table) {
                $table->enum('action', ['assigned', 'returned', 'transferred', 'reassigned', 'condemned', 'defective', 'disposed'])->default('assigned');
            });
        }
        else {
            DB::statement("ALTER TABLE pc_histories MODIFY COLUMN action ENUM('assigned', 'returned', 'transferred', 'reassigned', 'condemned', 'defective', 'disposed') NOT NULL DEFAULT 'assigned'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('pc_histories', function (Blueprint $table) {
                $table->dropColumn('action');
            });
            Schema::table('pc_histories', function (Blueprint $table) {
                $table->enum('action', ['assigned', 'returned', 'transferred', 'reassigned'])->default('assigned');
            });
        }
        else {
            DB::statement("ALTER TABLE pc_histories MODIFY COLUMN action ENUM('assigned', 'returned', 'transferred', 'reassigned') NOT NULL DEFAULT 'assigned'");
        }
    }
};
