<?php

namespace App\Http\Controllers;

use App\Models\NetworkDeviceHistory;
use App\Models\NetworkDevice;
use Illuminate\Http\Request;

class NetworkDeviceHistoryController extends Controller
{
    /**
     * Display Network Device assignment history
     */
    public function index(Request $request)
    {
        $query = NetworkDeviceHistory::query();

        // Filter by generic search (Network Device Brand/Model, Employee Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('networkDevice', function ($q2) use ($search) {
                        $q2->where('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");
                    }
                    )->orWhereHas('employee', function ($q3) use ($search) {
                        $q3->where('fname', 'like', "%{$search}%")
                           ->orWhere('lname', 'like', "%{$search}%")
                           ->orWhere('emp_id', 'like', "%{$search}%");
                    }
                    );
                });
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $history = $query->with(['networkDevice', 'employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('network-device-history.index', compact('history'));
    }

    /**
     * Show history for specific Network Device
     */
    public function showByDevice($networkDeviceId)
    {
        $networkDevice = NetworkDevice::findOrFail($networkDeviceId);
        $history = NetworkDeviceHistory::where('network_device_id', $networkDeviceId)
            ->with(['employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('network-device-history.show', compact('networkDevice', 'history'));
    }
}
