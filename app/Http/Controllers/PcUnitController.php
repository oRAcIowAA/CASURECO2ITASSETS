<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\DeviceHistoryService;
use App\Http\Requests\StorePcUnitRequest;
use App\Http\Requests\UpdatePcUnitRequest;
use App\Http\Requests\TransferDeviceRequest;
use App\Http\Requests\CondemnDeviceRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PcUnitController extends Controller
{
    protected $historyService;

    public function __construct(DeviceHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PcUnit::with(['employee']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('device_type', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($sq) use ($search) {
                    $sq->where('full_name', 'like', "%{$search}%");
                }
                );
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'All Statuses') {
            $query->where('status', strtolower(str_replace(' ', '_', $request->status)));
        }

        // Type filter
        if ($request->filled('type') && $request->type !== 'All Types') {
            $query->where('device_type', $request->type);
        }

        // Group filter
        if ($request->filled('group') && $request->group !== 'All Groups') {
            $query->where('group', $request->group);
        }

        // Division filter
        if ($request->filled('division') && $request->division !== 'All Divisions') {
            $query->where('division', $request->division);
        }

        // Department filter (for folder navigation)
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $pcUnits = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;

        return view('pc-units.index', compact('pcUnits', 'groups', 'divisions', 'departments'));
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

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $employees = Employee::all();
        $nextAssetTag = \App\Services\AssetTagService::generateNextTag(PcUnit::class, 'CAS-PC-');

        return view('pc-units.create', compact('groups', 'divisions', 'departments', 'employees', 'type', 'nextAssetTag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePcUnitRequest $request)
    {
        $validated = $request->validated();

        // Handle Assignment & Status
        if ($request->assignment_type === 'assign') {
            if (empty($validated['employee_id'])) {
                return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
            }
            $validated['status'] = 'assigned';
            $validated['date_assigned'] = now();
        }
        else {
            $validated['employee_id'] = null;
            $validated['status'] = 'available'; // Default to available
        }

        DB::transaction(function () use ($validated) {
            $pcUnit = PcUnit::create($validated);

            if ($pcUnit->employee_id) {
                $this->historyService->log($pcUnit, 'assigned', 'PC unit created and assigned');
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
        $pcUnit->load(['employee', 'updatedBy', 'history.employee', 'history.createdBy']);

        return view('pc-units.show', compact('pcUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PcUnit $pcUnit)
    {
        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $employees = Employee::all();

        return view('pc-units.edit', compact('pcUnit', 'groups', 'divisions', 'departments', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePcUnitRequest $request, PcUnit $pcUnit)
    {
        $validated = $request->validated();

        // Handle Assignment & Status Logic
        $specialStatuses = ['disposed', 'condemned', 'defective'];
        $currentStatus = strtolower($pcUnit->status);

        if (in_array($currentStatus, $specialStatuses)) {
            // Preserve status and unassigned state for special cases
            $validated['status'] = $pcUnit->status;
            $validated['employee_id'] = null;
        } else {
            if ($request->assignment_type === 'assign') {
                if (empty($validated['employee_id'])) {
                    return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
                }
                $validated['status'] = 'assigned';
            }
            else {
                // If checking 'standby', unassign
                $validated['employee_id'] = null;
                $validated['status'] = 'available';
            }
        }

        $oldEmployeeId = $pcUnit->employee_id;
        $newEmployeeId = $validated['employee_id'];

        if ($newEmployeeId && $oldEmployeeId !== $newEmployeeId) {
            $validated['date_assigned'] = now();
        }
        elseif ($oldEmployeeId && !$newEmployeeId) {
            // Returning logic
            $validated['date_returned'] = now();
            $validated['date_assigned'] = null;
        }
        $validated['updated_by'] = auth()->id();

        DB::transaction(function () use ($pcUnit, $validated, $oldEmployeeId, $newEmployeeId) {
            $pcUnit->update($validated);

            if ($oldEmployeeId == $newEmployeeId) {
                // If assignment didn't change, log it as an 'edited' action
                $this->historyService->log($pcUnit, 'edited', 'PC unit details updated');
            } else {
                if ($newEmployeeId) {
                    // Assigned or Transferred
                    $action = $oldEmployeeId ? 'transferred' : 'assigned';
                    $notes = $oldEmployeeId ? 'PC unit transferred' : 'PC unit assigned';
                    $this->historyService->log($pcUnit, $action, $notes, $oldEmployeeId);
                }
                else {
                    // Returned
                    $this->historyService->log($pcUnit, 'returned', 'PC unit returned/unassigned', $oldEmployeeId);
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
     * Print QR Code Label
     */
    public function printLabel(PcUnit $pcUnit)
    {
        $deviceName = 'PC Unit';
        $deviceType = 'Asset Tag';
        $assetTag = $pcUnit->asset_tag;
        $publicUrl = $pcUnit->public_url;
        $dateAssigned = $pcUnit->date_assigned ? \Carbon\Carbon::parse($pcUnit->date_assigned)->format('M d, Y') : 'N/A';

        return view('reports.qr-sticker', compact('pcUnit', 'deviceName', 'deviceType', 'assetTag', 'publicUrl', 'dateAssigned'));
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
            $this->historyService->log($pcUnit, $action, $request->assignment_notes ?? 'PC unit assigned', $oldEmployeeId);
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

            $this->historyService->log($pcUnit, 'returned', 'PC unit returned', $oldEmployeeId);
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
    public function condemn(CondemnDeviceRequest $request, PcUnit $pcUnit)
    {
        // Validation handled by CondemnDeviceRequest

        DB::transaction(function () use ($pcUnit, $request) {
            $oldEmployeeId = $pcUnit->employee_id;

            $status = $request->status;
            $shouldUnassign = in_array(strtolower($status), ['condemned', 'disposed']);

            $pcUnit->update([
                'status' => $status,
                'employee_id' => $shouldUnassign ? null : $oldEmployeeId,
                'date_returned' => $shouldUnassign ? now() : $pcUnit->date_returned,
                'remarks' => $pcUnit->remarks . "\n[Condemned/Defective]: " . $request->remarks,
                'spare_parts' => $request->spare_parts
            ]);

            $this->historyService->log($pcUnit, strtolower($request->status), $request->remarks, $oldEmployeeId);
        });

        if ($request->status === 'Disposed') {
            return redirect()->route('pc-units.show', $pcUnit)
                ->with('success', 'PC Unit marked as Disposed and permanently archived.')
                ->with('print_disposal', true);
        }

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC Unit marked as ' . ucfirst($request->status));
    }

    /**
     * Show transfer form
     */
    public function transfer(PcUnit $pcUnit)
    {
        $employees = Employee::orderBy('full_name')->get();
        return view('pc-units.transfer', compact('pcUnit', 'employees'));
    }

    /**
     * Process transfer
     */
    public function reassign(TransferDeviceRequest $request, PcUnit $pcUnit)
    {
        // Validation handled by TransferDeviceRequest

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

            $this->historyService->log($pcUnit, 'transferred', $request->notes ?? 'Transferred', $oldEmployeeId);
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC Unit transferred successfully!');
    }

    /**
     * Process repair
     */
    public function repair(Request $request, PcUnit $pcUnit)
    {
        if (strtolower($pcUnit->status) !== 'defective') {
            return back()->with('error', 'Only defective PC Units can be repaired.');
        }

        DB::transaction(function () use ($pcUnit) {
            // Restore previous employee if exists from history
            $lastAssignment = $pcUnit->history()
                ->whereIn('action', ['assigned', 'transferred', 'returned', 'defective'])
                ->whereNotNull('employee_id')
                ->latest()
                ->first();

            $status = $lastAssignment ? 'assigned' : 'available';
            $employeeId = $lastAssignment ? $lastAssignment->employee_id : null;

            $pcUnit->update([
                'status' => $status,
                'employee_id' => $employeeId,
                'date_assigned' => $lastAssignment ? now() : null
            ]);

            $this->historyService->log($pcUnit, 'repaired', 'PC Unit repaired and restored to ' . ($lastAssignment ? $lastAssignment->employee->full_name : 'Available'));
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC Unit marked as Repaired and is now Available.');
    }

    /**
     * Generate Certificate of Disposal PDF
     */
    public function printDisposal(PcUnit $pcUnit)
    {
        try {
            $device = $pcUnit;
            $deviceTypeLabel = 'PC Unit';
            $pdf = Pdf::loadView('reports.dispose-device', compact('device', 'deviceTypeLabel'));
            return $pdf->stream('Certificate-of-Disposal-' . ($pcUnit->asset_tag ?? $pcUnit->serial_number ?? 'PC') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Admin Override to Restore Disposed Entity
     */
    public function restore(Request $request, PcUnit $pcUnit)
    {
        $request->validate(['password' => 'required']);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Restoration failed.');
        }

        DB::transaction(function () use ($pcUnit, $request) {
            $pcUnit->update([
                'status' => 'available',
            ]);

            $this->historyService->log($pcUnit, 'restored', 'Restored by Admin Override. Reason: ' . ($request->reason ?? 'Admin Error Correction'));
        });

        return redirect()->route('pc-units.show', $pcUnit)
            ->with('success', 'PC Unit successfully restored from disposal.');
    }
}