<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin Staff OC',
        ]);

        $userRole = Role::create([
            'name' => 'user',
            'display_name' => 'User', 
            'description' => 'User Non Staff OC',
        ]);
        $engineerRole = Role::create([
            'name' => 'engineer',
            'display_name' => 'engineering', 
            'description' => 'Staff Engineer',
        ]);
    }
}
