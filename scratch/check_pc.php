<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$firstPc = Illuminate\Support\Facades\DB::table('pc_units')->whereNotNull('employee_id')->first();
echo "First PC Unit with employee_id:\n";
print_r($firstPc);
