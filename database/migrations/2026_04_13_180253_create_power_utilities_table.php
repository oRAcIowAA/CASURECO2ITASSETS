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
        Schema::dropIfExists('power_utilities');
        Schema::create('power_utilities', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique();
            $table->string('type'); // UPS, AVR
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('capacity')->nullable(); // VA
            $table->string('input_voltage')->nullable(); // For AVR
            $table->string('output_voltage')->nullable(); // For AVR
            
            // Standard management fields
            $table->string('status')->default('available');
            $table->string('group')->nullable(); // Location
            $table->string('division')->nullable();
            $table->string('department')->nullable();
            $table->string('employee_id')->nullable();
            $table->foreign('employee_id')->references('emp_id')->on('employees')->onDelete('set null');
            $table->string('previous_employee_id')->nullable();
            $table->foreign('previous_employee_id')->references('emp_id')->on('employees')->onDelete('set null');
            
            $table->date('date_issued')->nullable();
            $table->date('date_assigned')->nullable();
            $table->date('date_returned')->nullable();
            
            $table->text('spare_parts')->nullable();
            $table->uuid('tracking_uuid')->unique();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('power_utilities');
    }
};
