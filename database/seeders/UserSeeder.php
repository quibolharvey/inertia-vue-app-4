<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin user if not exists
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('admin123'),
        ]);
        
        // Assign Admin role
        $admin->assignRole('admin');

        // Create Regular User if not exists
        $user = User::firstOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'name' => 'Regular User',
            'password' => bcrypt('user123'),
        ]);
        
        // Assign User role
        $user->assignRole('user');
    }
}
