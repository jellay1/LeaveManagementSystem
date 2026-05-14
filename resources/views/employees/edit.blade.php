@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="max-w-2xl">
    <div class="mb-8">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Directory</p>
        <h1 class="text-4xl font-bold text-slate-900">Edit Employee</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $employee->user->name) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('name') border-red-500 @enderror" required>
                @error('name')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $employee->user->email) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('email') border-red-500 @enderror" required>
                @error('email')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Department & Position -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="department" class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                    <input type="text" id="department" name="department" value="{{ old('department', $employee->department) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('department') border-red-500 @enderror" required>
                    @error('department')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="position" class="block text-sm font-semibold text-slate-700 mb-2">Position</label>
                    <input type="text" id="position" name="position" value="{{ old('position', $employee->position) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('position') border-red-500 @enderror" required>
                    @error('position')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Date Hired & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="date_hired" class="block text-sm font-semibold text-slate-700 mb-2">Date Hired</label>
                    <input type="date" id="date_hired" name="date_hired" value="{{ old('date_hired', $employee->date_hired) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('date_hired') border-red-500 @enderror" required>
                    @error('date_hired')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('phone') border-red-500 @enderror" required>
                    @error('phone')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Address Field -->
            <div>
                <label for="address" class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
                <textarea id="address" name="address" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('address') border-red-500 @enderror" required>{{ old('address', $employee->address) }}</textarea>
                @error('address')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
                    Update Employee
                </button>
                <a href="{{ route('employees.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
