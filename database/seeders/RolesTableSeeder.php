<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage-users']);
        $addUsersPermission = Permission::firstOrCreate(['name' => 'add-users']);
        $editUsersPermission = Permission::firstOrCreate(['name' => 'edit-users']);
        $deleteUsersPermission = Permission::firstOrCreate(['name' => 'delete-users']);
        $viewUsersPermission = Permission::firstOrCreate(['name' => 'view-users']);

        // Check if the 'admin' role already exists
        if (!Role::where('name', 'admin')->exists()) {
            $admin = Role::create(['name' => 'admin']);
            // Assign multiple permissions to the admin role
            $admin->givePermissionTo([
                $manageUsersPermission,
                $editUsersPermission,
                $deleteUsersPermission,
                $viewUsersPermission
            ]);
        }

        // Check if the 'user' role already exists
        if (!Role::where('name', 'user')->exists()) {
            $user = Role::create(['name' => 'user']);
            // Assign add-users and view-users permissions to the user role
            $user->givePermissionTo([
                $addUsersPermission,
                $viewUsersPermission
            ]);
        }
    }
}
