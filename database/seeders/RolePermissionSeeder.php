<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Employee
            'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
            // Attendance
            'attendance.view', 'attendance.create', 'attendance.edit', 'attendance.import',
            // Overtime
            'overtime.view', 'overtime.create', 'overtime.approve',
            // Leave
            'leave.view', 'leave.create', 'leave.approve',
            // Salary
            'salary.view', 'salary.create', 'salary.edit',
            // Payroll
            'payroll.view', 'payroll.create', 'payroll.process', 'payroll.approve', 'payroll.pay',
            // Report
            'report.view', 'report.export',
            // Setting
            'setting.view', 'setting.edit',
            // User
            'user.view', 'user.create', 'user.edit', 'user.delete',
            // Loan
            'loan.view', 'loan.create', 'loan.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create roles and assign permissions
        $superAdminRole = Role::findOrCreate('super_admin', 'web');
        $superAdminRole->givePermissionTo(Permission::all());

        $hrdRole = Role::findOrCreate('hrd', 'web');
        $hrdRole->givePermissionTo([
            'employee.view', 'employee.create', 'employee.edit', 'employee.delete',
            'attendance.view', 'attendance.create', 'attendance.edit', 'attendance.import',
            'overtime.view', 'overtime.create',
            'leave.view', 'leave.create', 'leave.approve',
            'salary.view', 'salary.create', 'salary.edit',
            'payroll.view', 'payroll.create', 'payroll.process',
            'report.view', 'report.export',
            'loan.view', 'loan.create', 'loan.edit',
        ]);

        $financeRole = Role::findOrCreate('finance', 'web');
        $financeRole->givePermissionTo([
            'payroll.view', 'payroll.approve', 'payroll.pay',
            'report.view', 'report.export',
            'loan.view',
        ]);

        $managerRole = Role::findOrCreate('manager', 'web');
        $managerRole->givePermissionTo([
            'employee.view',
            'attendance.view',
            'overtime.view', 'overtime.approve',
            'leave.view', 'leave.approve',
            'payroll.view', 'payroll.approve',
            'report.view',
        ]);

        $employeeRole = Role::findOrCreate('employee', 'web');
        // Employee has no specific admin permissions, uses the portal
    }
}
