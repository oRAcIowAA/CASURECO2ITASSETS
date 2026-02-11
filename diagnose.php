<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- DEPARTMENTS ---\n";
foreach (App\Models\Department::all() as $d) {
    echo $d->id . ": " . $d->department_name . " (Branch ID: " . $d->branch_id . ")\n";
}

echo "\n--- PDF CLASS CHECK ---\n";
if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
    echo "Facade PDF exists\n";
    $methods = get_class_methods('Barryvdh\DomPDF\Facade\Pdf');
    echo "Methods: " . implode(', ', $methods) . "\n";
}
else {
    echo "Facade PDF NOT found\n";
}

if (class_exists('Barryvdh\DomPDF\PDF')) {
    echo "Class Barryvdh\DomPDF\PDF exists\n";
}
else {
    echo "Class Barryvdh\DomPDF\PDF NOT found\n";
}
