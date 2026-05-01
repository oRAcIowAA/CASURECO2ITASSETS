<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mobile Device Assignment History: ') . $mobileDevice->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Back Button & Actions -->
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('mobile-devices.show', $mobileDevice) }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-800 font-bold text-xs">
                    ← Back to Mobile Device Details
                </a>
                <a href="{{ route('mobile-device-history.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-bold rounded-md text-gray-700 hover:bg-gray-50">
                    View All History
                </a>
            </div>

            <!-- Mobile Device Info Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 border border-gray-100">
                <div class="p-6">
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $mobileDevice->asset_tag }}</h1>
                            <p class="text-gray-600 tracking-wide">{{ $mobileDevice->type }} - {{ $mobileDevice->brand }} {{ $mobileDevice->model }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                    @if(strtolower($mobileDevice->status) == 'available') bg-green-100 text-green-800 @elseif(in_array(strtolower($mobileDevice->status), ['disposed', 'condemned', 'defective'])) bg-red-100 text-red-800 @else bg-indigo-100 text-indigo-800 @endif">
                                    {{ ucfirst($mobileDevice->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Timeline -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Assignment Timeline</h3>
                    <p class="text-sm text-gray-500 font-medium tracking-tighter">Total events recorded: {{ $history->count() }}</p>
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
                                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-500 ring-8 ring-white shadow-sm">
                                            <span class="text-white font-bold text-xs">{{ $history->count() - $index }}</span>
                                        </span>
                                        <div class="ml-4 min-w-0 flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $record->employee->full_name ?? 'N/A' }}
                                                </p>
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                        @if($record->action == 'assigned' || $record->action == 'restored' || $record->action == 'repaired') bg-green-100 text-green-800 
                                                        @elseif($record->action == 'returned' || $record->action == 'disposed' || $record->action == 'condemned' || $record->action == 'defective') bg-red-100 text-red-800 
                                                        @elseif($record->action == 'transferred') bg-blue-100 text-blue-800 
                                                        @else bg-purple-100 text-purple-800 @endif">
                                                        {{ $record->action }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-500 font-medium whitespace-nowrap">
                                                        {{ $record->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            @if($record->previousEmployee)
                                            <p class="mt-1 text-xs text-gray-500">
                                                <span class="font-bold">Former User:</span> {{ $record->previousEmployee->full_name }}
                                            </p>
                                            @endif
                                            
                                            <div class="mt-1 text-xs text-gray-600 leading-relaxed font-medium">
                                                @if(str_contains($record->notes, 'EDITED RECORD DETAILS'))
                                                    <div class="font-mono text-[9px] text-indigo-600 bg-indigo-50 p-2 rounded border border-indigo-100 mt-2">
                                                        {!! str_replace(', ', '<br>', str_replace('EDITED RECORD DETAILS: ', '', $record->notes)) !!}
                                                    </div>
                                                @else
                                                    <span class="font-bold">Notes:</span> {{ $record->notes ?? 'No notes recorded.' }}
                                                @endif
                                            </div>
                                            
                                            @if($record->createdBy)
                                            <p class="mt-2 text-[10px] text-gray-400 italic">
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
                <div class="px-6 py-8 text-center text-gray-500 uppercase font-bold tracking-widest text-sm">
                    No assignment history available for this mobile device.
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>


