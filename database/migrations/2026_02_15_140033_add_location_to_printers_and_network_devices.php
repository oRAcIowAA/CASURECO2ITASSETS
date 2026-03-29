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
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn(['branch', 'department']);
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropColumn(['branch', 'department']);
        });
    }
};
