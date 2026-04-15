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
            if (!Schema::hasColumn('printers', 'ip_type')) {
                $table->string('ip_type')->default('Static')->after('has_network_port');
            }
            if (!Schema::hasColumn('printers', 'network_segment')) {
                $table->string('network_segment')->nullable()->after('mac_address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            $table->dropColumn(['ip_type', 'network_segment']);
        });
    }
};
