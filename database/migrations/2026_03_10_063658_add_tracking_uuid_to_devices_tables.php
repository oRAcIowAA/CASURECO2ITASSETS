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
            $table->string('tracking_uuid', 36)->nullable()->after('id')->unique();
        });
        Schema::table('printers', function (Blueprint $table) {
            $table->string('tracking_uuid', 36)->nullable()->after('id')->unique();
        });
        Schema::table('network_devices', function (Blueprint $table) {
            $table->string('tracking_uuid', 36)->nullable()->after('id')->unique();
        });

        // Generate UUIDs for existing records
        \Illuminate\Support\Facades\DB::table('pc_units')->whereNull('tracking_uuid')->orderBy('id')->lazy()->each(function ($item) {
            \Illuminate\Support\Facades\DB::table('pc_units')->where('id', $item->id)->update(['tracking_uuid' => \Illuminate\Support\Str::uuid()]);
        });
        \Illuminate\Support\Facades\DB::table('printers')->whereNull('tracking_uuid')->orderBy('id')->lazy()->each(function ($item) {
            \Illuminate\Support\Facades\DB::table('printers')->where('id', $item->id)->update(['tracking_uuid' => \Illuminate\Support\Str::uuid()]);
        });
        \Illuminate\Support\Facades\DB::table('network_devices')->whereNull('tracking_uuid')->orderBy('id')->lazy()->each(function ($item) {
            \Illuminate\Support\Facades\DB::table('network_devices')->where('id', $item->id)->update(['tracking_uuid' => \Illuminate\Support\Str::uuid()]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->dropColumn('tracking_uuid');
        });
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn('tracking_uuid');
        });
        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropColumn('tracking_uuid');
        });
    }
};
