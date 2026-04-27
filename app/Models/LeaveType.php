<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'annual_allocation',
        'requires_approval',
    ];

    /**
     * Get the leave requests for this leave type.
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the leave balances for this leave type.
     */
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
}
