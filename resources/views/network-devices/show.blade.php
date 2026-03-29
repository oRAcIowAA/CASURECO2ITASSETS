<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Network Device Details: ') . $networkDevice->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Network Device Details Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-6 gap-4">
                        <div class="flex items-start w-full sm:w-auto">
                            <div class="bg-green-100 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 01-2 2v4a2 2 0 012 2h14a2 2 0 012-2v-4a2 2 0 01-2-2m-2-4h.01M17 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $networkDevice->asset_tag }}</h1>
                                <p class="text-gray-600 font-bold tracking-wide">{{ strtoupper($networkDevice->brand) }} {{ strtoupper($networkDevice->model) }}</p>
                                
                                <!-- Status Badge -->
                                <div class="mt-3">
                                    @if(strtolower($networkDevice->status) == 'available')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Available
                                        </span>
                                    @elseif(strtolower($networkDevice->status) == 'assigned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Assigned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ strtoupper($networkDevice->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                            <a href="{{ route('network-device-history.show', $networkDevice->id) }}" 
                               class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 border border-blue-200 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                View History
                            </a>
                            <a href="{{ route('network-devices.edit', $networkDevice) }}" 
                               class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-blue-900 bg-yellow-400 hover:bg-yellow-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('organization.index') }}" 
                               class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50">
                                ← Back
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Actions Bar -->
                    <div class="flex flex-col sm:flex-row flex-wrap gap-2 mb-6 pt-4 border-t border-gray-100">
                        @if(strtolower($networkDevice->status) === 'disposed')
                            <div x-data="{ showRestoreModal: false }" class="inline w-full sm:w-auto">
                                <button type="button" @click="showRestoreModal = true" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-purple-200 text-sm font-medium rounded-md text-purple-700 bg-purple-50 hover:bg-purple-100">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Restore Data (Admin Override)
                                </button>

                                <!-- Restore Modal -->
                                <div x-show="showRestoreModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="showRestoreModal" @click="showRestoreModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="showRestoreModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <form method="POST" action="{{ route('network-devices.restore', $networkDevice) }}">
                                                @csrf
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Restore Disposed Data</h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500 mb-4">Please confirm your identity to restore this permanently archived device.</p>
                                                                <div class="mb-4">
                                                                    <label for="password" class="block text-sm font-medium text-gray-700">Your Password</label>
                                                                    <input type="password" name="password" id="password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                                                </div>
                                                                <div>
                                                                    <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Restore</label>
                                                                    <input type="text" name="reason" id="reason" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="e.g., Accidental disposal, Unit was recovered..." required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3">
                                                    <button type="button" @click="showRestoreModal = false" class="w-full sm:w-28 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="w-full sm:w-28 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                                        Restore
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('network-devices.transfer', $networkDevice) }}" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-indigo-200 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                Transfer Ownership
                            </a>
                            <a href="{{ route('network-devices.dispose', $networkDevice) }}" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-red-200 text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Defective/Condemn
                            </a>
                            
                            @if(strtolower($networkDevice->status) === 'defective')
                            <form method="POST" action="{{ route('network-devices.repair', $networkDevice) }}" class="inline w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-green-200 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100" onclick="return confirm('Mark this device as repaired?');">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Repaired
                                </button>
                            </form>
                            @endif
                        @endif

                        <a href="{{ route('network-devices.print-label', $networkDevice) }}" target="_blank" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-gray-200 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 sm:ml-4">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Print QR Label
                        </a>
                        
                        <!-- Print Actions Removed -->
                    </div>
                    
                    <!-- Hardware Specifications -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Brand</label>
                            <p class="text-lg font-semibold text-gray-900">{{ strtoupper($networkDevice->brand) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Model</label>
                            <p class="text-lg font-semibold text-gray-900">{{ strtoupper($networkDevice->model) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Device Type</label>
                            <p class="text-lg font-semibold text-gray-900">{{ ucfirst($networkDevice->device_type) }}</p>
                        </div>
                        <!-- Add more fields as needed -->
                        @if($networkDevice->device_type == 'switch')
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Switch Type</label>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($networkDevice->switch_type) }}</p>
                            </div>
                        @endif
                         <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Network Ports</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $networkDevice->network_ports }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Network Speed</label>
                            <p class="text-lg font-semibold text-gray-900">{{ ucfirst($networkDevice->network_speed) }}</p>
                        </div>

                    </div>

                    <!-- Network Information -->
                    @if($networkDevice->has_ip)
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Network Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Has IP</label>
                                <p class="text-lg font-semibold text-gray-900">Yes</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">IP Address</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $networkDevice->ip_address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Location & Assignment -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location & Assignment</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">DEPARTMENT</label>
                                <p class="text-lg font-semibold text-gray-900">{{ strtoupper($networkDevice->department ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">DIVISION</label>
                                <p class="text-lg font-semibold text-gray-900">{{ strtoupper($networkDevice->division ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">GROUP</label>
                                <p class="text-lg font-semibold text-gray-900">{{ strtoupper($networkDevice->group ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ASSIGNED TO</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $networkDevice->employee ? strtoupper($networkDevice->employee->full_name) : 'UNASSIGNED' }}
                                    @if($networkDevice->employee)
                                        <span class="text-sm text-gray-500">({{ strtoupper($networkDevice->employee->position) }})</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">DATE ASSIGNED</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $networkDevice->date_assigned ? \Carbon\Carbon::parse($networkDevice->date_assigned)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Actions -->
                    @if($networkDevice->employee_id)
                        <form method="POST" action="{{ route('network-devices.return', $networkDevice) }}" class="mb-6 border-t border-gray-200 pt-6">
                            @csrf
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-sm font-medium text-yellow-800">This device is currently assigned to {{ $networkDevice->employee->full_name }}</h3>
                                        <div class="mt-2">
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700"
                                                    onclick="return confirm('Are you sure you want to return this device?');">
                                                Return Device
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @elseif($networkDevice->status == 'Available')
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6 border-t border-gray-200 mt-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-medium text-blue-800">This device is available for assignment</h3>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Assignment History -->
                    @php
                        // Fetching history directly related to this device
                        $deviceHistory = \App\Models\NetworkDeviceHistory::where('network_device_id', $networkDevice->id)
                                            ->with(['employee', 'previousEmployee'])
                                            ->orderBy('created_at', 'desc')
                                            ->get();
                    @endphp

                    @if($deviceHistory->count() > 0)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6 border border-gray-100">
                        <div class="p-6 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Assignment History</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($deviceHistory as $history)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $history->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $action = strtolower($history->action);
                                                $actionColors = [
                                                    // Blue actions
                                                    'available' => 'bg-blue-100 text-blue-800',
                                                    'assigned' => 'bg-blue-100 text-blue-800',
                                                    'returned' => 'bg-blue-100 text-blue-800',
                                                    'transferred' => 'bg-blue-100 text-blue-800',
                                                    'reassigned' => 'bg-blue-100 text-blue-800',
                                                    
                                                    // Red actions
                                                    'defective' => 'bg-red-100 text-red-800',
                                                    'condemned' => 'bg-red-100 text-red-800',
                                                    'disposed' => 'bg-red-100 text-red-800',
                                                    
                                                    // Green actions
                                                    'repaired' => 'bg-green-100 text-green-800',
                                                    'restored' => 'bg-green-100 text-green-800',
                                                ];
                                                $colorClass = $actionColors[$action] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                {{ strtoupper($history->action) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $history->employee ? $history->employee->full_name : ($history->previousEmployee ? $history->previousEmployee->full_name . ' (Previous)' : 'N/A') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $history->notes ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if(session('print_disposal'))
    <script>
        window.open('{{ route('network-devices.print-disposal', $networkDevice) }}', '_blank');
    </script>
    @endif
</x-app-layout>
