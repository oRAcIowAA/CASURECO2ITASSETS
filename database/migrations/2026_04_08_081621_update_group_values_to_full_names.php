<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mapping = [
            'MAIN' => 'MAIN OFFICE (MAIN)',
            'PILI' => 'PILI SUB-OFFICE (PSO)',
            'CBSO' => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
            'TSSO' => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
            'CMSO' => 'CANAMAN MAGARAO SUB-OFFICE(CMSO)',
            'MMSO' => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        ];

        $tables = ['pc_units', 'printers', 'network_devices', 'employees'];

        foreach ($tables as $table) {
            foreach ($mapping as $old => $new) {
                \Illuminate\Support\Facades\DB::table($table)
                    ->where('group', $old)
                    ->update(['group' => $new]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $mapping = [
            'MAIN OFFICE (MAIN)' => 'MAIN',
            'PILI SUB-OFFICE (PSO)' => 'PILI',
            'CALABANGA BOMBON SUB-OFFICE (CBSO)' => 'CBSO',
            'TINAMBAC SIRUMA SUB-OFFICE (TSSO)' => 'TSSO',
            'CANAMAN MAGARAO SUB-OFFICE(CMSO)' => 'CMSO',
            'MILAOR MINALABAC SUB-OFFICE (MMSO)' => 'MMSO',
        ];

        $tables = ['pc_units', 'printers', 'network_devices', 'employees'];

        foreach ($tables as $table) {
            foreach ($mapping as $new => $old) {
                \Illuminate\Support\Facades\DB::table($table)
                    ->where('group', $new)
                    ->update(['group' => $old]);
            }
        }
    }
};
