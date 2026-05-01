<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PowerUtility;
use App\Models\Employee;
use App\Services\DeviceHistoryService;
use App\Http\Requests\StorePowerUtilityRequest;
use App\Http\Requests\UpdatePowerUtilityRequest;
use App\Http\Requests\TransferDeviceRequest;
use App\Http\Requests\CondemnDeviceRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PowerUtilityController extends Controller
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
        $query = PowerUtility::with(['employee']);

        // Location filter
        if ($request->filled('location') && $request->location !== 'All Locations') {
            $val = $request->location;
            $query->where(function($q) use ($val) {
                $q->where(function($sq) use ($val) {
                    $sq->whereNull('employee_id')->where('location', $val);
                })->orWhereHas('employee', function($sq) use ($val) {
                    $sq->where('location', $val);
                });
            });
        }

        // Division filter
        if ($request->filled('division') && $request->division !== 'All Divisions') {
            $val = $request->division;
            $query->where(function($q) use ($val) {
                $q->where(function($sq) use ($val) {
                    $sq->whereNull('employee_id')->where('division', $val);
                })->orWhereHas('employee', function($sq) use ($val) {
                    $sq->where('division', $val);
                });
            });
        }

        // Department filter
        if ($request->filled('department') && $request->department !== 'All Departments') {
            $val = $request->department;
            $query->where(function($q) use ($val) {
                $q->where(function($sq) use ($val) {
                    $sq->whereNull('employee_id')->where('department', $val);
                })->orWhereHas('employee', function($sq) use ($val) {
                    $sq->where('department', $val);
                });
            });
        }

        // Search filter (extended)
        if ($request->filled('search')) {
            $search = strtoupper($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($sq) use ($search) {
                        $sq->where('fname', 'like', "%{$search}%")
                           ->orWhere('lname', 'like', "%{$search}%")
                           ->orWhere('emp_id', 'like', "%{$search}%");
                    });
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        $powerUtilities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $groups = \App\Constants\Organization::LOCATIONS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $deptDivisions = \App\Constants\Organization::DEPT_DIVISIONS;

        return view('power-utilities.index', compact('powerUtilities', 'groups', 'divisions', 'departments', 'deptDivisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'UPS'); // Default to UPS

        $groups = \App\Constants\Organization::LOCATIONS;
        $employees = Employee::orderBy('lname')->orderBy('fname')->get();
        $nextAssetTag = \App\Services\AssetTagService::generateNextTag(PowerUtility::class, 'CAS-PU-');

        return view('power-utilities.create', compact('groups', 'employees', 'type', 'nextAssetTag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePowerUtilityRequest $request)
    {
        $validated = $request->validated();

        if ($request->assignment_type === 'ASSIGN') {
            if (empty($validated['employee_id'])) {
                return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
            }

            $employee = Employee::find($validated['employee_id']);
            if ($employee) {
                $validated['location'] = $employee->location;
                $validated['department'] = $employee->department;
                $validated['division'] = $employee->division;
            }

            $validated['status'] = 'assigned';
            $validated['date_assigned'] = now();
        } else {
            $validated['employee_id'] = null;
            $validated['status'] = 'available';
        }

        DB::transaction(function () use ($validated) {
            $powerUtility = PowerUtility::create($validated);

            if ($powerUtility->employee_id) {
                $this->historyService->log($powerUtility, 'assigned', 'Power Utility created and assigned');
            }
        });

        return redirect()->route('power-utilities.index')
            ->with('success', 'Power Utility created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PowerUtility $powerUtility)
    {
        $powerUtility->load(['employee', 'updatedBy', 'history.employee', 'history.createdBy']);
        return view('power-utilities.show', compact('powerUtility'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PowerUtility $powerUtility)
    {
        $groups = \App\Constants\Organization::LOCATIONS;
        $employees = Employee::all();
        return view('power-utilities.edit', compact('powerUtility', 'groups', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePowerUtilityRequest $request, PowerUtility $powerUtility)
    {
        $validated = $request->validated();

        // Prevent modification of date_issued if already set
        if ($powerUtility->date_issued) {
            unset($validated['date_issued']);
        }

        $specialStatuses = ['disposed', 'condemned', 'defective'];
        $currentStatus = strtolower($powerUtility->status);

        if (in_array($currentStatus, $specialStatuses)) {
            $validated['status'] = $powerUtility->status;
            $validated['employee_id'] = null;
        } else {
            if ($request->assignment_type === 'ASSIGN') {
                if (empty($validated['employee_id'])) {
                    return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
                }

                $employee = Employee::find($validated['employee_id']);
                if ($employee) {
                    $validated['location'] = $employee->location;
                    $validated['department'] = $employee->department;
                    $validated['division'] = $employee->division;
                }

                $validated['status'] = 'assigned';
            } else {
                $validated['employee_id'] = null;
                $validated['status'] = 'available';
            }
        }

        $oldEmployeeId = $powerUtility->employee_id;
        $newEmployeeId = $validated['employee_id'];

        if ($newEmployeeId && $oldEmployeeId !== $newEmployeeId) {
            $validated['date_assigned'] = now();
        } elseif ($oldEmployeeId && !$newEmployeeId) {
            $validated['date_returned'] = now();
            $validated['date_assigned'] = null;
        }
        $validated['updated_by'] = auth()->id();

        DB::transaction(function () use ($powerUtility, $validated, $oldEmployeeId, $newEmployeeId) {
            $powerUtility->fill($validated);
            $changeSummary = $this->historyService->generateChangesSummary($powerUtility);
            $powerUtility->save();

            if ($oldEmployeeId == $newEmployeeId) {
                if ($changeSummary) {
                    $this->historyService->log($powerUtility, 'edited', $changeSummary);
                }
            } else {
                if ($newEmployeeId) {
                    $action = $oldEmployeeId ? 'transferred' : 'assigned';
                    $notes = $oldEmployeeId ? 'Power Utility transferred' : 'Power Utility assigned';
                    if ($changeSummary) $notes .= " | " . $changeSummary;
                    $this->historyService->log($powerUtility, $action, $notes, $oldEmployeeId);
                } else {
                    $notes = 'Power Utility returned/unassigned';
                    if ($changeSummary) $notes .= " | " . $changeSummary;
                    $this->historyService->log($powerUtility, 'returned', $notes, $oldEmployeeId);
                }
            }
        });

        return redirect()->route('power-utilities.index')
            ->with('success', 'Power Utility updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PowerUtility $powerUtility)
    {
        DB::transaction(function () use ($powerUtility) {
            $powerUtility->delete();
        });

        return redirect()->route('power-utilities.index')
            ->with('success', 'Power Utility deleted successfully!');
    }

    /**
     * Print QR Code Label
     */
    public function printLabel(PowerUtility $powerUtility)
    {
        $deviceName = $powerUtility->type;
        $deviceType = 'Asset Tag';
        $assetTag = $powerUtility->asset_tag;
        $publicUrl = $powerUtility->public_url;
        $dateIssued = $powerUtility->date_issued ? \Carbon\Carbon::parse($powerUtility->date_issued)->format('M d, Y') : 'N/A';

        return view('reports.qr-sticker', compact('powerUtility', 'deviceName', 'deviceType', 'assetTag', 'publicUrl', 'dateIssued'));
    }

    /**
     * Show transfer form
     */
    public function transfer(PowerUtility $powerUtility)
    {
        $employees = Employee::orderBy('full_name')->get();
        return view('power-utilities.transfer', compact('powerUtility', 'employees'));
    }

    /**
     * Process transfer/reassignment
     */
    public function reassign(TransferDeviceRequest $request, PowerUtility $powerUtility)
    {
        $oldEmployeeId = $powerUtility->employee_id;
        $employee = Employee::find($request->employee_id);

        DB::transaction(function () use ($powerUtility, $request, $oldEmployeeId, $employee) {
            $powerUtility->update([
                'employee_id' => $request->employee_id,
                'location' => $employee->location ?? $powerUtility->location,
                'department' => $employee->department ?? $powerUtility->department,
                'division' => $employee->division ?? $powerUtility->division,
                'status' => 'assigned',
                'date_assigned' => now(),
            ]);

            $action = $oldEmployeeId ? 'transferred' : 'assigned';
            $this->historyService->log($powerUtility, $action, $request->notes ?? $request->remarks ?? 'Power Utility assigned/transferred', $oldEmployeeId);
        });

        return redirect()->route('power-utilities.show', $powerUtility)
            ->with('success', 'Power Utility transferred successfully!');
    }

    /**
     * Assign to employee
     */
    public function assign(Request $request, PowerUtility $powerUtility)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,emp_id',
            'assignment_notes' => 'nullable|string'
        ]);

        $oldEmployeeId = $powerUtility->employee_id;
        $employee = Employee::find($request->employee_id);

        DB::transaction(function () use ($powerUtility, $request, $oldEmployeeId, $employee) {
            $powerUtility->update([
                'employee_id' => $request->employee_id,
                'location' => $employee->location ?? $powerUtility->location,
                'department' => $employee->department ?? $powerUtility->department,
                'division' => $employee->division ?? $powerUtility->division,
                'status' => 'assigned',
                'date_assigned' => now(),
            ]);

            $action = $oldEmployeeId ? 'transferred' : 'assigned';
            $this->historyService->log($powerUtility, $action, $request->assignment_notes ?? 'Power Utility assigned/transferred', $oldEmployeeId);
        });

        return redirect()->route('power-utilities.show', $powerUtility)
            ->with('success', 'Power Utility assigned successfully!');
    }

    /**
     * Return from employee
     */
    public function return(PowerUtility $powerUtility)
    {
        if (!$powerUtility->employee_id) {
            return back()->with('error', 'Not assigned.');
        }

        $oldEmployeeId = $powerUtility->employee_id;

        DB::transaction(function () use ($powerUtility, $oldEmployeeId) {
            $powerUtility->update([
                'employee_id' => null,
                'status' => 'available',
                'date_returned' => now(),
            ]);

            $this->historyService->log($powerUtility, 'returned', 'Power Utility returned', $oldEmployeeId);
        });

        return redirect()->route('power-utilities.show', $powerUtility)
            ->with('success', 'Power Utility returned successfully!');
    }

    /**
     * Show disposal form
     */
    public function dispose(PowerUtility $powerUtility)
    {
        return view('power-utilities.dispose', compact('powerUtility'));
    }

    /**
     * Process disposal/condemnation
     */
    public function condemn(CondemnDeviceRequest $request, PowerUtility $powerUtility)
    {
        DB::transaction(function () use ($powerUtility, $request) {
            $oldEmployeeId = $powerUtility->employee_id;
            $status = $request->status;
            
            // Defective: Stay assigned. Condemned/Disposed: Unassign.
            $shouldUnassign = in_array(strtolower($status), ['condemned', 'disposed']);

            $powerUtility->update([
                'status' => $status,
                'employee_id' => $shouldUnassign ? null : $oldEmployeeId,
                'date_returned' => $shouldUnassign ? now() : $powerUtility->date_returned,
                'remarks' => ($powerUtility->remarks ? $powerUtility->remarks . "\n" : "") . "[" . ucfirst($status) . "]: " . $request->remarks,
                'spare_parts' => $request->spare_parts ?? $powerUtility->spare_parts
            ]);

            $this->historyService->log($powerUtility, strtolower($request->status), $request->remarks, $oldEmployeeId);
        });

        if ($request->status === 'Disposed') {
            return redirect()->route('power-utilities.show', $powerUtility)
                ->with('success', 'Power Utility marked as Disposed and permanently archived.')
                ->with('print_disposal', true);
        }

        return redirect()->route('power-utilities.show', $powerUtility)
            ->with('success', 'Power Utility marked as ' . ucfirst($request->status));
    }

    /**
     * Generate Certificate of Disposal PDF
     */
    public function printDisposal(PowerUtility $powerUtility)
    {
        try {
            $device = $powerUtility;
            $deviceTypeLabel = 'Power Utility';
            $pdf = Pdf::loadView('reports.dispose-device', compact('device', 'deviceTypeLabel'));
            return $pdf->stream('Certificate-of-Disposal-' . ($powerUtility->asset_tag ?? 'PU') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Process repair
     */
    public function repair(PowerUtility $powerUtility)
    {
        if (strtolower($powerUtility->status) !== 'defective') {
            return back()->with('error', 'Only defective units can be repaired.');
        }

        DB::transaction(function () use ($powerUtility) {
            // Restore previous employee if exists from history
            $lastAssignment = $powerUtility->history()
                ->whereIn('action', ['assigned', 'transferred', 'returned', 'defective'])
                ->whereNotNull('employee_id')
                ->latest()
                ->first();

            $status = $lastAssignment ? 'assigned' : 'available';
            $employeeId = $lastAssignment ? $lastAssignment->employee_id : null;

            $powerUtility->update([
                'status' => $status,
                'employee_id' => $employeeId,
                'date_assigned' => $lastAssignment ? now() : null
            ]);

            $this->historyService->log($powerUtility, 'repaired', 'Power Utility repaired and restored to ' . ($lastAssignment && $lastAssignment->employee ? $lastAssignment->employee->full_name : 'Available'));
        });

        return redirect()->route('power-utilities.show', $powerUtility)
            ->with('success', 'Power Utility marked as Repaired and is now Active.');
    }

    /**
     * Admin Override to Restore Disposed Entity
     */
    public function restore(Request $request, PowerUtility $powerUtility)
    {
        $request->validate(['password' => 'required']);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Restoration failed.');
        }

        DB::transaction(function () use ($powerUtility, $request) {
            $powerUtility->update([
                'status' => 'available',
                'employee_id' => null
            ]);

            $this->historyService->log($powerUtility, 'restored', 'Restored by Admin Override. Reason: ' . ($request->reason ?? 'Admin Error Correction'));
        });

        return redirect()->route('power-utilities.show', $powerUtility)
            ->with('success', 'Power Utility successfully restored from disposal.');
    }
}
