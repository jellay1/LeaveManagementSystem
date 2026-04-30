@extends('layouts.app')

@section('title', 'Leave Types')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Leave Types</h1>
        <p class="text-muted">Configure available leave categories and approval settings.</p>
    </div>
    <a href="{{ route('leave-types.create') }}" class="btn btn-primary">Add Leave Type</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Allocation</th>
                <th>Requires Approval</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaveTypes as $leaveType)
                <tr>
                    <td>{{ $leaveType->name }}</td>
                    <td>{{ $leaveType->annual_allocation }} days</td>
                    <td>{{ $leaveType->requires_approval ? 'Yes' : 'No' }}</td>
                    <td class="text-end">
                        <a href="{{ route('leave-types.edit', $leaveType) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('leave-types.destroy', $leaveType) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Delete this leave type?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No leave types available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $leaveTypes->links() }}
@endsection
