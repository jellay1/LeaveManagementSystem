@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div class="page-heading mb-4">
    <div class="page-label">Directory</div>
    <h1 class="page-title">Employees.</h1>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4">
    <form method="GET" action="{{ route('employees.index') }}" class="search-form w-100 w-md-50" id="searchForm">
        <div class="input-group search-input-group">
            <input type="search" name="search" value="{{ request('search') }}" class="form-control search-input" placeholder="Search name or email..." id="searchInput" autocomplete="off">
            <button type="submit" class="btn btn-search">Search</button>
        </div>
    </form>

    <a href="{{ route('employees.create') }}" class="btn btn-new-employee">+ New Employee</a>
</div>

<div class="page-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="border-bottom">
                    <tr>
                        <th class="py-3">Name</th>
                        <th class="py-3">Email</th>
                        <th class="py-3">Department</th>
                        <th class="py-3">Position</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Hired</th>
                        <th class="text-end py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td class="fw-semibold">{{ $employee->user->name }}</td>
                            <td class="text-muted">{{ $employee->user->email }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>
                                <span class="role-badge text-uppercase">
                                    {{ $employee->user->role }}
                                </span>
                            </td>
                            <td>{{ $employee->date_hired }}</td>
                            <td class="text-end">
                                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                                <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No employees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $employees->links() }}
</div>
@endsection
