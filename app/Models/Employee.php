<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department',
        'position',
        'date_hired',
        'phone',
        'address',
        'manager_id',
    ];

    /**
     * Get the user that owns the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the manager of this employee.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
