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
            $table->string('os_version')->nullable()->after('processor');
            $table->string('storage_secondary')->nullable()->after('storage');
            $table->string('ms_office_licensed')->nullable()->after('storage_secondary');
            $table->string('ms_office_version')->nullable()->after('ms_office_licensed');
            $table->string('ms_office_email')->nullable()->after('ms_office_version');
            $table->string('ms_office_password')->nullable()->after('ms_office_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->dropColumn([
                'os_version',
                'storage_secondary',
                'ms_office_licensed',
                'ms_office_version',
                'ms_office_email',
                'ms_office_password'
            ]);
        });
    }
};
