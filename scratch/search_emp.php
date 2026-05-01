<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$emp = DB::table('employees_full')->where('emp_id', '100542')->first();
if ($emp) {
    print_r($emp);
} else {
    echo "Employee 100542 not found.\n";
    $any = DB::table('employees_full')->limit(5)->get();
    print_r($any->toArray());
}
