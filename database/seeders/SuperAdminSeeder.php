<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create SUPER-ADMIN role
        $superAdminRole = Role::firstOrCreate([
            'name' => 'SUPER-ADMIN',
            'guard_name' => 'web',
        ]);

        // (Optional) Create permissions
        $permissions = [
            'View Admin Dashboard',
            'View Department Dashboard',
            'View HR Dashboard',
            'View Lab Supervisor',
            'View PGL Supervisor',
            'View User',
            'Create User',
            'Edit User',
            'Delete User',
            'View Roles',
            'Create Roles',
            'Edit Roles',
            'Delete Roles',
            'View Permission',
            'Create Permission',
            'Edit Permission',
            'Delete Permission',
            'View CPAR Form',
            'Edit CPAR Form',
            'Delete CPAR Form',
            // DEPARTMENT PERMISSIONS
            'View Departments',
            'Create Departments',
            'Edit Departments',
            'Delete Departments',
            // LOG PERMISSIONS
            'View Audit Logs',
            // reports
            'View Result/Others Reports',
            'View Result/Others Master File',
            'Edit Result/Others Reports',
            'PDF Result/Others Reports',
            // employees
            'View Employees',
            'Create Employees',
            'Edit Employees',
            'Delete Employees'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Give all permissions to SUPER-ADMIN
        $superAdminRole->syncPermissions(Permission::all());

        // Create or update the user
        $user = User::updateOrCreate(
            [
                'email' => 'jendrexlagando321@gmail.com',
            ],
            [
                'name' => 'Jendrex Lagando',
                'username' => 'JENLAG',
                'password' => Hash::make('test123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign SUPER-ADMIN role
        $user->assignRole($superAdminRole);
    }
}
