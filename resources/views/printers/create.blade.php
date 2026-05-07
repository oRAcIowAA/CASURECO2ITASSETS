<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Printer') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                <form method="POST" action="{{ route('printers.store') }}" x-data="{ 
                    type: '{{ old('type', 'PRINTER') }}', 
                    hasNetwork: '{{ old('has_network_port', '0') }}',
                    assignmentType: '{{ old('assignment_type', 'STANDBY') }}',
                    location: '{{ old('location_id') }}'
                }">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                            <input type="text" name="asset_tag" value="{{ $nextAssetTag }}" readonly
                                   class="w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300"
                                   title="This tag is automatically generated">
                            <p class="text-xs text-gray-500 mt-1 italic uppercase">Auto-generated</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DEVICE TYPE <span class="text-red-500">*</span></label>
                            <select name="type" x-model="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="PRINTER">PRINTER</option>
                                <option value="SCANNER">SCANNER</option>
                                <option value="PORTABLE PRINTER">PORTABLE PRINTER</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1 italic uppercase">&nbsp;</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4" :class="type === 'PORTABLE PRINTER' ? 'md:grid-cols-3' : ''">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND <span class="text-red-500">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. Epson, HP"
                                   oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL <span class="text-red-500">*</span></label>
                            <input type="text" name="model" value="{{ old('model') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. L3110, LaserJet Pro"
                                   oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div x-show="type === 'PORTABLE PRINTER'" x-transition>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SERIAL NUMBER <span class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" value="{{ old('serial_number') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="REQUIRED FOR PORTABLE"
                                   oninput="this.value = this.value.toUpperCase()"
                                   :required="type === 'PORTABLE PRINTER'">
                        </div>
                    </div>

                    <!-- Location & Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Location <span class="text-red-500">*</span></label>
                            <select name="location_id" x-model="location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $id => $name)
                                    <option value="{{ $id }}" {{ old('location_id') == $id ? 'selected' : '' }}>{{ strtoupper($name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DATE ISSUED</label>
                            <input type="date" name="date_issued" value="{{ old('date_issued') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">HAS NETWORK PORT?</label>
                        <div class="mb-4">
                            <div class="flex items-center space-x-4 mb-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="has_network_port" id="network_yes" value="1" 
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           x-model="hasNetwork"
                                           @click="hasNetwork = '1'">
                                    <span class="ml-2 text-sm text-gray-700">Yes (Network/WiFi)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="has_network_port" id="network_no" value="0"
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           x-model="hasNetwork"
                                           @click="hasNetwork = '0'">
                                    <span class="ml-2 text-sm text-gray-700">No (USB Only)</span>
                                </label>
                            </div>

                            <!-- Network Details (Conditional) -->
                            <div x-show="hasNetwork == '1'" x-transition class="mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
                                    <div x-data="{ 
                                        ipType: '{{ old('ip_type', 'Static') }}',
                                        ipAddress: '{{ old('ip_address') }}',
                                        lastStaticIp: '{{ old('ip_address') }}',
                                        toggleIpType(type) {
                                            if (type === 'Dynamic') {
                                                if (this.ipAddress !== 'Dynamic') this.lastStaticIp = this.ipAddress;
                                                this.ipAddress = 'Dynamic';
                                            } else {
                                                this.ipAddress = (this.lastStaticIp && this.lastStaticIp !== 'Dynamic') ? this.lastStaticIp : '';
                                            }
                                        }
                                    }">
                                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                            IP ADDRESS / CONFIGURATION
                                        </label>
                                        <div class="flex space-x-4 mb-3">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="ip_type" value="Static" x-model="ipType" @change="toggleIpType('Static')" class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700 font-semibold uppercase">STATIC</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="ip_type" value="Dynamic" x-model="ipType" @change="toggleIpType('Dynamic')" class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700 font-semibold uppercase">DYNAMIC</span>
                                            </label>
                                        </div>
                                        <input type="text" name="ip_address" id="ip_address" 
                                            x-model="ipAddress"
                                            :readonly="ipType === 'Dynamic'"
                                            :class="ipType === 'Dynamic' ? 'bg-gray-100 cursor-not-allowed text-gray-500' : ''"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ip_address') border-red-500 @enderror" 
                                            placeholder="192.168.1.100">
                                    </div>
                                    
                                    <div x-data="{ 
                                        mac: '{{ old('mac_address') }}',
                                        formatMac(e) {
                                            let val = e.target.value.replace(/[^a-fA-F0-9]/g, '').toUpperCase();
                                            let matches = val.match(/.{1,2}/g);
                                            if (matches) {
                                                this.mac = matches.join(':').substring(0, 17);
                                            } else {
                                                this.mac = val;
                                            }
                                        }
                                    }">
                                        <label for="mac_address" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                            MAC ADDRESS
                                        </label>
                                        <input type="text" name="mac_address" id="mac_address" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('mac_address') border-red-500 @enderror" 
                                            x-model="mac"
                                            @input="formatMac"
                                            placeholder="00:1A:2B:3C:4D:5E"
                                            maxlength="17">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Section -->
                    <div class="mb-6 border-t pt-4">
                        <label class="block text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">ASSIGNMENT</label>
                        
                        <div class="flex space-x-6 mb-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="assignment_type" value="STANDBY" 
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       x-model="assignmentType">
                                <span class="ml-2 text-gray-700 uppercase font-bold">STORAGE AVAILABLE</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="assignment_type" value="ASSIGN" 
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       x-model="assignmentType">
                                <span class="ml-2 text-gray-700 uppercase font-bold">DEPLOYMENT</span>
                            </label>
                        </div>

                        <div x-show="assignmentType === 'ASSIGN'" class="bg-gray-50 p-4 rounded-md"
                             x-data="{ 
                                search: '', 
                                open: false, 
                                selectedId: '{{ old('employee_id') }}',
                                employees: @js($employees->map(fn($e) => [
                                    'id' => $e->emp_id,
                                    'name' => strtoupper($e->full_name),
                                    'location_id' => $e->location_id,
                                    'dept' => strtoupper($e->department ?? 'N/A'),
                                    'div' => strtoupper($e->division ?? 'N/A')
                                ])),
                                get filteredEmployees() {
                                    return this.employees.filter(e => {
                                        const matchesSearch = e.name.toLowerCase().includes(this.search.toLowerCase());
                                        const matchesLoc = !this.location || e.location_id == this.location || !e.location_id;
                                        return matchesSearch && matchesLoc;
                                    }).slice(0, 10);
                                }
                             }">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SELECT EMPLOYEE</label>
                            
                            <div class="relative">
                                <input type="text" 
                                       x-model="search" 
                                       @focus="open = true" 
                                       @click.away="open = false"
                                       @keydown.escape="open = false"
                                       placeholder="TYPE TO SEARCH EMPLOYEE..." 
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase font-semibold text-xs h-10">
                                
                                <input type="hidden" name="employee_id" :value="selectedId">
                                
                                <div x-show="open && filteredEmployees.length > 0" 
                                     class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-gray-200"
                                     style="display: none;">
                                    <template x-for="e in filteredEmployees" :key="e.id">
                                        <div @click="selectedId = e.id; search = e.name; open = false" 
                                             class="cursor-pointer hover:bg-indigo-600 hover:text-white px-4 py-2 transition-colors">
                                            <div class="font-bold text-xs" x-text="e.name"></div>
                                            <div class="text-[10px] opacity-80" x-text="e.dept + ' / ' + e.div"></div>
                                        </div>
                                    </template>
                                </div>
                                
                                <div x-show="open && filteredEmployees.length === 0" 
                                     class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-md py-4 text-center text-gray-500 text-xs border border-gray-200"
                                     style="display: none;">
                                    NO EMPLOYEES FOUND
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('printers.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            ← Back
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Printer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


