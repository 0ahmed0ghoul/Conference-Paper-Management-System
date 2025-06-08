<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SpecialChairSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superchair@example.com'],
            [
                'name' => 'Super Chair',
                'password' => Hash::make('SuperSecurePassword123'),
                'role' => 'chair',
                'status' => 'approved'
            ]
        );
    }
}
// This seeder creates a special chair user with predefined credentials.

