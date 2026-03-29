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
        Schema::create('network_devices', function (Blueprint $table) {
            $table->id();
            $table->enum('device_type', ['router', 'switch']);
            $table->string('brand');
            $table->string('model');
            $table->integer('network_ports'); // 4, 8, 16, 24
            $table->enum('network_speed', ['gigabit', 'non_gigabit']);
            $table->enum('switch_type', ['managed', 'unmanaged'])->nullable();
            $table->boolean('has_ip')->default(false);
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_devices');
    }
};


