<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Power Utility Assignment History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter Bar -->
            <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('power-utility-history.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm uppercase font-bold" 
                               placeholder="Search by Asset Tag, Type, Model or Employee...">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <select name="action" onchange="this.form.submit()" 
                                class="block w-full py-2 pl-3 pr-10 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg uppercase font-bold">
                            <option value="">All Actions</option>
                            <option value="assigned" {{ request('action') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="returned" {{ request('action') == 'returned' ? 'selected' : '' }}>Returned</option>
                            <option value="transferred" {{ request('action') == 'transferred' ? 'selected' : '' }}>Transferred</option>
                            <option value="defective" {{ request('action') == 'defective' ? 'selected' : '' }}>Defective</option>
                            <option value="condemned" {{ request('action') == 'condemned' ? 'selected' : '' }}>Condemned</option>
                            <option value="disposed" {{ request('action') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                            <option value="restored" {{ request('action') == 'restored' ? 'selected' : '' }}>Restored</option>
                            <option value="repaired" {{ request('action') == 'repaired' ? 'selected' : '' }}>Repaired</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'action']))
                        <a href="{{ route('power-utility-history.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-bold rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            Clear
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- History List Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Asset Tag / Device</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Action</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Employee</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Notes</th>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date & Recorded By</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($history as $record)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 uppercase tracking-tight">
                                                    {{ $record->powerUtility->asset_tag ?? 'N/A' }}
                                                </div>
                                                <div class="text-[11px] text-gray-500 uppercase font-medium">
                                                    {{ $record->powerUtility ? $record->powerUtility->type . ' ' . $record->powerUtility->brand . ' ' . $record->powerUtility->model : 'Unknown Device' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $action = strtolower($record->action);
                                            $badgeColors = [
                                                'assigned' => 'bg-green-100 text-green-800',
                                                'returned' => 'bg-blue-100 text-blue-800',
                                                'transferred' => 'bg-indigo-100 text-indigo-800',
                                                'disposed' => 'bg-red-100 text-red-800',
                                                'condemned' => 'bg-red-900 text-white',
                                                'defective' => 'bg-red-50 text-red-600',
                                                'repaired' => 'bg-emerald-100 text-emerald-800',
                                                'restored' => 'bg-green-100 text-green-800',
                                                'edited' => 'bg-amber-100 text-amber-800'
                                            ];
                                            $colorClass = $badgeColors[$action] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $colorClass }}">
                                            {{ $record->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 uppercase">
                                            {{ $record->employee->full_name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="text-[11px] text-gray-600 uppercase line-clamp-2 leading-relaxed">
                                            {{ $record->notes }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-[11px] font-bold text-gray-900 uppercase">
                                            {{ $record->created_at->format('M d, Y h:i A') }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 uppercase italic">
                                            By: {{ $record->createdBy->name ?? 'System' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                        @if($record->powerUtility)
                                        <a href="{{ route('power-utility-history.show', $record->powerUtility->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold uppercase text-xs">
                                            View Unit Timeline
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 bg-gray-50">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="uppercase font-bold tracking-widest text-sm">No assignment history found matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($history->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $history->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
