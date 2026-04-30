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
        $employees = Employee::with('user')->latest()->paginate(12);

        return view('employees.index', compact('employees'));
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
            'role' => 'employee',
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
}
