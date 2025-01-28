<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\Role;
use App\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $viewerRole = Role::where('name', 'viewer')->first();

        $createUsers = Permission::where('name', 'create-user')->first();
        $updateUsers = Permission::where('name', 'update-user')->first();
        $approve = Permission::where('name', 'approve')->first();
        $createTask = Permission::where('name', 'create-task')->first();
        $updateTask = Permission::where('name', 'update-task')->first();
        $deleteTask = Permission::where('name', 'delete-task')->first();
        $readTask = Permission::where('name', 'read-task')->first();

        // Hubungkan Permissions ke Roles
        if ($adminRole && $createUsers) {
            $adminRole->permissions()->attach($createUsers->id);
        }

        if ($adminRole && $updateUsers) {
            $adminRole->permissions()->attach($updateUsers);
        }

        if ($adminRole && $approve) {
            $adminRole->permissions()->attach($approve);
        }

        if ($adminRole && $createTask) {
            $adminRole->permissions()->attach($createTask);
        }

        if ($adminRole && $updateTask) {
            $adminRole->permissions()->attach($updateTask);
        }

        if ($adminRole && $deleteTask) {
            $adminRole->permissions()->attach($deleteTask);
        }

        if ($adminRole && $readTask) {
            $adminRole->permissions()->attach($readTask);
        }

        if ($viewerRole && $readTask) {
            $viewerRole->permissions()->attach($readTask);
        }
    }
}
