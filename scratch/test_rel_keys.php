<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PcUnit;
use App\Models\Employee;

$pc = PcUnit::whereNotNull('employee_id')->where('employee_id', '!=', '')->first();
echo "PC Employee ID: " . $pc->employee_id . "\n";

// Test explicit finding
$emp = Employee::find($pc->employee_id);
echo "Direct Find Employee: " . ($emp ? $emp->full_name : 'NOT FOUND') . "\n";

// Test relationship
$relEmp = $pc->employee;
echo "Relationship Employee: " . ($relEmp && $relEmp->emp_id ? $relEmp->full_name : 'NOT FOUND (OR DEFAULT)') . "\n";
