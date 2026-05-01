<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('PC Assignment History: ') . $pcUnit->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Back Button & Actions -->
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('pc-units.show', $pcUnit) }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium">
                    ← Back to PC Details
                </a>
                <a href="{{ route('pc-history.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50">
                    View All History
                </a>
            </div>

            <!-- PC Unit Info Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $pcUnit->asset_tag }}</h1>
                            <p class="text-gray-600">{{ $pcUnit->model }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($pcUnit->status == 'available') bg-green-100 text-green-800 @elseif($pcUnit->status == 'not_available') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $pcUnit->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Timeline -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Assignment Timeline</h3>
                    <p class="text-sm text-gray-500">Total assignments: {{ $history->count() }}</p>
                </div>
                
                @if($history->count() > 0)
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($history as $index => $record)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex items-start group">
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-500 ring-8 ring-white">
                                            <span class="text-white font-bold">{{ $index + 1 }}</span>
                                        </span>
                                        <div class="ml-4 min-w-0 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $record->employee->full_name ?? 'N/A' }}
                                                </p>
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        @if($record->action == 'assigned') bg-green-100 text-green-800 @elseif($record->action == 'returned') bg-red-100 text-red-800 @elseif($record->action == 'transferred') bg-blue-100 text-blue-800 @else bg-purple-100 text-purple-800 @endif">
                                                        {{ ucfirst($record->action) }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $record->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($record->previousEmployee)
                                            <p class="mt-1 text-sm text-gray-500">
                                                <span class="font-medium">From:</span> {{ $record->previousEmployee->full_name }}
                                            </p>
                                            @endif
                                            
                                            @if($record->notes)
                                            <p class="mt-1 text-sm text-gray-600">
                                                <span class="font-medium">Notes:</span> {{ $record->notes }}
                                            </p>
                                            @endif
                                            
                                            @if($record->createdBy)
                                            <p class="mt-1 text-xs text-gray-400">
                                                Recorded by: {{ $record->createdBy->name }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @else
                <div class="px-6 py-8 text-center text-gray-500">
                    No assignment history available for this PC unit.
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


