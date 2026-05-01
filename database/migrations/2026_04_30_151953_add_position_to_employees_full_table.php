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
        if (Schema::hasTable('employees_full')) {
            Schema::table('employees_full', function (Blueprint $table) {
                if (!Schema::hasColumn('employees_full', 'position')) {
                    $table->string('position')->nullable()->after('lname');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees_full', function (Blueprint $table) {
            //
        });
    }
};
