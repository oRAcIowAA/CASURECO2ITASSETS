<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase">
                {{ __('Mobile Devices') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Actions Bar -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 uppercase">ALL MOBILE DEVICES</h3>
                    <p class="text-sm text-gray-500 uppercase font-semibold">TOTAL: {{ $mobileDevices->total() }} UNITS</p>
                </div>
                <div class="flex space-x-2">
                     <a href="{{ route('activities.index') }}?category=Mobile+Device" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 shadow-sm bg-white">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        VIEW HISTORY
                    </a>
                </div>
            </div>

            <!-- Search & Filters -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm print:hidden">
                <form method="GET" action="{{ route('mobile-devices.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
                      x-data="{ 
                          department: '{{ request('department') }}', 
                          division: '{{ request('division') }}',
                          deptDivisions: @js($deptDivisions),
                          get filteredDivisions() {
                              return this.department ? (this.deptDivisions[this.department] || []) : [];
                          }
                      }">
                    <div class="col-span-1 md:col-span-2 lg:col-span-1">
                         <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" placeholder="SEARCH..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-500 uppercase font-semibold text-xs"
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div>
                        <select name="group" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                            <option value="">ALL LOCATIONS</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="department" x-model="department" @change="division = ''"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                            <option value="">ALL DEPARTMENTS</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}">{{ strtoupper($department) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="division" x-model="division" :disabled="!department"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 disabled:bg-gray-100 disabled:text-gray-400 font-semibold text-xs h-10">
                            <option value="">ALL DIVISIONS</option>
                            <template x-for="div in filteredDivisions" :key="div">
                                <option :value="div" x-text="div.toUpperCase()" :selected="division === div"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                            <option value="">ALL TYPES</option>
                            <option value="CELLPHONE" {{ request('type') == 'CELLPHONE' ? 'selected' : '' }}>CELLPHONE</option>
                        </select>
                    </div>

                    <div>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                            <option value="">ALL STATUSES</option>
                            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>ASSIGNED</option>
                            <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>AVAILABLE</option>
                            <option value="Defective" {{ request('status') == 'Defective' ? 'selected' : '' }}>DEFECTIVE</option>
                            <option value="Condemned" {{ request('status') == 'Condemned' ? 'selected' : '' }}>CONDEMNED</option>
                            <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>DISPOSED</option>
                        </select>
                    </div>

                    <div class="flex justify-start col-span-1 md:col-span-1 lg:col-span-1">
                        <button type="submit" class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm transition-colors uppercase">
                            SEARCH
                        </button>
                    </div>

                    <!-- Quick Filters -->
                    <div class="col-span-1 md:col-span-3 lg:col-span-6 flex items-center gap-2">
                         <a href="{{ route('mobile-devices.index', array_merge(request()->query(), ['status' => 'Available'])) }}" 
                            class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-bold rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 uppercase">
                            SHOW AVAILABLE (STANDBY)
                        </a>
                         @if(request('status') === 'Available')
                            <a href="{{ route('mobile-devices.index', request()->except('status')) }}" class="ml-2 text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase transition-colors">CLEAR FILTER</a>
                         @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Tag</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device / Brand / Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specs (CPU/RAM/Storage)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($mobileDevices as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                        <a href="{{ route('mobile-devices.show', $item) }}" class="hover:underline">{{ $item->asset_tag }}</a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->type }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->brand }} {{ $item->model }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs text-gray-900 font-semibold uppercase">{{ $item->processor ?: 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 uppercase">{{ $item->ram ?: 'N/A' }} RAM / {{ $item->storage ?: 'N/A' }} STORAGE</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ strtoupper(($item->employee ? $item->employee->group : $item->group) ?? 'N/A') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @php
                                                $source = $item->employee ?: $item;
                                                $locParts = array_filter([$source->department, $source->division]);
                                                echo strtoupper(implode(' / ', $locParts)) ?: 'N/A';
                                            @endphp
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->employee->full_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $item->status == 'assigned' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $item->status == 'available' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ in_array(strtolower($item->status), ['defective', 'condemned', 'disposed']) ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array(strtolower($item->status), ['assigned', 'available', 'defective', 'condemned', 'disposed']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('mobile-devices.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                        <a href="{{ route('mobile-devices.edit', $item) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <form action="{{ route('mobile-devices.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 uppercase font-bold">
                                        NO MOBILE DEVICES FOUND.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4">
                {{ $mobileDevices->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
