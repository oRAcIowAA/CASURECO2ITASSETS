<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

$e = new Employee();
$e->emp_id = 'TEST_ID_' . time();
$e->lname = 'TEST';
$e->department_id = 1;
$e->location_id = 1;
$e->division_id = 1;
$e->save();
echo "New employee got ID: " . $e->id . PHP_EOL;
$e->delete();

$max = DB::table('employees')->max('id');
echo "Max ID now: " . $max . PHP_EOL;
DB::statement("ALTER TABLE employees AUTO_INCREMENT = " . ($max + 1));
echo "Attempted to reset AI to " . ($max + 1) . PHP_EOL;
