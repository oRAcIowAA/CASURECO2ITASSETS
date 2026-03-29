<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('History Log: ') . $printer->brand . ' ' . $printer->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('printers.show', $printer) }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; Back to Device Details
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Device Information</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 lg:grid-cols-4 mt-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Brand/Model</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $printer->brand }} {{ $printer->model }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($printer->status) }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Current Owner</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $printer->employee->full_name ?? 'Unassigned' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 mb-4">History Timeline</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Owner</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logged By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($history as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ in_array(strtolower($log->action), ['assigned', 'transferred']) ? 'bg-green-100 text-green-800' : '' }}
                                                {{ in_array(strtolower($log->action), ['condemned', 'defective', 'returned']) ? 'bg-red-100 text-red-800' : '' }}
                                                {{ !in_array(strtolower($log->action), ['assigned', 'transferred', 'condemned', 'defective', 'returned']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $log->employee ? $log->employee->full_name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->previousEmployee ? $log->previousEmployee->full_name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $log->notes }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->createdBy ? $log->createdBy->name : 'System' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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
