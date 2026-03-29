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
        Schema::table('pc_histories', function (Blueprint $table) {
            // Because older Doctrine versions don't support changing ENUMs nicely,
            // we drop the enum column and re-add it as a string. (SQLite doesn't support dropping columns easily without a rebuild, but Laravel handles string casting well in MySQL)
            if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE pc_histories MODIFY action VARCHAR(255) NOT NULL DEFAULT 'assigned'");
            } else {
                // If sqlite, a drop and add is required, or it might just ignore
                $table->string('action')->default('assigned')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_histories', function (Blueprint $table) {
            if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE pc_histories MODIFY action ENUM('assigned', 'returned', 'transferred', 'reassigned', 'condemned', 'defective', 'disposed') NOT NULL DEFAULT 'assigned'");
            }
        });
    }
};
