<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Admin Purchasing',
                'email' => 'purchasing@company.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager Production',
                'email' => 'production@company.com',
                'password' => Hash::make('password123'),
                'role' => 'manager',
            ],
            [
                'name' => 'Supervisor Warehouse',
                'email' => 'warehouse@company.com',
                'password' => Hash::make('password123'),
                'role' => 'supervisor',
            ],
            [
                'name' => 'Engineer Maintenance',
                'email' => 'maintenance@company.com',
                'password' => Hash::make('password123'),
                'role' => 'engineer',
            ],
            [
                'name' => 'Finance Staff',
                'email' => 'finance@company.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}