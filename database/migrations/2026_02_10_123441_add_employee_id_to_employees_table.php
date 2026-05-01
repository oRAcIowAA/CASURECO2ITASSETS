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
        if (!Schema::hasColumn('employees', 'emp_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('emp_id')->nullable()->after('id')->unique();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'emp_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('emp_id');
            });
        }
    }
};
