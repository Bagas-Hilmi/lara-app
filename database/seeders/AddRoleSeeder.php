<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class AddRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user dan role
        $user = User::find(4); 
        $adminRole = Role::where('name', 'admin')->first();

        // Atau gunakan Laratrust Helper:
        $user->addRole('admin');
    }
}
