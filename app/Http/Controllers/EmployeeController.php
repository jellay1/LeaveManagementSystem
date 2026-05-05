<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $search = request('search');

        $employees = Employee::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $managers = User::where('role', 'manager')->get();

        return view('employees.index', compact('employees', 'managers'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'employee',
        ]);

        $user->employee()->create($request->only(['department', 'position', 'date_hired', 'phone', 'address']));

        return redirect()->route('employees.index')->with('success', 'Employee record created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $employee->update($request->only(['department', 'position', 'date_hired', 'phone', 'address']));

        return redirect()->route('employees.index')->with('success', 'Employee record updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->delete();

        return redirect()->route('employees.index')->with('success', 'Employee record deleted successfully.');
    }

    public function search()
    {
        $search = request('q');

        $employees = Employee::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->limit(50)
            ->get();

        return response()->json([
            'data' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->user->name,
                    'email' => $employee->user->email,
                    'department' => $employee->department,
                    'position' => $employee->position,
                    'role' => $employee->user->role,
                    'date_hired' => $employee->date_hired,
                ];
            })
        ]);
    }
}
