<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileDevice;
use App\Models\Employee;
use App\Services\DeviceHistoryService;
use App\Http\Requests\StoreMobileDeviceRequest;
use App\Http\Requests\UpdateMobileDeviceRequest;
use App\Http\Requests\TransferDeviceRequest;
use App\Http\Requests\CondemnDeviceRequest;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class MobileDeviceController extends Controller
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
        $query = MobileDevice::with(['employee']);

        // Group/Location filter
        if ($request->filled('group')) {
            $query->where('group', $request->group);
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
                        $sq->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Division filter
        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        $mobileDevices = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $groups = \App\Constants\Organization::LOCATIONS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $deptDivisions = \App\Constants\Organization::DEPT_DIVISIONS;

        return view('mobile-devices.index', compact('mobileDevices', 'groups', 'divisions', 'departments', 'deptDivisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'CELLPHONE');

        $groups = \App\Constants\Organization::LOCATIONS;
        $employees = Employee::orderBy('full_name')->get();
        $nextAssetTag = \App\Services\AssetTagService::generateNextTag(MobileDevice::class, 'CAS-MD-');

        return view('mobile-devices.create', compact('groups', 'employees', 'type', 'nextAssetTag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMobileDeviceRequest $request)
    {
        $validated = $request->validated();

        if ($request->assignment_type === 'ASSIGN') {
            if (empty($validated['employee_id'])) {
                return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
            }

            $employee = Employee::find($validated['employee_id']);
            if ($employee) {
                $validated['group'] = $employee->group;
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
            $mobileDevice = MobileDevice::create($validated);

            if ($mobileDevice->employee_id) {
                $this->historyService->log($mobileDevice, 'assigned', 'Mobile Device created and assigned');
            }
        });

        return redirect()->route('mobile-devices.index')
            ->with('success', 'Mobile Device created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MobileDevice $mobileDevice)
    {
        $mobileDevice->load(['employee', 'updatedBy', 'history.employee', 'history.createdBy']);
        return view('mobile-devices.show', compact('mobileDevice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MobileDevice $mobileDevice)
    {
        $groups = \App\Constants\Organization::LOCATIONS;
        $employees = Employee::all();
        return view('mobile-devices.edit', compact('mobileDevice', 'groups', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMobileDeviceRequest $request, MobileDevice $mobileDevice)
    {
        $validated = $request->validated();

        $specialStatuses = ['disposed', 'condemned', 'defective'];
        $currentStatus = strtolower($mobileDevice->status);

        if (in_array($currentStatus, $specialStatuses)) {
            $validated['status'] = $mobileDevice->status;
            $validated['employee_id'] = null;
        } else {
            if ($request->assignment_type === 'ASSIGN') {
                if (empty($validated['employee_id'])) {
                    return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
                }

                $employee = Employee::find($validated['employee_id']);
                if ($employee) {
                    $validated['group'] = $employee->group;
                    $validated['department'] = $employee->department;
                    $validated['division'] = $employee->division;
                }

                $validated['status'] = 'assigned';
            } else {
                $validated['employee_id'] = null;
                $validated['status'] = 'available';
            }
        }

        $oldEmployeeId = $mobileDevice->employee_id;
        $newEmployeeId = $validated['employee_id'];

        if ($newEmployeeId && $oldEmployeeId !== $newEmployeeId) {
            $validated['date_assigned'] = now();
        } elseif ($oldEmployeeId && !$newEmployeeId) {
            $validated['date_returned'] = now();
            $validated['date_assigned'] = null;
        }
        $validated['updated_by'] = auth()->id();

        DB::transaction(function () use ($mobileDevice, $validated, $oldEmployeeId, $newEmployeeId) {
            $mobileDevice->fill($validated);
            $changeSummary = $this->historyService->generateChangesSummary($mobileDevice);
            $mobileDevice->save();

            if ($oldEmployeeId == $newEmployeeId) {
                if ($changeSummary) {
                    $this->historyService->log($mobileDevice, 'edited', $changeSummary);
                }
            } else {
                if ($newEmployeeId) {
                    $action = $oldEmployeeId ? 'transferred' : 'assigned';
                    $notes = $oldEmployeeId ? 'Mobile Device transferred' : 'Mobile Device assigned';
                    if ($changeSummary) $notes .= " | " . $changeSummary;
                    $this->historyService->log($mobileDevice, $action, $notes, $oldEmployeeId);
                } else {
                    $notes = 'Mobile Device returned/unassigned';
                    if ($changeSummary) $notes .= " | " . $changeSummary;
                    $this->historyService->log($mobileDevice, 'returned', $notes, $oldEmployeeId);
                }
            }
        });

        return redirect()->route('mobile-devices.index')
            ->with('success', 'Mobile Device updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MobileDevice $mobileDevice)
    {
        DB::transaction(function () use ($mobileDevice) {
            $mobileDevice->delete();
        });

        return redirect()->route('mobile-devices.index')
            ->with('success', 'Mobile Device deleted successfully!');
    }

    /**
     * Print QR Code Label
     */
    public function printLabel(MobileDevice $mobileDevice)
    {
        $deviceName = $mobileDevice->type;
        $deviceType = 'Asset Tag';
        $assetTag = $mobileDevice->asset_tag;
        $publicUrl = $mobileDevice->public_url;
        $dateAssigned = $mobileDevice->date_assigned ? \Carbon\Carbon::parse($mobileDevice->date_assigned)->format('M d, Y') : 'N/A';

        return view('reports.qr-sticker', compact('mobileDevice', 'deviceName', 'deviceType', 'assetTag', 'publicUrl', 'dateAssigned'));
    }

    /**
     * Show transfer form
     */
    public function transfer(MobileDevice $mobileDevice)
    {
        $employees = Employee::orderBy('full_name')->get();
        return view('mobile-devices.transfer', compact('mobileDevice', 'employees'));
    }

    /**
     * Process transfer/reassignment
     */
    public function reassign(TransferDeviceRequest $request, MobileDevice $mobileDevice)
    {
        $oldEmployeeId = $mobileDevice->employee_id;
        $employee = Employee::find($request->employee_id);

        DB::transaction(function () use ($mobileDevice, $request, $oldEmployeeId, $employee) {
            $mobileDevice->update([
                'employee_id' => $request->employee_id,
                'group' => $employee->group ?? $mobileDevice->group,
                'department' => $employee->department ?? $mobileDevice->department,
                'division' => $employee->division ?? $mobileDevice->division,
                'status' => 'assigned',
                'date_assigned' => now(),
            ]);

            $action = $oldEmployeeId ? 'transferred' : 'assigned';
            $this->historyService->log($mobileDevice, $action, $request->notes ?? $request->remarks ?? 'Mobile Device assigned/transferred', $oldEmployeeId);
        });

        return redirect()->route('mobile-devices.show', $mobileDevice)
            ->with('success', 'Mobile Device transferred successfully!');
    }

    /**
     * Assign to employee
     */
    public function assign(Request $request, MobileDevice $mobileDevice)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'assignment_notes' => 'nullable|string'
        ]);

        $oldEmployeeId = $mobileDevice->employee_id;
        $employee = Employee::find($request->employee_id);

        DB::transaction(function () use ($mobileDevice, $request, $oldEmployeeId, $employee) {
            $mobileDevice->update([
                'employee_id' => $request->employee_id,
                'group' => $employee->group ?? $mobileDevice->group,
                'department' => $employee->department ?? $mobileDevice->department,
                'division' => $employee->division ?? $mobileDevice->division,
                'status' => 'assigned',
                'date_assigned' => now(),
            ]);

            $action = $oldEmployeeId ? 'transferred' : 'assigned';
            $this->historyService->log($mobileDevice, $action, $request->assignment_notes ?? 'Mobile Device assigned/transferred', $oldEmployeeId);
        });

        return redirect()->route('mobile-devices.show', $mobileDevice)
            ->with('success', 'Mobile Device assigned successfully!');
    }

    /**
     * Return from employee
     */
    public function return(MobileDevice $mobileDevice)
    {
        if (!$mobileDevice->employee_id) {
            return back()->with('error', 'Not assigned.');
        }

        $oldEmployeeId = $mobileDevice->employee_id;

        DB::transaction(function () use ($mobileDevice, $oldEmployeeId) {
            $mobileDevice->update([
                'employee_id' => null,
                'status' => 'available',
                'date_returned' => now(),
            ]);

            $this->historyService->log($mobileDevice, 'returned', 'Mobile Device returned', $oldEmployeeId);
        });

        return redirect()->route('mobile-devices.show', $mobileDevice)
            ->with('success', 'Mobile Device returned successfully!');
    }

    /**
     * Show disposal form
     */
    public function dispose(MobileDevice $mobileDevice)
    {
        return view('mobile-devices.dispose', compact('mobileDevice'));
    }

    /**
     * Process disposal/condemnation
     */
    public function condemn(CondemnDeviceRequest $request, MobileDevice $mobileDevice)
    {
        DB::transaction(function () use ($mobileDevice, $request) {
            $oldEmployeeId = $mobileDevice->employee_id;
            $status = $request->status;
            
            // Defective: Stay assigned. 
            // Condemned/Disposed: Unassign.
            $shouldUnassign = in_array(strtolower($status), ['condemned', 'disposed']);

            $mobileDevice->update([
                'status' => $status,
                'employee_id' => $shouldUnassign ? null : $oldEmployeeId,
                'date_returned' => $shouldUnassign ? now() : $mobileDevice->date_returned,
                'remarks' => ($mobileDevice->remarks ? $mobileDevice->remarks . "\n" : "") . "[" . ucfirst($status) . "]: " . $request->remarks,
                'spare_parts' => $request->spare_parts ?? $mobileDevice->spare_parts
            ]);

            $this->historyService->log($mobileDevice, strtolower($request->status), $request->remarks, $oldEmployeeId);
        });

        if ($request->status === 'Disposed') {
            return redirect()->route('mobile-devices.show', $mobileDevice)
                ->with('success', 'Mobile Device marked as Disposed and permanently archived.')
                ->with('print_disposal', true);
        }

        return redirect()->route('mobile-devices.show', $mobileDevice)
            ->with('success', 'Mobile Device marked as ' . ucfirst($request->status));
    }

    /**
     * Generate Certificate of Disposal PDF
     */
    public function printDisposal(MobileDevice $mobileDevice)
    {
        try {
            $device = $mobileDevice;
            $deviceTypeLabel = 'Mobile Device';
            $pdf = Pdf::loadView('reports.dispose-device', compact('device', 'deviceTypeLabel'));
            return $pdf->stream('Certificate-of-Disposal-' . ($mobileDevice->asset_tag ?? $mobileDevice->serial_number ?? 'MD') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Process repair
     */
    public function repair(MobileDevice $mobileDevice)
    {
        if (strtolower($mobileDevice->status) !== 'defective') {
            return back()->with('error', 'Only defective units can be repaired.');
        }

        DB::transaction(function () use ($mobileDevice) {
            $lastAssignment = $mobileDevice->history()
                ->whereIn('action', ['assigned', 'transferred', 'returned', 'defective'])
                ->whereNotNull('employee_id')
                ->latest()
                ->first();

            $status = $lastAssignment ? 'assigned' : 'available';
            $employeeId = $lastAssignment ? $lastAssignment->employee_id : null;

            $mobileDevice->update([
                'status' => $status,
                'employee_id' => $employeeId,
                'date_assigned' => $lastAssignment ? now() : null
            ]);

            $this->historyService->log($mobileDevice, 'repaired', 'Mobile Device repaired and restored to ' . ($lastAssignment && $lastAssignment->employee ? $lastAssignment->employee->full_name : 'Available'));
        });

        return redirect()->route('mobile-devices.show', $mobileDevice)
            ->with('success', 'Mobile Device marked as Repaired and is now Active.');
    }

    /**
     * Admin Override to Restore Disposed Entity
     */
    public function restore(Request $request, MobileDevice $mobileDevice)
    {
        $request->validate(['password' => 'required']);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Restoration failed.');
        }

        DB::transaction(function () use ($mobileDevice, $request) {
            $mobileDevice->update([
                'status' => 'available',
                'employee_id' => null
            ]);

            $this->historyService->log($mobileDevice, 'restored', 'Restored by Admin Override. Reason: ' . ($request->reason ?? 'Admin Error Correction'));
        });

        return redirect()->route('mobile-devices.show', $mobileDevice)
            ->with('success', 'Mobile Device successfully restored from disposal.');
    }
}
