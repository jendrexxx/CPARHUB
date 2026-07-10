<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'jendrexlagando321@gmail.com', // change if needed
            ],
            [
                'name' => 'Jendrex Lagando',
                'username' => 'JENLAG',
                'password' => Hash::make('test123'),
                'status' => 'Active',
                'role' => 'SUPER-ADMIN',
                'email_verified_at' => now(),
            ]
        );
    }
}
