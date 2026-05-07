<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mobile Device Details: ') . $mobileDevice->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded font-bold text-xs uppercase">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded uppercase font-bold text-xs">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Mobile Device Details Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between mb-6 gap-4">
                        <div class="flex items-start w-full sm:w-auto">
                            <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 uppercase">{{ $mobileDevice->asset_tag }}</h1>
                                <p class="text-gray-600 font-bold tracking-wide uppercase">{{ $mobileDevice->type }} - {{ $mobileDevice->brand }} {{ $mobileDevice->model }}</p>

                                @if($mobileDevice->updatedBy)
                                    <div class="mt-1 text-xs text-gray-500">
                                        <span class="font-medium text-gray-700">Last updated by:</span> {{ $mobileDevice->updatedBy->name }}
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="mt-3">
                                    @php $statusStr = strtolower($mobileDevice->status); @endphp
                                    @if($statusStr == 'available')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Available
                                        </span>
                                    @elseif($statusStr == 'assigned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Assigned
                                        </span>
                                    @elseif($statusStr == 'defective')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Defective
                                        </span>
                                    @elseif($statusStr == 'condemned')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Condemned
                                        </span>
                                    @elseif($statusStr == 'disposed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                             <svg class="-ml-1 mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                            </svg>
                                            Disposed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($mobileDevice->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto mt-4 sm:mt-0">
                            <a href="{{ route('mobile-devices.edit', $mobileDevice) }}" 
                               class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-blue-900 bg-yellow-400 hover:bg-yellow-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('mobile-devices.index') }}" 
                               class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-bold rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                ← Back
                            </a>
                        </div>
                    </div>

                    <!-- Advanced Actions Bar -->
                    <div class="flex flex-col sm:flex-row flex-wrap gap-2 mb-6 pt-4 border-t border-gray-100">
                        <a href="{{ route('mobile-device-history.show', $mobileDevice->id) }}" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-blue-200 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 font-bold">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            View History
                        </a>

                        @if($statusStr === 'disposed')
                            <div x-data="{ showRestoreModal: false }" class="inline w-full sm:w-auto">
                                <button type="button" @click="showRestoreModal = true" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-indigo-200 text-sm font-semibold rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Restore Data (Admin Override)
                                </button>

                                <!-- Restore Modal -->
                                <div x-show="showRestoreModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="showRestoreModal" @click="showRestoreModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="showRestoreModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <form method="POST" action="{{ route('mobile-devices.restore', $mobileDevice) }}">
                                                @csrf
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                            <h3 class="text-lg leading-6 font-bold text-gray-900 uppercase" id="modal-title">Restore Disposed Data</h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500 mb-4 uppercase">Please confirm your identity to restore this permanently archived device.</p>
                                                                <div class="mb-4">
                                                                    <label for="password" class="block text-sm font-bold text-gray-700 uppercase">Your Password</label>
                                                                    <input type="password" name="password" id="password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                                                                </div>
                                                                <div>
                                                                    <label for="reason" class="block text-sm font-bold text-gray-700 uppercase">Reason for Restore</label>
                                                                    <input type="text" name="reason" id="reason" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md uppercase font-bold" placeholder="e.g., Accidental disposal..." required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3 font-bold uppercase">
                                                    <button type="button" @click="showRestoreModal = false" class="w-full sm:w-28 inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="w-full sm:w-28 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm uppercase">
                                                        Restore
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else

                            @if(!in_array($statusStr, ['disposed', 'condemned']))
                                <a href="{{ route('mobile-devices.transfer', $mobileDevice) }}" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-indigo-200 text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100 font-bold">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    Transfer Ownership
                                </a>
                            @endif

                            <a href="{{ route('mobile-devices.dispose', $mobileDevice) }}" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-red-200 text-sm font-semibold rounded-md text-red-700 bg-red-50 hover:bg-red-100">
                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                @if($statusStr === 'condemned')
                                    Disposed
                                @elseif($statusStr === 'defective')
                                    Defective / Condemn
                                @else
                                    Defective / Condemn
                                @endif
                            </a>
                            
                            @if($statusStr === 'defective')
                            <form method="POST" action="{{ route('mobile-devices.repair', $mobileDevice) }}" class="inline w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto justify-center inline-flex items-center px-3 py-1.5 border border-green-200 text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 font-bold" onclick="return confirm('Mark this device as repaired?');">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Repaired
                                </button>
                            </form>
                            @endif
                        @endif

                        <a href="{{ route('mobile-devices.print-label', $mobileDevice) }}" target="_blank" class="w-full sm:w-auto justify-center inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-semibold rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            Print QR Label
                        </a>
                        
                    </div>
                    
                    <!-- Hardware Specifications -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1 uppercase">Type</label>
                            <p class="text-lg font-bold text-gray-900 uppercase">{{ $mobileDevice->type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1 uppercase">Brand / Model</label>
                            <p class="text-lg font-bold text-gray-900 uppercase">{{ $mobileDevice->brand }} {{ $mobileDevice->model }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1 uppercase">Serial Number / IMEI</label>
                            <p class="text-lg font-bold text-gray-900 uppercase font-mono tracking-tight">{{ $mobileDevice->serial_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Processor</label>
                            <p class="text-lg font-bold text-gray-900 uppercase">{{ $mobileDevice->processor ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">RAM</label>
                            <p class="text-lg font-bold text-gray-900 uppercase">{{ $mobileDevice->ram ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Storage</label>
                            <p class="text-lg font-bold text-gray-900 uppercase">{{ $mobileDevice->storage ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Date Issued</label>
                            <p class="text-lg font-bold text-gray-900 uppercase">{{ $mobileDevice->date_issued ? $mobileDevice->date_issued->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($mobileDevice->remarks)
                    <div class="border-t border-gray-100 pt-4 mb-6">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Remarks</label>
                        <p class="text-gray-900 whitespace-pre-wrap uppercase text-sm font-bold">{{ $mobileDevice->remarks }}</p>
                    </div>
                    @endif

                    <!-- Location & Assignment -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location & Assignment</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @php
                                $source = $mobileDevice->employee_id ? $mobileDevice->employee : $mobileDevice;
                            @endphp
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1 uppercase">Location <span
                                        class="text-[10px] text-indigo-500 font-bold uppercase tracking-tight">{{ $mobileDevice->employee_id ? '(BASED ON EMPLOYEE)' : '' }}</span></label>
                                <p class="text-lg font-bold text-gray-900 uppercase">{{ $source->group ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                                <p class="text-lg font-bold text-gray-900 uppercase">{{ $source->department ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Division</label>
                                <p class="text-lg font-bold text-gray-900 uppercase">{{ $source->division ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Assigned To</label>
                                <p class="text-lg font-bold text-gray-900 uppercase">
                                    {{ $mobileDevice->employee_id ? strtoupper($mobileDevice->employee->full_name) : 'UNASSIGNED' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Date Issued</label>
                                <p class="text-lg font-bold text-gray-900 uppercase">
                                    {{ $mobileDevice->date_issued ? $mobileDevice->date_issued->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Status Message Box -->
                    <div class="mt-6 border-t border-gray-100 pt-6">
                        @if(!$mobileDevice->employee_id && !in_array($statusStr, ['disposed', 'condemned']))
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded shadow-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-blue-800 uppercase">
                                            This device is available for assignment.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif($mobileDevice->employee_id)
                            <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded shadow-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-indigo-800 uppercase">
                                            This device is currently assigned to {{ $mobileDevice->employee->full_name }}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @elseif(in_array($statusStr, ['disposed', 'condemned']))
                             <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded shadow-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-red-800 uppercase">
                                            This device is {{ $statusStr }} and is no longer available for assignment.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assignment History -->
            @if($mobileDevice->history->count() > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6 border border-gray-100">
                <div class="p-6 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Assignment History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Updated By</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 uppercase">
                            @foreach($mobileDevice->history->sortByDesc('created_at') as $history)
                            <tr class="font-bold">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $history->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $action = strtolower($history->action);
                                        $actionColors = [
                                            'available' => 'bg-blue-100 text-blue-800',
                                            'assigned' => 'bg-blue-100 text-blue-800',
                                            'returned' => 'bg-blue-100 text-blue-800',
                                            'transferred' => 'bg-blue-100 text-blue-800',
                                            'reassigned' => 'bg-blue-100 text-blue-800',
                                            'defective' => 'bg-red-100 text-red-800',
                                            'condemned' => 'bg-red-100 text-red-800',
                                            'disposed' => 'bg-red-100 text-red-800',
                                            'repaired' => 'bg-green-100 text-green-800',
                                            'restored' => 'bg-green-100 text-green-800',
                                        ];
                                        $colorClass = $actionColors[$action] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-0.5 text-[10px] leading-5 font-bold rounded-full {{ $colorClass }}">
                                        {{ strtoupper($history->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $history->employee->full_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $history->createdBy->name ?? 'System' }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 min-w-[200px] tracking-tight leading-tight">
                                    @if(str_contains($history->notes, 'EDITED RECORD DETAILS'))
                                        <div class="font-mono text-[9px] text-indigo-600 bg-indigo-50 p-1 rounded">
                                            {!! str_replace(', ', '<br>', str_replace('EDITED RECORD DETAILS: ', '', $history->notes)) !!}
                                        </div>
                                    @else
                                        {{ $history->notes ?? 'N/A' }}
                                    @endif
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
</x-app-layout>


