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
        Schema::table('pc_units', function (Blueprint $table) {
            // Drop foreign keys and integer columns if they exist
            if (Schema::hasColumn('pc_units', 'branch_id')) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            }

            if (Schema::hasColumn('pc_units', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }

            if (! Schema::hasColumn('pc_units', 'branch')) {
                $table->string('branch')->after('network_segment')->nullable();
            }

            if (! Schema::hasColumn('pc_units', 'department')) {
                $table->string('department')->after('branch')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            if (Schema::hasColumn('pc_units', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('pc_units', 'branch')) {
                $table->dropColumn('branch');
            }

            if (! Schema::hasColumn('pc_units', 'branch_id')) {
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            }
            if (! Schema::hasColumn('pc_units', 'department_id')) {
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
            }
        });
    }
};


