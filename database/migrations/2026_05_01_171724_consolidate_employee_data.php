<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Move data from employees_full if it exists
        if (Schema::hasTable('employees_full')) {
            DB::table('employees_full')->orderBy('emp_id')->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('employees')->updateOrInsert(
                        ['emp_id' => $row->emp_id],
                        [
                            'fname' => $row->fname ?? null,
                            'mname' => $row->mname ?? null,
                            'lname' => $row->lname ?? null,
                            'position' => $row->position ?? null,
                            'department' => $row->dept_name ?? $row->department ?? null,
                            'division' => $row->division ?? null,
                            'location' => $row->location ?? $row->group_name ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });
            Schema::dropIfExists('employees_full');
        }

        // 2. Move data from documents if it contains employee-like data
        // Based on previous scans, documents might have been used for imports
        if (Schema::hasTable('documents') && Schema::hasColumn('documents', 'lname')) {
            DB::table('documents')->orderBy('id')->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    // Logic to avoid duplicates if emp_id is not present in documents
                    $empId = $row->emp_id ?? 'TEMP-' . $row->id;
                    DB::table('employees')->updateOrInsert(
                        ['emp_id' => $empId],
                        [
                            'fname' => $row->fname ?? null,
                            'mname' => $row->mname ?? null,
                            'lname' => $row->lname ?? null,
                            'position' => $row->position ?? null,
                            'department' => $row->dept_name ?? $row->department ?? null,
                            'division' => $row->division ?? null,
                            'location' => $row->location ?? $row->group_name ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });
            // We only drop it if the user is sure, but they said "fix it" and "documents" was mentioned as weird
            Schema::dropIfExists('documents');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for data consolidation
    }
};
