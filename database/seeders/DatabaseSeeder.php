<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hardcoded Default Admin Account
        User::updateOrCreate(
            ['email' => 'admin@casureco.com'],
            [
                'name' => 'System Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('opqrstuvwxyz09218191'),
            ]
        );

        // Seed Organizational Data
        $this->call(OrganizationSeeder::class);
    }
}
