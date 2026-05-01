<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PcUnit;

$pc = PcUnit::with('employee')->first();
echo "Asset Tag: " . $pc->asset_tag . "\n";
echo "Employee ID: " . $pc->employee_id . "\n";
echo "Employee Name: " . ($pc->employee ? $pc->employee->full_name : 'NULL') . "\n";
echo "PC Status: " . $pc->status . "\n";
