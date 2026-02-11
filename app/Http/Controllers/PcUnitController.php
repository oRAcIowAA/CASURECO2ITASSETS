<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PcHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PcUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pcUnits = PcUnit::with(['branch', 'department', 'employee'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pc-units.index', compact('pcUnits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type');

        if (!$type) {
            return view('pc-units.select-type');
        }

        $branches = Branch::all();
        $departments = Department::with('branch')->get();
        $employees = Employee::with('department')->get();

        return view('pc-units.create', compact('branches', 'departments', 'employees', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_type' => 'required|in:PC,Laptop,Printer',
            'asset_tag' => 'required|string|unique:pc_units,asset_tag',
            'model' => 'required|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'status' => 'required|in:available,not_available,incoming,assigned,defective,condemned',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'nullable|exists:employees,id',
            'date_received' => 'nullable|date',
            'remarks' => 'nullable|string',
            'ip_address' => 'nullable|ipv4|unique:pc_units,ip_address',
            'mac_address' => 'nullable|string|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'network_segment' => 'nullable|string',
        ]);

        // Force correct status if employee is selected
        if (!empty($validated['employee_id'])) {
            $validated['status'] = 'assigned';
            $validated['date_assigned'] = now();
        }

        DB::transaction(function () use ($validated, $request) {
            $pcUnit = PcUnit::create($validated);

            if ($pcUnit->employee_id) {
                $this->logHistory($pcUnit, 'assigned', 'PC unit created and assigned');
            }
        });

        return redirect()->route('pc-units.index')
            ->with('success', 'PC unit created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PcUnit $pcUnit)
    {
        $pcUnit->load(['branch', 'department', 'employee', 'history.employee', 'history.createdBy']);

        return view('pc-units.show', compact('pcUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PcUnit $pcUnit)
    {
        $branches = Branch::all();
        $departments = Department::with('branch')->get();
        $employees = Employee::with('department')->get();

        return view('pc-units.edit', compact('pcUnit', 'branches', 'departments', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PcUnit $pcUnit)
    {
        $validated = $request->validate([
            'device_type' => 'required|in:PC,Laptop,Printer',
            'asset_tag' => 'required|string|unique:pc_units,asset_tag,' . $pcUnit->id,
            'model' => 'required|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'status' => 'required|in:available,not_available,incoming,assigned,defective,condemned',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'nullable|exists:employees,id',
            'date_received' => 'nullable|date',
            'remarks' => 'nullable|string',
            'ip_address' => 'nullable|ipv4|unique:pc_units,ip_address,' . $pcUnit->id,
            'mac_address' => 'nullable|string|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'network_segment' => 'nullable|string',
        ]);

        $oldEmployeeId = $pcUnit->employee_id;
        $newEmployeeId = $request->employee_id;

        // Logic: If employee is set, status MUST be assigned. 
        // If employee is removed, status should be available (unless explicitly set to something else like not_available)
        if ($newEmployeeId) {
            $validated['status'] = 'assigned';
            if ($oldEmployeeId !== $newEmployeeId) {
                $validated['date_assigned'] = now();
            }
        }
        elseif ($oldEmployeeId && !$newEmployeeId) {
            // Employee was removed
            $validated['status'] = 'available'; // Default to available on return
            $validated['date_returned'] = now();
            $validated['date_assigned'] = null;
        }

        DB::transaction(function () use ($pcUnit, $validated, $oldEmployeeId, $newEmployeeId) {
            $pcUnit->update($validated);

            if ($oldEmployeeId != $newEmployeeId) {
                if ($newEmployeeId) {
                    // Assigned or Transferred
                    $action = $oldEmployeeId ? 'transferred' : 'assigned';
                    $notes = $oldEmployeeId ? 'PC unit transferred' : 'PC unit assigned';
                    $this->logHistory($pcUnit, $action, $notes, $oldEmployeeId);
                }
                else {
                    // Returned
                    $this->logHistory($pcUnit, 'returned', 'PC unit returned/unassigned', $oldEmployeeId);
                }
            }
        });

        return redirect()->route('pc-units.index')
            ->with('success', 'PC unit updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PcUnit $pcUnit)
    {
        DB::transaction(function () use ($pcUnit) {
            $pcUnit->delete();
        });

        return redirect()->route('pc-units.index')
            ->with('success', 'PC unit deleted successfully!');
    }

    /**
     * Assign PC to employee
     */
    public function assign(Request $request, PcUnit $pcUnit)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assignment_notes' => 'nullable|string'
        ]);

        $oldEmployeeId = $pcUnit->employee_id;

        DB::transaction(function () use ($pcUnit, $request, $oldEmployeeId) {
            $pcUnit->update([
                'employee_id' => $request->employee_id,
                'status' => 'assigned',
                'date_assigned' => now(),
                'assignment_notes' => $request->assignment_notes
            ]);

            $action = $oldEmployeeId ? 'transferred' : 'assigned';
            $this->logHistory($pcUnit, $action, $request->assignment_notes ?? 'PC unit assigned', $oldEmployeeId);
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC unit assigned successfully!');
    }

    /**
     * Return PC from employee
     */
    public function return (Request $request, PcUnit $pcUnit)
    {
        if (!$pcUnit->employee_id) {
            return redirect()->route('pc-units.show', $pcUnit)
                ->with('error', 'This PC is not assigned to anyone.');
        }

        $oldEmployeeId = $pcUnit->employee_id;

        DB::transaction(function () use ($pcUnit, $oldEmployeeId) {
            $pcUnit->update([
                'employee_id' => null,
                'status' => 'available',
                'date_returned' => now(),
                'assignment_notes' => 'Returned on ' . now()->format('Y-m-d')
            ]);

            $this->logHistory($pcUnit, 'returned', 'PC unit returned', $oldEmployeeId);
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC unit returned successfully!');
    }

    /**
     * Show disposal form
     */
    public function dispose(PcUnit $pcUnit)
    {
        return view('pc-units.dispose', compact('pcUnit'));
    }

    /**
     * Process disposal/condemnation
     */
    public function condemn(Request $request, PcUnit $pcUnit)
    {
        $request->validate([
            'status' => 'required|in:condemned,defective',
            'remarks' => 'required|string'
        ]);

        DB::transaction(function () use ($pcUnit, $request) {
            $oldEmployeeId = $pcUnit->employee_id;

            $pcUnit->update([
                'status' => $request->status,
                'employee_id' => null,
                'date_returned' => now(), // Considered returned from employee if it was assigned
                'remarks' => $pcUnit->remarks . "\n[Condemned/Defective]: " . $request->remarks
            ]);

            $this->logHistory($pcUnit, $request->status, $request->remarks, $oldEmployeeId);
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC Unit marked as ' . ucfirst($request->status));
    }

    /**
     * Show transfer form
     */
    public function transfer(PcUnit $pcUnit)
    {
        $employees = Employee::with('department')->get();
        return view('pc-units.transfer', compact('pcUnit', 'employees'));
    }

    /**
     * Process transfer
     */
    public function reassign(Request $request, PcUnit $pcUnit)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'notes' => 'nullable|string'
        ]);

        $oldEmployeeId = $pcUnit->employee_id;

        if ($oldEmployeeId == $request->employee_id) {
            return back()->with('error', 'Cannot transfer to the same employee.');
        }

        DB::transaction(function () use ($pcUnit, $request, $oldEmployeeId) {
            $pcUnit->update([
                'employee_id' => $request->employee_id,
                'status' => 'assigned',
                'date_assigned' => now(),
                'assignment_notes' => $request->notes
            ]);

            $this->logHistory($pcUnit, 'transferred', $request->notes ?? 'Transferred', $oldEmployeeId);
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC Unit transferred successfully!');
    }

    /**
     * Log history for PC Unit actions
     */
    private function logHistory(PcUnit $pcUnit, string $action, ?string $notes = null, ?int $previousEmployeeId = null)
    {
        PcHistory::create([
            'pc_unit_id' => $pcUnit->id,
            'employee_id' => $pcUnit->employee_id, // Current employee (null if returned/condemned)
            'previous_employee_id' => $previousEmployeeId,
            'assigned_date' => in_array($action, ['assigned', 'transferred']) ? now() : null,
            'returned_date' => in_array($action, ['returned', 'condemned', 'defective', 'disposed']) ? now() : null,
            'action' => $action,
            'notes' => $notes,
            'created_by' => Auth::id()
        ]);
    }
}