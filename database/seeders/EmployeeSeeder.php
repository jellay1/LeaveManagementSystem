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
        // HR Admin Employee Profile
        \App\Models\Employee::create([
            'user_id' => 1,
            'department' => 'HR',
            'position' => 'HR Admin',
            'date_hired' => '2020-01-01',
            'phone' => '123-456-7890',
            'address' => '123 Main St',
            'manager_id' => 4, // HR Manager
        ]);

        // Engineering Manager Employee Profile
        \App\Models\Employee::create([
            'user_id' => 2,
            'department' => 'Engineering',
            'position' => 'Engineering Manager',
            'date_hired' => '2019-05-15',
            'phone' => '987-654-3210',
            'address' => '456 Elm St',
            'manager_id' => null,
        ]);

        // IT Manager Employee Profile
        \App\Models\Employee::create([
            'user_id' => 3,
            'department' => 'IT',
            'position' => 'IT Manager',
            'date_hired' => '2019-08-20',
            'phone' => '555-123-4567',
            'address' => '789 Oak Ave',
            'manager_id' => null,
        ]);

        // HR Manager Employee Profile
        \App\Models\Employee::create([
            'user_id' => 4,
            'department' => 'HR',
            'position' => 'HR Manager',
            'date_hired' => '2018-03-10',
            'phone' => '555-987-6543',
            'address' => '321 Pine St',
            'manager_id' => null,
        ]);

        // Engineering Employee 1
        \App\Models\Employee::create([
            'user_id' => 5,
            'department' => 'Engineering',
            'position' => 'Software Engineer',
            'date_hired' => '2021-01-15',
            'phone' => '555-111-2222',
            'address' => '111 Engineer Ln',
            'manager_id' => 2, // Engineering Manager
        ]);

        // IT Employee 1
        \App\Models\Employee::create([
            'user_id' => 6,
            'department' => 'IT',
            'position' => 'IT Support Specialist',
            'date_hired' => '2021-06-01',
            'phone' => '555-333-4444',
            'address' => '222 Tech Dr',
            'manager_id' => 3, // IT Manager
        ]);

        // HR Employee 1
        \App\Models\Employee::create([
            'user_id' => 7,
            'department' => 'HR',
            'position' => 'HR Specialist',
            'date_hired' => '2021-09-01',
            'phone' => '555-555-6666',
            'address' => '333 HR Blvd',
            'manager_id' => 4, // HR Manager
        ]);
    }
}
