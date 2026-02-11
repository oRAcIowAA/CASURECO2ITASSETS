<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('pc_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_unit_id')->constrained('pc_units')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('previous_employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->date('assigned_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->enum('action', ['assigned', 'returned', 'transferred', 'reassigned', 'condemned', 'defective', 'disposed'])->default('assigned');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_histories');
    }
};