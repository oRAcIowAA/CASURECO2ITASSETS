<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PcHistory;
use App\Models\PrinterHistory;
use App\Models\NetworkDeviceHistory;
use App\Models\EmployeeHistory;
use Illuminate\Http\Request;
use App\Constants\Organization;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\DeviceHistoryService;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    protected $historyService;

    public function __construct(DeviceHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function index(Request $request)
    {
        $query = Employee::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('lname', 'like', "%{$search}%")
                    ->orWhere('mname', 'like', "%{$search}%")
                    ->orWhere('emp_id', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(15)->withQueryString();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $groups = Organization::LOCATIONS;
        $departments = Organization::DEPARTMENTS;
        $deptDivisions = Organization::DEPT_DIVISIONS;

        return view('employees.create', compact('groups', 'departments', 'deptDivisions'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        // Validation handled by StoreEmployeeRequest
        $employee = Employee::create($request->validated());

        // Log creation
        $this->historyService->logEmployeeAction($employee, 'created', 'Employee record created');

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['pcUnits', 'printers', 'networkDevices', 'employeeHistory.createdBy']);

        // Asset History
        $pcHistory = PcHistory::where('employee_id', $employee->emp_id)
            ->with(['pcUnit', 'createdBy', 'previousEmployee', 'employee'])
            ->get();

        $printerHistory = PrinterHistory::where('employee_id', $employee->emp_id)
            ->with(['printer', 'createdBy', 'previousEmployee', 'employee'])
            ->get();

        $networkHistory = NetworkDeviceHistory::where('employee_id', $employee->emp_id)
            ->with(['networkDevice', 'createdBy', 'previousEmployee', 'employee'])
            ->get();

        // Combine all asset histories
        $assetHistory = $pcHistory->concat($printerHistory)->concat($networkHistory)->sortByDesc('created_at');

        // Employee Record History
        $recordHistory = $employee->employeeHistory()->with('createdBy')->latest()->get();

        return view('employees.show', compact('employee', 'assetHistory', 'recordHistory'));
    }

    public function edit(Employee $employee)
    {
        $groups = Organization::LOCATIONS;
        $departments = Organization::DEPARTMENTS;
        $deptDivisions = Organization::DEPT_DIVISIONS;

        return view('employees.edit', compact('employee', 'groups', 'departments', 'deptDivisions'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        
        DB::transaction(function () use ($employee, $validated) {
            $changeSummary = $this->historyService->generateChangesSummary($employee->fill($validated));
            
            if ($changeSummary) {
                $employee->save();
                $this->historyService->logEmployeeAction($employee, 'edited', $changeSummary);
            } else {
                $employee->save();
            }
        });

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        // Check if employee has any assets assigned
        if ($employee->pcUnits()->count() > 0 || $employee->printers()->count() > 0 || $employee->networkDevices()->count() > 0) {
            return back()->with('error', 'Cannot delete employee with assigned IT assets.');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
