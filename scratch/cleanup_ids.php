<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

DB::transaction(function () {
    Schema::disableForeignKeyConstraints();

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

    // 1. Create a temporary mapping of old ID to new sequential ID
    $employees = DB::table('employees')->orderBy('id')->get();
    $mapping = [];
    $newId = 1;
    foreach ($employees as $employee) {
        $mapping[$employee->id] = $newId++;
    }

    echo "Re-sequencing " . count($mapping) . " employees..." . PHP_EOL;

    // 2. Update foreign keys in all tables
    foreach ($tables as $tableName) {
        if (!Schema::hasTable($tableName)) continue;
        
        echo "Updating table: $tableName" . PHP_EOL;
        
        // Update employee_id
        foreach ($mapping as $oldId => $newIdVal) {
            DB::table($tableName)->where('employee_id', $oldId)->update(['employee_id' => $newIdVal]);
            
            // Update previous_employee_id if exists
            if (Schema::hasColumn($tableName, 'previous_employee_id')) {
                DB::table($tableName)->where('previous_employee_id', $oldId)->update(['previous_employee_id' => $newIdVal]);
            }
        }
    }

    // 3. Update employee IDs themselves
    // We need to do this carefully to avoid collisions if we just update in place.
    // Better to use a temp column or a specific offset.
    DB::statement('UPDATE employees SET id = id + 1000000'); // Move away from low range
    
    foreach ($mapping as $oldId => $newIdVal) {
        DB::table('employees')->where('id', $oldId + 1000000)->update(['id' => $newIdVal]);
    }

    // 4. Reset Auto-Increment
    $nextId = count($mapping) + 1;
    DB::statement("ALTER TABLE employees AUTO_INCREMENT = $nextId");

    Schema::enableForeignKeyConstraints();
    
    echo "Done! Next ID will be $nextId." . PHP_EOL;
});
