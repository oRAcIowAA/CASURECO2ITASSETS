<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Network Device') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                <form method="POST" action="{{ route('network-devices.update', $networkDevice) }}"
                      x-data="{ 
                          deviceType: '{{ old('device_type', $networkDevice->device_type) }}',
                          switchType: '{{ old('switch_type', $networkDevice->switch_type) }}',
                          hasIp: '{{ old('has_ip', $networkDevice->has_ip) ? '1' : '0' }}',
                          assignmentType: '{{ old('assignment_type', $networkDevice->employee_id ? 'ASSIGN' : 'STANDBY') }}',
                          location: '{{ old('location_id', $networkDevice->location_id) }}'
                      }"
                      x-init="$watch('switchType', (val) => { if(deviceType === 'switch') hasIp = (val === 'managed' ? '1' : '0') }); 
                             $watch('deviceType', (val) => { if(val === 'switch') hasIp = (switchType === 'managed' ? '1' : '0') })">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                        <input type="text" name="asset_tag" value="{{ $networkDevice->asset_tag }}" readonly
                               class="w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300">
                        <p class="text-xs text-gray-500 mt-1 italic">Asset tags cannot be modified once created.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">
                            DEVICE TYPE <span class="text-red-500">*</span>
                        </label>
                        <select name="device_type" x-model="deviceType"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                            <option value="router">ROUTER</option>
                            <option value="switch">SWITCH</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND <span class="text-red-500">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand', $networkDevice->brand) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. TP-LINK, CISCO, ASUS"
                                   oninput="this.value = this.value.toUpperCase()"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL <span class="text-red-500">*</span></label>
                            <input type="text" name="model" value="{{ old('model', $networkDevice->model) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. TL-WR840N, WS-C2960-24TC-L"
                                   oninput="this.value = this.value.toUpperCase()"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">NETWORK PORTS <span class="text-red-500">*</span></label>
                            <input type="number" name="network_ports" value="{{ old('network_ports', $networkDevice->network_ports) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. 4, 8, 16, 24, 48"
                                   min="1"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">NETWORK SPEED</label>
                            <select name="network_speed"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="gigabit" {{ old('network_speed', $networkDevice->network_speed) === 'gigabit' ? 'selected' : '' }}>Gigabit</option>
                                <option value="non_gigabit" {{ old('network_speed', $networkDevice->network_speed) === 'non_gigabit' ? 'selected' : '' }}>Non-Gigabit</option>
                            </select>
                        </div>
                    </div>

                    <div x-show="deviceType === 'switch'" x-transition class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">
                            TYPE OF SWITCH
                        </label>
                        <select name="switch_type" x-model="switchType"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                            <option value="managed">MANAGED (HAS IP)</option>
                            <option value="unmanaged">UNMANAGED (NO IP)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            Unmanaged switches: no IP address (Layer 2 forwarding only). Managed switches: have IP for management interface.
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">
                            IP ADDRESS
                        </label>
                        <div class="flex items-center space-x-4 mb-2">
                            <label class="inline-flex items-center" :class="deviceType === 'switch' ? 'opacity-50 cursor-not-allowed' : ''">
                                <input type="radio" name="has_ip" value="1"
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       x-model="hasIp"
                                       :disabled="deviceType === 'switch'">
                                <span class="ml-2 text-sm text-gray-700">Yes</span>
                            </label>
                            <label class="inline-flex items-center" :class="deviceType === 'switch' ? 'opacity-50 cursor-not-allowed' : ''">
                                <input type="radio" name="has_ip" value="0"
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       x-model="hasIp"
                                       :disabled="deviceType === 'switch'">
                                <span class="ml-2 text-sm text-gray-700">No</span>
                            </label>
                        </div>
                        <div x-show="hasIp == '1'" x-transition>
                            <input type="text" name="ip_address" value="{{ old('ip_address', $networkDevice->ip_address) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="192.168.1.1">
                            <p class="mt-1 text-xs text-gray-500">
                                Required if "Yes" is selected. For Unmanaged switches, select "No".
                            </p>
                        </div>
                    </div>

                    <!-- Location & Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Location <span class="text-red-500">*</span></label>
                            <select name="location_id" x-model="location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $id => $name)
                                    <option value="{{ $id }}" {{ old('location_id', $networkDevice->location_id) == $id ? 'selected' : '' }}>{{ strtoupper($name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DATE ISSUED</label>
                            <input type="date" name="date_issued" value="{{ old('date_issued', $networkDevice->date_issued?->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Assignment Section -->
                    @php
                        $specialStatuses = ['disposed', 'condemned', 'defective'];
                        $isSpecialStatus = in_array(strtolower($networkDevice->status), $specialStatuses);
                    @endphp

                    @if($isSpecialStatus)
                        <div class="mb-6 border-t pt-4">
                            <label class="block text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">STATUS & ASSIGNMENT</label>
                            <div class="p-4 rounded-lg {{ strtolower($networkDevice->status) === 'disposed' ? 'bg-red-50' : 'bg-yellow-50' }} border border-{{ strtolower($networkDevice->status) === 'disposed' ? 'red' : 'yellow' }}-200">
                                <div class="flex items-center">
                                    <span class="px-3 py-1 text-sm font-bold uppercase rounded-full 
                                        {{ strtolower($networkDevice->status) === 'disposed' ? 'bg-red-600 text-white' : 'bg-yellow-500 text-white' }}">
                                        {{ strtoupper($networkDevice->status) }}
                                    </span>
                                    <span class="ml-3 text-sm text-gray-600">
                                        This unit is currently <strong>{{ strtoupper($networkDevice->status) }}</strong> and cannot be reassigned. To change the status, please use the appropriate action from the details page.
                                    </span>
                                </div>
                                <input type="hidden" name="assignment_type" value="STANDBY">
                            </div>
                        </div>
                    @else
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
                                        search: '{{ old('employee_id') ? ($employees->firstWhere('emp_id', old('employee_id'))->full_name ?? '') : ($networkDevice->employee->full_name ?? '') }}', 
                                        open: false, 
                                        selectedId: '{{ old('employee_id', $networkDevice->employee_id) }}',
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
                                                const matchesLoc = !this.location || e.location_id == this.location || !e.location_id || e.id === '{{ $networkDevice->employee_id }}';
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
                                    
                                    @error('employee_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                        </div>
                    @endif


                    <div class="flex justify-between items-center">
                        <a href="{{ route('network-devices.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            ← Back
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Network Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


