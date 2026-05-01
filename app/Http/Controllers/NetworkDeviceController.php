<?php

namespace App\Http\Controllers;

use App\Models\NetworkDevice;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Services\DeviceHistoryService;
use App\Http\Requests\StoreNetworkDeviceRequest;
use App\Http\Requests\UpdateNetworkDeviceRequest;
use App\Http\Requests\TransferDeviceRequest;
use App\Http\Requests\CondemnDeviceRequest;

class NetworkDeviceController extends Controller
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
        $query = NetworkDevice::query()->with('employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('device_type', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('division', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($sq) use ($search) {
                        $sq->where('fname', 'like', "%{$search}%")
                           ->orWhere('lname', 'like', "%{$search}%")
                           ->orWhere('emp_id', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('type') && in_array($request->type, ['router', 'switch'])) {
            $query->where('device_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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

        $networkDevices = $query->latest()->paginate(15)->withQueryString();
        $groups = \App\Constants\Organization::LOCATIONS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $deptDivisions = \App\Constants\Organization::DEPT_DIVISIONS;

        return view('network-devices.index', compact('networkDevices', 'groups', 'divisions', 'departments', 'deptDivisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = \App\Constants\Organization::LOCATIONS;
        $employees = Employee::orderBy('lname')->orderBy('fname')->get();
        $nextAssetTag = \App\Services\AssetTagService::generateNextTag(NetworkDevice::class, 'CAS-ND-');
        return view('network-devices.create', compact('groups', 'employees', 'nextAssetTag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNetworkDeviceRequest $request)
    {
        $validated = $request->validated();

        // Logic formatting
        if ($request->device_type === 'switch') {
            if ($request->switch_type === 'unmanaged') {
                $validated['has_ip'] = false;
                $validated['ip_address'] = null;
            }
            elseif ($request->switch_type === 'managed') {
                $validated['has_ip'] = true; // Managed implies IP access
            }
        }

        // For routers, we trust the 'has_ip' input, but if has_ip is false, clear IP
        if (isset($validated['has_ip']) && !$validated['has_ip']) {
            $validated['ip_address'] = null;
        }

        // Ensure IP is present if has_ip is true
        if (isset($validated['has_ip']) && $validated['has_ip'] && empty($validated['ip_address'])) {
            if (empty($request->ip_address)) {
                return back()->withErrors(['ip_address' => 'IP Address is required when Yes is selected.'])->withInput();
            }
        }

        // Handle Assignment & Status
        if ($request->assignment_type === 'ASSIGN') {
            if (empty($validated['employee_id'])) {
                return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
            }
            $validated['status'] = 'Assigned';
            $validated['date_assigned'] = now();
        }
        else {
            $validated['employee_id'] = null;
            $validated['status'] = 'Available';
        }

        DB::transaction(function () use ($validated) {
            $divice = NetworkDevice::create($validated);

            if ($divice->employee_id) {
                $this->historyService->log($divice, 'assigned', 'Network device created and assigned');
            }
        });

        return redirect()->route('network-devices.index')
            ->with('success', 'Network device created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NetworkDevice $networkDevice)
    {
        $networkDevice->load(['updatedBy']);
        $history = $networkDevice->history()->with(['employee', 'previousEmployee', 'createdBy'])->latest()->get();
        return view('network-devices.show', compact('networkDevice', 'history'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NetworkDevice $networkDevice)
    {
        $groups = \App\Constants\Organization::LOCATIONS;
        $employees = Employee::orderBy('lname')->orderBy('fname')->get();
        return view('network-devices.edit', compact('networkDevice', 'groups', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNetworkDeviceRequest $request, NetworkDevice $networkDevice)
    {
        $validated = $request->validated();

        // Prevent modification of date_issued if already set
        if ($networkDevice->date_issued) {
            unset($validated['date_issued']);
        }

        // Logic formatting
        if ($request->device_type === 'switch') {
            if ($request->switch_type === 'unmanaged') {
                $validated['has_ip'] = false;
                $validated['ip_address'] = null;
            }
            elseif ($request->switch_type === 'managed') {
                $validated['has_ip'] = true;
            }
        }

        if (isset($validated['has_ip']) && !$validated['has_ip']) {
            $validated['ip_address'] = null;
        }

        if (isset($validated['has_ip']) && $validated['has_ip'] && empty($validated['ip_address'])) {
            if (empty($request->ip_address)) {
                return back()->withErrors(['ip_address' => 'IP Address is required when Yes is selected.'])->withInput();
            }
        }

        // Handle Assignment & Status Logic
        $specialStatuses = ['disposed', 'condemned', 'defective'];
        $currentStatus = strtolower($networkDevice->status);

        if (in_array($currentStatus, $specialStatuses)) {
            // Preserve status and unassigned state for special cases
            $validated['status'] = $networkDevice->status;
            $validated['employee_id'] = null;
        } else {
            if ($request->assignment_type === 'ASSIGN') {
                if (empty($validated['employee_id'])) {
                    return back()->withErrors(['employee_id' => 'Please select an employee.'])->withInput();
                }
                $validated['status'] = 'Assigned';
            }
            else {
                $validated['employee_id'] = null;
                $validated['status'] = 'Available';
            }
        }

        $oldEmployeeId = $networkDevice->employee_id;
        $newEmployeeId = $validated['employee_id'];

        if ($newEmployeeId && $oldEmployeeId !== $newEmployeeId) {
            $validated['date_assigned'] = now();
        }
        elseif ($oldEmployeeId && !$newEmployeeId) {
            $validated['date_returned'] = now();
            $validated['date_assigned'] = null;
        }

        $validated['updated_by'] = auth()->id();

        DB::transaction(function () use ($networkDevice, $validated, $oldEmployeeId, $newEmployeeId) {
            $networkDevice->fill($validated);
            $changeSummary = $this->historyService->generateChangesSummary($networkDevice);
            $networkDevice->save();

            if ($oldEmployeeId == $newEmployeeId) {
                // If assignment didn't change, log it as an 'edited' action with details
                if ($changeSummary) {
                    $this->historyService->log($networkDevice, 'edited', $changeSummary);
                }
            } else {
                if ($newEmployeeId) {
                    $action = $oldEmployeeId ? 'transferred' : 'assigned';
                    $notes = $oldEmployeeId ? 'Device transferred' : 'Device assigned';
                    
                    if ($changeSummary) {
                        $notes .= " | " . $changeSummary;
                    }
                    
                    $this->historyService->log($networkDevice, $action, $notes, $oldEmployeeId);
                }
                else {
                    $notes = 'Device returned/unassigned';
                    if ($changeSummary) {
                        $notes .= " | " . $changeSummary;
                    }
                    $this->historyService->log($networkDevice, 'returned', $notes, $oldEmployeeId);
                }
            }
        });

        return redirect()->route('network-devices.index')
            ->with('success', 'Network device updated successfully.');
    }

    /**
     * Print QR Code Label
     */
    public function printLabel(NetworkDevice $networkDevice)
    {
        $deviceName = $networkDevice->brand . ' ' . $networkDevice->model;
        $deviceType = 'Asset Tag/S.N.';
        $assetTag = $networkDevice->asset_tag ?? $networkDevice->serial_number ?? 'N/A';
        $publicUrl = $networkDevice->public_url;
        $dateIssued = $networkDevice->date_issued ? \Carbon\Carbon::parse($networkDevice->date_issued)->format('M d, Y') : 'N/A';

        return view('reports.qr-sticker', compact('networkDevice', 'deviceName', 'deviceType', 'assetTag', 'publicUrl', 'dateIssued'));
    }


    /**
     * Return Network Device from employee
     */
    public function returnDevice(Request $request, NetworkDevice $networkDevice)
    {
        if (!$networkDevice->employee_id) {
            return redirect()->route('network-devices.show', $networkDevice)
                ->with('error', 'This device is not assigned to anyone.');
        }

        $oldEmployeeId = $networkDevice->employee_id;

        DB::transaction(function () use ($networkDevice, $oldEmployeeId) {
            $networkDevice->update([
                'employee_id' => null,
                'status' => 'Available',
                'date_returned' => now(),
                'date_assigned' => null,
            ]);

            $this->historyService->log($networkDevice, 'returned', 'Device returned', $oldEmployeeId);
        });

        return redirect()->route('network-devices.show', $networkDevice)
            ->with('success', 'Network device returned successfully!');
    }

    /**
     * Show transfer form
     */
    public function transfer(NetworkDevice $networkDevice)
    {
        $employees = Employee::orderBy('full_name')->get();
        return view('network-devices.transfer', compact('networkDevice', 'employees'));
    }

    /**
     * Process transfer/reassignment
     */
    public function reassign(TransferDeviceRequest $request, NetworkDevice $networkDevice)
    {
        // Validation handled by TransferDeviceRequest

        $oldEmployeeId = $networkDevice->employee_id;

        DB::transaction(function () use ($networkDevice, $request, $oldEmployeeId) {
            $networkDevice->update([
                'employee_id' => $request->employee_id,
                'status' => 'Assigned',
                'date_assigned' => now(),
            ]);

            $action = $oldEmployeeId ? 'Transferred' : 'Assigned';
            $this->historyService->log($networkDevice, $action, $request->notes ?? $request->remarks ?? 'Network device assigned', $oldEmployeeId);
        });

        return redirect()->route('network-devices.show', $networkDevice)
            ->with('success', 'Network device transferred successfully!');
    }

    /**
     * Show disposal form
     */
    public function dispose(NetworkDevice $networkDevice)
    {
        return view('network-devices.dispose', compact('networkDevice'));
    }

    /**
     * Process disposal/condemnation
     */
    public function condemn(CondemnDeviceRequest $request, NetworkDevice $networkDevice)
    {
        // Validation handled by CondemnDeviceRequest

        $oldEmployeeId = $networkDevice->employee_id;

        DB::transaction(function () use ($networkDevice, $request, $oldEmployeeId) {
            $status = $request->status;
            $shouldUnassign = in_array(strtolower($status), ['condemned', 'disposed']);

            $networkDevice->update([
                'status' => $status,
                'employee_id' => $shouldUnassign ? null : $oldEmployeeId,
                'spare_parts' => $request->spare_parts
            ]);

            $this->historyService->log($networkDevice, $request->status, $request->remarks, $oldEmployeeId);
        });

        if ($request->status === 'Disposed') {
            return redirect()->route('network-devices.show', $networkDevice)
                ->with('success', 'Network Device marked as Disposed and permanently archived.')
                ->with('print_disposal', true);
        }

        return redirect()->route('network-devices.show', $networkDevice)
            ->with('success', 'Network device marked as ' . $request->status);
    }

    /**
     * Generate MR PDF
     */
    public function printMr(NetworkDevice $networkDevice)
    {
        if (!$networkDevice->employee) {
            return back()->with('error', 'Cannot generate MR for unassigned device.');
        }

        try {
            // Reusing report view, passing networkDevice as variable but checking view compatibility
            // Since report views often expect specific variable names, we might need to adjust the view or pass partial data
            $pdf = Pdf::loadView('reports.mr-network', compact('networkDevice'));
            return $pdf->stream('MR-' . ($networkDevice->asset_tag ?? $networkDevice->serial_number ?? 'ND') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate Waste Material Report (condemned devices)
     */
    public function printCondemn(NetworkDevice $networkDevice)
    {
        if (!in_array($networkDevice->status, ['Condemned', 'Defective'])) {
            return back()->with('error', 'Device is not marked as condemned or defective.');
        }

        try {
            $pdf = Pdf::loadView('reports.condemn-network', compact('networkDevice'));
            return $pdf->stream('Waste-Report-' . ($networkDevice->asset_tag ?? $networkDevice->serial_number ?? 'ND') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Process repair
     */
    public function repair(Request $request, NetworkDevice $networkDevice)
    {
        if (strtolower($networkDevice->status) !== 'defective') {
            return back()->with('error', 'Only defective Network Devices can be repaired.');
        }

        DB::transaction(function () use ($networkDevice) {
            // Restore previous employee if exists from history
            $lastAssignment = $networkDevice->history()
                ->whereIn('action', ['assigned', 'transferred', 'returned', 'defective'])
                ->whereNotNull('employee_id')
                ->latest()
                ->first();

            $status = $lastAssignment ? 'Assigned' : 'Available';
            $employeeId = $lastAssignment ? $lastAssignment->employee_id : null;

            $networkDevice->update([
                'status' => $status,
                'employee_id' => $employeeId,
                'date_assigned' => $lastAssignment ? now() : null
            ]);

            $this->historyService->log($networkDevice, 'repaired', 'Network Device repaired and restored to ' . ($lastAssignment && $lastAssignment->employee ? $lastAssignment->employee->full_name : 'Available'));
        });

        return redirect()->route('network-devices.show', $networkDevice)
            ->with('success', 'Network Device marked as Repaired and is now Available.');
    }

    /**
     * Generate Certificate of Disposal PDF
     */
    public function printDisposal(NetworkDevice $networkDevice)
    {
        try {
            $device = $networkDevice;
            $deviceTypeLabel = 'Network Device';
            $pdf = Pdf::loadView('reports.dispose-device', compact('device', 'deviceTypeLabel'));
            return $pdf->stream('Certificate-of-Disposal-' . ($networkDevice->asset_tag ?? $networkDevice->serial_number ?? 'ND') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Admin Override to Restore Disposed Entity
     */
    public function restore(Request $request, NetworkDevice $networkDevice)
    {
        $request->validate(['password' => 'required']);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Restoration failed.');
        }

        DB::transaction(function () use ($networkDevice, $request) {
            $networkDevice->update([
                'status' => 'Available',
            ]);

            $this->historyService->log($networkDevice, 'restored', 'Restored by Admin Override. Reason: ' . ($request->reason ?? 'Admin Error Correction'));
        });

        return redirect()->route('network-devices.show', $networkDevice)
            ->with('success', 'Network Device successfully restored from disposal.');
    }
}
