@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Employee Management</h1>
        <p class="text-muted">Review and manage employee profiles.</p>
    </div>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Position</th>
                <th>Date Hired</th>
                <th>Phone</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->user->name }}</td>
                    <td>{{ $employee->user->email }}</td>
                    <td>{{ $employee->department }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->date_hired }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td class="text-end">
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this employee?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No employees found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $employees->links() }}
@endsection
