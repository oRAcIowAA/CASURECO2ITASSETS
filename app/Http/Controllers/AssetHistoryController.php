<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\PcHistory;
use App\Models\PrinterHistory;
use App\Models\NetworkDeviceHistory;

class AssetHistoryController extends Controller
{
    /**
     * Display a unified activity log for all assets.
     */
    public function index(Request $request)
    {
        $pcHistory = PcHistory::with(['pcUnit', 'employee', 'previousEmployee', 'createdBy'])->get();
        $printerHistory = PrinterHistory::with(['printer', 'employee', 'previousEmployee', 'createdBy'])->get();
        $networkHistory = NetworkDeviceHistory::with(['networkDevice', 'employee', 'previousEmployee', 'createdBy'])->get();

        $merged = $pcHistory->map(function($h) {
            return (object)[
                'id' => $h->id,
                'created_at' => $h->created_at,
                'asset_tag' => $h->pcUnit->asset_tag ?? 'N/A',
                'device_type' => 'PC Unit',
                'device_link' => $h->pc_unit_id ? route('pc-units.show', $h->pc_unit_id) : '#',
                'action' => $h->action,
                'employee_name' => $h->employee->full_name ?? 'N/A',
                'previous_employee_name' => $h->previousEmployee->full_name ?? 'N/A',
                'notes' => $h->notes,
                'recorded_by' => $h->createdBy->name ?? 'N/A'
            ];
        })->concat($printerHistory->map(function($h) {
            return (object)[
                'id' => $h->id,
                'created_at' => $h->created_at,
                'asset_tag' => $h->printer->asset_tag ?? 'N/A',
                'device_type' => 'Printer',
                'device_link' => $h->printer_id ? route('printers.show', $h->printer_id) : '#',
                'action' => $h->action,
                'employee_name' => $h->employee->full_name ?? 'N/A',
                'previous_employee_name' => $h->previousEmployee->full_name ?? 'N/A',
                'notes' => $h->notes,
                'recorded_by' => $h->createdBy->name ?? 'N/A'
            ];
        }))->concat($networkHistory->map(function($h) {
            return (object)[
                'id' => $h->id,
                'created_at' => $h->created_at,
                'asset_tag' => $h->networkDevice->asset_tag ?? 'N/A',
                'device_type' => 'Networking',
                'device_link' => $h->network_device_id ? route('network-devices.show', $h->network_device_id) : '#',
                'action' => $h->action,
                'employee_name' => $h->employee->full_name ?? 'N/A',
                'previous_employee_name' => $h->previousEmployee->full_name ?? 'N/A',
                'notes' => $h->notes,
                'recorded_by' => $h->createdBy->name ?? 'N/A'
            ];
        }))->sortByDesc('created_at');

        // Search filtering if provided
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $merged = $merged->filter(function($h) use ($search) {
                return str_contains(strtolower($h->asset_tag), $search) || 
                       str_contains(strtolower($h->employee_name), $search) ||
                       str_contains(strtolower($h->device_type), $search) ||
                       str_contains(strtolower($h->action), $search);
            });
        }

        // Action filtering
        if ($request->filled('action')) {
            $action = strtolower($request->action);
            $merged = $merged->filter(function($h) use ($action) {
                return strtolower($h->action) === $action;
            });
        }

        $perPage = 10;
        $page = $request->get('page', 1);
        $history = new LengthAwarePaginator(
            $merged->forPage($page, $perPage),
            $merged->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('activities.index', compact('history'));
    }
}
