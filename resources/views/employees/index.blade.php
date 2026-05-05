@extends('layouts.app')

@section('title', 'Employee List')

@section('content')
<div x-data="{ showModal: false }">
    <div class="page-heading mb-4">
        <div class="page-label">Directory</div>
        <h1 class="page-title">Employees.</h1>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-3 mb-4">
        <form method="GET" action="{{ route('employees.index') }}" class="w-full md:w-1/2" id="searchForm">
            <div class="flex w-full rounded-full overflow-hidden border border-gray-200 bg-white shadow-sm">
                <input type="search" name="search" value="{{ request('search') }}" class="w-full px-4 py-3 focus:outline-none" placeholder="Search name or email..." id="searchInput" autocomplete="off">
                <button type="submit" class="px-5 bg-gray-900 text-white font-semibold hover:bg-gray-800">Search</button>
            </div>
        </form>

        <button type="button" class="btn-new-employee" @click="showModal = true">+ New Employee</button>
    </div>

<div class="page-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Hired</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $employee)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $employee->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $employee->department }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $employee->position }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="role-badge text-uppercase">{{ $employee->user->role }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $employee->date_hired }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Delete this employee?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">No employees found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>

    <!-- Modal for New Employee using Tailwind + Alpine -->
    <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full mx-4" @click.outside="showModal = false">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">NEW EMPLOYEE</h2>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 max-h-[80vh] overflow-y-auto">
                <form method="POST" action="{{ route('employees.store') }}" id="newEmployeeForm">
                    @csrf

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">FULL NAME</label>
                            <input type="text" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('name') border-red-500 @enderror" required>
                            @error('name')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">EMAIL</label>
                            <input type="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('email') border-red-500 @enderror" required>
                            @error('email')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">PASSWORD</label>
                            <input type="password" name="password" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('password') border-red-500 @enderror" required>
                            @error('password')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">CONFIRM PASSWORD</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent" required>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">MANAGER</label>
                            <select name="manager_id" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent">
                                <option value="">Select manager</option>
                                @foreach($managers ?? [] as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">DEPARTMENT</label>
                            <input type="text" name="department" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('department') border-red-500 @enderror" required>
                            @error('department')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">POSITION</label>
                            <input type="text" name="position" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('position') border-red-500 @enderror" required>
                            @error('position')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">ROLE</label>
                            <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent">
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="hr_admin">HR Admin</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">DATE HIRED</label>
                            <input type="date" name="date_hired" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('date_hired') border-red-500 @enderror" required>
                            @error('date_hired')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 mt-4">
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">PHONE</label>
                            <input type="text" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('phone') border-red-500 @enderror" required>
                            @error('phone')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700">ADDRESS</label>
                            <textarea name="address" rows="1" class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-black focus:border-transparent @error('address') border-red-500 @enderror" required></textarea>
                            @error('address')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex flex-col gap-3 p-6 border-t border-gray-200 bg-gray-50 md:flex-row md:justify-end md:items-center">
                <button type="button" @click="showModal = false" class="w-full md:w-auto px-6 py-3 border border-gray-300 rounded-2xl text-gray-700 font-medium hover:bg-gray-100">Cancel</button>
                <button type="submit" form="newEmployeeForm" class="w-full md:w-auto px-6 py-3 bg-black text-white font-semibold rounded-2xl hover:bg-gray-900">Create employee</button>
            </div>
        </div>
    </div>
</div>
@endsection