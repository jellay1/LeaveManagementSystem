<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Employee::create([
            'user_id' => 1, // Assuming user with ID 1 exists
            'department' => 'IT',
            'position' => 'Developer',
            'date_hired' => '2020-01-01',
            'phone' => '123-456-7890',
            'address' => '123 Main St',
        ]);

        \App\Models\Employee::create([
            'user_id' => 2,
            'department' => 'HR',
            'position' => 'Manager',
            'date_hired' => '2019-05-15',
            'phone' => '987-654-3210',
            'address' => '456 Elm St',
        ]);
    }
}
