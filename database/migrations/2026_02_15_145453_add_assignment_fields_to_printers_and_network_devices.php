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
        Schema::table('printers', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('Available'); // Assigned, Available, Defective, etc.
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('Available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['employee_id', 'status']);
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn(['employee_id', 'status']);
        });
    }
};
