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
        Schema::table('printer_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('printer_histories', 'previous_employee_id')) {
                $table->foreignId('previous_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            }
            if (!Schema::hasColumn('printer_histories', 'assigned_date')) {
                $table->date('assigned_date')->nullable();
            }
            if (!Schema::hasColumn('printer_histories', 'returned_date')) {
                $table->date('returned_date')->nullable();
            }
        });

        Schema::table('network_device_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('network_device_histories', 'previous_employee_id')) {
                $table->foreignId('previous_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            }
            if (!Schema::hasColumn('network_device_histories', 'assigned_date')) {
                $table->date('assigned_date')->nullable();
            }
            if (!Schema::hasColumn('network_device_histories', 'returned_date')) {
                $table->date('returned_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('histories_tables', function (Blueprint $table) {
        //
        });
    }
};
