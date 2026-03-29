<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Temporarily allow both PC and new types
        Schema::table('pc_units', function (Blueprint $table) {
            $table->enum('device_type', ['PC', 'Desktop', 'Laptop', 'Server', 'All-in-One'])
                ->default('Desktop')
                ->change();
        });

        // 2. Map existing 'PC' values to 'Desktop'
        DB::table('pc_units')->where('device_type', 'PC')->update(['device_type' => 'Desktop']);

        // 3. Remove 'PC' from the allowed values
        Schema::table('pc_units', function (Blueprint $table) {
            $table->enum('device_type', ['Desktop', 'Laptop', 'Server', 'All-in-One'])
                ->default('Desktop')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->enum('device_type', ['PC', 'Desktop', 'Laptop', 'Server', 'All-in-One'])
                ->default('PC')
                ->change();
        });

        DB::table('pc_units')->where('device_type', 'Desktop')->update(['device_type' => 'PC']);

        Schema::table('pc_units', function (Blueprint $table) {
            $table->enum('device_type', ['PC', 'Laptop', 'Server'])
                ->default('PC')
                ->change();
        });
    }
};
