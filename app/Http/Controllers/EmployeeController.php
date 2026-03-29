<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PcHistory;
use Illuminate\Http\Request;
use App\Constants\Organization;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('group', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(15)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $groups = Organization::GROUPS;
        $divisions = Organization::DIVISIONS;
        $departments = Organization::DEPARTMENTS;

        return view('employees.create', compact('groups', 'divisions', 'departments'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        // Validation handled by StoreEmployeeRequest
        Employee::create($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['pcUnits', 'printers', 'networkDevices']);

        $history = PcHistory::where('employee_id', $employee->id)
            ->orWhere('previous_employee_id', $employee->id)
            ->with(['pcUnit', 'createdBy', 'previousEmployee', 'employee'])
            ->latest()
            ->get();

        return view('employees.show', compact('employee', 'history'));
    }

    public function edit(Employee $employee)
    {
        $groups = Organization::GROUPS;
        $divisions = Organization::DIVISIONS;
        $departments = Organization::DEPARTMENTS;

        return view('employees.edit', compact('employee', 'groups', 'divisions', 'departments'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        // Validation handled by UpdateEmployeeRequest
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Check if employee has any PC units assigned
        if ($employee->pcUnits()->count() > 0) {
            return back()->with('error', 'Cannot delete employee with assigned PC units.');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
