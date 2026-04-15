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
        Schema::create('mobile_device_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mobile_device_id')->constrained('mobile_devices')->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('previous_employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamp('assigned_date')->nullable();
            $table->timestamp('returned_date')->nullable();
            $table->string('action');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_device_histories');
    }
};
