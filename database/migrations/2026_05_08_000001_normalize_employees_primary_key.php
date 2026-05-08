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
        Schema::disableForeignKeyConstraints();

        // 1. Explicitly drop all foreign keys referencing employees
        $fks = DB::select("
            SELECT TABLE_NAME, CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_NAME = 'employees' 
            AND TABLE_SCHEMA = (SELECT DATABASE())
        ");

        foreach ($fks as $fk) {
            try {
                DB::statement("ALTER TABLE {$fk->TABLE_NAME} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            } catch (\Exception $e) {
                // Already dropped or doesn't exist
            }
        }

        // 2. Prepare Employees table
        if (!Schema::hasColumn('employees', 'id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->first();
            });

            // Populate IDs
            DB::statement('SET @count = 0');
            DB::statement('UPDATE employees SET id = (@count := @count + 1)');
        }

        // 3. Swap primary keys
        try {
            DB::statement('ALTER TABLE employees DROP PRIMARY KEY');
        } catch (\Exception $e) {
             // Maybe it was already dropped or it's failing because of something else
             // But we need to ensure it's dropped.
        }
        
        // Final attempt to swap PK
        DB::statement('ALTER TABLE employees ADD PRIMARY KEY (id)');
        DB::statement('ALTER TABLE employees MODIFY COLUMN id BIGINT UNSIGNED AUTO_INCREMENT');
        
        Schema::table('employees', function (Blueprint $table) {
            $table->string('emp_id', 50)->unique()->change();
        });

        // 4. Update related tables data and schema
        $tables = [
            'pc_units',
            'printers',
            'network_devices',
            'power_utilities',
            'mobile_devices',
            'employee_histories',
            'pc_histories',
            'printer_histories',
            'network_device_histories',
            'power_utility_histories',
            'mobile_device_histories'
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) continue;

            // Add new column
            if (!Schema::hasColumn($tableName, 'new_employee_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->unsignedBigInteger('new_employee_id')->nullable()->after('employee_id');
                });
            }

            // Populate new column
            DB::statement("
                UPDATE {$tableName} t
                JOIN employees e ON t.employee_id = e.emp_id
                SET t.new_employee_id = e.id
            ");

            // Drop old column and rename new one
            Schema::table($tableName, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'employee_id')) {
                    $table->dropColumn('employee_id');
                }
                if (Schema::hasColumn($table->getTable(), 'new_employee_id')) {
                    $table->renameColumn('new_employee_id', 'employee_id');
                }
            });

            // Add new foreign key
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            });

            // Handle previous_employee_id for history tables
            if (in_array($tableName, [
                'pc_histories', 
                'printer_histories', 
                'network_device_histories', 
                'power_utility_histories', 
                'mobile_device_histories'
            ])) {
                if (!Schema::hasColumn($tableName, 'new_prev_employee_id')) {
                    Schema::table($tableName, function (Blueprint $table) {
                        $table->unsignedBigInteger('new_prev_employee_id')->nullable()->after('previous_employee_id');
                    });
                }

                DB::statement("
                    UPDATE {$tableName} t
                    JOIN employees e ON t.previous_employee_id = e.emp_id
                    SET t.new_prev_employee_id = e.id
                ");

                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'previous_employee_id')) {
                        $table->dropColumn('previous_employee_id');
                    }
                    if (Schema::hasColumn($table->getTable(), 'new_prev_employee_id')) {
                        $table->renameColumn('new_prev_employee_id', 'previous_employee_id');
                    }
                });

                Schema::table($tableName, function (Blueprint $table) {
                    $table->foreign('previous_employee_id')->references('id')->on('employees')->onDelete('set null');
                });
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
