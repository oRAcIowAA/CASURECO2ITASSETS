<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$links = [];
$tables = ['pc_units', 'printers', 'network_devices', 'power_utilities', 'mobile_devices'];

foreach ($tables as $table) {
    if (Illuminate\Support\Facades\Schema::hasTable($table)) {
        $counts = Illuminate\Support\Facades\DB::table($table)
            ->whereNotNull('employee_id')
            ->select('employee_id', Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->groupBy('employee_id')
            ->get();
        $links[$table] = $counts;
    }
}

$employees = Illuminate\Support\Facades\DB::table('employees')->get();

echo "Current Employees:\n";
foreach ($employees as $e) {
    echo "ID: {$e->id}, EmpID: {$e->employee_id}, Name: {$e->full_name}\n";
}

echo "\nLinks:\n";
print_r($links);
