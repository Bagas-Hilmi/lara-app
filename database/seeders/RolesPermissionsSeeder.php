<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Struktur roles dan permissions
        $roles = [
            'super-admin' => ['task' => 'c,r,u,d,apv,ack', 'user' => 'c,r,u,d'],
            'admin' => ['task' => 'c,r,u,apv,ack', 'user' => 'c,r,u'],
            'manager' => ['task' => 'c,r,u,apv,ack', 'user' => 'c,r,u'],
            'superintendent' => ['task' => 'c,r,u,apv,ack'],
            'senior-supervisor' => ['task' => 'c,r,u,apv,ack'],
            'supervisor' => ['task' => 'c,r,u,apv'],
            'senior-staff' => ['task' => 'c,r,u,apv'],
            'staff' => ['task' => 'c,r,u'],
            'viewer' => ['task' => 'r']
        ];

        // Mapping permissions
        $permissionsMap = [
            'c' => 'create',
            'r' => 'read',
            'u' => 'update',
            'd' => 'delete',
            'apv' => 'approve',
            'ack' => 'acknowledge'
        ];

        // Loop untuk membuat roles dan permissions
        foreach ($roles as $roleName => $modules) {
            // Buat role atau ambil role jika sudah ada
            $role = Role::firstOrCreate(['name' => $roleName]);

            foreach ($modules as $module => $actions) {
                $actionsArray = explode(',', $actions);

                foreach ($actionsArray as $action) {
                    $permissionName = $permissionsMap[$action] . '-' . $module;

                    // Buat permission atau ambil permission jika sudah ada
                    $permission = Permission::firstOrCreate(['name' => $permissionName]);

                    // Hubungkan permission dengan role
                    $role->permissions()->attach($permission);
                }
            }
        }
    }
}
