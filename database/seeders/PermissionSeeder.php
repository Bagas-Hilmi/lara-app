<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $createTask = Permission::create([
            'name' => 'create-task',
            'display_name' => 'Create Task', 
            'description' => 'create New Capex, CIP, Upload Doc', 
        ]);

        $editTask = Permission::create([
            'name' => 'update-task',
            'display_name' => 'Update Task', 
            'description' => 'Update Capex, CIP, Upload Doc', 
        ]);

        $deleteTask = Permission::create([
            'name' => 'delete-task',
            'display_name' => 'Delete Task', 
            'description' => 'Delete Capex, CIP, Upload Doc', 
        ]);

        $readTask = Permission::create([
            'name' => 'read-task',
            'display_name' => 'read Task', 
            'description' => 'read Capex, CIP, Upload Doc', 
        ]);
    }
}
