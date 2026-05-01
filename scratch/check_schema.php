<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$tables = ['pc_units', 'printers', 'network_devices', 'power_utilities', 'mobile_devices', 'employee_histories', 'pc_histories', 'printer_histories', 'network_device_histories'];
foreach ($tables as $table) {
    if (Illuminate\Support\Facades\Schema::hasTable($table)) {
        $cols = Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing($table);
        echo "Table: $table\n";
        print_r($cols);
    }
}
