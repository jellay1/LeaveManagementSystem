<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create HR Admin
        User::factory()->create([
            'name' => 'HR Admin',
            'email' => 'hr@acme.com',
            'password' => 'password',
            'role' => 'hr_admin',
        ]);

        // Create Manager for Engineering Department
        User::factory()->create([
            'name' => 'Engineering Manager',
            'email' => 'manager.eng@acme.com',
            'password' => 'password',
            'role' => 'manager',
        ]);

        // Create Manager for IT Department
        User::factory()->create([
            'name' => 'IT Manager',
            'email' => 'manager.it@acme.com',
            'password' => 'password',
            'role' => 'manager',
        ]);

        // Create Manager for HR Department
        User::factory()->create([
            'name' => 'HR Manager',
            'email' => 'manager.hr@acme.com',
            'password' => 'password',
            'role' => 'manager',
        ]);

        // Create Employees
        User::factory()->create([
            'name' => 'Jella Gesim',
            'email' => 'jelagesim@gmail.com',
            'password' => 'password',
            'role' => 'employee',
        ]);

        User::factory()->create([
            'name' => 'Athena Gumanoy',
            'email' => 'athena@gmail.com',
            'password' => 'password',
            'role' => 'employee',
        ]);

        User::factory()->create([
            'name' => 'Jasper Ursal',
            'email' => 'jasperursal@gmail.com',
            'password' => 'password',
            'role' => 'employee',
        ]);

        $this->call(LeaveTypeSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(LeaveBalanceSeeder::class);
    }
}
