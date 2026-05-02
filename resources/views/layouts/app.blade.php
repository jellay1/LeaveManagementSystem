<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Leave Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
        .sidebar .nav-icon {
            width: 1.35rem;
            height: 1.35rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            flex-shrink: 0;
        }
        .sidebar .nav-link:hover .nav-icon,
        .sidebar .nav-link.active .nav-icon {
            color: #0d6efd;
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
        .notification-btn {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dee2e6;
            border-radius: .75rem;
            background: #fff;
            color: #495057;
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
            transition: border-color .15s ease, color .15s ease, background .15s ease;
        }
        .notification-btn:hover,
        .notification-btn:focus {
            border-color: #adb5bd;
            color: #212529;
            background: #f8f9fa;
        }
        .notification-btn svg {
            width: 1.1rem;
            height: 1.1rem;
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
        .page-heading {
            margin-bottom: 1rem;
        }
        .page-heading .page-label {
            text-transform: uppercase;
            letter-spacing: .24em;
            font-size: .75rem;
            color: #6c757d;
            margin-bottom: .5rem;
        }
        .page-heading .page-title {
            font-size: clamp(2rem, 2.4vw, 3rem);
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }
        .filter-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-bottom: 1.5rem;
        }
        .filter-tabs .btn {
            border-radius: .35rem;
            padding: .625rem 1rem;
            font-weight: 600;
            color: #495057;
            background: #fff;
            border: 1px solid #dee2e6;
        }
        .filter-tabs .btn.active {
            background: #212529;
            color: #fff;
            border-color: #212529;
        }
        .page-card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: .85rem;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,.04);
        }
        .page-card .card-header {
            padding: 1.5rem 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .page-card .card-header .btn-apply-leave {
            background: #000;
            color: #fff;
            border: none;
            padding: .85rem 1.1rem;
            border-radius: .65rem;
            font-weight: 700;
            letter-spacing: .02em;
        }
        .page-card .card-header .btn-apply-leave:hover {
            background: #222;
        }
        .page-card .card-body {
            padding: 1.5rem 1.75rem 1.75rem;
        }
        .page-card .table {
            margin-bottom: 0;
        }
        .table-sm th,
        .table-sm td {
            padding: .85rem .85rem;
        }
        .form-heading {
            text-transform: uppercase;
            letter-spacing: .18em;
            font-size: .75rem;
            color: #6c757d;
            margin-bottom: .75rem;
        }
        .form-title {
            font-size: clamp(2.5rem, 3vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 1.75rem;
            line-height: 1.05;
        }
        .field-label {
            text-transform: uppercase;
            letter-spacing: .16em;
            font-size: .75rem;
            color: #6c757d;
            margin-bottom: .5rem;
            display: block;
        }
        .summary-panel {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: .85rem;
            padding: 1.5rem;
            min-height: 100%;
        }
        .summary-panel h3 {
            font-size: .95rem;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #6c757d;
            margin-bottom: 1.25rem;
        }
        .summary-panel .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .95rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .summary-panel .summary-item:last-child {
            border-bottom: none;
        }
        .summary-panel .summary-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212529;
        }
        .summary-panel .summary-note {
            color: #6c757d;
            font-size: .9rem;
        }
        .btn-submit-request {
            background: #000;
            border: none;
            color: #fff;
            padding: .85rem 1.25rem;
            border-radius: .75rem;
            font-weight: 700;
            letter-spacing: .02em;
        }
        .btn-submit-request:hover {
            background: #222;
        }
        .btn-cancel-request {
            border-radius: .75rem;
            padding: .85rem 1.25rem;
        }
        .badge-status {
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .3rem .55rem;
            border-radius: .55rem;
            font-weight: 700;
            font-size: .75rem;
            line-height: 1.1;
        }
        .badge-status.approved {
            color: #218838;
            background: rgba(40, 167, 69, .12);
        }
        .badge-status.pending {
            color: #856404;
            background: rgba(255, 193, 7, .15);
        }
        .badge-status.rejected {
            color: #c82333;
            background: rgba(220, 53, 69, .12);
        }
        .badge-status.cancelled {
            color: #495057;
            background: rgba(108, 117, 125, .12);
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
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M3 10.5L12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5z"/></svg></span>
                        Dashboard
                    </a>
                    <a href="{{ route('leave-requests.index') }}" class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a2 2 0 0 1 2 2v2H2V6a2 2 0 0 1 2-2h1V3a1 1 0 0 1 1-1zm12 7H5v11a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9z"/></svg></span>
                        My Leaves
                    </a>
                    <a href="{{ route('leave-requests.create') }}" class="nav-link {{ request()->routeIs('leave-requests.create') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 5a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H6a1 1 0 1 1 0-2h5V6a1 1 0 0 1 1-1z"/></svg></span>
                        Apply Leave
                    </a>
                    <a href="{{ route('calendar') }}" class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M19 4h-1V2a1 1 0 0 0-2 0v2H8V2a1 1 0 1 0-2 0v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 16H5V9h14v11zm0-13H5V6h14v1z"/></svg></span>
                        Calendar
                    </a>
                    <a href="{{ route('approvals') }}" class="nav-link {{ request()->routeIs('approvals') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M9.29 16.71a1 1 0 0 1-1.42 0l-3.6-3.59a1 1 0 0 1 1.42-1.42L9 14.59l7.29-7.3a1 1 0 0 1 1.42 1.42L9.29 16.71z"/></svg></span>
                        Approvals
                    </a>
                    <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M6 7a4 4 0 1 1 8 0 4 4 0 0 1-8 0zm4 7c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z"/></svg></span>
                        Employees
                    </a>
                    <a href="{{ route('leave-types.index') }}" class="nav-link {{ request()->routeIs('leave-types.*') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M4 4h16v16H4V4zm2 2v12h12V6H6zm3 3h6v2H9V9zm0 4h6v2H9v-2z"/></svg></span>
                        Leave Types
                    </a>
                    <a href="{{ route('reports') }}" class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
                        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M5 21h14v-2H5v2zm3-6h2v4H8v-4zm4-8h2v12h-2V7zm4 4h2v8h-2v-8z"/></svg></span>
                        Reports
                    </a>
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
                        <button class="notification-btn" type="button" aria-label="Notifications">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 0 0-3 0v.68C7.64 5.36 6 7.92 6 11v5l-1.7 1.7a1 1 0 0 0 .7 1.7h13a1 1 0 0 0 .7-1.7L18 16z"/>
                            </svg>
                        </button>
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