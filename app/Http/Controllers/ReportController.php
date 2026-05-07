<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
use App\Models\PowerUtility;
use App\Models\MobileDevice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display listing of reports.
     */
    public function index(Request $request)
    {
        $category = $request->input('category', 'All');
        $items = $this->getFilteredItems($request);

        // Calculate Statistics
        $stats = [
            'total' => $items->count(),
            'pc_units' => [
                'total' => $items->where('category', 'PC Unit')->count(),
                'Desktop' => $items->where('category', 'PC Unit')->where('type_label', 'Desktop')->count(),
                'Laptop' => $items->where('category', 'PC Unit')->where('type_label', 'Laptop')->count(),
                'Server' => $items->where('category', 'PC Unit')->where('type_label', 'Server')->count(),
                'All-in-One' => $items->where('category', 'PC Unit')->where('type_label', 'All-in-One')->count(),
            ],
            'network_devices' => [
                'total' => $items->where('category', 'Network Device')->count(),
                'Router' => $items->where('category', 'Network Device')->filter(function ($i) {
                    return strtolower($i->type_label) === 'router';
                })->count(),
                'Switch' => $items->where('category', 'Network Device')->filter(function ($i) {
                    return strtolower($i->type_label) === 'switch';
                })->count(),
            ],
            'printers' => [
                'total' => $items->where('category', 'Printer')->count(),
                'Printer' => $items->where('category', 'Printer')->where('type_label', 'Printer')->count(),
                'Scanner' => $items->where('category', 'Printer')->where('type_label', 'Scanner')->count(),
                'Portable Printer' => $items->where('category', 'Printer')->where('type_label', 'Portable Printer')->count(),
            ],
            'power_utilities' => [
                'total' => $items->where('category', 'Power Utility')->count(),
                'UPS' => $items->where('category', 'Power Utility')->where('type_label', 'UPS')->count(),
                'AVR' => $items->where('category', 'Power Utility')->where('type_label', 'AVR')->count(),
            ],
            'mobile_devices' => [
                'total' => $items->where('category', 'Mobile Device')->count(),
                'Cellphone' => $items->where('category', 'Mobile Device')->where('type_label', 'Cellphone')->count(),
            ]
        ];

        // Pagination for merged collection
        $page = $request->input('page', 1);
        $perPage = 20;
        $slicedItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $slicedItems,
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $groups = \Illuminate\Support\Facades\DB::table('locations')->pluck('name', 'id');
        $divisions = \Illuminate\Support\Facades\DB::table('divisions')->pluck('name', 'id');
        $departments = \Illuminate\Support\Facades\DB::table('departments')->pluck('name', 'id');
        
        $deptDivisions = [];
        $allDepartments = \Illuminate\Support\Facades\DB::table('departments')->get();
        foreach ($allDepartments as $dept) {
            $deptDivisions[$dept->id] = \Illuminate\Support\Facades\DB::table('divisions')
                ->where('department_id', $dept->id)
                ->pluck('name', 'id')
                ->toArray();
        }

        return view('reports.index', compact('paginatedItems', 'groups', 'divisions', 'departments', 'deptDivisions', 'stats'));
    }

    /**
     * Print the filtered list.
     */
    public function printList(Request $request)
    {
        $items = $this->getFilteredItems($request);

        $pdf = Pdf::loadView('reports.print-list', compact('items', 'request'))
            ->setPaper('letter', 'portrait');

        // Add headers to prevent IDM interception
        return $pdf->stream('Unified-Device-List.pdf');
    }

    /**
     * Helper to get and filter items from all sources
     */
    private function getFilteredItems(Request $request)
    {
        $category = $request->input('category', 'All');
        $collection = collect();

        // 1. PC Units
        if ($category === 'All' || $category === 'PC Units') {
            $query = PcUnit::with('employee');
            $this->applyFilters($query, $request, 'pc_unit');

            $pcUnits = $query->latest()->get()->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'asset_tag' => $item->asset_tag,
                    'view_route' => 'pc-units.show',
                    'ip_address' => $item->ip_address ?? 'N/A',
                    'type_model' => ($item->asset_tag ? $item->asset_tag . ' - ' : '') . $item->device_type . ' - ' . $item->model,
                    'type_label' => $item->device_type,
                    'location' => $item->location,
                    'department' => $item->department,
                    'division' => $item->division,
                    'assigned_to' => $item->employee->full_name ?? 'N/A',
                    'status' => $item->status,
                    'category' => 'PC Unit',
                    'original' => $item
                ];
            });
            $collection = $collection->merge($pcUnits);
        }

        // 2. Printers
        if ($category === 'All' || $category === 'Printers') {
            $query = \App\Models\Printer::with('employee');
            $this->applyFilters($query, $request, 'printer');

            $printers = $query->latest()->get()->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'asset_tag' => $item->asset_tag,
                    'view_route' => 'printers.show',
                    'ip_address' => $item->ip_address ?? 'N/A',
                    'type_model' => ($item->asset_tag ? $item->asset_tag . ' - ' : '') . ucfirst(strtolower($item->type)) . ' - ' . $item->brand . ' ' . $item->model,
                    'type_label' => ucfirst(strtolower($item->type)),
                    'location' => $item->location,
                    'department' => $item->department,
                    'division' => $item->division,
                    'assigned_to' => $item->employee->full_name ?? 'N/A',
                    'status' => $item->status,
                    'category' => 'Printer',
                    'original' => $item
                ];
            });
            $collection = $collection->merge($printers);
        }

        // 3. Network Devices
        if ($category === 'All' || $category === 'Network Devices') {
            $query = \App\Models\NetworkDevice::with('employee');
            $this->applyFilters($query, $request, 'network_device');

            $netDevices = $query->latest()->get()->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'asset_tag' => $item->asset_tag,
                    'view_route' => 'network-devices.show',
                    'ip_address' => $item->ip_address ?? 'N/A',
                    'type_model' => ($item->asset_tag ? $item->asset_tag . ' - ' : '') . ucfirst($item->device_type) . ' - ' . $item->brand . ' ' . $item->model,
                    'type_label' => ucfirst($item->device_type),
                    'location' => $item->location,
                    'department' => $item->department,
                    'division' => $item->division,
                    'assigned_to' => $item->employee->full_name ?? 'N/A',
                    'status' => $item->status,
                    'category' => 'Network Device',
                    'original' => $item
                ];
            });
            $collection = $collection->merge($netDevices);
        }
        
        // 4. Power Utilities
        if ($category === 'All' || $category === 'Power Utilities') {
            $query = PowerUtility::with('employee');
            $this->applyFilters($query, $request, 'power_utility');

            $powerUtilities = $query->latest()->get()->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'asset_tag' => $item->asset_tag,
                    'view_route' => 'power-utilities.show',
                    'ip_address' => 'N/A',
                    'type_model' => ($item->asset_tag ? $item->asset_tag . ' - ' : '') . $item->type . ' - ' . $item->brand . ' ' . $item->model,
                    'type_label' => $item->type,
                    'location' => $item->location,
                    'department' => $item->department,
                    'division' => $item->division,
                    'assigned_to' => $item->employee->full_name ?? 'N/A',
                    'status' => $item->status,
                    'category' => 'Power Utility',
                    'original' => $item
                ];
            });
            $collection = $collection->merge($powerUtilities);
        }
        
        // 5. Mobile Devices
        if ($category === 'All' || $category === 'Mobile Devices') {
            $query = MobileDevice::with('employee');
            $this->applyFilters($query, $request, 'mobile_device');

            $mobileDevices = $query->latest()->get()->map(function ($item) {
                return (object)[
                    'id' => $item->id,
                    'asset_tag' => $item->asset_tag,
                    'view_route' => 'mobile-devices.show',
                    'ip_address' => 'N/A',
                    'type_model' => ($item->asset_tag ? $item->asset_tag . ' - ' : '') . ucfirst(strtolower($item->type)) . ' - ' . $item->brand . ' ' . $item->model,
                    'type_label' => ucfirst(strtolower($item->type)),
                    'location' => $item->location,
                    'department' => $item->department,
                    'division' => $item->division,
                    'assigned_to' => $item->employee->full_name ?? 'N/A',
                    'status' => $item->status,
                    'category' => 'Mobile Device',
                    'original' => $item
                ];
            });
            $collection = $collection->merge($mobileDevices);
        }

        return $collection;
    }

    private function applyFilters($query, Request $request, $modelType)
    {
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $modelType) {
                // Common fields
                $q->where('model', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('employee', function ($sq) use ($search) {
                        $sq->where('fname', 'like', "%{$search}%")
                           ->orWhere('lname', 'like', "%{$search}%")
                           ->orWhere('emp_id', 'like', "%{$search}%");
                    });

                // Model specific
                if ($modelType === 'pc_unit') {
                    $q->orWhere('asset_tag', 'like', "%{$search}%")
                        ->orWhere('device_type', 'like', "%{$search}%");
                } elseif ($modelType === 'printer') {
                    $q->orWhere('brand', 'like', "%{$search}%");
                } elseif ($modelType === 'network_device') {
                    $q->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('device_type', 'like', "%{$search}%");
                } elseif ($modelType === 'power_utility') {
                    $q->orWhere('asset_tag', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                } elseif ($modelType === 'mobile_device') {
                    $q->orWhere('asset_tag', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%");
                }
            });
        }

        // Status
        if ($request->filled('status')) {
            $statuses = (array) $request->status;
            $statuses = array_filter($statuses, fn($s) => !empty($s) && $s !== 'All Statuses');
            
            if (!empty($statuses)) {
                $query->whereIn('status', array_map(fn($s) => strtolower(str_replace(' ', '_', $s)), $statuses));
            }
        }

        // Group
        if ($request->filled('group') && $request->group !== 'All Groups' && $request->group !== 'All Locations') {
            $query->where(function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->whereNull('employee_id')->where('location_id', $request->group);
                })->orWhereHas('employee', function ($eq) use ($request) {
                    $eq->where('location_id', $request->group);
                });
            });
        }

        // Division
        if ($request->filled('division') && $request->division !== 'All Divisions') {
            $query->where(function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->whereNull('employee_id')->where('division_id', $request->division);
                })->orWhereHas('employee', function ($eq) use ($request) {
                    $eq->where('division_id', $request->division);
                });
            });
        }

        // Department
        if ($request->filled('department') && $request->department !== 'All Departments') {
            $query->where(function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->whereNull('employee_id')->where('department_id', $request->department);
                })->orWhereHas('employee', function ($eq) use ($request) {
                    $eq->where('department_id', $request->department);
                });
            });
        }

        // Type
        $types = $request->input('type', []);
        if (!is_array($types)) {
            $types = explode(',', $types);
        }
        $types = array_filter($types, function ($t) {
            return !empty($t) && $t !== 'All Types' && $t !== 'All';
        });

        if (!empty($types)) {
            if ($modelType === 'pc_unit') {
                $validPcTypes = ['Desktop', 'Laptop', 'Server', 'All-in-One'];
                $selectedPcTypes = array_intersect($types, $validPcTypes);
                if (empty($selectedPcTypes)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $query->whereIn('device_type', $selectedPcTypes);
                }
            } elseif ($modelType === 'network_device') {
                $validNetTypes = ['Router', 'Switch'];
                $selectedNetTypes = array_intersect($types, $validNetTypes);
                if (empty($selectedNetTypes)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $selectedNetTypes = array_map('strtolower', $selectedNetTypes);
                    $query->whereIn('device_type', $selectedNetTypes);
                }
            } elseif ($modelType === 'printer') {
                $validPrinterTypes = ['Printer', 'Scanner', 'Portable Printer'];
                $selectedPrinterTypes = array_intersect($types, $validPrinterTypes);
                if (empty($selectedPrinterTypes)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $selectedPrinterTypes = array_map('strtoupper', $selectedPrinterTypes);
                    $query->whereIn('type', $selectedPrinterTypes);
                }
            } elseif ($modelType === 'power_utility') {
                $validPowerTypes = ['UPS', 'AVR'];
                $selectedPowerTypes = array_intersect($types, $validPowerTypes);
                if (empty($selectedPowerTypes)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $query->whereIn('type', $selectedPowerTypes);
                }
            } elseif ($modelType === 'mobile_device') {
                $validMobileTypes = ['Cellphone'];
                $selectedMobileTypes = array_intersect($types, $validMobileTypes);
                if (empty($selectedMobileTypes)) {
                    $query->whereRaw('1 = 0');
                } else {
                    $selectedMobileTypes = array_map('strtoupper', $selectedMobileTypes);
                    $query->whereIn('type', $selectedMobileTypes);
                }
            }
        }
    }

    /**
     * Generate Memorandum Receipt (MR) / Property Acknowledgment Receipt (PAR)
     */
    public function printMr(PcUnit $pcUnit)
    {
        if (!$pcUnit->employee) {
            return back()->with('error', 'Cannot generate MR for unassigned unit.');
        }

        try {
            $pdf = Pdf::loadView('reports.mr', compact('pcUnit'));
            return $pdf->stream('MR-' . $pcUnit->asset_tag . '.pdf');
        } catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return view('reports.mr', compact('pcUnit'));
        }
    }

    /**
     * Generate Waste Material Report (condemned units)
     */
    public function printCondemn(PcUnit $pcUnit)
    {
        if (!in_array($pcUnit->status, ['condemned', 'defective'])) {
            return back()->with('error', 'Unit is not marked as condemned or defective.');
        }

        try {
            $pdf = Pdf::loadView('reports.condemn', compact('pcUnit'));
            return $pdf->stream('Waste-Report-' . $pcUnit->asset_tag . '.pdf');
        } catch (\Throwable $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return view('reports.condemn', compact('pcUnit'));
        }
    }

    /**
     * Display the Department Assets Summary Report.
     */
    public function department(Request $request)
    {
        $category = $request->input('category', 'All');
        $items = $this->getFilteredItems($request);

        $stats = [
            'total' => $items->count(),
            'pc_units' => [
                'total' => $items->where('category', 'PC Unit')->count(),
                'Desktop' => $items->where('category', 'PC Unit')->where('type_label', 'Desktop')->count(),
                'Laptop' => $items->where('category', 'PC Unit')->where('type_label', 'Laptop')->count(),
                'Server' => $items->where('category', 'PC Unit')->where('type_label', 'Server')->count(),
                'All-in-One' => $items->where('category', 'PC Unit')->where('type_label', 'All-in-One')->count(),
            ],
            'network_devices' => [
                'total' => $items->where('category', 'Network Device')->count(),
                'Router' => $items->where('category', 'Network Device')->filter(function ($i) {
                    return strtolower($i->type_label) === 'router';
                })->count(),
                'Switch' => $items->where('category', 'Network Device')->filter(function ($i) {
                    return strtolower($i->type_label) === 'switch';
                })->count(),
            ],
            'printers' => [
                'total' => $items->where('category', 'Printer')->count(),
                'Printer' => $items->where('category', 'Printer')->where('type_label', 'Printer')->count(),
                'Scanner' => $items->where('category', 'Printer')->where('type_label', 'Scanner')->count(),
                'Portable Printer' => $items->where('category', 'Printer')->where('type_label', 'Portable Printer')->count(),
            ],
            'power_utilities' => [
                'total' => $items->where('category', 'Power Utility')->count(),
                'UPS' => $items->where('category', 'Power Utility')->where('type_label', 'UPS')->count(),
                'AVR' => $items->where('category', 'Power Utility')->where('type_label', 'AVR')->count(),
            ],
            'mobile_devices' => [
                'total' => $items->where('category', 'Mobile Device')->count(),
                'Cellphone' => $items->where('category', 'Mobile Device')->where('type_label', 'Cellphone')->count(),
            ]
        ];

        $groups = \Illuminate\Support\Facades\DB::table('locations')->pluck('name', 'id');
        $divisions = \Illuminate\Support\Facades\DB::table('divisions')->pluck('name', 'id');
        $departments = \Illuminate\Support\Facades\DB::table('departments')->pluck('name', 'id');

        $locationRows = [];
        foreach ($items as $item) {
            $locString = $item->location ?: 'UNASSIGNED';
            if (!in_array($locString, $locationRows)) {
                $locationRows[] = $locString;
            }
        }
        sort($locationRows);

        $selectedTypes = $request->input('type', []);
        if (!is_array($selectedTypes)) {
            $selectedTypes = explode(',', $selectedTypes);
        }
        $selectedTypes = array_filter($selectedTypes);

        $allDeviceTypes = ['Desktop', 'Laptop', 'Server', 'All-in-One', 'Router', 'Switch', 'Printer', 'Scanner', 'Portable Printer', 'UPS', 'AVR', 'Cellphone'];
        if (empty($selectedTypes)) {
            $deviceColumns = $allDeviceTypes;
        } else {
            $deviceColumns = array_intersect($allDeviceTypes, $selectedTypes);
        }

        $reportMatrix = [];
        $colTotals = array_fill_keys($deviceColumns, 0);
        $totals = ['total_issued' => 0];

        foreach ($locationRows as $loc) {
            $row = [
                'department' => $loc,
                'types' => [],
                'row_total' => 0
            ];

            foreach ($deviceColumns as $type) {
                $count = $items->filter(function ($item) use ($loc, $type) {
                    $itemLocStr = $item->location ?: 'UNASSIGNED';
                    if ($itemLocStr !== $loc) return false;

                    if (in_array($item->category, ['PC Unit', 'Network Device', 'Power Utility', 'Mobile Device'])) {
                        return strtolower($item->type_label) === strtolower($type);
                    }
                    // For Printer category, match type_label (Printer or Scanner)
                    return strtolower($item->category) === 'printer' && strtolower($item->type_label) === strtolower($type);
                })->count();

                $row['types'][$type] = $count;
                $row['row_total'] += $count;
                $colTotals[$type] += $count;
                $totals['total_issued'] += $count;
            }
            $reportMatrix[] = $row;
        }

        $totals['col_totals'] = $colTotals;

        $departments = \Illuminate\Support\Facades\DB::table('departments')->pluck('name', 'id');
        
        $deptDivisions = [];
        $allDepartments = \Illuminate\Support\Facades\DB::table('departments')->get();
        foreach ($allDepartments as $dept) {
            $deptDivisions[$dept->id] = \Illuminate\Support\Facades\DB::table('divisions')
                ->where('department_id', $dept->id)
                ->pluck('name', 'id')
                ->toArray();
        }

        return view('reports.department', compact('reportMatrix', 'deviceColumns', 'locationRows', 'totals', 'stats', 'groups', 'divisions', 'departments', 'deptDivisions', 'items', 'selectedTypes'));
    }

    /**
     * Print the Department Assets Summary Report.
     */
    public function printDepartment(Request $request)
    {
        $items = $this->getFilteredItems($request);

        $locationRows = [];
        foreach ($items as $item) {
            $locString = $item->location ?: 'UNASSIGNED';
            if (!in_array($locString, $locationRows)) {
                $locationRows[] = $locString;
            }
        }
        sort($locationRows);

        $selectedTypes = $request->input('type', []);
        if (!is_array($selectedTypes)) {
            $selectedTypes = explode(',', $selectedTypes);
        }
        $selectedTypes = array_filter($selectedTypes);

        $allDeviceTypes = ['Desktop', 'Laptop', 'Server', 'All-in-One', 'Router', 'Switch', 'Printer', 'Scanner', 'Portable Printer', 'UPS', 'AVR', 'Cellphone'];
        if (empty($selectedTypes)) {
            $deviceColumns = $allDeviceTypes;
        } else {
            $deviceColumns = array_intersect($allDeviceTypes, $selectedTypes);
        }

        $reportMatrix = [];
        $colTotals = array_fill_keys($deviceColumns, 0);
        $totals = ['total_issued' => 0];

        foreach ($locationRows as $loc) {
            $row = [
                'department' => $loc,
                'types' => [],
                'row_total' => 0
            ];

            foreach ($deviceColumns as $type) {
                $count = $items->filter(function ($item) use ($loc, $type) {
                    $itemLocStr = $item->location ?: 'UNASSIGNED';
                    if ($itemLocStr !== $loc) return false;

                    if (in_array($item->category, ['PC Unit', 'Network Device', 'Power Utility', 'Mobile Device'])) {
                        return strtolower($item->type_label) === strtolower($type);
                    }
                    // For Printer category, match type_label (Printer or Scanner)
                    return strtolower($item->category) === 'printer' && strtolower($item->type_label) === strtolower($type);
                })->count();

                $row['types'][$type] = $count;
                $row['row_total'] += $count;
                $colTotals[$type] += $count;
                $totals['total_issued'] += $count;
            }
            $reportMatrix[] = $row;
        }

        $totals['col_totals'] = $colTotals;

        $pdf = Pdf::loadView('reports.print-department', compact('reportMatrix', 'deviceColumns', 'locationRows', 'totals', 'request'))
            ->setPaper('letter', 'portrait');

        return $pdf->stream('Department-Distribution.pdf', [
            'Content-Disposition' => 'inline; filename="Department-Distribution.pdf"',
            'Content-Type' => 'application/pdf',
        ]);
    }
}
