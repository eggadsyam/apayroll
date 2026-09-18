<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@payroll.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // HRD
        $hrd = User::firstOrCreate(
            ['email' => 'hrd@payroll.com'],
            [
                'name' => 'HRD Staff',
                'password' => Hash::make('password'),
            ]
        );
        $hrd->assignRole('hrd');

        // Finance
        $finance = User::firstOrCreate(
            ['email' => 'finance@payroll.com'],
            [
                'name' => 'Finance Staff',
                'password' => Hash::make('password'),
            ]
        );
        $finance->assignRole('finance');

        // Manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@payroll.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
            ]
        );
        $manager->assignRole('manager');

        // Employee
        $employee = User::firstOrCreate(
            ['email' => 'budi@payroll.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
            ]
        );
        $employee->assignRole('employee');
    }
}
