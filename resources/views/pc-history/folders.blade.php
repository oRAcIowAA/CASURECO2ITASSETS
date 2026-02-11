<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PC Lifecycle History') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('pc-history.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-medium">
                    List View
                </a>
                <a href="{{ route('pc-history.index', ['view' => 'folders']) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                    Folder View
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{ activeBranch: null, activeDept: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-4">
                @foreach($branches as $branch)
                <!-- Branch Card -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                    <div class="bg-blue-900 px-6 py-4 flex items-center justify-between cursor-pointer" @click="activeBranch = (activeBranch === {{ $branch->id }} ? null : {{ $branch->id }})">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                            <h3 class="text-lg font-bold text-white">{{ $branch->branch_name }}</h3>
                        </div>
                        <svg class="w-5 h-5 text-white transform transition-transform" :class="activeBranch === {{ $branch->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>

                    <div x-show="activeBranch === {{ $branch->id }}" x-collapse>
                        <div class="p-4 space-y-3 bg-gray-50">
                            @forelse($branch->departments as $dept)
                            <!-- Department Accordion -->
                            <div class="bg-white rounded-md border border-gray-200 shadow-sm">
                                <div class="px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-blue-50 transition-colors" @click="activeDept === {{ $dept->id }} ? activeDept = null : activeDept = {{ $dept->id }}">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                        <span class="font-semibold text-gray-800">{{ $dept->department_name }}</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 rounded-full px-2 py-0.5">{{ $dept->pcUnits->count() }} Assets</span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 transform transition-transform" :class="activeDept === {{ $dept->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>

                                <div x-show="activeDept === {{ $dept->id }}" x-collapse class="border-t border-gray-100">
                                    <div class="p-4 bg-white">
                                        @forelse($dept->pcUnits as $pc)
                                        <!-- PC History Block -->
                                        <div class="mb-6 last:mb-0">
                                            <div class="flex items-center justify-between mb-3 pb-2 border-b border-dashed border-gray-200">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-bold text-gray-900">{{ $pc->asset_tag }}</span>
                                                    <span class="text-sm text-gray-500">({{ $pc->model }})</span>
                                                </div>
                                                <a href="{{ route('pc-units.show', $pc) }}" class="text-xs text-indigo-600 hover:underline">Full Details</a>
                                            </div>

                                            <div class="ml-4 border-l-2 border-gray-100 pl-6 relative space-y-4">
                                                @forelse($pc->history as $log)
                                                <div class="relative">
                                                    <!-- Timeline Dot -->
                                                    <div class="absolute -left-[31px] top-1.5 w-4 h-4 rounded-full border-2 border-white 
                                                        {{ $log->action == 'assigned' ? 'bg-green-500' : ($log->action == 'returned' ? 'bg-red-500' : 'bg-blue-500') }}">
                                                    </div>
                                                    
                                                    <div class="text-sm">
                                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                                            <span>{{ $log->created_at->format('M d, Y') }}</span>
                                                            <span>By: {{ $log->createdBy->name ?? 'System' }}</span>
                                                        </div>
                                                        <p class="font-medium text-gray-900">
                                                            {{ strtoupper($log->action) }}: 
                                                            @if($log->employee)
                                                                {{ $log->employee->full_name }}
                                                            @elseif($log->action == 'returned')
                                                                Back to Stock
                                                            @else
                                                                {{ ucfirst($log->action) }}
                                                            @endif
                                                        </p>
                                                        @if($log->notes)
                                                            <p class="text-gray-600 italic mt-1 text-xs">"{{ $log->notes }}"</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @empty
                                                <p class="text-xs text-gray-400 italic">No history records found for this unit.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        @empty
                                            <p class="text-sm text-gray-500 italic p-4">No assets found in this department.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No departments found in this branch.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
