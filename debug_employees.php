<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;

$employees = Employee::take(10)->get();
foreach ($employees as $e) {
    echo "ID: " . $e->emp_id . " | Name: '" . $e->full_name . "' | Fname: '" . $e->fname . "' | Lname: '" . $e->lname . "'\n";
}
