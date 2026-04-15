<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use App\Models\Printer;
use App\Models\NetworkDevice;
use App\Models\PowerUtility;
use App\Models\MobileDevice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class QrAssetController extends Controller
{
    public function index(Request $request)
    {
        $pcQuery = PcUnit::query();
        $printerQuery = Printer::query();
        $networkQuery = NetworkDevice::query();
        $powerQuery = PowerUtility::query();
        $mobileQuery = MobileDevice::query();

        // Apply filters
        $applyCommonFilters = function($query) use ($request) {
            if ($request->filled('group')) {
                $query->where(function ($q) use ($request) {
                    $q->where(function ($sub) use ($request) {
                        $sub->whereNull('employee_id')->where('group', $request->group);
                    })->orWhereHas('employee', function ($eq) use ($request) {
                        $eq->where('group', $request->group);
                    });
                });
            }

            if ($request->filled('division')) {
                $query->where(function ($q) use ($request) {
                    $q->where(function ($sub) use ($request) {
                        $sub->whereNull('employee_id')->where('division', $request->division);
                    })->orWhereHas('employee', function ($eq) use ($request) {
                        $eq->where('division', $request->division);
                    });
                });
            }

            if ($request->filled('department')) {
                $query->where(function ($q) use ($request) {
                    $q->where(function ($sub) use ($request) {
                        $sub->whereNull('employee_id')->where('department', $request->department);
                    })->orWhereHas('employee', function ($eq) use ($request) {
                        $eq->where('department', $request->department);
                    });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', strtolower(str_replace(' ', '_', $request->status)));
            }
        };

        if ($request->filled('search')) {
            $search = $request->search;
            
            $pcQuery->where(function ($q) use ($search) {
                $q->whereAny(['asset_tag', 'model', 'device_type', 'ip_address'], 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%");
                  });
            });

            $printerQuery->where(function ($q) use ($search) {
                $q->whereAny(['asset_tag', 'model', 'brand', 'ip_address'], 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%");
                  });
            });

            $networkQuery->where(function ($q) use ($search) {
                $q->whereAny(['asset_tag', 'model', 'brand', 'device_type', 'ip_address'], 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%");
                  });
            });

            $powerQuery->where(function ($q) use ($search) {
                $q->whereAny(['asset_tag', 'model', 'brand', 'type'], 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%");
                  });
            });

            $mobileQuery->where(function ($q) use ($search) {
                $q->whereAny(['asset_tag', 'model', 'brand', 'type', 'serial_number'], 'like', "%{$search}%")
                  ->orWhereHas('employee', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('type')) {
            $types = (array)$request->type;
            
            // Filter PC Units
            $pcTypes = array_intersect($types, ['Desktop', 'Laptop', 'Server', 'All-in-One']);
            if (empty($pcTypes)) {
                $pcQuery->whereRaw('1 = 0');
            } else {
                $pcQuery->whereIn('device_type', $pcTypes);
            }

            // Filter Printers
            $validPrinterTypes = ['Printer', 'Scanner', 'Portable Printer'];
            $selectedPrinterTypes = array_intersect($types, $validPrinterTypes);
            if (empty($selectedPrinterTypes)) {
                $printerQuery->whereRaw('1 = 0');
            } else {
                $printerQuery->whereIn('type', array_map('strtoupper', $selectedPrinterTypes));
            }

            // Filter Network Devices
            $netTypes = array_intersect($types, ['Router', 'Switch']);
            if (empty($netTypes)) {
                $networkQuery->whereRaw('1 = 0');
            } else {
                $networkQuery->whereIn('device_type', array_map('strtolower', $netTypes));
            }

            // Filter Power Utilities
            $powerTypes = array_intersect($types, ['UPS', 'AVR']);
            if (empty($powerTypes)) {
                $powerQuery->whereRaw('1 = 0');
            } else {
                $powerQuery->whereIn('type', $powerTypes);
            }

            // Filter Mobile Devices
            $mobileTypes = array_intersect($types, ['Cellphone']);
            if (empty($mobileTypes)) {
                $mobileQuery->whereRaw('1 = 0');
            } else {
                $mobileQuery->whereIn('type', array_map('strtoupper', $mobileTypes));
            }
        }

        $applyCommonFilters($pcQuery);
        $applyCommonFilters($printerQuery);
        $applyCommonFilters($networkQuery);
        $applyCommonFilters($powerQuery);
        $applyCommonFilters($mobileQuery);

        $pcUnits = $pcQuery->with('employee')->orderBy('asset_tag')->get();
        $printers = $printerQuery->with('employee')->orderBy('asset_tag')->get();
        $networkDevices = $networkQuery->with('employee')->orderBy('asset_tag')->get();
        $powerUtilities = $powerQuery->with('employee')->orderBy('asset_tag')->get();
        $mobileDevices = $mobileQuery->with('employee')->orderBy('asset_tag')->get();

        $groups = \App\Constants\Organization::LOCATIONS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;
        $deptDivisions = \App\Constants\Organization::DEPT_DIVISIONS;

        return view('qr-assets.index', compact('pcUnits', 'printers', 'networkDevices', 'powerUtilities', 'mobileDevices', 'groups', 'divisions', 'departments', 'deptDivisions'));
    }

    public function printLabels(Request $request)
    {
        $selectedItems = $request->input('selected_assets', []);
        
        if (empty($selectedItems)) {
            return back()->with('error', 'Please select at least one asset to print.');
        }

        $assets = [];

        foreach ($selectedItems as $item) {
            list($type, $id) = explode(':', $item);
            
            $asset = null;
            $deviceName = '';
            $deviceTypeLabel = 'Asset Tag';

            switch ($type) {
                case 'pc':
                    $asset = PcUnit::find($id);
                    $deviceName = $asset->device_type ?? 'PC Unit';
                    break;
                case 'printer':
                    $asset = Printer::find($id);
                    $deviceName = ucfirst(strtolower($asset->type)) . ' (' . $asset->brand . ')';
                    break;
                case 'network':
                    $asset = NetworkDevice::find($id);
                    $deviceName = $asset->device_type ?? 'Network Device';
                    break;
                case 'power_utility':
                    $asset = PowerUtility::find($id);
                    $deviceName = $asset->type . ' (' . $asset->brand . ')';
                    break;
                case 'mobile_device':
                    $asset = MobileDevice::find($id);
                    $deviceName = $asset->type . ' (' . $asset->brand . ')';
                    break;
            }

            if ($asset) {
                $assets[] = [
                    'id' => $id,
                    'type' => $type,
                    'deviceName' => $deviceName,
                    'deviceType' => $deviceTypeLabel,
                    'assetTag' => $asset->asset_tag,
                    'publicUrl' => $asset->public_url,
                    'dateAssigned' => $asset->date_assigned ? \Carbon\Carbon::parse($asset->date_assigned)->format('M d, Y') : 'N/A'
                ];
            }
        }

        return view('qr-assets.print', compact('assets'));
    }

    public function downloadLabels(Request $request)
    {
        $selectedItems = $request->input('selected_assets', []);
        
        if (empty($selectedItems)) {
            return back()->with('error', 'Please select at least one asset to download.');
        }

        $assets = [];

        foreach ($selectedItems as $item) {
            list($type, $id) = explode(':', $item);
            
            $asset = null;
            $deviceName = '';
            $deviceTypeLabel = 'Asset Tag';

            switch ($type) {
                case 'pc':
                    $asset = PcUnit::find($id);
                    $deviceName = $asset->device_type ?? 'PC Unit';
                    break;
                case 'printer':
                    $asset = Printer::find($id);
                    $deviceName = ucfirst(strtolower($asset->type)) . ' (' . $asset->brand . ')';
                    break;
                case 'network':
                    $asset = NetworkDevice::find($id);
                    $deviceName = $asset->device_type ?? 'Network Device';
                    break;
                case 'power_utility':
                    $asset = \App\Models\PowerUtility::find($id);
                    $deviceName = $asset->type . ' (' . $asset->brand . ')';
                    break;
                case 'mobile_device':
                    $asset = \App\Models\MobileDevice::find($id);
                    $deviceName = $asset->type . ' (' . $asset->brand . ')';
                    break;
            }

            if ($asset) {
                // Fetch QR code via curl for better reliability in PDF (margin=0 for better fit)
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=0&data=" . urlencode($asset->asset_tag);
                $qrBase64 = '';
                
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $qrUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    $qrData = curl_exec($ch);
                    curl_close($ch);
                    
                    if ($qrData) {
                        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrData);
                    }
                } catch (\Exception $e) {
                    $qrBase64 = $qrUrl;
                }

                $assets[] = [
                    'deviceName' => $deviceName,
                    'deviceType' => $deviceTypeLabel,
                    'assetTag' => $asset->asset_tag,
                    'qrBase64' => $qrBase64,
                    'dateAssigned' => $asset->date_assigned ? \Carbon\Carbon::parse($asset->date_assigned)->format('M d, Y') : 'N/A'
                ];
            }
        }

        $pdf = Pdf::loadView('qr-assets.pdf', compact('assets'))
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ]);

        return $pdf->stream('Asset-QR-Labels.pdf');
    }
}
