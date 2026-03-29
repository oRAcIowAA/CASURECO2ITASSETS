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
        $tables = ['employees', 'pc_units', 'printers', 'network_devices'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'branch')) {
                    $t->dropColumn('branch');
                }
                if (!Schema::hasColumn($table, 'group')) {
                    $t->string('group')->nullable()->after('department');
                }
                if (!Schema::hasColumn($table, 'division')) {
                    $t->string('division')->nullable()->after('group');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['employees', 'pc_units', 'printers', 'network_devices'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'division')) {
                    $t->dropColumn('division');
                }
                if (Schema::hasColumn($table, 'group')) {
                    $t->dropColumn('group');
                }
                if (!Schema::hasColumn($table, 'branch')) {
                    $t->string('branch')->nullable();
                }
            });
        }
    }
};
