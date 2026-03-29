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
        Schema::table('employees', function (Blueprint $table) {
            // Drop foreign key to departments and the column itself, then add fixed branch/department strings
            if (Schema::hasColumn('employees', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }

            if (! Schema::hasColumn('employees', 'branch')) {
                $table->string('branch')->after('position')->nullable();
            }

            if (! Schema::hasColumn('employees', 'department')) {
                $table->string('department')->after('branch')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('employees', 'branch')) {
                $table->dropColumn('branch');
            }

            if (! Schema::hasColumn('employees', 'department_id')) {
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
            }
        });
    }
};


