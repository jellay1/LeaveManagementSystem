<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'leave_request_id',
        'read',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the leave request associated with the notification.
     */
    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    /**
     * Scope to get unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update(['read' => true]);
        return $this;
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        $this->update(['read' => false]);
        return $this;
    }
}
