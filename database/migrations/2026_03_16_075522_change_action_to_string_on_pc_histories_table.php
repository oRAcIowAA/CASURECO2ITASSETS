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
        // Changing ENUM to VARCHAR safely
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `pc_histories` CHANGE `action` `action` VARCHAR(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `pc_histories` CHANGE `action` `action` ENUM('assigned', 'returned', 'transferred', 'reassigned', 'condemned', 'defective', 'disposed') NOT NULL DEFAULT 'assigned'");
    }
};
