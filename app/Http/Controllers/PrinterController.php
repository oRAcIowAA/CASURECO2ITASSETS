<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Services\DeviceHistoryService;
use App\Http\Requests\StorePrinterRequest;
use App\Http\Requests\UpdatePrinterRequest;
use App\Http\Requests\TransferDeviceRequest;
use App\Http\Requests\CondemnDeviceRequest;

class PrinterController extends Controller
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
        $query = Printer::query()->with('employee');

        // Apply search
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('asset_tag', 'like', "%{$searchTerm}%")
                    ->orWhere('brand', 'like', "%{$searchTerm}%")
                    ->orWhere('model', 'like', "%{$searchTerm}%")
                    ->orWhere('ip_address', 'like', "%{$searchTerm}%")
                    ->orWhere('group', 'like', "%{$searchTerm}%")
                    ->orWhere('division', 'like', "%{$searchTerm}%")
                    ->orWhere('department', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $printers = $query->latest()->paginate(15)->withQueryString();

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;

        return view('printers.index', compact('printers', 'groups', 'divisions', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $employees = Employee::orderBy('full_name')->get();
        return view('printers.create', compact('groups', 'divisions', 'departments', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrinterRequest $request)
    {
        $validated = $request->validated();

        if ($validated['has_network_port'] && empty($validated['ip_address'])) {
            return back()->withErrors(['ip_address' => 'IP Address is required when Yes is selected.'])->withInput();
        }

        if (!$validated['has_network_port']) {
            $validated['ip_address'] = null;
        }

        // Handle Assignment & Status
        if ($request->assignment_type === 'assign') {
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
            $printer = Printer::create($validated);

            if ($printer->employee_id) {
                $this->historyService->log($printer, 'assigned', 'Printer created and assigned');
            }
        });

        return redirect()->route('printers.index')
            ->with('success', 'Printer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Printer $printer)
    {
        return view('printers.show', compact('printer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Printer $printer)
    {
        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $employees = Employee::orderBy('full_name')->get();
        return view('printers.edit', compact('printer', 'groups', 'divisions', 'departments', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrinterRequest $request, Printer $printer)
    {
        $validated = $request->validated();

        if ($validated['has_network_port'] && empty($validated['ip_address'])) {
            return back()->withErrors(['ip_address' => 'IP Address is required when Yes is selected.'])->withInput();
        }

        if (!$validated['has_network_port']) {
            $validated['ip_address'] = null;
        }

        // Handle Assignment & Status Logic
        $specialStatuses = ['disposed', 'condemned', 'defective'];
        $currentStatus = strtolower($printer->status);

        if (in_array($currentStatus, $specialStatuses)) {
            // Preserve status and unassigned state for special cases
            $validated['status'] = $printer->status;
            $validated['employee_id'] = null;
        } else {
            if ($request->assignment_type === 'assign') {
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

        $oldEmployeeId = $printer->employee_id;
        $newEmployeeId = $validated['employee_id'];

        if ($newEmployeeId && $oldEmployeeId !== $newEmployeeId) {
            $validated['date_assigned'] = now();
        }
        elseif ($oldEmployeeId && !$newEmployeeId) {
            $validated['date_returned'] = now();
            $validated['date_assigned'] = null;
        }

        DB::transaction(function () use ($printer, $validated, $oldEmployeeId, $newEmployeeId) {
            $printer->update($validated);

            if ($oldEmployeeId != $newEmployeeId) {
                if ($newEmployeeId) {
                    $action = $oldEmployeeId ? 'transferred' : 'assigned';
                    $notes = $oldEmployeeId ? 'Printer transferred' : 'Printer assigned';
                    $this->historyService->log($printer, $action, $notes, $oldEmployeeId);
                }
                else {
                    $this->historyService->log($printer, 'returned', 'Printer returned/unassigned', $oldEmployeeId);
                }
            }
        });

        return redirect()->route('printers.index')
            ->with('success', 'Printer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Printer $printer)
    {
        DB::transaction(function () use ($printer) {
            $printer->delete();
        });

        return redirect()->route('printers.index')
            ->with('success', 'Printer deleted successfully!');
    }

    /**
     * Print QR Code Label
     */
    public function printLabel(Printer $printer)
    {
        $deviceName = $printer->brand . ' ' . $printer->model;
        $deviceType = 'Asset Tag/S.N.';
        $assetTag = $printer->asset_tag ?? $printer->serial_number ?? 'N/A';
        $publicUrl = $printer->public_url;

        return view('reports.qr-sticker', compact('printer', 'deviceName', 'deviceType', 'assetTag', 'publicUrl'));
    }

    /**
     * Return Printer from employee
     */
    public function returnPrinter(Request $request, Printer $printer)
    {
        if (!$printer->employee_id) {
            return redirect()->route('printers.show', $printer)
                ->with('error', 'This printer is not assigned to anyone.');
        }

        $oldEmployeeId = $printer->employee_id;

        DB::transaction(function () use ($printer, $oldEmployeeId) {
            $printer->update([
                'employee_id' => null,
                'status' => 'Available',
                'date_returned' => now(),
                'date_assigned' => null,
            ]);

            $this->historyService->log($printer, 'returned', 'Printer returned', $oldEmployeeId);
        });

        return redirect()->route('printers.show', $printer)
            ->with('success', 'Printer returned successfully!');
    }

    /**
     * Show transfer form
     */
    public function transfer(Printer $printer)
    {
        $employees = Employee::orderBy('full_name')->get();
        return view('printers.transfer', compact('printer', 'employees'));
    }

    /**
     * Process transfer
     */
    public function reassign(TransferDeviceRequest $request, Printer $printer)
    {
        // Validation handled by TransferDeviceRequest

        $oldEmployeeId = $printer->employee_id;

        if ($oldEmployeeId == $request->employee_id) {
            return back()->with('error', 'Cannot transfer to the same employee.');
        }

        DB::transaction(function () use ($printer, $request, $oldEmployeeId) {
            $printer->update([
                'employee_id' => $request->employee_id,
                'status' => 'Assigned',
                'date_assigned' => now(),
            ]);

            $this->historyService->log($printer, 'transferred', $request->remarks ?? $request->notes ?? 'Transferred', $oldEmployeeId);
        });

        return redirect()->route('printers.show', $printer)
            ->with('success', 'Printer transferred successfully.');
    }

    /**
     * Show dispose form
     */
    public function dispose(Printer $printer)
    {
        return view('printers.dispose', compact('printer'));
    }

    /**
     * Process condemn/dispose
     */
    public function condemn(CondemnDeviceRequest $request, Printer $printer)
    {
        // Validation handled by CondemnDeviceRequest

        $oldEmployeeId = $printer->employee_id;

        DB::transaction(function () use ($printer, $request, $oldEmployeeId) {
            $status = $request->status;
            $shouldUnassign = in_array(strtolower($status), ['condemned', 'disposed']);

            $printer->update([
                'status' => $status,
                'employee_id' => $shouldUnassign ? null : $oldEmployeeId,
                'spare_parts' => $request->spare_parts
            ]);

            $this->historyService->log($printer, strtolower($request->status), $request->remarks, $oldEmployeeId);
        });

        if ($request->status === 'Disposed') {
            return redirect()->route('printers.show', $printer)
                ->with('success', 'Printer marked as Disposed and permanently archived.')
                ->with('print_disposal', true);
        }

        return redirect()->route('printers.show', $printer)
            ->with('success', 'Printer marked as ' . $request->status);
    }

    /**
     * Generate MR PDF
     */
    public function printMr(Printer $printer)
    {
        if (!$printer->employee_id) {
            return back()->with('error', 'Printer is not assigned to anyone.');
        }

        try {
            $pdf = Pdf::loadView('reports.mr-printer', compact('printer'));
            return $pdf->stream('MR-' . ($printer->asset_tag ?? $printer->serial_number ?? 'PR') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate Waste Material Report (condemned devices)
     */
    public function printCondemn(Printer $printer)
    {
        if (!in_array($printer->status, ['Condemned', 'Defective'])) {
            return back()->with('error', 'Printer is not marked as condemned or defective.');
        }

        try {
            $pdf = Pdf::loadView('reports.condemn-printer', compact('printer'));
            return $pdf->stream('Waste-Report-' . ($printer->asset_tag ?? $printer->serial_number ?? 'PR') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Process repair
     */
    public function repair(Request $request, Printer $printer)
    {
        if (strtolower($printer->status) !== 'defective') {
            return back()->with('error', 'Only defective printers can be repaired.');
        }

        DB::transaction(function () use ($printer) {
            // Restore previous employee if exists from history
            $lastAssignment = $printer->history()
                ->whereIn('action', ['assigned', 'transferred', 'returned', 'defective'])
                ->whereNotNull('employee_id')
                ->latest()
                ->first();

            $status = $lastAssignment ? 'Assigned' : 'Available';
            $employeeId = $lastAssignment ? $lastAssignment->employee_id : null;

            $printer->update([
                'status' => $status,
                'employee_id' => $employeeId,
                'date_assigned' => $lastAssignment ? now() : null
            ]);

            $this->historyService->log($printer, 'repaired', 'Printer repaired and restored to ' . ($lastAssignment && $lastAssignment->employee ? $lastAssignment->employee->full_name : 'Available'));
        });

        return redirect()->route('printers.show', $printer)
            ->with('success', 'Printer marked as Repaired and is now Available.');
    }

    /**
     * Generate Certificate of Disposal PDF
     */
    public function printDisposal(Printer $printer)
    {
        try {
            $device = $printer;
            $deviceTypeLabel = 'Printer';
            $pdf = Pdf::loadView('reports.dispose-device', compact('device', 'deviceTypeLabel'));
            return $pdf->stream('Certificate-of-Disposal-' . ($printer->asset_tag ?? $printer->serial_number ?? 'PR') . '.pdf');
        }
        catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Admin Override to Restore Disposed Entity
     */
    public function restore(Request $request, Printer $printer)
    {
        $request->validate(['password' => 'required']);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Restoration failed.');
        }

        DB::transaction(function () use ($printer, $request) {
            $printer->update([
                'status' => 'Available',
            ]);

            $this->historyService->log($printer, 'restored', 'Restored by Admin Override. Reason: ' . ($request->reason ?? 'Admin Error Correction'));
        });

        return redirect()->route('printers.show', $printer)
            ->with('success', 'Printer successfully restored from disposal.');
    }
}
