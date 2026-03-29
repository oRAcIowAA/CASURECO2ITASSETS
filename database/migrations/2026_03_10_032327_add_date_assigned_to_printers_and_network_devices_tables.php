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
            $table->date('date_assigned')->nullable()->after('employee_id');
            $table->date('date_returned')->nullable()->after('date_assigned');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->date('date_assigned')->nullable()->after('employee_id');
            $table->date('date_returned')->nullable()->after('date_assigned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn(['date_assigned', 'date_returned']);
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropColumn(['date_assigned', 'date_returned']);
        });
    }
};
