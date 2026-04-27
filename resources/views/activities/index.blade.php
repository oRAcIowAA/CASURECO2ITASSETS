<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('RECENT ACTIVITY LOG') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-bold uppercase text-xs">
                    &larr; BACK TO DASHBOARD
                </a>
            </div>

            <!-- Header -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 uppercase">
                    ALL ASSET ACTIVITY RECORDS
                </h3>
                <p class="text-xs text-gray-500 uppercase font-bold mt-1">TOTAL: {{ $history->total() }} RECORDS</p>
            </div>


            <!-- Filter Bar -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                <form method="GET" action="{{ route('activities.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-widest">SEARCH</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" placeholder="SEARCH ASSET TAG, EMPLOYEE, OR TYPE..." 
                                   class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-500 uppercase font-bold text-[11px]"
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-widest">ACTION</label>
                        <select name="action" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-xs font-bold uppercase">
                            <option value="">ALL ACTIONS</option>
                            <option value="assigned" {{ request('action') == 'assigned' ? 'selected' : '' }}>ASSIGNED</option>
                            <option value="returned" {{ request('action') == 'returned' ? 'selected' : '' }}>RETURNED</option>
                            <option value="transferred" {{ request('action') == 'transferred' ? 'selected' : '' }}>TRANSFERRED</option>
                            <option value="reassigned" {{ request('action') == 'reassigned' ? 'selected' : '' }}>REASSIGNED</option>
                            <option value="defective" {{ request('action') == 'defective' ? 'selected' : '' }}>DEFECTIVE</option>
                            <option value="condemned" {{ request('action') == 'condemned' ? 'selected' : '' }}>CONDEMNED</option>
                            <option value="disposed" {{ request('action') == 'disposed' ? 'selected' : '' }}>DISPOSED</option>
                            <option value="restored" {{ request('action') == 'restored' ? 'selected' : '' }}>RESTORED</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm transition-colors uppercase text-xs tracking-widest">
                            FILTER
                        </button>
                    </div>
                </form>
            </div>

            <!-- History Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Asset Type</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Asset Tag</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Action</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Employee</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Notes</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Recorded By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($history as $record)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium">
                                    {{ $record->created_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700 font-bold uppercase">
                                    {{ $record->device_type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ $record->device_link }}" 
                                       class="text-indigo-600 hover:text-indigo-900 font-bold text-xs uppercase underline">
                                        {{ $record->asset_tag }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $actionColors = [
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
                                        $colorClass = $actionColors[strtolower($record->action)] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-0.5 inline-flex text-[10px] leading-4 font-black rounded-full {{ $colorClass }} uppercase tracking-tighter">
                                        {{ $record->action }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900 font-bold uppercase">
                                    {{ $record->employee_name }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 italic max-w-xs truncate">
                                    {{ $record->notes ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-medium uppercase">
                                    {{ $record->recorded_by }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 uppercase font-black tracking-widest text-sm bg-gray-50">
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
