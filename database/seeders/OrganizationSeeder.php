<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear tables first to ensure clean state
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('divisions')->truncate();
        DB::table('locations')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Departments
        $departments = [
            0 => 'OFFICE GENERAL MANAGER DEPARTMENT (OGM)',
            1 => 'CORPORATE PLANNING DEPARTMENT (CORPLAN)',
            2 => 'INSTITUTIONAL SERVICE DEPARTMENT (ISD)',
            3 => 'FINANCE DEPARTMENT (FIN DEPT)',
            4 => 'AUDIT DEPARTMENT (AUDIT DEPT)',
            5 => 'ENGINEERING DEPARTMENT (ENGR DEPT)',
            6 => 'NORTH AREA OFFICE (NAO)',
            7 => 'SOUTH AREA OFFICE (SAO)',
        ];

        foreach ($departments as $id => $name) {
            DB::table('departments')->insert([
                'id' => $id + 1,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 2. Seed Locations
        $locations = [
            0 => 'MAIN OFFICE (MAIN)',
            1 => 'NAGA AREA OFFICE (NAO)',
            2 => 'PILI SUB-OFFICE (PSO)',
            3 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
            4 => 'CANAMAN MAGARAO SUB-OFFICE(CMSO)',
            5 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
            6 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
        ];

        foreach ($locations as $id => $name) {
            DB::table('locations')->insert([
                'id' => $id + 1,
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 3. Seed Divisions and link them to Departments
        $deptDivisions = [
            'OFFICE GENERAL MANAGER DEPARTMENT (OGM)' => [
                'N/A'
            ],
            'CORPORATE PLANNING DEPARTMENT (CORPLAN)' => [
                'I.T. DIVISION',
                'CORPORATE PLANNING DIVISION',
                'N/A'
            ],
            'INSTITUTIONAL SERVICE DEPARTMENT (ISD)' => [
                'HUMAN RESOURCE DIVISION',
                'MANAGEMENT SERVICE DIVISION',
                'N/A'
            ],
            'FINANCE DEPARTMENT (FIN DEPT)' => [
                'ACCOUNTING DIVISION',
                'BUDGET & TREASURER DIVISION',
                'N/A'
            ],
            'AUDIT DEPARTMENT (AUDIT DEPT)' => [
                'FINANCIAL DIVISION',
                'TECHNICAL DIVISION',
                'N/A'
            ],
            'ENGINEERING DEPARTMENT (ENGR DEPT)' => [
                'SYSTEMS LOSS REDUCTION DIVISION',
                'PLANNING DIVISION',
                'CONSTRUCTION MANAGE & SERVICING DIVISION',
                'N/A'
            ],
            'NORTH AREA OFFICE (NAO)' => [
                'CANAMAN MAGARAO SUB-OFFICE (CMSO)',
                'CALABANGA BOMBON SUB-OFFICE (CBSO)',
                'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
                'NAGA AREA (COLLECTION OFFICE)',
                'N/A'
            ],
            'SOUTH AREA OFFICE (SAO)' => [
                'PILI SUB-OFFICE (PSO)',
                'MILAOR MINALABAC SUB-OFFICE (MMSO)',
                'N/A'
            ],
        ];

        $divisionIdCounter = 1;
        foreach ($deptDivisions as $deptName => $divNames) {
            // Find the department ID we just inserted
            $deptId = DB::table('departments')->where('name', $deptName)->value('id');
            
            foreach ($divNames as $divName) {
                // Check if division already exists (to handle N/A being in multiple departments)
                // Actually, the user's original logic had "N/A" in every department.
                // In a relational DB, "N/A" for Dept A is different from "N/A" for Dept B if we want strict linking.
                // However, the user's DIVISIONS list had only one "N/A" (ID 0).
                
                // If we want "N/A" to be shared, we can check existence.
                // But usually, it's better to have unique rows if we're linking to specific departments.
                // But the user's list: "0 => 'N/A'" implies ONE N/A.
                
                // Let's create unique rows for each division instance to maintain the mapping properly.
                DB::table('divisions')->insert([
                    'id' => $divisionIdCounter++,
                    'name' => $divName,
                    'department_id' => $deptId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
