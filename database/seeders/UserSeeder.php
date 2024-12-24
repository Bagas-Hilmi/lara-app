<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use app\models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buat user admin
        $adminUser = User::create([
            'name' => 'Santo Wijaya',
            'email' => 'santowijaya@example.com',
            'password' => bcrypt('12345678')
        ]);
        $userRole = Role::where('name', 'user')->first();
        $adminUser->addRole($userRole);

       
        
    }
}
