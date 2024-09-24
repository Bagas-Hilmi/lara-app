<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'task-create',
                'display_name' => 'Create Task',
                'description' => 'Permission to create tasks',
            ],
            [
                'name' => 'task-read',
                'display_name' => 'Read Task',
                'description' => 'Permission to read tasks',
            ],
            [
                'name' => 'task-update',
                'display_name' => 'Update Task',
                'description' => 'Permission to update tasks',
            ],
            [
                'name' => 'task-delete',
                'display_name' => 'Delete Task',
                'description' => 'Permission to delete tasks',
            ],
            [
                'name' => 'task-approve',
                'display_name' => 'Approve Task',
                'description' => 'Permission to approve tasks',
            ],
            [
                'name' => 'task-acknowledge',
                'display_name' => 'Acknowledge Task',
                'description' => 'Permission to acknowledge tasks',
            ],
            [
                'name' => 'user-create',
                'display_name' => 'Create User',
                'description' => 'Permission to create users',
            ],
            [
                'name' => 'user-read',
                'display_name' => 'Read User',
                'description' => 'Permission to read users',
            ],
            [
                'name' => 'user-update',
                'display_name' => 'Update User',
                'description' => 'Permission to update users',
            ],
            [
                'name' => 'user-delete',
                'display_name' => 'Delete User',
                'description' => 'Permission to delete users',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'],
            ]);
        }
    }
}
