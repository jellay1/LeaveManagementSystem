<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Leave Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <style>
        body {
            background: #f2f2f2;
            min-height: 100vh;
        }
        .app-shell {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: #fff;
            border-right: 1px solid #e5e5e5;
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
        }
        .sidebar .brand {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: .25rem;
        }
        .sidebar .brand-subtitle {
            font-size: .75rem;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        .sidebar .nav-link {
            color: #343a40;
            font-weight: 500;
            padding: .75rem 0;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-radius: .5rem;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #f8f9fa;
            text-decoration: none;
        }
        .sidebar .divider {
            border-top: 1px solid #e5e5e5;
            margin: 1.5rem 0;
        }
        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #e5e5e5;
        }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .page-header {
            background: #fff;
            border-bottom: 1px solid #e5e5e5;
            padding: 1.25rem 1.75rem;
        }
        .page-content {
            flex: 1;
            padding: 1.75rem;
        }
        .card-compact {
            border: 1px solid #e5e5e5;
            border-radius: .5rem;
            overflow: hidden;
        }
        .card-compact .card-body {
            padding: 1.5rem;
        }
        .stats-row .stat-card {
            border-right: 1px solid #e5e5e5;
        }
        .stats-row .stat-card:last-child {
            border-right: none;
        }
        .stats-row .stat-card .text-muted {
            color: #6c757d;
        }
        .table th,
        .table td {
            border-top: none;
        }
    </style>

    <div class="app-shell">
        <aside class="sidebar">
            <div>
                <div class="brand">Leave-OS</div>
                <div class="brand-subtitle">ACME CORP</div>
            </div>

            @auth
                <nav class="nav flex-column mb-4">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('leave-requests.index') }}" class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">My Leaves</a>
                    <a href="{{ route('leave-requests.create') }}" class="nav-link {{ request()->routeIs('leave-requests.create') ? 'active' : '' }}">Apply Leave</a>
                    <a href="{{ route('leave-requests.index') }}" class="nav-link">Calendar</a>
                    <a href="{{ route('leave-requests.index') }}" class="nav-link">Approvals</a>
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">Employees</a>
                    <a href="{{ route('leave-types.index') }}" class="nav-link {{ request()->routeIs('leave-types.*') ? 'active' : '' }}">Leave Types</a>
                    <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">Reports</a>
                </nav>

                <div class="sidebar-footer">
                    <p class="text-uppercase text-muted small mb-1">Signed In</p>
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-muted small mb-3">{{ auth()->user()->email }}</div>
                    <div class="text-uppercase text-muted small mb-1">{{ strtoupper(auth()->user()->role) }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm w-100">Logout</button>
                    </form>
                </div>
            @else
                <nav class="nav flex-column mb-4">
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                </nav>
            @endauth
        </aside>

        <div class="main">
            <div class="page-header d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-uppercase text-muted small mb-1">Workspace</div>
                    <h2 class="mb-0">Leave Management</h2>
                </div>
                <div>
                    @auth
                        <button class="btn btn-outline-secondary btn-sm">🔔</button>
                    @endauth
                </div>
            </div>

            <main class="page-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>