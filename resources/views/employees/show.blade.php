<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Employee: ') . $employee->full_name }}
        </h2>
        <div class="mt-1 text-sm text-gray-500">
            ID: <span class="font-medium text-gray-900">{{ $employee->employee_id ?? 'N/A' }}</span> &bull; 
            {{ $employee->position ?? 'No Position' }} &bull; {{ $employee->department->department_name }} ({{ $employee->department->branch->branch_name }})
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
                            <h3 class="text-lg font-medium text-gray-900">Current Assets</h3>
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
                                    @forelse($employee->pcUnits as $unit)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <a href="{{ route('pc-units.show', $unit) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $unit->asset_tag }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $unit->model }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $unit->device_type }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $unit->date_assigned ? $unit->date_assigned->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('pc-units.transfer', $unit) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Transfer</a>
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

                    <!-- History Log -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Assignment History</h3>
                        </div>
                        <ul class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                            @forelse($history as $log)
                                <li class="px-6 py-4 hover:bg-gray-50">
                                    <div class="flex space-x-3">
                                        <div class="flex-shrink-0">
                                            <!-- Icon based on action -->
                                            @if($log->action == 'assigned')
                                                <span class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </span>
                                            @elseif($log->action == 'returned')
                                                <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </span>
                                            @elseif($log->action == 'transferred')
                                                <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                </span>
                                            @else
                                                 <span class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">
                                                    <span class="font-medium text-gray-900">{{ ucfirst($log->action) }}</span> 
                                                    <a href="{{ route('pc-units.show', $log->pc_unit_id) }}" class="text-indigo-600 hover:text-indigo-900 mx-1">
                                                        {{ $log->pcUnit->asset_tag ?? 'Unknown Unit' }}
                                                    </a>
                                                    @if($log->action == 'transferred' && $log->previous_employee_id == $employee->id)
                                                        to <span class="font-medium text-gray-900">{{ $log->employee->full_name ?? 'Unknown' }}</span>
                                                    @elseif($log->action == 'transferred' && $log->employee_id == $employee->id)
                                                        from <span class="font-medium text-gray-900">{{ $log->previousEmployee->full_name ?? 'Unknown' }}</span>
                                                    @endif
                                                </p>
                                                @if($log->notes)
                                                    <p class="text-sm text-gray-500 mt-1">"{{ $log->notes }}"</p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M d, Y') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="px-6 py-4 text-sm text-gray-500 text-center">No history recorded.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Stats Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 mb-6">
                        <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Current Assets</div>
                        <div class="mt-2 text-3xl font-bold text-blue-600">{{ $employee->pcUnits->count() }}</div>
                    </div>
                    
                     <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <div class="text-gray-500 text-sm font-medium uppercase tracking-wider">Lifetime Assignments</div>
                         <!-- Count unique PC Units ever assigned -->
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $history->unique('pc_unit_id')->count() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
