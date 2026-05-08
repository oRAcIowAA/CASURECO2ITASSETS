<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$vars = DB::select("SHOW VARIABLES LIKE 'auto_increment_%'");
foreach ($vars as $v) {
    echo $v->Variable_name . ": " . $v->Value . PHP_EOL;
}

$status = DB::select("SHOW TABLE STATUS LIKE 'employees'");
echo "Auto_increment for employees: " . $status[0]->Auto_increment . PHP_EOL;

$triggers = DB::select("SHOW TRIGGERS LIKE 'employees'");
echo "Triggers: " . count($triggers) . PHP_EOL;
foreach ($triggers as $t) {
    echo "Trigger: " . $t->Trigger . " (" . $t->Event . " " . $t->Timing . ")" . PHP_EOL;
}
