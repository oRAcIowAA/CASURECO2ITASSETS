<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use App\Models\Printer;
use App\Models\NetworkDevice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class QrAssetController extends Controller
{
    public function index(Request $request)
    {
        $pcQuery = PcUnit::query();
        $printerQuery = Printer::query();
        $networkQuery = NetworkDevice::query();

        // Apply filters
        $applyCommonFilters = function($query) use ($request) {
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
                $query->where('status', strtolower(str_replace(' ', '_', $request->status)));
            }
        };

        if ($request->filled('search')) {
            $search = $request->search;
            
            $pcQuery->whereAny(['asset_tag', 'model', 'device_type'], 'like', "%{$search}%");

            $printerQuery->whereAny(['asset_tag', 'model', 'brand'], 'like', "%{$search}%");

            $networkQuery->whereAny(['asset_tag', 'model', 'brand', 'device_type'], 'like', "%{$search}%");
        }

        $applyCommonFilters($pcQuery);
        $applyCommonFilters($printerQuery);
        $applyCommonFilters($networkQuery);

        $pcUnits = $pcQuery->orderBy('asset_tag')->get();
        $printers = $printerQuery->orderBy('asset_tag')->get();
        $networkDevices = $networkQuery->orderBy('asset_tag')->get();

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;

        return view('qr-assets.index', compact('pcUnits', 'printers', 'networkDevices', 'groups', 'divisions', 'departments'));
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
                    $deviceName = 'Printer (' . $asset->brand . ')';
                    break;
                case 'network':
                    $asset = NetworkDevice::find($id);
                    $deviceName = $asset->device_type ?? 'Network Device';
                    break;
            }

            if ($asset) {
                $assets[] = [
                    'id' => $id,
                    'type' => $type,
                    'deviceName' => $deviceName,
                    'deviceType' => $deviceTypeLabel,
                    'assetTag' => $asset->asset_tag,
                    'publicUrl' => $asset->public_url
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
                    $deviceName = 'Printer (' . $asset->brand . ')';
                    break;
                case 'network':
                    $asset = NetworkDevice::find($id);
                    $deviceName = $asset->device_type ?? 'Network Device';
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
                    'qrBase64' => $qrBase64
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
