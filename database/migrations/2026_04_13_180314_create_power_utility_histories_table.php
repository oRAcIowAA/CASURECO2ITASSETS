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
        Schema::dropIfExists('power_utility_histories');
        Schema::create('power_utility_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('power_utility_id')->constrained('power_utilities')->onDelete('cascade');
            $table->string('employee_id')->nullable();
            $table->foreign('employee_id')->references('emp_id')->on('employees')->onDelete('set null');
            $table->string('previous_employee_id')->nullable();
            $table->foreign('previous_employee_id')->references('emp_id')->on('employees')->onDelete('set null');
            $table->string('action');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_utility_histories');
    }
};
