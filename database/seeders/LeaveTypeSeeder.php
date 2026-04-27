<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\LeaveType::create([
            'name' => 'Vacation',
            'annual_allocation' => 15,
            'requires_approval' => true,
        ]);

        \App\Models\LeaveType::create([
            'name' => 'Sick',
            'annual_allocation' => 10,
            'requires_approval' => false,
        ]);

        \App\Models\LeaveType::create([
            'name' => 'Emergency',
            'annual_allocation' => 5,
            'requires_approval' => true,
        ]);
    }
}
