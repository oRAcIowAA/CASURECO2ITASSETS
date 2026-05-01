<?php

namespace App\Http\Controllers;

use App\Models\MobileDeviceHistory;
use App\Models\MobileDevice;
use Illuminate\Http\Request;

class MobileDeviceHistoryController extends Controller
{
    /**
     * Display Mobile Device assignment history
     */
    public function index(Request $request)
    {
        $query = MobileDeviceHistory::query();

        // Filter by generic search (Asset Tag, Type, Brand/Model, Employee Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('mobileDevice', function ($q2) use ($search) {
                        $q2->where('asset_tag', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('brand', 'like', "%{$search}%")
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

        $history = $query->with(['mobileDevice', 'employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('mobile-device-history.index', compact('history'));
    }

    /**
     * Show history for specific Mobile Device
     */
    public function showByDevice($deviceId)
    {
        $mobileDevice = MobileDevice::findOrFail($deviceId);
        $history = MobileDeviceHistory::where('mobile_device_id', $deviceId)
            ->with(['employee', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mobile-device-history.show', compact('mobileDevice', 'history'));
    }
}
