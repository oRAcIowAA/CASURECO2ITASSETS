<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('REPORTS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            
            <!-- Actions / Navigation -->
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 uppercase">
                    UNIFIED DEVICE MASTER LIST
                    <span class="text-sm font-normal text-gray-500 ml-2 uppercase">({{ $paginatedItems->total() }} ITEMS FOUND)</span>
                </h3>
                <div class="flex space-x-2">
                    <!-- Button removed as requested -->
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
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
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Printers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['printers']['total'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 flex justify-between">
                        <span>Printer: <span class="font-semibold text-gray-900">{{ $stats['printers']['Printer'] }}</span></span>
                        <span>Scanner: <span class="font-semibold text-gray-900">{{ $stats['printers']['Scanner'] }}</span></span>
                        <span>Portable: <span class="font-semibold text-gray-900">{{ $stats['printers']['Portable Printer'] }}</span></span>
                    </div>
                </div>

                <!-- Power Utilities -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Power Utils</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['power_utilities']['total'] }}</p>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-full shadow-sm">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 flex justify-start">
                        <span class="mr-6">UPS: <span class="font-semibold text-gray-900">{{ $stats['power_utilities']['UPS'] }}</span></span>
                        <span>AVR: <span class="font-semibold text-gray-900">{{ $stats['power_utilities']['AVR'] }}</span></span>
                    </div>
                </div>

                <!-- Mobile Devices -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Mobile Units</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['mobile_devices']['total'] }}</p>
                        </div>
                        <div class="p-3 bg-pink-50 rounded-full shadow-sm">
                            <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600 flex justify-start">
                        <span>Cellphone: <span class="font-semibold text-gray-900">{{ $stats['mobile_devices']['Cellphone'] }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form id="report-filter-form" method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4"
                      x-data="{ 
                          department: '{{ request('department') }}', 
                          division: '{{ request('division') }}',
                          deptDivisions: @js($deptDivisions),
                          get filteredDivisions() {
                              return this.department ? (this.deptDivisions[this.department] || []) : [];
                          },
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
                              return this.allTypes.every(t => this.selectedTypes.includes(t)) || this.selectedTypes.length === 0;
                          },
                          isCategoryIndeterminate(categoryTypes) {
                              const count = categoryTypes.filter(t => this.selectedTypes.includes(t)).length;
                              return count > 0 && count < categoryTypes.length;
                          }
                      }">
                    
                    <!-- Type -->
                    <div class="relative col-span-1" @click.away="open = false">
                        
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">TYPE</label>
                        
                        <button type="button" @click="open = !open" 
                            class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm h-10 font-semibold text-xs uppercase">
                            <span class="block truncate uppercase font-semibold" x-text="selectedTypes.length ? selectedTypes.join(', ') : 'ALL DEVICES'"></span>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
                                 <div @click="toggleCategory(printerTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                     <span>PRINTERS</span>
                                     <input type="checkbox" :checked="isCategorySelected(printerTypes)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                 </div>
                                 <div class="ml-4 mt-1 space-y-1">
                                     <template x-for="type in printerTypes">
                                         <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 rounded p-1 transition-colors">
                                             <input type="checkbox" :checked="isTypeSelected(type)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                             <span x-text="type.toUpperCase()" class="text-gray-700 font-medium"></span>
                                         </div>
                                     </template>
                                 </div>
                             </div>

                             <div class="border-t border-gray-100"></div>

                             <!-- Power Utilities -->
                             <div class="px-3 py-2">
                                 <div @click="toggleCategory(powerTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                     <span>POWER UTILITIES</span>
                                     <input type="checkbox" :checked="isCategorySelected(powerTypes)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                 </div>
                                 <div class="ml-4 mt-1 space-y-1">
                                     <template x-for="type in powerTypes">
                                         <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 rounded p-1 transition-colors">
                                             <input type="checkbox" :checked="isTypeSelected(type)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                             <span x-text="type.toUpperCase()" class="text-gray-700 font-medium"></span>
                                         </div>
                                     </template>
                                 </div>
                             </div>

                             <div class="border-t border-gray-100"></div>

                             <!-- Mobile Devices -->
                             <div class="px-3 py-2">
                                 <div @click="toggleCategory(mobileTypes)" class="flex items-center justify-between font-bold text-gray-900 cursor-pointer hover:bg-gray-100 rounded p-1">
                                     <span>MOBILE DEVICES</span>
                                     <input type="checkbox" :checked="isCategorySelected(mobileTypes)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                 </div>
                                 <div class="ml-4 mt-1 space-y-1">
                                     <template x-for="type in mobileTypes">
                                         <div @click="toggleType(type)" class="flex items-center cursor-pointer hover:bg-gray-50 rounded p-1 transition-colors">
                                             <input type="checkbox" :checked="isTypeSelected(type)" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                             <span x-text="type.toUpperCase()" class="text-gray-700 font-medium"></span>
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

                    <!-- Search -->
                    <div class="col-span-1">
                        <label for="search" class="block text-sm font-bold text-gray-700 mb-1 uppercase">SEARCH</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="ASSET TAG, MODEL..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10 uppercase placeholder-gray-400">
                    </div>

                    <!-- Group -->
                    <div>
                         <label for="group" class="block text-sm font-medium text-gray-700 mb-1">LOCATION</label>
                         <select name="group" id="group" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                            <option value="">ALL LOCATIONS</option>
                            @foreach($groups as $group)
                                <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-bold text-gray-700 uppercase">DEPARTMENT</label>
                        <select name="department" id="department" x-model="department" @change="division = ''"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
                            <option value="">ALL DEPARTMENTS</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}">{{ strtoupper($dept) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Division -->
                    <div>
                         <label for="division" class="block text-sm font-bold text-gray-700 uppercase">DIVISION</label>
                         <select name="division" id="division" x-model="division" :disabled="!department"
                                 class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 disabled:bg-gray-100 disabled:text-gray-400 font-semibold text-xs h-10">
                            <option value="">ALL DIVISIONS</option>
                            <template x-for="div in filteredDivisions" :key="div">
                                <option :value="div" x-text="div.toUpperCase()" :selected="division === div"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">STATUS</label>
                        <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10">
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
                        
                        <a href="{{ route('reports.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-bold rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            RESET
                        </a>

                        <a href="{{ route('reports.print-list', request()->query()) }}" target="_blank" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-bold rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            PRINT LIST
                        </a>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type / Brand / Model</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($paginatedItems as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $item->asset_tag }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route($item->view_route, $item->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline">
                                            {{ str_replace($item->asset_tag . ' - ', '', $item->type_model) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->category }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->location }}</div>
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
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        NO RESULTS FOUND MATCHING YOUR FILTERS.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $paginatedItems->links() }}
            </div>
            
        </div>
    </div>
</x-app-layout>
