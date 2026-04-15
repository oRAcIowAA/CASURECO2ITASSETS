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
        // 1. Add columns to asset tables
        $assetTables = ['pc_units', 'printers', 'network_devices'];
        foreach ($assetTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'department')) {
                    $table->string('department')->nullable()->after('location');
                }
                if (!Schema::hasColumn($tableName, 'division')) {
                    $table->string('division')->nullable()->after('department');
                }
                if (!Schema::hasColumn($tableName, 'group')) {
                    $table->string('group')->nullable()->after('division');
                }
            });
        }

        // 2. Add columns to employees table and drop legacy columns
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'department')) {
                $table->string('department')->nullable()->after('location');
            }
            if (!Schema::hasColumn('employees', 'division')) {
                $table->string('division')->nullable()->after('department');
            }
            if (!Schema::hasColumn('employees', 'group')) {
                $table->string('group')->nullable()->after('division');
            }
            
            // Drop legacy ID columns if they exist
            // Note: We are not dropping foreign keys explicitly here as they might not be defined with standard names
            // but Laravel will handle basic column drops if they exist.
        });

        // Separate closure for dropping columns to ensure we don't conflict with adding
        try {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['department_id']);
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['division_id']);
            });
        } catch (\Exception $e) {}

        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'department_id')) {
                $table->dropColumn('department_id');
            }
            if (Schema::hasColumn('employees', 'division_id')) {
                $table->dropColumn('division_id');
            }
        });

        // 3. Drop the tables
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('departments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse process (minimal restoration)
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->dropColumn(['department', 'division', 'group']);
        });

        foreach (['pc_units', 'printers', 'network_devices'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['department', 'division', 'group']);
            });
        }
    }
};
