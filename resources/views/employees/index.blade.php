@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div x-data="{ 
    showModal: false,
    searchQuery: '{{ request('search') }}',
    performSearch() {
        let url = new URL(window.location.href);
        if (this.searchQuery) {
            url.searchParams.set('search', this.searchQuery);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page'); // Reset to page 1 on new search
        
        fetch(url.toString())
            .then(res => res.text())
            .then(html => {
                let doc = new DOMParser().parseFromString(html, 'text/html');
                document.getElementById('table-container').innerHTML = doc.getElementById('table-container').innerHTML;
                window.history.pushState({}, '', url);
            });
    }
}" class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Directory</p>
            <h1 class="text-4xl font-bold text-slate-900">Employees</h1>
        </div>
        <button type="button" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md" @click="showModal = true">
            + New Employee
        </button>
    </div>

    <!-- Search Bar -->
    <div class="flex gap-2">
        <div class="flex-1 flex gap-2">
            <input type="search" x-model="searchQuery" @input.debounce.300ms="performSearch()" class="flex-1 px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition" placeholder="Search name or email...">
        </div>
    </div>

    <!-- Employees Table -->
    <div id="table-container">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 uppercase tracking-wider">Hired</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $employee->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $employee->user->email }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $employee->department }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $employee->position }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                    {{ ucfirst(str_replace('_', ' ', $employee->user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $employee->date_hired }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex gap-2 justify-end">
                                    <a href="{{ route('employees.edit', $employee) }}" class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition">Edit</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Delete this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-600">
                                <p class="text-sm">No employees found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
        <div class="mt-6">
            {{ $employees->links() }}
        </div>
    </div>

    <!-- New Employee Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto" @click.outside="showModal = false">
            <div class="sticky top-0 bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center rounded-t-2xl">
                <h2 class="text-2xl font-bold text-slate-900">Create New Employee</h2>
                <button @click="showModal = false" class="text-slate-500 hover:text-slate-700 transition">
                    <svg class="w-10 h-10 text-slate-400 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('employees.store') }}" id="newEmployeeForm" class="p-6 space-y-6">
                @csrf

                <!-- Top Row -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('name') border-red-500 @enderror" required>
                        @error('name')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('email') border-red-500 @enderror" required>
                        @error('email')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Password Row -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('password') border-red-500 @enderror" required>
                        @error('password')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition" required>
                    </div>
                </div>

                <!-- Manager & Department -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Manager</label>
                        <select name="manager_id" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition">
                            <option value="">Select manager</option>
                            @foreach($managers ?? [] as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Department</label>
                        <input type="text" name="department" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('department') border-red-500 @enderror" required>
                        @error('department')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Position & Role -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Position</label>
                        <input type="text" name="position" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('position') border-red-500 @enderror" required>
                        @error('position')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Role</label>
                        <select name="role" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition">
                            <option value="employee">Employee</option>
                            <option value="manager">Manager</option>
                            <option value="hr_admin">HR Admin</option>
                        </select>
                    </div>
                </div>

                <!-- Date Hired & Phone -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Date Hired</label>
                        <input type="date" name="date_hired" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('date_hired') border-red-500 @enderror" required>
                        @error('date_hired')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Phone</label>
                        <input type="text" name="phone" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('phone') border-red-500 @enderror" required>
                        @error('phone')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-transparent transition @error('address') border-red-500 @enderror" required></textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </form>

            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 sm:justify-end rounded-b-2xl">
                <button type="button" @click="showModal = false" class="px-6 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="submit" form="newEmployeeForm" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-lg transition shadow-md">
                    Create Employee
                </button>
            </div>
        </div>
    </div>
</div>
@endsection