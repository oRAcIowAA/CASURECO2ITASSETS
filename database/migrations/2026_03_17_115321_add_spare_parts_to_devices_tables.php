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
            $table->text('spare_parts')->nullable()->after('status');
        });

        Schema::table('printers', function (Blueprint $table) {
            $table->text('spare_parts')->nullable()->after('status');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->text('spare_parts')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->dropColumn('spare_parts');
        });

        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn('spare_parts');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropColumn('spare_parts');
        });
    }
};
