@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="text-center mb-5">
            <h1 class="display-6 fw-bold">Edit Employee</h1>
        </div>

        <div class="card border-0 shadow-lg">
            <div class="card-body p-5">
                <form method="POST" action="{{ route('employees.update', $employee) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-uppercase small">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $employee->user->name) }}" class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-uppercase small">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $employee->user->email) }}" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                        @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="department" class="form-label fw-semibold text-uppercase small">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $employee->department) }}" class="form-control form-control-lg @error('department') is-invalid @enderror" required>
                        @error('department')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="position" class="form-label fw-semibold text-uppercase small">Position</label>
                        <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" class="form-control form-control-lg @error('position') is-invalid @enderror" required>
                        @error('position')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label for="date_hired" class="form-label fw-semibold text-uppercase small">Date Hired</label>
                            <input type="date" id="date_hired" name="date_hired" value="{{ old('date_hired', $employee->date_hired) }}" class="form-control form-control-lg @error('date_hired') is-invalid @enderror" required>
                            @error('date_hired')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold text-uppercase small">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" class="form-control form-control-lg @error('phone') is-invalid @enderror" required>
                            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label for="address" class="form-label fw-semibold text-uppercase small">Address</label>
                        <textarea id="address" name="address" rows="4" class="form-control form-control-lg @error('address') is-invalid @enderror" required>{{ old('address', $employee->address) }}</textarea>
                        @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Update Employee</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-lg px-5">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
