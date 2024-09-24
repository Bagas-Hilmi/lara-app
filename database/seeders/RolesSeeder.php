<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => 'Super Admin',
                'description' => 'Super Administrator with full access.',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrator with limited access.',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Manager role with task management capabilities.',
            ],
            [
                'name' => 'superintendent',
                'display_name' => 'Superintendent',
                'description' => 'Superintendent with approval rights.',
            ],
            [
                'name' => 'senior-supervisor',
                'display_name' => 'Senior Supervisor',
                'description' => 'Senior Supervisor with oversight capabilities.',
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Supervisor',
                'description' => 'Supervisor with task management rights.',
            ],
            [
                'name' => 'senior-staff',
                'display_name' => 'Senior Staff',
                'description' => 'Senior staff with additional responsibilities.',
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'Regular staff member.',
            ],
            [
                'name' => 'viewer',
                'display_name' => 'Viewer',
                'description' => 'Viewer with read-only access.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'],
            ]);
        }
    }
}
