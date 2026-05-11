@auth
<div x-data="notificationBell()" x-init="init()" class="dropdown position-relative">
    <button class="notification-btn dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 0 0-3 0v.68C7.64 5.36 6 7.92 6 11v5l-1.7 1.7a1 1 0 0 0 .7 1.7h13a1 1 0 0 0 .7-1.7L18 16z"/>
        </svg>
        <span x-show="unreadCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: .65rem; padding: 0.35em 0.65em;">
            <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </span>
    </button>

    <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationDropdown" style="width: 380px; max-height: 500px; overflow-y: auto;">
        {{-- Header --}}
        <div class="dropdown-header d-flex justify-content-between align-items-center border-bottom">
            <strong>Notifications</strong>
            <template x-if="unreadCount > 0">
                <form @submit.prevent="markAllAsRead()" style="display:inline;">
                    <button type="submit" class="btn btn-link btn-sm p-0" style="font-size: 0.8rem;">Mark all read</button>
                </form>
            </template>
        </div>

        {{-- Notifications List --}}
        <template x-if="notifications.length === 0">
            <div class="dropdown-item-text text-center text-muted py-4">
                <svg style="font-size:2rem;display:block;margin:0 auto .5rem;opacity:.3;width:2rem;height:2rem;" fill="currentColor" viewBox="0 0 24 24"><path d="M4 20h16v-2H4v2zm2-10h12V8H6v10zm14-6h-4V2h-4v4H4c-1.1 0-2 .9-2 2v8h0v6h20v-6h0v-8c0-1.1-.9-2-2-2z"/></svg>
                <small>No notifications yet</small>
            </div>
        </template>

        <template x-for="notification in notifications.slice(0, 5)" :key="notification.id">
            <div class="dropdown-item border-bottom p-3" :style="{ backgroundColor: notification.read ? '#fff' : '#f0f7ff' }">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <strong style="font-size: 0.95rem;" x-text="notification.title"></strong>
                    <template x-if="!notification.read">
                        <span class="badge bg-primary" style="font-size: 0.65rem;">New</span>
                    </template>
                </div>
                <p class="mb-1 text-muted" style="font-size: 0.85rem; line-height: 1.4;" x-text="notification.message"></p>
                <small class="text-muted" x-text="formatTime(notification.created_at)"></small>
            </div>
        </template>

        {{-- View All Link --}}
        <template x-if="notifications.length > 0">
            <div class="dropdown-footer text-center border-top p-2">
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">View all notifications</a>
            </div>
        </template>
    </div>
</div>

<script>
function notificationBell() {
    return {
        notifications: [],
        unreadCount: 0,
        refreshInterval: null,

        async init() {
            try {
                await this.loadNotifications();
                // Refresh every 30 seconds
                this.refreshInterval = setInterval(() => this.loadNotifications(), 30000);
            } catch (error) {
                console.warn('Notifications unavailable:', error);
            }
        },

        async loadNotifications() {
            try {
                const response = await fetch('{{ route("notifications.unread") }}');
                if (!response.ok) throw new Error('Failed to load notifications');
                this.notifications = await response.json();

                const countResponse = await fetch('{{ route("notifications.unread-count") }}');
                if (!countResponse.ok) throw new Error('Failed to get notification count');
                const data = await countResponse.json();
                this.unreadCount = data.count || 0;
            } catch (error) {
                console.warn('Error loading notifications:', error);
                this.notifications = [];
                this.unreadCount = 0;
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('{{ route("notifications.mark-all-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                });
                if (response.ok) {
                    await this.loadNotifications();
                }
            } catch (error) {
                console.warn('Error marking notifications as read:', error);
            }
        },

        formatTime(dateString) {
            try {
                const date = new Date(dateString);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                const diffHours = Math.floor(diffMs / 3600000);
                const diffDays = Math.floor(diffMs / 86400000);

                if (diffMins < 1) return 'Just now';
                if (diffMins < 60) return `${diffMins}m ago`;
                if (diffHours < 24) return `${diffHours}h ago`;
                if (diffDays < 7) return `${diffDays}d ago`;

                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            } catch {
                return 'Recently';
            }
        },

        destroy() {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        }
    }
}
</script>

<style>
.dropdown-menu {
    border-radius: 0.75rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.dropdown-header {
    padding: 1rem;
    background: #f8f9fa;
}

.dropdown-item-text {
    padding: 2rem 1rem !important;
}

.dropdown-footer {
    padding: 0.75rem;
    background: #f8f9fa;
}
</style>
@endauth
