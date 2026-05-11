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
            'employees',
            'pc_units',
            'printers',
            'network_devices',
            'power_utilities',
            'mobile_devices',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName))
                continue;

            // Update department_id from department name
            DB::table($tableName)
                ->join('departments', "$tableName.department", '=', 'departments.name')
                ->update(["$tableName.department_id" => DB::raw('departments.id')]);

            // Update location_id from location name
            DB::table($tableName)
                ->join('locations', "$tableName.location", '=', 'locations.name')
                ->update(["$tableName.location_id" => DB::raw('locations.id')]);

            // Update division_id from division name
            DB::table($tableName)
                ->join('divisions', "$tableName.division", '=', 'divisions.name')
                ->update(["$tableName.division_id" => DB::raw('divisions.id')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to clear IDs on rollback as the columns will be dropped by the previous migration
    }
};
