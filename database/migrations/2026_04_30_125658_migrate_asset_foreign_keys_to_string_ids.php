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
        $tables = [
            'pc_units', 
            'printers', 
            'network_devices', 
            'power_utilities', 
            'mobile_devices', 
            'employee_histories', 
            'pc_histories', 
            'printer_histories', 
            'network_device_histories'
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) continue;

            // 1. Drop Foreign Key if exists
            try {
                Schema::table($table, function (Blueprint $tableGroup) {
                    $tableGroup->dropForeign(['employee_id']);
                });
            } catch (\Exception $e) {}

            if (in_array($table, ['pc_histories', 'printer_histories', 'network_device_histories'])) {
                try {
                    Schema::table($table, function (Blueprint $tableGroup) {
                        $tableGroup->dropForeign(['previous_employee_id']);
                    });
                } catch (\Exception $e) {}
            }

            // 2. Change column types to string (varchar 50)
            Schema::table($table, function (Blueprint $tableGroup) use ($table) {
                $tableGroup->string('employee_id', 50)->nullable()->change();
                
                if (in_array($table, ['pc_histories', 'printer_histories', 'network_device_histories'])) {
                    $tableGroup->string('previous_employee_id', 50)->nullable()->change();
                }
            });

            // 3. Update existing integer IDs to string Employee IDs
            // Only if 'id' column exists in employees (legacy support)
            if (Schema::hasColumn('employees', 'id') && Schema::hasColumn('employees', 'employee_id')) {
                DB::statement("
                    UPDATE {$table} t
                    JOIN employees e ON t.employee_id = CAST(e.id AS CHAR)
                    SET t.employee_id = e.employee_id
                    WHERE t.employee_id IS NOT NULL AND t.employee_id != '' AND t.employee_id REGEXP '^[0-9]+$'
                ");

                if (in_array($table, ['pc_histories', 'printer_histories', 'network_device_histories'])) {
                    DB::statement("
                        UPDATE {$table} t
                        JOIN employees e ON t.previous_employee_id = CAST(e.id AS CHAR)
                        SET t.previous_employee_id = e.employee_id
                        WHERE t.previous_employee_id IS NOT NULL AND t.previous_employee_id != '' AND t.previous_employee_id REGEXP '^[0-9]+$'
                    ");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
