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
        if (!Schema::hasColumn('printers', 'asset_tag')) {
            Schema::table('printers', function (Blueprint $table) {
                $table->string('asset_tag')->unique()->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('network_devices', 'asset_tag')) {
            Schema::table('network_devices', function (Blueprint $table) {
                $table->string('asset_tag')->unique()->nullable()->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn('asset_tag');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropColumn('asset_tag');
        });
    }
};
