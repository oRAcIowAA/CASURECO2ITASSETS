<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$groups = Illuminate\Support\Facades\DB::table('employees_full')
    ->select('group_code', 'group_name')
    ->distinct()
    ->get();

echo "Groups in employees_full:\n";
print_r($groups);

$depts = Illuminate\Support\Facades\DB::table('employees_full')
    ->select('dept_code', 'dept_name')
    ->distinct()
    ->get();

echo "\nDepartments in employees_full:\n";
print_r($depts);
