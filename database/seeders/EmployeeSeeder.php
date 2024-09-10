<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 1000) as $index) {
            Employee::create([
                'name' => $faker->name,
                'position' => $faker->jobTitle,
                'birth_date' => $faker->date('Y-m-d', '2000-01-01'),
                'hired_on' => $faker->date('Y-m-d', 'now'),
            ]);
        }
    }
}
