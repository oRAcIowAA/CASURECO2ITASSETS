<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('PC Assignment History Report') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Report Header -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">PC Assignment Report</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                Generated on: {{ now()->format('F d, Y h:i A') }}
                            </p>
                            @if(request('start_date') || request('end_date'))
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="font-medium">Date Range:</span> 
                                {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') : 'All' }}
                                {{ request('end_date') ? ' to ' . \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') : '' }}
                            </p>
                            @endif
                            @if(request('action'))
                            <p class="text-sm text-gray-500 mt-1">
                                <span class="font-medium">Filter:</span> {{ ucfirst(request('action')) }} actions only
                            </p>
                            @endif
                        </div>
                        <div class="flex space-x-3">
                            <!-- Print button removed as requested -->
                            <a href="{{ route('pc-history.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                ← Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <a href="{{ route('pc-history.report', request()->except(['action', 'page'])) }}" 
                   class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500 hover:bg-indigo-50 transition block cursor-pointer {{ !request('action') ? 'ring-2 ring-indigo-500' : '' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Records</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $history->total() }}</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('pc-history.report', array_merge(request()->all(), ['action' => 'assigned'])) }}" 
                   class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:bg-green-50 transition block cursor-pointer {{ request('action') == 'assigned' ? 'ring-2 ring-green-500' : '' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Assignments</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $history->where('action', 'assigned')->count() }}
                            </p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('pc-history.report', array_merge(request()->all(), ['action' => 'returned'])) }}" 
                   class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 hover:bg-red-50 transition block cursor-pointer {{ request('action') == 'returned' ? 'ring-2 ring-red-500' : '' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Returns</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $history->where('action', 'returned')->count() }}
                            </p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('pc-history.report', array_merge(request()->all(), ['action' => 'transferred'])) }}" 
                   class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:bg-blue-50 transition block cursor-pointer {{ request('action') == 'transferred' ? 'ring-2 ring-blue-500' : '' }}">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Transfers</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ $history->where('action', 'transferred')->count() + $history->where('action', 'reassigned')->count() }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Detailed Report Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detailed Assignment Records</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Tag</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($history as $record)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $record->pcUnit->asset_tag }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->pcUnit->model }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($record->action == 'assigned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Assigned
                                        </span>
                                    @elseif($record->action == 'returned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Returned
                                        </span>
                                    @elseif($record->action == 'transferred')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Transferred
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ ucfirst($record->action) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $record->employee->full_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->previousEmployee->full_name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->notes ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->createdBy->name ?? 'N/A' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No records found matching the filter criteria.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($history->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $history->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>