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
        if (Schema::hasTable('departments')) {
            Schema::drop('departments');
        }

        if (Schema::hasTable('branches')) {
            Schema::drop('branches');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate minimal versions of the tables if needed
        if (! Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->string('branch_name');
                $table->string('location')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('department_name');
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }
};


