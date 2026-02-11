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
        Schema::create('pc_units', function (Blueprint $table) {
    $table->id();
    $table->string('asset_tag')->unique();
    $table->string('model');
    $table->string('processor')->nullable();
    $table->string('ram')->nullable();
    $table->string('storage')->nullable();

    $table->enum('status', ['available', 'not_available', 'incoming'])
          ->default('available');

    $table->date('date_received')->nullable();
    $table->text('remarks')->nullable();

    $table->foreignId('branch_id')->constrained()->onDelete('cascade');
    $table->foreignId('department_id')->constrained()->onDelete('cascade');
    $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
    

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_units');
    }
};
