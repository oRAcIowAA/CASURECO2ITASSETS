<?php

namespace App\Services;

use App\Models\PcHistory;
use App\Models\PrinterHistory;
use App\Models\NetworkDeviceHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class DeviceHistoryService
{
    /**
     * Log a history record for a device (PC Unit, Printer, or Network Device).
     *
     * @param Model $device The device model instance.
     * @param string $action The action performed (e.g., 'assigned', 'returned', 'transferred', 'condemned').
     * @param string|null $notes Optional notes or remarks.
     * @param int|null $previousEmployeeId The ID of the previous employee (for transfers/returns).
     * @return Model The created history record.
     */
    public function log(Model $device, string $action, ?string $notes = null, ?int $previousEmployeeId = null)
    {
        // Determine the history model and foreign key based on the device type
        $historyModel = null;
        $foreignKey = null;

        if ($device instanceof \App\Models\PcUnit) {
            $historyModel = PcHistory::class;
            $foreignKey = 'pc_unit_id';
        }
        elseif ($device instanceof \App\Models\Printer) {
            $historyModel = PrinterHistory::class;
            $foreignKey = 'printer_id';
        }
        elseif ($device instanceof \App\Models\NetworkDevice) {
            $historyModel = NetworkDeviceHistory::class;
            $foreignKey = 'network_device_id';
        }
        else {
            throw new \InvalidArgumentException("Unsupported device type for history logging.");
        }

        // Determine dates based on action
        $assignedDate = in_array($action, ['assigned', 'transferred']) ? now() : null;
        $returnedDate = in_array($action, ['returned', 'condemned', 'defective', 'disposed']) ? now() : null;

        return $historyModel::create([
            $foreignKey => $device->id,
            'employee_id' => $device->employee_id, // Current employee (null if returned/condemned)
            'previous_employee_id' => $previousEmployeeId,
            'assigned_date' => $assignedDate,
            'returned_date' => $returnedDate,
            'action' => $action,
            'notes' => $notes,
            'created_by' => Auth::id() ?? 1, // Fallback to 1 (usually admin) if no auth, though auth should be present
        ]);
    }
}
