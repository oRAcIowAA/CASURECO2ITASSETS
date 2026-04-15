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
            $table->renameColumn('date_received', 'date_issued');
        });

        Schema::table('printers', function (Blueprint $table) {
            $table->enum('type', ['PRINTER', 'SCANNER'])->default('PRINTER')->after('asset_tag');
            $table->date('date_issued')->nullable()->after('group');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->date('date_issued')->nullable()->after('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->renameColumn('date_issued', 'date_received');
        });

        Schema::table('printers', function (Blueprint $table) {
            if (Schema::hasColumn('printers', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('printers', 'date_issued')) {
                $table->dropColumn('date_issued');
            }
        });

        Schema::table('network_devices', function (Blueprint $table) {
            if (Schema::hasColumn('network_devices', 'date_issued')) {
                $table->dropColumn('date_issued');
            }
        });
    }
};
