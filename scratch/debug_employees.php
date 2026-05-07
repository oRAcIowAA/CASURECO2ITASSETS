<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;

$employees = Employee::orderBy('lname')->orderBy('fname')->limit(5)->get();
$mapped = $employees->map(fn($e) => [
    'value' => (string)$e->id,
    'label' => strtoupper($e->full_name) . ' — ' . strtoupper($e->department ?? 'N/A') . ' / ' . strtoupper($e->division ?? 'N/A'),
    'location' => $e->location ?? 'N/A'
]);

echo json_encode($mapped, JSON_PRETTY_PRINT);
