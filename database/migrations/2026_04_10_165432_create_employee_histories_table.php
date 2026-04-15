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
        Schema::create('employee_histories', function (Blueprint $header) {
            $header->id();
            $header->foreignId('employee_id')->constrained()->onDelete('cascade');
            $header->string('action');
            $header->text('notes')->nullable();
            $header->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $header->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_histories');
    }
};
