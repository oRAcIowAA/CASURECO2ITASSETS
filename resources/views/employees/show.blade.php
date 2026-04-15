<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee: ') . $employee->full_name }}
        </h2>
        <div class="mt-1 text-sm text-gray-500 uppercase">
            ID: <span class="font-medium text-gray-900">{{ strtoupper($employee->employee_id ?? 'N/A') }}</span> &bull; 
            {{ strtoupper($employee->position ?? 'NO POSITION') }} &bull; 
            {{ strtoupper($employee->department ?? 'N/A') }} / {{ strtoupper($employee->division ?? 'N/A') }} / {{ strtoupper($employee->group ?? 'N/A') }}
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Active Assignments (Main Content) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Current Assets -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 uppercase">Current Assets</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset Tag</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $assets = collect();
                                        
                                        foreach($employee->pcUnits as $pc) {
                                            $assets->push((object)[
                                                'id' => $pc->id,
                                                'type' => $pc->device_type,
                                                'brand_model' => $pc->model,
                                                'asset_tag' => $pc->asset_tag,
                                                'date_assigned' => $pc->date_assigned,
                                                'route_show' => route('pc-units.show', $pc),
                                                'route_transfer' => route('pc-units.transfer', $pc),
                                                'category' => 'PC Unit'
                                            ]);
                                        }

                                        foreach($employee->printers as $printer) {
                                            $assets->push((object)[
                                                'id' => $printer->id,
                                                'type' => 'Printer',
                                                'brand_model' => $printer->brand . ' ' . $printer->model,
                                                'asset_tag' => $printer->asset_tag,
                                                'date_assigned' => $printer->date_assigned,
                                                'route_show' => route('printers.show', $printer),
                                                'route_transfer' => route('printers.transfer', $printer),
                                                'category' => 'Printer'
                                            ]);
                                        }

                                        foreach($employee->networkDevices as $device) {
                                            $assets->push((object)[
                                                'id' => $device->id,
                                                'type' => ucfirst($device->device_type),
                                                'brand_model' => $device->brand . ' ' . $device->model,
                                                'asset_tag' => $device->asset_tag,
                                                'date_assigned' => $device->date_assigned,
                                                'route_show' => route('network-devices.show', $device),
                                                'route_transfer' => route('network-devices.transfer', $device),
                                                'category' => 'Network Device'
                                            ]);
                                        }
                                    @endphp

                                    @forelse ($assets as $asset)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ $asset->route_show }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $asset->asset_tag }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $asset->brand_model }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ strtoupper($asset->type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $asset->date_assigned ? \Carbon\Carbon::parse($asset->date_assigned)->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ $asset->route_transfer }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Transfer</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No active assets assigned.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Asset Assignment History -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 uppercase">Asset Assignment History</h3>
                        </div>
                        <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @forelse ($assetHistory as $log)
                                <li class="px-6 py-4 hover:bg-gray-50">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            @php 
                                                $action = strtolower($log->action); 
                                                $asset = $log->pcUnit ?? $log->printer ?? $log->networkDevice;
                                                $route = 'pc-units.show';
                                                if($log->printer_id) $route = 'printers.show';
                                                elseif($log->network_device_id) $route = 'network-devices.show';
                                                
                                                $assetId = $log->pc_unit_id ?: ($log->printer_id ?: $log->network_device_id);
                                            @endphp
                                            
                                            @if(in_array($action, ['defective', 'condemned', 'disposed']))
                                                <span class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </span>
                                            @else
                                                <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    <span class="font-medium text-gray-900 uppercase">{{ $log->action }}</span> 
                                                    @if($asset)
                                                        <a href="{{ route($route, $assetId) }}" class="text-indigo-600 hover:text-indigo-900 mx-1">
                                                            {{ $asset->asset_tag }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400 mx-1">[DELETED ASSET]</span>
                                                    @endif
                                                    
                                                    @if($log->action == 'transferred' && $log->previous_employee_id == $employee->id)
                                                        to <span class="font-medium text-gray-900">{{ $log->employee->full_name ?? 'Unknown' }}</span>
                                                    @elseif($log->action == 'transferred' && $log->employee_id == $employee->id)
                                                        from <span class="font-medium text-gray-900">{{ $log->previousEmployee->full_name ?? 'Unknown' }}</span>
                                                    @endif
                                                </p>
                                                @if($log->notes)
                                                    <p class="text-xs text-gray-500 mt-1 italic">"{{ $log->notes }}"</p>
                                                @endif
                                                <p class="text-[10px] text-gray-400 mt-0.5">Logged by: {{ $log->createdBy->name ?? 'System' }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M d, Y') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-4 text-sm text-gray-500 text-center">No asset history recorded.</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Employee Record History -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-indigo-100">
                        <div class="p-6 border-b border-gray-200 bg-indigo-50">
                            <h3 class="text-lg font-bold text-indigo-900 uppercase">Profile Edit History</h3>
                        </div>
                        <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @forelse ($recordHistory as $log)
                                <li class="px-6 py-4 hover:bg-gray-50">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <span class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900 font-medium">
                                                    RECORD {{ strtoupper($log->action) }}
                                                </p>
                                                @if($log->notes)
                                                    <p class="text-xs text-gray-600 mt-1 font-mono bg-gray-50 p-2 rounded border border-gray-100 leading-relaxed">
                                                        {!! str_replace(', ', '<br>', $log->notes) !!}
                                                    </p>
                                                @endif
                                                <p class="text-[10px] text-gray-400 mt-1">Updated by: {{ $log->createdBy->name ?? 'System' }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M d, Y H:i') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-4 text-sm text-gray-500 text-center">No record history available.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Stats Sidebar -->
                <div class="lg:col-span-1 border-l pl-4 border-gray-100">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 mb-6 border border-blue-100">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-widest">Active Assets</div>
                        <div class="mt-2 text-4xl font-extrabold text-blue-600">{{ $employee->pcUnits->count() + $employee->printers->count() + $employee->networkDevices->count() }}</div>
                        <div class="mt-4 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500 uppercase">PC Units</span>
                                <span class="font-bold">{{ $employee->pcUnits->count() }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500 uppercase">Printers</span>
                                <span class="font-bold">{{ $employee->printers->count() }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-500 uppercase">Networking</span>
                                <span class="font-bold">{{ $employee->networkDevices->count() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="text-gray-500 text-xs font-bold uppercase tracking-widest leading-tight">Total History Log Entries</div>
                        <div class="mt-2 text-3xl font-extrabold text-gray-900">
                            {{ $assetHistory->count() + $recordHistory->count() }}
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-2">
                        <a href="{{ route('employees.edit', $employee) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-bold text-xs text-blue-900 uppercase tracking-widest hover:bg-yellow-500">
                            Edit Profile
                        </a>
                        <a href="{{ route('employees.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-bold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            Back to List
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>