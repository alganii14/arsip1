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
        // Create unit kerja user (admin)
        User::create([
            'name' => 'Unit Kerja Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'unit_kerja',
        ]);

        // Create another unit kerja user (petugas)
        User::create([
            'name' => 'Unit Kerja Petugas',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'unit_kerja',
        ]);

        // Create unit pengelola users for each department
        $departments = User::getAvailableDepartments();
        
        foreach ($departments as $index => $department) {
            User::create([
                'name' => 'Unit Pengelola ' . $department,
                'email' => 'peminjam' . ($index + 1) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'unit_pengelola',
                'department' => $department,
                'phone' => '08123456789' . $index,
            ]);
        }
    }
}