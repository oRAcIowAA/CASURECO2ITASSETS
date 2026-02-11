<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->date('date_assigned')->nullable()->after('employee_id');
            $table->date('date_returned')->nullable()->after('date_assigned');
            $table->text('assignment_notes')->nullable()->after('date_returned');
        });
    }

    public function down(): void
    {
        Schema::table('pc_units', function (Blueprint $table) {
            $table->dropColumn(['date_assigned', 'date_returned', 'assignment_notes']);
        });
    }
};