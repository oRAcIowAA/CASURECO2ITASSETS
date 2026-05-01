<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asset QR Codes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            
            <!-- Search & Filters -->
            <div class="mb-6 p-6 bg-white rounded-lg border border-gray-200 shadow-sm print:hidden">
                <form method="GET" action="{{ route('qr-assets.index') }}" 
                      class="mb-0"
                      x-data="{ 
                          department: '{{ request('department') }}', 
                          division: '{{ request('division') }}',
                          deptDivisions: @js($deptDivisions),
                          get filteredDivisions() {
                              return this.department ? (this.deptDivisions[this.department] || []) : [];
                          }
                      }">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        
                        <!-- Type Filter -->
                        <div x-data="{
                                open: false,
                                selectedTypes: {{ json_encode((array)request('type', [])) }},
                                pcTypes: ['Desktop', 'Laptop', 'Server', 'All-in-One'],
                                netTypes: ['Router', 'Switch'],
                                printerTypes: ['Printer', 'Scanner', 'Portable Printer'],
                                powerTypes: ['UPS', 'AVR'],
                                mobileTypes: ['Cellphone'],
                                get allTypes() { return [...this.pcTypes, ...this.netTypes, ...this.printerTypes, ...this.powerTypes, ...this.mobileTypes]; },
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
                                    return this.selectedTypes.length === 0;
                                }
                            }" class="relative col-span-1" @click.away="open = false">
                            
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">TYPE</label>
                            
                            <button type="button" @click="open = !open" 
                                class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-10 transition-all duration-200">
                                <span class="block truncate uppercase font-semibold text-xs text-gray-700" x-text="selectedTypes.length ? selectedTypes.join(', ') : 'ALL DEVICES'"></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </button>
                        
                            <div x-show="open" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="absolute hide-scrollbar z-50 mt-1 w-64 bg-white shadow-xl max-h-80 rounded-xl py-2 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-xs">
                                
                                <div @click="toggleAll()" class="px-3 py-2 flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <span>ALL DEVICES</span>
                                    <input type="checkbox" :checked="isAllSelected()" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                </div>

                                <div class="border-t border-gray-100 my-1"></div>

                                <div class="px-3 py-2">
                                    <div @click="toggleCategory(pcTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <span>PC UNITS</span>
                                        <div class="h-4 w-4 border border-gray-300 rounded flex items-center justify-center" :class="isCategorySelected(pcTypes) ? 'bg-indigo-600 border-indigo-600' : ''">
                                            <svg x-show="isCategorySelected(pcTypes)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 mt-1 space-y-1">
                                        <template x-for="type in pcTypes">
                                            <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                                                <input type="checkbox" :checked="isTypeSelected(type)" class="h-3.5 w-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                <span x-text="type.toUpperCase()" class="text-gray-600 font-medium"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="px-3 py-2">
                                    <div @click="toggleCategory(netTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <span>NETWORK DEVICES</span>
                                        <div class="h-4 w-4 border border-gray-300 rounded flex items-center justify-center" :class="isCategorySelected(netTypes) ? 'bg-indigo-600 border-indigo-600' : ''">
                                            <svg x-show="isCategorySelected(netTypes)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 mt-1 space-y-1">
                                        <template x-for="type in netTypes">
                                            <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded">
                                                <input type="checkbox" :checked="isTypeSelected(type)" class="h-3.5 w-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                <span x-text="type.toUpperCase()" class="text-gray-600 font-medium"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="px-3 py-2">
                                    <div @click="toggleCategory(printerTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <span>PRINTERS</span>
                                        <div class="h-4 w-4 border border-gray-300 rounded flex items-center justify-center" :class="isCategorySelected(printerTypes) ? 'bg-indigo-600 border-indigo-600' : ''">
                                            <svg x-show="isCategorySelected(printerTypes)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 mt-1 space-y-1">
                                        <template x-for="type in printerTypes">
                                            <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded transition-colors">
                                                <input type="checkbox" :checked="isTypeSelected(type)" class="h-3.5 w-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                <span x-text="type.toUpperCase()" class="text-gray-600 font-medium"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="px-3 py-2">
                                    <div @click="toggleCategory(powerTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <span>POWER UTILITIES</span>
                                        <div class="h-4 w-4 border border-gray-300 rounded flex items-center justify-center" :class="isCategorySelected(powerTypes) ? 'bg-indigo-600 border-indigo-600' : ''">
                                            <svg x-show="isCategorySelected(powerTypes)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 mt-1 space-y-1">
                                        <template x-for="type in powerTypes">
                                            <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded transition-colors">
                                                <input type="checkbox" :checked="isTypeSelected(type)" class="h-3.5 w-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                <span x-text="type.toUpperCase()" class="text-gray-600 font-medium"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="px-3 py-2">
                                    <div @click="toggleCategory(mobileTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-50 p-1 rounded">
                                        <span>MOBILE DEVICES</span>
                                        <div class="h-4 w-4 border border-gray-300 rounded flex items-center justify-center" :class="isCategorySelected(mobileTypes) ? 'bg-indigo-600 border-indigo-600' : ''">
                                            <svg x-show="isCategorySelected(mobileTypes)" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                    <div class="ml-4 mt-1 space-y-1">
                                        <template x-for="type in mobileTypes">
                                            <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 p-1 rounded transition-colors">
                                                <input type="checkbox" :checked="isTypeSelected(type)" class="h-3.5 w-3.5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                <span x-text="type.toUpperCase()" class="text-gray-600 font-medium"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <!-- Hidden Inputs for Form Submission -->
                            <template x-for="type in selectedTypes">
                                <input type="hidden" name="type[]" :value="type">
                            </template>
                        </div>
                        
                        <!-- Search Input -->
                        <div class="col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SEARCH</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="search" placeholder="ASSET TAG, MODEL, IP..." 
                                       class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-400 uppercase font-semibold text-xs shadow-sm h-10 transition-all duration-200"
                                       value="{{ request('search') }}">
                            </div>
                        </div>
 
                        <!-- Group -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">LOCATION</label>
                            <select name="group" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                                <option value="">ALL LOCATIONS</option>
                                @foreach($groups ?? [] as $group)
                                    <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DEPARTMENT</label>
                            <div>
                                <select name="department" x-model="department" @change="division = ''"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                                    <option value="">ALL DEPARTMENTS</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department }}">{{ strtoupper($department) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Division -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DIVISION</label>
                            <div>
                                <select name="division" x-model="division" :disabled="!department"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 disabled:bg-gray-100 disabled:text-gray-400 font-semibold text-xs h-10">
                                    <option value="">ALL DIVISIONS</option>
                                    <template x-for="div in filteredDivisions" :key="div">
                                        <option :value="div" x-text="div.toUpperCase()" :selected="division === div"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
 
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">STATUS</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                                <option value="">ALL STATUSES</option>
                                <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>ASSIGNED</option>
                                <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>AVAILABLE</option>
                                <option value="Defective" {{ request('status') == 'Defective' ? 'selected' : '' }}>DEFECTIVE</option>
                                <option value="Condemned" {{ request('status') == 'Condemned' ? 'selected' : '' }}>CONDEMNED</option>
                                <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>DISPOSED</option>
                            </select>
                        </div>
                    </div>
 
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex justify-center py-2 px-8 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase transition-all duration-200">
                            Apply Filters
                        </button>
                        
                        <a href="{{ route('qr-assets.index') }}" class="inline-flex justify-center py-2 px-6 border border-gray-300 shadow-sm text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase transition-all duration-200">
                            Reset
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('qr-assets.index', array_merge(request()->query(), ['status' => 'Available'])) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl text-green-700 bg-green-50 hover:bg-green-100 transition-colors uppercase">
                                Show Available (Standby)
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('qr-assets.print') }}" method="POST" target="_blank" onsubmit="return validateSelection()">
                        @csrf
                        
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Select Assets for QR Printing</h3>
                                <p class="text-sm text-gray-500">Choose the units you want to generate QR labels for.</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                    Generate Labels for Printing
                                </button>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <!-- PC Units Group -->
                            <div>
                                <div class="flex items-center justify-between bg-blue-50 p-4 rounded-t-lg border-x border-t border-blue-200">
                                    <h4 class="text-blue-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        PC Units ({{ count($pcUnits) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 select-all-group" data-target="pc-checkbox">
                                        <span class="ml-2 text-sm font-medium text-blue-800">Select All PC Units</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($pcUnits as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="pc:{{ $unit->id }}" class="pc-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->device_type }} - {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-mono text-gray-400 italic">
                                                        {{ ($unit->employee_id && $unit->employee) 
                                                            ? strtoupper(implode(' / ', array_filter([$unit->employee->group, $unit->employee->department, $unit->employee->division])))
                                                            : strtoupper(implode(' / ', array_filter([$unit->group, $unit->department, $unit->division]))) 
                                                        }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Printers Group -->
                            <div>
                                <div class="flex items-center justify-between bg-orange-50 p-4 rounded-t-lg border-x border-t border-orange-200">
                                    <h4 class="text-orange-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Printers ({{ count($printers) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500 select-all-group" data-target="printer-checkbox">
                                        <span class="ml-2 text-sm font-medium text-orange-800">Select All Printers</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($printers as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="printer:{{ $unit->id }}" class="printer-checkbox rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->brand }} {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-mono text-gray-400 italic">
                                                        {{ ($unit->employee_id && $unit->employee) 
                                                            ? strtoupper(implode(' / ', array_filter([$unit->employee->group, $unit->employee->department, $unit->employee->division])))
                                                            : strtoupper(implode(' / ', array_filter([$unit->group, $unit->department, $unit->division]))) 
                                                        }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Networking Devices Group -->
                            <div>
                                <div class="flex items-center justify-between bg-emerald-50 p-4 rounded-t-lg border-x border-t border-emerald-200">
                                    <h4 class="text-emerald-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"></path>
                                        </svg>
                                        Networking Devices ({{ count($networkDevices) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 select-all-group" data-target="network-checkbox">
                                        <span class="ml-2 text-sm font-medium text-emerald-800">Select All Network Devices</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($networkDevices as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="network:{{ $unit->id }}" class="network-checkbox rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->brand }} {{ $unit->model }} ({{ $unit->device_type }})</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-mono text-gray-400 italic">
                                                        {{ ($unit->employee_id && $unit->employee) 
                                                            ? strtoupper(implode(' / ', array_filter([$unit->employee->group, $unit->employee->department, $unit->employee->division])))
                                                            : strtoupper(implode(' / ', array_filter([$unit->group, $unit->department, $unit->division]))) 
                                                        }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Power Utilities Group -->
                            <div>
                                <div class="flex items-center justify-between bg-indigo-50 p-4 rounded-t-lg border-x border-t border-indigo-200">
                                    <h4 class="text-indigo-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Power Utilities ({{ count($powerUtilities) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 select-all-group" data-target="power-checkbox">
                                        <span class="ml-2 text-sm font-medium text-indigo-800">Select All Power Utilities</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($powerUtilities as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="power_utility:{{ $unit->id }}" class="power-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->type }} - {{ $unit->brand }} {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-mono text-gray-400 italic">
                                                        {{ ($unit->employee_id && $unit->employee) 
                                                            ? strtoupper(implode(' / ', array_filter([$unit->employee->group, $unit->employee->department, $unit->employee->division])))
                                                            : strtoupper(implode(' / ', array_filter([$unit->group, $unit->department, $unit->division]))) 
                                                        }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Devices Group -->
                            <div>
                                <div class="flex items-center justify-between bg-teal-50 p-4 rounded-t-lg border-x border-t border-teal-200">
                                    <h4 class="text-teal-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        Mobile Devices ({{ count($mobileDevices) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500 select-all-group" data-target="mobile-checkbox">
                                        <span class="ml-2 text-sm font-medium text-teal-800">Select All Mobile Devices</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($mobileDevices as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="mobile_device:{{ $unit->id }}" class="mobile-checkbox rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->type }} - {{ $unit->brand }} {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap text-xs font-mono text-gray-400 italic">
                                                        {{ ($unit->employee_id && $unit->employee) 
                                                            ? strtoupper(implode(' / ', array_filter([$unit->employee->group, $unit->employee->department, $unit->employee->division])))
                                                            : strtoupper(implode(' / ', array_filter([$unit->group, $unit->department, $unit->division]))) 
                                                        }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function validateSelection() {
            const checkedCount = document.querySelectorAll('input[name="selected_assets[]"]:checked').length;
            if (checkedCount === 0) {
                alert('Please select at least one unit to generate labels.');
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAllCheckboxes = document.querySelectorAll('.select-all-group');
            
            selectAllCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const targetClass = this.getAttribute('data-target');
                    const targetCheckboxes = document.querySelectorAll('.' + targetClass);
                    
                    targetCheckboxes.forEach(target => {
                        target.checked = this.checked;
                    });
                });
            });

            // Update "Select All" state when individual checkboxes change
            const individualCheckboxes = document.querySelectorAll('input[name="selected_assets[]"]');
            individualCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const groupClass = Array.from(this.classList).find(cls => cls.endsWith('-checkbox'));
                    const groupCheckboxes = document.querySelectorAll('.' + groupClass);
                    const groupSelectAll = document.querySelector(`.select-all-group[data-target="${groupClass}"]`);
                    
                    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(groupCheckboxes).some(cb => cb.checked);
                    
                    groupSelectAll.checked = allChecked;
                    groupSelectAll.indeterminate = someChecked && !allChecked;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>


