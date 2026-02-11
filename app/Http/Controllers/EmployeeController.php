<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('department.branch')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::with('branch')->get();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id',
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department.branch', 'pcUnits']);

        $history = \App\Models\PcHistory::where('employee_id', $employee->id)
            ->orWhere('previous_employee_id', $employee->id)
            ->with(['pcUnit', 'createdBy', 'previousEmployee', 'employee'])
            ->latest()
            ->get();

        return view('employees.show', compact('employee', 'history'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::with('branch')->get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_id' => 'required|string|max:255|unique:employees,employee_id,' . $employee->id,
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }
}