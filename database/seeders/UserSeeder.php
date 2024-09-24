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
        $admin->name = "Santo Wijaya";
        $admin->email = "santo.wijaya@ecogreenoleo.com";
        $admin->password = $password;
        $admin->save();
        $admin->addRole('super-admin');
        $admin->givePermission('task-create');
        $admin->givePermission('task-read');
        $admin->givePermission('task-update');
        $admin->givePermission('task-delete');
        $admin->givePermission('task-approve');
        $admin->givePermission('task-acknowledge');
        $admin->givePermission('user-create');
        $admin->givePermission('user-read');
        $admin->givePermission('user-update');
        $admin->givePermission('user-delete');

        // Manager
        $password = Hash::make('Jimmy_eo456++');

        $admin = new User ;
        $admin->name = "Jimmy";
        $admin->email = "jimmy@ecogreenoleo.com";
        $admin->password = $password;
        $admin->save();
        $admin->addRole('manager');
        $admin->givePermission('task-create');
        $admin->givePermission('task-read');
        $admin->givePermission('task-update');
        $admin->givePermission('task-approve');
        $admin->givePermission('task-acknowledge');
        $admin->givePermission('user-create');
        $admin->givePermission('user-read');
        $admin->givePermission('user-update');

        // Admin
        $password = Hash::make('Rofita_eo935++');

        $admin = new User ;
        $admin->name = "Rofita Sari";
        $admin->email = "rofita.sari@ecogreenoleo.com";
        $admin->password = $password;
        $admin->save();
        $admin->addRole('admin');
        $admin->givePermission('task-create');
        $admin->givePermission('task-read');
        $admin->givePermission('task-update');
        $admin->givePermission('task-approve');
        $admin->givePermission('task-acknowledge');
        $admin->givePermission('user-create');
        $admin->givePermission('user-read');
        $admin->givePermission('user-update');
    }
}