<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin account.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'siffahim01@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
