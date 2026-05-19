<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Leave Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col">
            <div class="p-6 border-b border-slate-200">
                <div class="text-xl font-bold text-slate-900 tracking-wide">Leave-OS</div>
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-1">ACME CORP</div>
            </div>

            @auth
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    {{-- Dashboard --}}
                    @if(auth()->user()->hasRole('manager'))
                        <a href="{{ route('manager-dashboard') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('manager-dashboard') || request()->routeIs('manager-dashboard.*') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('manager-dashboard') || request()->routeIs('manager-dashboard.*') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5z"/></svg>
                            <span>Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5z"/></svg>
                            <span>Dashboard</span>
                        </a>
                    @endif

                    {{-- My Leaves (Employee & Manager only) --}}
                    @if(!auth()->user()->hasRole('hr_admin') || auth()->user()->hasRole('manager'))
                        <a href="{{ route('leave-requests.index') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('leave-requests.index') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('leave-requests.index') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H2V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1zm12 7H5v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/></svg>
                            <span>My Leaves</span>
                        </a>
                    @endif

                    {{-- Apply Leave --}}
                    <a href="{{ route('leave-requests.create') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('leave-requests.create') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('leave-requests.create') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 5a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H6a1 1 0 1 1 0-2h5V6a1 1 0 0 1 1-1z"/></svg>
                        <span>Apply Leave</span>
                    </a>

                    {{-- Calendar --}}
                    <a href="{{ route('calendar') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('calendar') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                        <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('calendar') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2a1 1 0 0 0-2 0v2H8V2a1 1 0 1 0-2 0v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 16H5V9h14v11zm0-13H5V6h14v1z"/></svg>
                        <span>Calendar</span>
                    </a>

                    {{-- Approvals (Manager & HR) --}}
                    @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr_admin'))
                        <a href="{{ route('approvals') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('approvals') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('approvals') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M9.29 16.71a1 1 0 0 1-1.42 0l-3.6-3.59a1 1 0 0 1 1.42-1.42L9 14.59l7.29-7.3a1 1 0 0 1 1.42 1.42L9.29 16.71z"/></svg>
                            <span>Approvals</span>
                        </a>
                    @endif

                    {{-- Employees (HR Admin only) --}}
                    @if(auth()->user()->hasRole('hr_admin'))
                        <a href="{{ route('employees.index') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('employees.*') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('employees.*') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M6 7a4 4 0 1 1 8 0 4 4 0 0 1-8 0zm4 7c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z"/></svg>
                            <span>Employees</span>
                        </a>
                    @endif

                    {{-- Leave Types (HR Admin only) --}}
                    @if(auth()->user()->hasRole('hr_admin'))
                        <a href="{{ route('leave-types.index') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('leave-types.*') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('leave-types.*') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h16v16H4V4zm2 2v12h12V6H6zm3 3h6v2H9V9zm0 4h6v2H9v-2z"/></svg>
                            <span>Leave Types</span>
                        </a>
                    @endif

                    {{-- Reports (Manager & HR) --}}
                    @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr_admin'))
                        <a href="{{ route('reports') }}" class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('reports') ? 'bg-slate-100 text-slate-900 font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100/60 hover:text-slate-900 font-medium' }}">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ request()->routeIs('reports') ? 'text-slate-900' : 'text-slate-400 group-hover:text-slate-900' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M5 21h14v-2H5v2zm3-6h2v4H8v-4zm4-8h2v12h-2V7zm4 4h2v8h-2v-8z"/></svg>
                            <span>Reports</span>
                        </a>
                    @endif
                </nav>

                <div class="border-t border-slate-200 p-4 mt-auto">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Signed In</p>
                    <div class="font-semibold text-slate-900 text-sm">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-600 mb-3">{{ auth()->user()->email }}</div>
                    @if(auth()->user()->role === 'employee')
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">
                            EMPLOYEE
                        </div>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">Logout</button>
                    </form>
                </div>
            @endauth
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <div class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-widest">Workspace</div>
                    <h2 class="text-lg font-semibold text-slate-900">Leave Management</h2>
                </div>
                <div>
                    @auth
                        @include('components.notification-bell')
                    @endauth
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg text-amber-800 text-sm">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <style>
        /* Modern Sidebar Styles */
    </style>
</body>
</html>