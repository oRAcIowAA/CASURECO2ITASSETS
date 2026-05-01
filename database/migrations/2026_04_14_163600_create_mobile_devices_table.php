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
        Schema::dropIfExists('mobile_devices');
        Schema::create('mobile_devices', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique();
            $table->string('type'); // Cellphone
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('processor')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('serial_number')->nullable();
            
            // Standard management fields
            $table->string('status')->default('available');
            $table->string('group')->nullable(); // Location
            $table->string('division')->nullable();
            $table->string('department')->nullable();
            $table->string('employee_id')->nullable();
            $table->foreign('employee_id')->references('emp_id')->on('employees')->onDelete('set null');
            
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
        Schema::dropIfExists('mobile_devices');
    }
};
