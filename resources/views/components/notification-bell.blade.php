@auth
<div x-data="notificationBell()" x-init="init()" class="relative">
    <button @click="open = !open" type="button" class="relative p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" aria-label="Notifications">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 0 0-3 0v.68C7.64 5.36 6 7.92 6 11v5l-1.7 1.7a1 1 0 0 0 .7 1.7h13a1 1 0 0 0 .7-1.7L18 16z"/>
        </svg>
        <span x-show="unreadCount > 0" class="absolute top-1 right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
            <span x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
        </span>
    </button>

    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-lg border border-slate-200 z-50" style="max-height: 500px; overflow-y: auto;">
        {{-- Header --}}
        <div class="sticky top-0 bg-white border-b border-slate-200 p-4 flex justify-between items-center rounded-t-xl">
            <h3 class="font-semibold text-slate-900">Notifications</h3>
            <template x-if="unreadCount > 0">
                <form @submit.prevent="markAllAsRead()">
                    <button type="submit" class="text-sm font-medium text-slate-900 hover:text-black">Mark all read</button>
                </form>
            </template>
        </div>

        {{-- Notifications List --}}
        <template x-if="notifications.length === 0">
            <div class="text-center py-8 px-4">
                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M4 20h16v-2H4v2zm2-10h12V8H6v10zm14-6h-4V2h-4v4H4c-1.1 0-2 .9-2 2v8h0v6h20v-6h0v-8c0-1.1-.9-2-2-2z"/></svg>
                <p class="text-slate-600 text-sm">No notifications yet</p>
            </div>
        </template>

        <template x-for="notification in notifications.slice(0, 5)" :key="notification.id">
            <div :class="notification.read ? 'bg-white' : 'bg-slate-50'" class="border-b border-slate-200 p-4 hover:bg-slate-100 transition">
                <div class="flex justify-between items-start gap-3 mb-1">
                    <h4 class="font-semibold text-slate-900 text-sm" x-text="notification.title"></h4>
                    <template x-if="!notification.read">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">New</span>
                    </template>
                </div>
                <p class="text-slate-600 text-sm mb-1 leading-relaxed" x-text="notification.message"></p>
                <p class="text-xs text-slate-500" x-text="formatTime(notification.created_at)"></p>
            </div>
        </template>

        {{-- View All Link --}}
        <template x-if="notifications.length > 0">
            <div class="border-t border-slate-200 p-3 rounded-b-xl text-center">
                <a href="{{ route('notifications.index') }}" class="inline-block w-full px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100 rounded-lg transition">View all notifications</a>
            </div>
        </template>
    </div>
</div>

<script>
function notificationBell() {
    return {
        notifications: [],
        unreadCount: 0,
        open: false,
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
