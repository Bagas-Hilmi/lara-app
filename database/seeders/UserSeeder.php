<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $password = Hash::make('Admin123++');

        $admin = new User ;
        $admin->name = "bagazz";
        $admin->email = "bagaz3@gmail.com";
        $admin->password = $password;
        $admin->save();
        $admin->addRole('viewer');
        $admin->givePermission('task-read');
        
    }
}
