<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Details | CASURECO DMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased text-gray-900">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-lg">
        <!-- Header -->
        <div class="bg-blue-800 text-white p-6 shadow-md text-center">
            <h1 class="text-xl font-bold tracking-tight">CASURECO IT Assets</h1>
            <p class="text-blue-200 text-sm mt-1">Verified Device Info</p>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs uppercase tracking-wider font-semibold text-gray-500">{{ $deviceType }}</span>
                @if(strtolower($device->status) === 'available')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Available</span>
                @elseif(strtolower($device->status) === 'assigned')
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Assigned</span>
                @elseif(in_array(strtolower($device->status), ['defective', 'condemned', 'disposed']))
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ ucfirst($device->status) }}</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($device->status) }}</span>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">
                @if($deviceType === 'PC Unit')
                    {{ $device->asset_tag ?? 'CAS-PC-UNK' }}
                @else
                    {{ $device->brand }} {{ $device->model }}
                @endif
            </h2>

            @if($deviceType === 'PC Unit')
                <p class="text-gray-600 mb-6">{{ $device->processor ?? 'CPU Unspecified' }} | {{ $device->ram ?? 'RAM Unspecified' }}</p>
            @elseif($deviceType === 'Printer')
                <p class="text-gray-600 mb-6">
                    @if($device->has_network_port) Network Printer @else Local Printer @endif
                </p>
            @elseif($deviceType === 'Network Device')
                <p class="text-gray-600 mb-6">{{ ucfirst($device->switch_type ?? '') }} {{ ucfirst($device->device_type) }}</p>
            @endif

            <div class="border-t border-gray-100 py-4">
                <dl class="grid grid-cols-1 gap-y-4">
                    @if(isset($device->ip_address) && $device->ip_address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 p-2 rounded border border-gray-200 inline-block">{{ $device->ip_address }}</dd>
                    </div>
                    @endif

                    @if(isset($device->mac_address) && $device->mac_address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">MAC Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $device->mac_address }}</dd>
                    </div>
                    @endif

                    <!-- Location details -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 mt-2">
                        <dt class="text-sm font-semibold text-gray-900 mb-3 border-b border-gray-200 pb-2">Deployment & Assignment</dt>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Group</span>
                                <span class="font-medium text-sm">{{ $device->group ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Department</span>
                                <span class="font-medium text-sm">{{ $device->department ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4 border-t border-gray-100 pt-3">
                            <div>
                                <span class="block text-xs text-gray-500 mb-1">Division</span>
                                <span class="font-medium text-sm">{{ strtoupper($device->division ?? 'N/A') }}</span>
                            </div>
                        <div>
                            <span class="block text-xs text-gray-500 mb-1">Assigned To</span>
                            @if($device->employee)
                                <span class="font-medium text-sm text-gray-900">{{ $device->employee->full_name }} <span class="text-gray-500 font-normal">({{ $device->employee->employee_id ?? 'No ID' }})</span></span>
                            @else
                                <span class="font-medium text-sm text-gray-900 text-gray-500 italic">Unassigned</span>
                            @endif
                        </div>
                    </div>

                    <!-- Recent History -->
                    @if($device->history && $device->history->count() > 0)
                    <div class="mt-4">
                        <dt class="text-sm font-semibold text-gray-900 mb-3 border-b border-gray-200 pb-2">Recent History</dt>
                        <div class="space-y-3">
                            @foreach($device->history as $log)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-2 w-2 mt-1.5 rounded-full bg-blue-500"></div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-900">
                                            @if($log->employee)
                                                <span class="font-medium">{{ $log->employee->full_name }}</span> - 
                                            @endif
                                            {{ ucfirst($log->action) }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $log->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Footer Note -->
            <div class="mt-8 text-center text-xs text-gray-400">
                <p>Scanned via CASURECO DMS Secure Tracker</p>
                <p class="mt-1 font-mono break-all">{{ $device->tracking_uuid }}</p>
            </div>
        </div>
    </div>
</body>
</html>
