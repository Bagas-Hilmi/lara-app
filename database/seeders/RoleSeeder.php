<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Admin Staff OC',
        ]);

        Role::create([
            'name' => 'viewer',
            'display_name' => 'Viewer',
            'description' => 'User with read-only access to the system',
        ]);
    }
}
