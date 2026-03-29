<?php

namespace App\Http\Controllers;

use App\Models\PcUnit;
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

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;

        return view('reports.index', compact('paginatedItems', 'groups', 'divisions', 'departments', 'stats'));
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
        return $pdf->stream('Unified-Device-List.pdf', [
            'Content-Disposition' => 'inline; filename="Unified-Device-List.pdf"',
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Helper to get and filter items from all sources
     */
    private function getFilteredItems(Request $request)
    {
        $category = $request->input('category', 'All');
        $search = $request->search;
        $status = $request->status;
        $group = $request->group;
        $division = $request->division;
        $department = $request->department;
        $type = $request->type; // This acts as 'Device Type' for PC/Network, or ignored for Printer if not applicable

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
                    'location' => strtoupper(implode(' / ', array_filter([$item->group, $item->department, $item->division]))),
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
                    'type_model' => ($item->asset_tag ? $item->asset_tag . ' - ' : '') . 'Printer - ' . $item->brand . ' ' . $item->model,
                    'type_label' => 'Printer',
                    'location' => strtoupper(implode(' / ', array_filter([$item->group, $item->department, $item->division]))),
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
                    'location' => strtoupper(implode(' / ', array_filter([$item->group, $item->department, $item->division]))),
                    'assigned_to' => $item->employee->full_name ?? 'N/A',
                    'status' => $item->status,
                    'category' => 'Network Device',
                    'original' => $item
                ];
            });
            $collection = $collection->merge($netDevices);
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
                        $sq->where('full_name', 'like', "%{$search}%");
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
                }
            });
        }

        // Status
        if ($request->filled('status') && $request->status !== 'All Statuses') {
            $status = strtolower(str_replace(' ', '_', $request->status));
            $query->where(function ($q) use ($status) {
                $q->where('status', $status)
                    ->orWhere('status', ucfirst($status));
            });
        }

        // Group
        if ($request->filled('group') && $request->group !== 'All Groups') {
            $query->where('group', $request->group);
        }

        // Division
        if ($request->filled('division') && $request->division !== 'All Divisions') {
            $query->where('division', $request->division);
        }

        // Department
        if ($request->filled('department') && $request->department !== 'All Departments') {
            $query->where('department', $request->department);
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
                if (!in_array('Printer', $types)) {
                    $query->whereRaw('1 = 0');
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
            ]
        ];

        $groups = \App\Constants\Organization::GROUPS;
        $divisions = \App\Constants\Organization::DIVISIONS;
        $departments = \App\Constants\Organization::DEPARTMENTS;

        $locationRows = [];
        foreach ($items as $item) {
            $u = $item->original;
            $locParts = array_filter([$u->group, $u->department, $u->division]);
            $locString = empty($locParts) ? 'UNASSIGNED' : strtoupper(implode(' / ', $locParts));
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

        $allDeviceTypes = ['Desktop', 'Laptop', 'Server', 'All-in-One', 'Router', 'Switch', 'Printer'];
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
                    $u = $item->original;
                    $locParts = array_filter([$u->group, $u->department, $u->division]);
                    $itemLocStr = empty($locParts) ? 'UNASSIGNED' : strtoupper(implode(' / ', $locParts));
                    if ($itemLocStr !== $loc) return false;

                    if (in_array($item->category, ['PC Unit', 'Network Device'])) {
                        return strtolower($item->type_label) === strtolower($type);
                    }
                    return strtolower($item->category) === 'printer' && strtolower($type) === 'printer';
                })->count();

                $row['types'][$type] = $count;
                $row['row_total'] += $count;
                $colTotals[$type] += $count;
                $totals['total_issued'] += $count;
            }
            $reportMatrix[] = $row;
        }

        $totals['col_totals'] = $colTotals;

        return view('reports.department', compact('reportMatrix', 'deviceColumns', 'locationRows', 'totals', 'stats', 'groups', 'divisions', 'departments', 'items', 'selectedTypes'));
    }

    /**
     * Print the Department Assets Summary Report.
     */
    public function printDepartment(Request $request)
    {
        $items = $this->getFilteredItems($request);

        $locationRows = [];
        foreach ($items as $item) {
            $u = $item->original;
            $locParts = array_filter([$u->group, $u->department, $u->division]);
            $locString = empty($locParts) ? 'UNASSIGNED' : strtoupper(implode(' / ', $locParts));
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

        $allDeviceTypes = ['Desktop', 'Laptop', 'Server', 'All-in-One', 'Router', 'Switch', 'Printer'];
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
                    $u = $item->original;
                    $locParts = array_filter([$u->group, $u->department, $u->division]);
                    $itemLocStr = empty($locParts) ? 'UNASSIGNED' : strtoupper(implode(' / ', $locParts));
                    if ($itemLocStr !== $loc) return false;

                    if (in_array($item->category, ['PC Unit', 'Network Device'])) {
                        return strtolower($item->type_label) === strtolower($type);
                    }
                    return strtolower($item->category) === 'printer' && strtolower($type) === 'printer';
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