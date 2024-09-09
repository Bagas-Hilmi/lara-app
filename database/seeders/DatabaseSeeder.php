<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laratrust\LaratrustSeeder;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustCustomSeeder::class);
    }
}
