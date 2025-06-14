<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create petugas user
        User::create([
            'name' => 'Petugas Arsip',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // Create peminjam users for each department
        $departments = User::getAvailableDepartments();
        
        foreach ($departments as $index => $department) {
            User::create([
                'name' => 'Peminjam ' . $department,
                'email' => 'peminjam' . ($index + 1) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'peminjam',
                'department' => $department,
                'phone' => '08123456789' . $index,
            ]);
        }
    }
}