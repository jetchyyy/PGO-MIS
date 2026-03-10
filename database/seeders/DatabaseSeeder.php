<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FundCluster;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $office = Office::firstOrCreate(['code' => 'PGSO'], ['name' => 'PGSO Main Office']);
        FundCluster::firstOrCreate(['code' => 'FC-01'], ['name' => 'General Fund']);

        $staff = Employee::firstOrCreate(
            ['email' => 'property.staff@example.com'],
            ['office_id' => $office->id, 'name' => 'Property Staff', 'designation' => 'Staff']
        );
        $approver = Employee::firstOrCreate(
            ['email' => 'approver@example.com'],
            ['office_id' => $office->id, 'name' => 'Approving Official', 'designation' => 'Head']
        );

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'role' => User::ROLE_SUPER_ADMIN, 'employee_id' => null],
            ['name' => 'System Admin', 'email' => 'admin@example.com', 'role' => User::ROLE_SYSTEM_ADMIN, 'employee_id' => null],
            ['name' => 'PGSO Property Staff', 'email' => 'property.staff@example.com', 'role' => User::ROLE_PROPERTY_STAFF, 'employee_id' => $staff->id],
            ['name' => 'Accountable Officer', 'email' => 'accountable@example.com', 'role' => User::ROLE_ACCOUNTABLE_OFFICER, 'employee_id' => $staff->id],
            ['name' => 'Approving Official', 'email' => 'approver@example.com', 'role' => User::ROLE_APPROVING_OFFICIAL, 'employee_id' => $approver->id],
            ['name' => 'Audit Viewer', 'email' => 'audit@example.com', 'role' => User::ROLE_AUDIT_VIEWER, 'employee_id' => null],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                    'employee_id' => $user['employee_id'],
                    'is_active' => true,
                ]
            );
        }

       
    }
}
