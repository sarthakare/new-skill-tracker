<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => 'SUPER_ADMIN',
                'college_id' => null,
            ]
        );

        $this->command->info('Super Admin user created successfully!');
        $this->command->info('Email: superadmin@example.com');
        $this->command->info('Password: password');
    }
}
