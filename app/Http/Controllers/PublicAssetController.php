<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PcUnit;
use App\Models\Printer;
use App\Models\NetworkDevice;

class PublicAssetController extends Controller
{
    /**
     * Display a public, read-only view of an asset by its tracking UUID.
     */
    public function show($uuid)
    {
        // Try to find the device across the three tables
        $device = null;
        $deviceType = '';

        if ($pcUnit = PcUnit::where('tracking_uuid', $uuid)->first()) {
            $device = $pcUnit;
            $deviceType = 'PC Unit';
        }
        elseif ($printer = Printer::where('tracking_uuid', $uuid)->first()) {
            $device = $printer;
            $deviceType = 'Printer';
        }
        elseif ($networkDevice = NetworkDevice::where('tracking_uuid', $uuid)->first()) {
            $device = $networkDevice;
            $deviceType = 'Network Device';
        }
        else {
            abort(404, 'Asset not found or invalid QR code.');
        }

        // Load current employee and the latest 5 history logs
        $device->load(['employee', 'history' => function ($query) {
            $query->with('employee')->latest()->take(5);
        }]);

        return view('public.asset-show', compact('device', 'deviceType'));
    }
}
