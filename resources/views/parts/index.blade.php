<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Parts Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-6">
                <form action="{{ route('parts.index') }}" method="GET" class="flex items-center">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by Asset Tag, Brand, Model, or Parts..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-bold">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('parts.index') }}" class="ml-4 text-sm font-semibold text-gray-500 hover:text-gray-700">Clear Search</a>
                    @endif
                </form>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-8">
                <!-- PC Units Spare Parts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            PC Units Spare Parts
                        </h3>
                        @if($pcUnits->isEmpty())
                            <p class="text-gray-500 italic text-sm">No PC Unit spare parts found.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($pcUnits as $pc)
                                    <div class="p-4 border rounded-xl bg-white shadow-sm border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <a href="{{ route('pc-units.show', $pc) }}" class="font-bold text-blue-600 hover:underline">
                                                {{ $pc->asset_tag }}
                                            </a>
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $pc->device_type }} {{ $pc->model }}</span>
                                        </div>
                                        <div x-data="{ editing: false }">
                                            <p class="text-xs text-gray-500 mb-2 italic">Disposed Device</p>
                                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[80px] relative">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-semibold underline text-blue-800">Available Parts:</span>
                                                    <button x-show="!editing" @click="editing = true" class="flex items-center text-xs text-blue-600 hover:text-blue-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        Edit
                                                    </button>
                                                </div>
                                                
                                                <div x-show="!editing" class="whitespace-pre-wrap">{!! e($pc->spare_parts) !!}</div>
                                                
                                                <div x-show="editing" x-cloak>
                                                    <form action="{{ route('parts.update', ['type' => 'pc-unit', 'id' => $pc->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="spare_parts" rows="4" class="w-full text-sm p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 mb-2">{{ $pc->spare_parts }}</textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="editing = false" class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800 font-bold">Cancel</button>
                                                            <button type="submit" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 font-bold">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Printers Spare Parts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold text-green-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            PRINTERS SPARE PARTS
                        </h3>
                        @if($printers->isEmpty())
                            <p class="text-gray-500 italic text-sm uppercase">NO PRINTER SPARE PARTS FOUND.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($printers as $printer)
                                    <div class="p-4 border rounded-xl bg-white shadow-sm border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <a href="{{ route('printers.show', $printer) }}" class="font-bold text-green-600 hover:underline">
                                                {{ $printer->asset_tag ?? 'NO TAG' }}
                                            </a>
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase">{{ $printer->brand }} {{ $printer->model }}</span>
                                        </div>
                                        <div x-data="{ editing: false }">
                                            <p class="text-xs text-gray-500 mb-2 italic uppercase">DISPOSED DEVICE</p>
                                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[80px] relative">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-semibold underline text-green-800 uppercase">Available Parts:</span>
                                                    <button x-show="!editing" @click="editing = true" class="flex items-center text-xs text-green-600 hover:text-green-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        EDIT
                                                    </button>
                                                </div>
                                                
                                                <div x-show="!editing" class="whitespace-pre-wrap">{!! e($printer->spare_parts) !!}</div>
                                                
                                                <div x-show="editing" x-cloak>
                                                    <form action="{{ route('parts.update', ['type' => 'printer', 'id' => $printer->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="spare_parts" rows="4" class="w-full text-sm p-2 border rounded-lg focus:ring-2 focus:ring-green-500 mb-2">{{ $printer->spare_parts }}</textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="editing = false" class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800 uppercase font-bold">CANCEL</button>
                                                            <button type="submit" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 uppercase font-bold">SAVE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Networking Spare Parts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold text-indigo-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"></path></svg>
                            NETWORKING SPARE PARTS
                        </h3>
                        @if($networkDevices->isEmpty())
                            <p class="text-gray-500 italic text-sm">No spare parts found.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($networkDevices as $device)
                                    <div class="p-4 border rounded-xl bg-white shadow-sm border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <a href="{{ route('network-devices.show', $device) }}" class="font-bold text-indigo-600 hover:underline">
                                                {{ $device->asset_tag ?? 'NO TAG' }}
                                            </a>
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase">{{ $device->brand }} {{ $device->model }}</span>
                                        </div>
                                        <div x-data="{ editing: false }">
                                            <p class="text-xs text-gray-500 mb-2 italic uppercase">DISPOSED DEVICE</p>
                                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[80px] relative">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-semibold underline text-indigo-800 uppercase">Available Parts:</span>
                                                    <button x-show="!editing" @click="editing = true" class="flex items-center text-xs text-indigo-600 hover:text-indigo-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        EDIT
                                                    </button>
                                                </div>
                                                
                                                <div x-show="!editing" class="whitespace-pre-wrap">{!! e($device->spare_parts) !!}</div>
                                                
                                                <div x-show="editing" x-cloak>
                                                    <form action="{{ route('parts.update', ['type' => 'network-device', 'id' => $device->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="spare_parts" rows="4" class="w-full text-sm p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 mb-2">{{ $device->spare_parts }}</textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="editing = false" class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800 uppercase font-bold">CANCEL</button>
                                                            <button type="submit" class="px-3 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700 uppercase font-bold">SAVE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Power Utilities Spare Parts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold text-purple-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            POWER UTILITIES SPARE PARTS
                        </h3>
                        @if($powerUtilities->isEmpty())
                            <p class="text-gray-500 italic text-sm uppercase">NO POWER UTILITY SPARE PARTS FOUND.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($powerUtilities as $pu)
                                    <div class="p-4 border rounded-xl bg-white shadow-sm border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <a href="{{ route('power-utilities.show', $pu) }}" class="font-bold text-purple-600 hover:underline">
                                                {{ $pu->asset_tag ?? 'NO TAG' }}
                                            </a>
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase">{{ $pu->type }} - {{ $pu->brand }}</span>
                                        </div>
                                        <div x-data="{ editing: false }">
                                            <p class="text-xs text-gray-500 mb-2 italic uppercase">DISPOSED DEVICE</p>
                                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[80px] relative">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-semibold underline text-purple-800 uppercase">Available Parts:</span>
                                                    <button x-show="!editing" @click="editing = true" class="flex items-center text-xs text-purple-600 hover:text-purple-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        EDIT
                                                    </button>
                                                </div>
                                                
                                                <div x-show="!editing" class="whitespace-pre-wrap">{!! e($pu->spare_parts) !!}</div>
                                                
                                                <div x-show="editing" x-cloak>
                                                    <form action="{{ route('parts.update', ['type' => 'power-utility', 'id' => $pu->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="spare_parts" rows="4" class="w-full text-sm p-2 border rounded-lg focus:ring-2 focus:ring-purple-500 mb-2">{{ $pu->spare_parts }}</textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="editing = false" class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800 uppercase font-bold">CANCEL</button>
                                                            <button type="submit" class="px-3 py-1 text-xs bg-purple-600 text-white rounded hover:bg-purple-700 uppercase font-bold">SAVE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Mobile Devices Spare Parts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold text-orange-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            MOBILE DEVICES SPARE PARTS
                        </h3>
                        @if($mobileDevices->isEmpty())
                            <p class="text-gray-500 italic text-sm uppercase">NO MOBILE DEVICE SPARE PARTS FOUND.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($mobileDevices as $device)
                                    <div class="p-4 border rounded-xl bg-white shadow-sm border-gray-200 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-2">
                                            <a href="{{ route('mobile-devices.show', $device) }}" class="font-bold text-orange-600 hover:underline">
                                                {{ $device->asset_tag ?? 'NO TAG' }}
                                            </a>
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase">{{ $device->brand }} {{ $device->model }}</span>
                                        </div>
                                        <div x-data="{ editing: false }">
                                            <p class="text-xs text-gray-500 mb-2 italic uppercase">DISPOSED DEVICE</p>
                                            <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 min-h-[80px] relative">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="font-semibold underline text-orange-800 uppercase">Available Parts:</span>
                                                    <button x-show="!editing" @click="editing = true" class="flex items-center text-xs text-orange-600 hover:text-orange-800 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                        EDIT
                                                    </button>
                                                </div>
                                                
                                                <div x-show="!editing" class="whitespace-pre-wrap">{!! e($device->spare_parts) !!}</div>
                                                
                                                <div x-show="editing" x-cloak>
                                                    <form action="{{ route('parts.update', ['type' => 'mobile-device', 'id' => $device->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <textarea name="spare_parts" rows="4" class="w-full text-sm p-2 border rounded-lg focus:ring-2 focus:ring-orange-500 mb-2">{{ $device->spare_parts }}</textarea>
                                                        <div class="flex justify-end space-x-2">
                                                            <button type="button" @click="editing = false" class="px-3 py-1 text-xs text-gray-600 hover:text-gray-800 uppercase font-bold">CANCEL</button>
                                                            <button type="submit" class="px-3 py-1 text-xs bg-orange-600 text-white rounded hover:bg-orange-700 uppercase font-bold">SAVE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
