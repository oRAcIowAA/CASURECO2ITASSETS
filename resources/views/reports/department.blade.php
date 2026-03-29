<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('REPORTS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Actions / Navigation -->
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 uppercase">
                    DEPARTMENT ASSETS
                    <span class="text-sm font-normal text-gray-500 ml-2 uppercase">({{ $items->count() }} ITEMS FOUND)</span>
                </h3>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 print:hidden">
                <!-- Total Devices -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Devices</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-full">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                </div>

                <!-- PC Units -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">PC Units</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['pc_units']['total'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 flex justify-between">
                        <span>Desktop: <span class="font-semibold text-gray-900">{{ $stats['pc_units']['Desktop'] }}</span></span>
                        <span>All-in-One: <span class="font-semibold text-gray-900">{{ $stats['pc_units']['All-in-One'] }}</span></span>
                        <span>Laptop: <span class="font-semibold text-gray-900">{{ $stats['pc_units']['Laptop'] }}</span></span>
                        <span>Server: <span class="font-semibold text-gray-900">{{ $stats['pc_units']['Server'] }}</span></span>
                    </div>
                </div>

                <!-- Network Devices -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Network</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['network_devices']['total'] }}</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 flex justify-start">
                        <span class="mr-6">Router: <span class="font-semibold text-gray-900">{{ $stats['network_devices']['Router'] }}</span></span>
                        <span>Switch: <span class="font-semibold text-gray-900">{{ $stats['network_devices']['Switch'] }}</span></span>
                    </div>
                </div>

                <!-- Printers -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Printers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['printers']['total'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6 print:hidden">
                <form id="report-filter-form" method="GET" action="{{ route('reports.department') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    
                    <!-- Type Filter -->
                    <div x-data="{
                            open: false,
                            selectedTypes: {{ json_encode((array)request('type', [])) }},
                            pcTypes: ['Desktop', 'Laptop', 'Server', 'All-in-One'],
                            netTypes: ['Router', 'Switch'],
                            get allTypes() { return [...this.pcTypes, ...this.netTypes, 'Printer']; },
                            toggleType(type) {
                                if (this.selectedTypes.includes(type)) {
                                    this.selectedTypes = this.selectedTypes.filter(t => t !== type);
                                } else {
                                    this.selectedTypes.push(type);
                                }
                            },
                            toggleCategory(categoryTypes) {
                                const allSelected = categoryTypes.every(t => this.selectedTypes.includes(t));
                                if (allSelected) {
                                    this.selectedTypes = this.selectedTypes.filter(t => !categoryTypes.includes(t));
                                } else {
                                    const toAdd = categoryTypes.filter(t => !this.selectedTypes.includes(t));
                                    this.selectedTypes = [...this.selectedTypes, ...toAdd];
                                }
                            },
                            toggleAll() {
                                this.selectedTypes = [];
                            },
                            isTypeSelected(type) {
                                return this.selectedTypes.includes(type);
                            },
                            isCategorySelected(categoryTypes) {
                                return categoryTypes.every(t => this.selectedTypes.includes(t));
                            },
                            isAllSelected() {
                                return this.allTypes.every(t => this.selectedTypes.includes(t)) || this.selectedTypes.length === 0;
                            },
                            isCategoryIndeterminate(categoryTypes) {
                                const count = categoryTypes.filter(t => this.selectedTypes.includes(t)).length;
                                return count > 0 && count < categoryTypes.length;
                            }
                        }" class="relative col-span-1" @click.away="open = false">
                        
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">TYPE</label>
                        
                        <button type="button" @click="open = !open" 
                            class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-10">
                            <span class="block truncate uppercase font-semibold" x-text="selectedTypes.length ? selectedTypes.join(', ') : 'ALL DEVICES'"></span>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </button>
                    
                        <div x-show="open" 
                            class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                            
                            <!-- All Devices -->
                            <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
                                <div @click="toggleAll()" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                    <span>ALL DEVICES</span>
                                    <input type="checkbox" :checked="isAllSelected()" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                            </div>

                            <!-- PC Units -->
                            <div class="px-3 py-2">
                                <div @click="toggleCategory(pcTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                    <span>PC UNITS</span>
                                    <input type="checkbox" :checked="isCategorySelected(pcTypes)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-4 mt-1 space-y-1">
                                    <template x-for="type in pcTypes">
                                        <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 rounded p-1">
                                            <input type="checkbox" :checked="isTypeSelected(type)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                            <span x-text="type.toUpperCase()" class="text-gray-700"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-100"></div>

                            <!-- Network Devices -->
                            <div class="px-3 py-2">
                                <div @click="toggleCategory(netTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                    <span>NETWORK DEVICES</span>
                                    <input type="checkbox" :checked="isCategorySelected(netTypes)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                                <div class="ml-4 mt-1 space-y-1">
                                    <template x-for="type in netTypes">
                                        <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 rounded p-1">
                                            <input type="checkbox" :checked="isTypeSelected(type)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                            <span x-text="type.toUpperCase()" class="text-gray-700"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="border-t border-gray-100"></div>

                            <!-- Printers -->
                            <div class="px-3 py-2">
                                <div @click="toggleType('Printer')" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                    <span>PRINTERS</span>
                                    <input type="checkbox" :checked="isTypeSelected('Printer')" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Inputs for Form Submission -->
                        <template x-for="type in selectedTypes">
                            <input type="hidden" name="type[]" :value="type">
                        </template>
                    </div>
                    
                    <!-- Search -->
                    <div class="col-span-1 border-transparent">
                        <label for="search" class="block text-sm font-bold text-gray-700 mb-1 uppercase">SEARCH</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="ASSET TAG, MODEL..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm uppercase font-semibold">
                    </div>

                    <!-- Group -->
                    <div>
                         <label for="group" class="block text-sm font-medium text-gray-700 mb-1">GROUP</label>
                         <select name="group" id="group" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">ALL GROUPS</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">DEPARTMENT</label>
                        <select name="department" id="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">ALL DEPARTMENTS</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ strtoupper($dept) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Division -->
                    <div>
                         <label for="division" class="block text-sm font-medium text-gray-700 mb-1">DIVISION</label>
                         <select name="division" id="division" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">ALL DIVISIONS</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division }}" {{ request('division') == $division ? 'selected' : '' }}>{{ strtoupper($division) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">STATUS</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">ALL STATUSES</option>
                            <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>ASSIGNED</option>
                            <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>AVAILABLE</option>
                            <option value="Defective" {{ request('status') == 'Defective' ? 'selected' : '' }}>DEFECTIVE</option>
                            <option value="Condemned" {{ request('status') == 'Condemned' ? 'selected' : '' }}>CONDEMNED</option>
                            <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>DISPOSED</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-span-1 md:col-span-2 lg:col-span-6 flex justify-start space-x-3 mt-2">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            APPLY FILTERS
                        </button>
                        
                        <a href="{{ route('reports.department') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-bold rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            RESET
                        </a>

                        <a href="{{ route('reports.print-department', request()->query()) }}" target="_blank" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-bold rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            PRINT LIST
                        </a>
                    </div>
                </form>
            </div>

            <!-- Department Matrix Table -->
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 mb-8 mt-4" id="report-container">
                <div class="p-6 border-b border-gray-200 bg-white">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">DEPARTMENT ASSETS MATRIX</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-200 bg-white whitespace-nowrap text-xs">
                        <thead>
                            <tr class="bg-gray-50 uppercase font-bold tracking-wider text-gray-500 text-xs">
                                <th class="border border-gray-200 px-4 py-3 text-left sticky left-0 bg-gray-50 z-10 w-64">DEPARTMENTS & AREA OFFICES</th>
                                @foreach($deviceColumns as $colType)
                                    <th class="border border-gray-200 px-4 py-3 text-center">{{ $colType }}</th>
                                @endforeach
                                <th class="border border-gray-200 px-4 py-3 text-center bg-gray-100 font-bold sticky right-0">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 leading-relaxed">
                            @foreach($reportMatrix as $row)
                            <tr class="hover:bg-blue-50 transition-colors duration-150">
                                <td class="border border-gray-200 px-4 py-3 text-[11px] sm:text-xs font-semibold uppercase tracking-wide bg-white sticky left-0 z-10 text-gray-800">{{ $row['department'] }}</td>
                                
                                <!-- Count by Columns (Devices) -->
                                @foreach($deviceColumns as $colType)
                                    <td class="border border-gray-200 px-4 py-3 text-center {{ $row['types'][$colType] > 0 ? 'text-gray-900 font-medium' : 'text-gray-400' }}">
                                        {{ $row['types'][$colType] > 0 ? $row['types'][$colType] : '-' }}
                                    </td>
                                @endforeach

                                <!-- Row Total -->
                                <td class="border border-gray-200 px-4 py-3 text-center font-bold bg-gray-50 text-gray-800 sticky right-0">
                                    {{ $row['row_total'] }}
                                </td>
                            </tr>
                            @endforeach
                            
                            <!-- Grand Totals Footer -->
                            <tr class="bg-gray-100 font-bold uppercase text-gray-800">
                                <td class="border border-gray-200 px-4 py-4 tracking-wide text-xs sticky left-0 bg-gray-100 z-10">TOTAL</td>
                                @foreach($deviceColumns as $colType)
                                    <td class="border border-gray-200 px-4 py-4 text-center text-sm {{ $totals['col_totals'][$colType] > 0 ? 'text-blue-600' : 'text-gray-500' }}">
                                        {{ $totals['col_totals'][$colType] > 0 ? $totals['col_totals'][$colType] : '0' }}
                                    </td>
                                @endforeach
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm font-bold bg-gray-200 text-gray-900 sticky right-0">
                                    {{ $totals['total_issued'] }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summaries (Dynamic based on selected active device rows) -->
                <div class="px-6 py-6 bg-white flex flex-col items-start space-y-1 text-gray-800 text-sm">
                    @foreach($deviceColumns as $colType)
                        <div class="font-medium">Issued {{ $colType }}: <span class="text-base text-gray-900 ml-1">{{ $totals['col_totals'][$colType] ?? 0 }}</span></div>
                    @endforeach
                    
                    <div class="font-bold pt-2 border-t border-gray-200 w-full max-w-sm mt-2">Total Devices Assessed: <span class="text-lg text-gray-900 ml-1">{{ $totals['total_issued'] }}</span></div>
                </div>
            </div>

            <!-- Master List Style Devices Table (Units on the rows, Departments as column) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                 <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 uppercase">Filtered Master List View</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Asset Tag</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type / Brand / Model</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Assigned To</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($items as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $item->asset_tag }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ strtoupper($item->original->group ?? 'N/A') }}</div>
                                        <div class="text-xs text-gray-500 font-normal">
                                            @php
                                                $locParts = array_filter([$item->original->department, $item->original->division]);
                                                echo strtoupper(implode(' / ', $locParts)) ?: 'N/A';
                                            @endphp
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route($item->view_route, $item->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ str_replace($item->asset_tag . ' - ', '', $item->type_model) }}
                                        </a>
                                        <div class="text-xs text-gray-500 mt-1">IP: {{ $item->ip_address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->category }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->assigned_to }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ strtolower($item->status) == 'assigned' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ strtolower($item->status) == 'available' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ strtolower($item->status) == 'defective' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ strtolower($item->status) == 'condemned' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        NO RESULTS FOUND MATCHING YOUR FILTERS.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Print styling -->
            <style>
                @media print {
                    body { background-color: white !important; }
                    #report-container { box-shadow: none !important; border: none !important; margin-bottom: 0 !important; }
                    @page { margin: 10mm; }
                }
            </style>
        </div>
    </div>
</x-app-layout>
