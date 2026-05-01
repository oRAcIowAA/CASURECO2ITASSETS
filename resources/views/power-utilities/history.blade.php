<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('History Log: ') . $powerUtility->type . ' ' . $powerUtility->brand . ' ' . $powerUtility->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('power-utilities.show', $powerUtility) }}" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                    &larr; Back to Device Details
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Device Information</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 lg:grid-cols-4 mt-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Brand/Model</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold uppercase">{{ $powerUtility->brand }} {{ $powerUtility->model }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold uppercase">{{ strtoupper($powerUtility->status) }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Current Owner</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold uppercase">{{ $powerUtility->employee->full_name ?? 'Unassigned' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4">History Timeline</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned To</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Logged By</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($history as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $action = strtolower($log->action);
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
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase {{ $colorClass }}">
                                                {{ strtoupper($log->action) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold uppercase">
                                            {{ $log->employee ? $log->employee->full_name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->createdBy ? $log->createdBy->name : 'System' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500 uppercase tracking-tight leading-tight">
                                            @if(str_contains($log->notes, 'EDITED RECORD DETAILS'))
                                                <div class="font-mono text-[9px] text-indigo-600 bg-indigo-50 p-1 rounded">
                                                    {!! str_replace(', ', '<br>', str_replace('EDITED RECORD DETAILS: ', '', $log->notes)) !!}
                                                </div>
                                            @else
                                                {{ $log->notes }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 uppercase font-bold">
                                            No history found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


