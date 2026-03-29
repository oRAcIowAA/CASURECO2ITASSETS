<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use App\Models\Printer;
use App\Models\NetworkDevice;
use Illuminate\Http\Request;

class PartsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pcUnits = PcUnit::where('status', 'Disposed')
            ->whereNotNull('spare_parts')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_tag', 'like', "%{$search}%")
                      ->orWhere('device_type', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%")
                      ->orWhere('spare_parts', 'like', "%{$search}%");
                });
            })
            ->get();

        $printers = Printer::where('status', 'Disposed')
            ->whereNotNull('spare_parts')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_tag', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%")
                      ->orWhere('spare_parts', 'like', "%{$search}%");
                });
            })
            ->get();

        $networkDevices = NetworkDevice::where('status', 'Disposed')
            ->whereNotNull('spare_parts')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('asset_tag', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%")
                      ->orWhere('spare_parts', 'like', "%{$search}%");
                });
            })
            ->get();

        return view('parts.index', compact('pcUnits', 'printers', 'networkDevices', 'search'));
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'spare_parts' => 'required|string',
        ]);

        $modelMap = [
            'pc-unit' => PcUnit::class,
            'printer' => Printer::class,
            'network-device' => NetworkDevice::class,
        ];

        if (!isset($modelMap[$type])) {
            return back()->with('error', 'Invalid device type.');
        }

        $device = $modelMap[$type]::findOrFail($id);
        $device->update(['spare_parts' => $request->spare_parts]);

        return back()->with('success', 'Spare parts updated successfully.');
    }
}
