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
        if (Schema::hasTable('employees_full')) {
            // Adding a virtual generated column for full_name to employees_full
            // This allows orderBy('full_name') and where('full_name', ...) to work as before.
            DB::statement("ALTER TABLE employees_full ADD full_name VARCHAR(255) AS (CONCAT(fname, IF(mname IS NULL OR mname = '', '', CONCAT(' ', mname)), ' ', lname)) VIRTUAL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_full', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
};
