<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table))
                continue;

            Schema::table($table, function (Blueprint $tableGroup) use ($table) {
                // Rename group to location if group exists and location does not
                if (Schema::hasColumn($table, 'group') && !Schema::hasColumn($table, 'location')) {
                    $tableGroup->renameColumn('group', 'location');
                } elseif (!Schema::hasColumn($table, 'location')) {
                    $tableGroup->string('location')->nullable()->after('status');
                }

                // Ensure department and division exist
                if (!Schema::hasColumn($table, 'department')) {
                    $tableGroup->string('department')->nullable()->after('location');
                }
                if (!Schema::hasColumn($table, 'division')) {
                    $tableGroup->string('division')->nullable()->after('department');
                }
            });
        }

        // Recreate the employees table with the standardized structure
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('employees');

        Schema::create('employees', function (Blueprint $table) {
            $table->string('emp_id', 50)->primary(); // Standard primary key
            $table->string('lname')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('division')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
        // Re-renaming location to group is skipped for simplicity as we are moving forward
    }
};
