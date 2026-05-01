<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$desc = Illuminate\Support\Facades\DB::select('DESCRIBE employees_full');
print_r($desc);
