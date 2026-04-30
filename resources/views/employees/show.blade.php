@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3">Employee Details</h1>
                <p class="text-muted">Viewing profile information for {{ $employee->user->name }}.</p>
            </div>
            <div>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8">{{ $employee->user->name }}</dd>

                    <dt class="col-sm-4">Email</dt>
                    <dd class="col-sm-8">{{ $employee->user->email }}</dd>

                    <dt class="col-sm-4">Department</dt>
                    <dd class="col-sm-8">{{ $employee->department }}</dd>

                    <dt class="col-sm-4">Position</dt>
                    <dd class="col-sm-8">{{ $employee->position }}</dd>

                    <dt class="col-sm-4">Date Hired</dt>
                    <dd class="col-sm-8">{{ $employee->date_hired }}</dd>

                    <dt class="col-sm-4">Phone</dt>
                    <dd class="col-sm-8">{{ $employee->phone }}</dd>

                    <dt class="col-sm-4">Address</dt>
                    <dd class="col-sm-8">{{ $employee->address }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
