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
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            'cpar.view',
            'cpar.edit',
            'cpar.create',
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
