<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $leaveTypes = \App\Models\LeaveType::all();
        $year = date('Y');

        foreach ($users as $user) {
            foreach ($leaveTypes as $leaveType) {
                \App\Models\LeaveBalance::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'leave_type_id' => $leaveType->id,
                        'year' => $year,
                    ],
                    [
                        'used_days' => 0,
                    ]
                );
            }
        }
    }
}
