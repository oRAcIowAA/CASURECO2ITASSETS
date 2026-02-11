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
        $table->enum('device_type', ['PC', 'Laptop'])->after('id');
    });
}

public function down(): void
{
    Schema::table('pc_units', function (Blueprint $table) {
        $table->dropColumn('device_type');
    });
}
};
