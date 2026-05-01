<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Clearing organizational fields for all employees...\n";

DB::table('employees_full')->update([
    'dept_code' => null,
    'dept_name' => null,
    'group_code' => null,
    'group_name' => null,
    'division' => null,
    'location' => null,
    'position' => null,
]);

echo "Done. All employees now only have their basic data (Name and ID).\n";
