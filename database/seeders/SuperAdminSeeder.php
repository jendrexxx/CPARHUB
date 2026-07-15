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
            'View Department Head',
            'View HR Head',
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
            'View Departments',
            'Create Departments',
            'Edit Departments',
            'Delete Departments',
            'View Audit Logs',
            'View CPARReports',
            'Edit CPAR Reports',
            'PDF CPAR Reports',
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
                'status' => 'Active',
                'email_verified_at' => now(),
            ]
        );

        // Assign SUPER-ADMIN role
        $user->assignRole($superAdminRole);
    }
}
