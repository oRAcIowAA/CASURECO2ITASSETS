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
        // Create reference tables
        Schema::create('departments', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        // Add ID columns to existing tables
        $tables = [
            'employees',
            'pc_units',
            'printers',
            'network_devices',
            'power_utilities',
            'mobile_devices',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) continue;

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Add columns after their respective string counterparts if they exist
                if (Schema::hasColumn($tableName, 'department')) {
                    $table->unsignedTinyInteger('department_id')->nullable()->after('department');
                } else {
                    $table->unsignedTinyInteger('department_id')->nullable();
                }

                if (Schema::hasColumn($tableName, 'location')) {
                    $table->unsignedTinyInteger('location_id')->nullable()->after('location');
                } else {
                    $table->unsignedTinyInteger('location_id')->nullable();
                }

                if (Schema::hasColumn($tableName, 'division')) {
                    $table->unsignedTinyInteger('division_id')->nullable()->after('division');
                } else {
                    $table->unsignedTinyInteger('division_id')->nullable();
                }

                // Add foreign key constraints
                $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
                $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
                $table->foreign('division_id')->references('id')->on('divisions')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'employees',
            'pc_units',
            'printers',
            'network_devices',
            'power_utilities',
            'mobile_devices',
        ];

        foreach ($tables as $tableName) {
            if (!Schema::hasTable($tableName)) continue;

            Schema::table($tableName, function (Blueprint $table) {
                // Drop foreign keys first
                $table->dropForeign([$table->getTable() . '_department_id_foreign']);
                $table->dropForeign([$table->getTable() . '_location_id_foreign']);
                $table->dropForeign([$table->getTable() . '_division_id_foreign']);
                $table->dropColumn(['department_id', 'location_id', 'division_id']);
            });
        }

        Schema::dropIfExists('divisions');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('departments');
    }
};
