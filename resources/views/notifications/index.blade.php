@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="page-heading mb-4">
    <div>
        <div class="page-label">System</div>
        <h1 class="page-title">Notifications.</h1>
    </div>
</div>

<div class="card border" style="border-radius:.85rem;overflow:hidden;">
    <div class="card-body p-0">
        {{-- Notification Controls --}}
        @if($notifications->total() > 0)
        <div class="px-4 pt-4 pb-3 d-flex justify-content-between align-items-center border-bottom">
            <div class="text-uppercase text-muted small" style="letter-spacing:.18em;font-size:.72rem;">
                {{ $notifications->total() }} Notification{{ $notifications->total() !== 1 ? 's' : '' }}
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark all as read</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Notifications List --}}
        @forelse($notifications as $notification)
        <div class="notification-item p-4 border-bottom" style="background-color: {{ $notification->read ? '#f8f9fa' : '#f0f7ff' }};">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="flex-grow-1">
                    <h6 class="mb-1">
                        {{ $notification->title }}
                        @if(!$notification->read)
                        <span class="badge bg-primary ms-2">New</span>
                        @endif
                    </h6>
                    <p class="mb-2 text-muted" style="font-size:.9rem;">{{ $notification->message }}</p>
                    <small class="text-muted">{{ $notification->created_at->format('M d, Y H:i') }}</small>
                </div>
                <div class="ms-3">
                    {{-- Mark as read/unread button --}}
                    @if(!$notification->read)
                    <form action="{{ route('notifications.mark-as-read', $notification) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Mark as read">
                            <i class="bi bi-check"></i>
                        </button>
                    </form>
                    @endif

                    {{-- Delete button --}}
                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Show related leave request link if available --}}
            @if($notification->leave_request_id)
            <a href="{{ route('leave-requests.show', $notification->leaveRequest) }}" class="btn btn-sm btn-outline-info mt-2">
                View Leave Request <i class="bi bi-arrow-right ms-1"></i>
            </a>
            @endif
        </div>
        @empty
        <div class="p-4 text-center text-muted">
            <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:1rem;opacity:.5;"></i>
            <p>You have no notifications yet.</p>
        </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div class="p-4 border-top">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<style>
.notification-item {
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f0f7ff !important;
}
</style>
@endsection
