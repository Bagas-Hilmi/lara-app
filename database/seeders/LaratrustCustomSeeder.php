<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class LaratrustCustomSeeder extends Seeder
{
    public function run()
    {
        // 1. Mengambil konfigurasi dari file
        $config = config('laratrust_seeder');

        // 2. Truncate tabel jika diperlukan
        if ($config['truncate_tables']) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('role_user')->truncate();
            DB::table('permission_role')->truncate();
            Role::truncate();
            Permission::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 3. Mengambil struktur roles dan permissions dari konfigurasi
        $roles = $config['roles_structure'];
        $permissionsMap = $config['permissions_map'];

        // 4. Iterasi melalui setiap role
        foreach ($roles as $roleName => $modules) {
            // 5. Membuat role
            $role = Role::create([
                'name' => $roleName,
                'display_name' => ucwords(str_replace('-', ' ', $roleName)),
                'description' => ucwords(str_replace('-', ' ', $roleName))
            ]);

            $permissions = [];

            // 6. Iterasi melalui setiap modul dan aksi untuk role ini
            foreach ($modules as $module => $actions) {
                foreach (explode(',', $actions) as $action) {
                    // 7. Membuat atau mengambil permission
                    $permissionName = $module . '-' . $permissionsMap[$action];
                    
                    $permission = Permission::firstOrCreate([
                        'name' => $permissionName,
                        'display_name' => ucfirst($permissionsMap[$action]) . ' ' . ucfirst($module),
                        'description' => 'Can ' . $permissionsMap[$action] . ' ' . $module,
                    ]);

                    $permissions[] = $permission->id;
                }
            }

            // 8. Menghubungkan permissions ke role
            $role->permissions()->sync($permissions);
        }
    }
}