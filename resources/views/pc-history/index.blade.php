<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PC Assignment History') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('pc-history.index') }}" class="px-4 py-2 {{ !request()->has('view') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-blue-700 hover:text-white text-sm font-medium transition">
                    List View
                </a>
                <a href="{{ route('pc-history.index', ['view' => 'folders']) }}" class="px-4 py-2 {{ request()->get('view') == 'folders' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md hover:bg-blue-700 hover:text-white text-sm font-medium transition">
                    Folder View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">All PC Assignment Records</h3>
                <p class="text-sm text-gray-500">Total: {{ $history->total() }} records</p>
            </div>

            <!-- Filter Bar -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <form method="GET" action="{{ route('pc-history.report') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               value="{{ request('end_date') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select name="action" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Actions</option>
                            <option value="assigned" {{ request('action') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="returned" {{ request('action') == 'returned' ? 'selected' : '' }}>Returned</option>
                            <option value="transferred" {{ request('action') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            <option value="reassigned" {{ request('action') == 'reassigned' ? 'selected' : '' }}>Reassigned</option>
                            <option value="defective" {{ request('action') == 'defective' ? 'selected' : '' }}>Defective</option>
                            <option value="condemned" {{ request('action') == 'condemned' ? 'selected' : '' }}>Condemned</option>
                            <option value="disposed" {{ request('action') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm">
                            <svg class="-ml-1 mr-2 h-4 w-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- History Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Tag</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Previous Employee</th>
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('pc-units.show', $record->pcUnit) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        {{ $record->pcUnit->asset_tag }}
                                    </a>
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
                                    @elseif($record->action == 'reassigned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Reassigned
                                        </span>
                                    @elseif($record->action == 'defective')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Defective
                                        </span>
                                    @elseif($record->action == 'condemned')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Condemned
                                        </span>
                                    @elseif($record->action == 'disposed')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-black text-white">
                                            Disposed
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
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No history records found.
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