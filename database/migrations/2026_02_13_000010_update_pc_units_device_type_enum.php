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
        Schema::table('pc_units', function (Blueprint $table) {
            // Extend allowed device types to include Server
            $table->enum('device_type', ['PC', 'Laptop', 'Server'])
                ->default('PC')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            // Revert back to original enum
            $table->enum('device_type', ['PC', 'Laptop'])
                ->default('PC')
                ->change();
        });
    }
};


