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
        $deptMapping = [
            'CORPLAN' => 'CORPORATE PLANNING DEPARTMENT (CORPLAN)',
            'ISD' => 'INSTITUTIONAL SERVICE DEPARTMENT (ISD)',
            'OGM' => 'OFFICE GENERAL MANAGER DEPARTMENT (OGM)',
            'Finance' => 'FINANCE DEPARTMENT (FIN DEPT)',
            'Audit' => 'AUDIT DEPARTMENT (AUDIT DEPT)',
            'Engineer' => 'ENGINEERING DEPARTMENT (ENGR DEPT)',
            'Naga Area' => 'NAGA AREA OFFICE (NAO)',
            'North Area' => 'NORTH AREA OFFICE',
            'South Area' => 'SOUTH AREA OFFICE',
        ];

        $divMapping = [
            'Information Technology Division' => 'I.T. DIVISION',
            'Accounting Division' => 'ACCOUNTING DIVISION',
            'Budget & Treasury Division' => 'BUDGET & TREASURER DIVISION',
            'Human Resource Division' => 'HUMAN RESOURCE DIVISION',
            'Management Services & Financal Audit Division' => 'MANAGEMENT SERVICE DIVISION',
            'Systems Loss Reduction Division' => 'SYSTEMS LOSS REDUCTION DIVISION',
            'Operations & Planning Division' => 'PLANNING DIVISION',
            'Construction & Maintenance Division' => 'CONSTRUCTION MANAGE & SERVICING DIVISION',
            'Canaman/Magarao Sub-Office' => 'CANAMAN MAGARAO SUB-OFFICE (CMSO)',
            'Calabanga/Bombon Sub-Office' => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
            'Tinambac/Siruma Sub-Office' => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
            'Pili Sub-Office' => 'PILI SUB-OFFICE (PSO)',
            'Milaor-Minalabac Sub-Office' => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        ];

        foreach ($deptMapping as $old => $new) {
            DB::table('employees')->where('department', $old)->update(['department' => $new]);
        }

        foreach ($divMapping as $old => $new) {
            DB::table('employees')->where('division', $old)->update(['division' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without a backup or inverse mapping, 
        // but typically these data migrations are one-way.
    }
};
