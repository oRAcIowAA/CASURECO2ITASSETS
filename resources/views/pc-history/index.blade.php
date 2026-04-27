<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PC ASSIGNMENT HISTORY') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full sm:px-6 lg:px-8">
            
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

            <div class="mb-4">
                <a href="{{ route('pc-units.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    &larr; BACK TO PC UNITS
                </a>
            </div>

            <!-- Header -->
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                            @if(request('group') || request('division') || request('department'))
                                HISTORY FOR {{ request('department') ? strtoupper(request('department')) . (request('division') || request('group') ? ' / ' : '') : '' }}{{ request('division') ? strtoupper(request('division')) . (request('group') ? ' / ' : '') : '' }}{{ request('group') ? strtoupper(request('group')) : '' }}
                            @else
                                ALL PC ASSIGNMENT RECORDS
                            @endif
                    </h3>
                    <p class="text-sm text-gray-500 uppercase font-semibold">TOTAL: {{ $history->total() }} RECORDS</p>
                </div>
            </div>


            <!-- Filter Bar -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <form method="GET" action="{{ route('pc-history.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SEARCH</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" placeholder="SEARCH ASSET TAG OR EMPLOYEE..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-500 uppercase font-semibold text-xs"
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ACTION</label>
                        <select name="action" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">ALL ACTIONS</option>
                            <option value="assigned" {{ request('action') == 'assigned' ? 'selected' : '' }}>ASSIGNED</option>
                            <option value="returned" {{ request('action') == 'returned' ? 'selected' : '' }}>RETURNED</option>
                            <option value="transferred" {{ request('action') == 'transferred' ? 'selected' : '' }}>TRANSFERRED</option>
                            <option value="reassigned" {{ request('action') == 'reassigned' ? 'selected' : '' }}>REASSIGNED</option>
                            <option value="defective" {{ request('action') == 'defective' ? 'selected' : '' }}>DEFECTIVE</option>
                            <option value="condemned" {{ request('action') == 'condemned' ? 'selected' : '' }}>CONDEMNED</option>
                            <option value="disposed" {{ request('action') == 'disposed' ? 'selected' : '' }}>DISPOSED</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm transition-colors uppercase">
                            SEARCH
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
                                    @php
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
                                        $colorClass = $actionColors[$record->action] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                        {{ strtoupper($record->action) }}
                                    </span>
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
                                    NO HISTORY RECORDS FOUND.
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