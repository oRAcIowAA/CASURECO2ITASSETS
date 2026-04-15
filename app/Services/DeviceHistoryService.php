<?php

namespace App\Services;

use App\Models\PcHistory;
use App\Models\PrinterHistory;
use App\Models\NetworkDeviceHistory;
use App\Models\PowerUtilityHistory;
use App\Models\MobileDeviceHistory;
use App\Models\EmployeeHistory;
use App\Models\Employee;
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
        elseif ($device instanceof \App\Models\PowerUtility) {
            $historyModel = PowerUtilityHistory::class;
            $foreignKey = 'power_utility_id';
        }
        elseif ($device instanceof \App\Models\MobileDevice) {
            $historyModel = MobileDeviceHistory::class;
            $foreignKey = 'mobile_device_id';
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
            'created_by' => Auth::id() ?? 1, // Fallback to 1 (usually admin) if no auth
        ]);
    }

    /**
     * Log a history record for an employee.
     */
    public function logEmployeeAction(Employee $employee, string $action, ?string $notes = null)
    {
        return EmployeeHistory::create([
            'employee_id' => $employee->id,
            'action' => $action,
            'notes' => $notes,
            'created_by' => Auth::id() ?? 1,
        ]);
    }

    /**
     * Generate a summary of changes for a model.
     *
     * @param Model $model
     * @return string|null
     */
    public function generateChangesSummary(Model $model)
    {
        if (!$model->isDirty()) {
            return null;
        }

        $changes = [];
        $dirty = $model->getDirty();
        
        // Skip timestamp fields and internal fields
        $skippedFields = ['updated_at', 'created_at', 'updated_by'];

        foreach ($dirty as $field => $newValue) {
            if (in_array($field, $skippedFields)) continue;

            $oldValue = $model->getOriginal($field);
            
            $fieldName = strtoupper(str_replace('_', ' ', $field));
            $oldDisplay = $oldValue ?: 'N/A';
            $newDisplay = $newValue ?: 'N/A';

            $changes[] = "{$fieldName}: [{$oldDisplay} -> {$newDisplay}]";
        }

        return !empty($changes) ? "EDITED RECORD DETAILS: " . implode(", ", $changes) : null;
    }
}
