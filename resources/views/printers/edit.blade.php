<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Printer: ') . $printer->brand . ' ' . $printer->model }}
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

                <form method="POST" action="{{ route('printers.update', $printer->id) }}" x-data="{ type: '{{ old('type', $printer->type) }}', hasNetwork: '{{ old('has_network_port', $printer->has_network_port ? '1' : '0') }}' }">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                            <input type="text" name="asset_tag" value="{{ old('asset_tag', $printer->asset_tag) }}" readonly
                                   class="w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300"
                                   title="This tag is automatically generated">
                            <p class="text-xs text-gray-500 mt-1 italic uppercase">Auto-generated</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DEVICE TYPE</label>
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
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND</label>
                            <input type="text" name="brand" value="{{ old('brand', $printer->brand) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. Epson, HP"
                                   oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL</label>
                            <input type="text" name="model" value="{{ old('model', $printer->model) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. L3110, LaserJet Pro"
                                   oninput="this.value = this.value.toUpperCase()" required>
                        </div>
                        <div x-show="type === 'PORTABLE PRINTER'" x-transition>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SERIAL NUMBER <span class="text-red-500">*</span></label>
                            <input type="text" name="serial_number" value="{{ old('serial_number', $printer->serial_number) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="REQUIRED FOR PORTABLE"
                                   oninput="this.value = this.value.toUpperCase()"
                                   :required="type === 'PORTABLE PRINTER'">
                        </div>
                    </div>

                    <!-- Location & Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Location</label>
                            <select name="location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('location', $printer->location) == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DATE ISSUED <span class="text-red-500">*</span></label>
                            <input type="date" name="date_issued" value="{{ old('date_issued', $printer->date_issued ? $printer->date_issued->format('Y-m-d') : '') }}"
                                   @if($printer->date_issued) readonly @endif
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @if($printer->date_issued) bg-gray-100 text-gray-600 @endif"
                                   required>
                            @if($printer->date_issued)
                                <p class="text-[10px] text-gray-500 mt-1 italic uppercase">Fixed once set</p>
                            @endif
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
                                        ipType: '{{ strtoupper(old('ip_type', $printer->ip_type ?? 'STATIC')) }}',
                                        ipAddress: '{{ old('ip_address', $printer->ip_address) }}',
                                        lastStaticIp: '{{ old('ip_address', $printer->ip_address !== 'DYNAMIC' ? $printer->ip_address : '') }}',
                                        toggleIpType(type) {
                                            if (type === 'DYNAMIC') {
                                                if (this.ipAddress !== 'DYNAMIC') this.lastStaticIp = this.ipAddress;
                                                this.ipAddress = 'DYNAMIC';
                                            } else {
                                                this.ipAddress = (this.lastStaticIp && this.lastStaticIp !== 'DYNAMIC') ? this.lastStaticIp : '';
                                            }
                                        }
                                    }">
                                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                            IP ADDRESS / CONFIGURATION
                                        </label>
                                        <div class="flex space-x-4 mb-3">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="ip_type" value="STATIC" x-model="ipType" @change="toggleIpType('STATIC')" class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700 font-semibold uppercase">STATIC</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="radio" name="ip_type" value="DYNAMIC" x-model="ipType" @change="toggleIpType('DYNAMIC')" class="form-radio h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <span class="ml-2 text-sm text-gray-700 font-semibold uppercase">DYNAMIC</span>
                                            </label>
                                        </div>
                                        <input type="text" name="ip_address" id="ip_address" 
                                            x-model="ipAddress"
                                            :readonly="ipType === 'DYNAMIC'"
                                            :class="ipType === 'DYNAMIC' ? 'bg-gray-100 cursor-not-allowed text-gray-500' : ''"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ip_address') border-red-500 @enderror" 
                                            placeholder="192.168.1.100">
                                    </div>
                                    
                                    <div x-data="{ 
                                        mac: '{{ old('mac_address', $printer->mac_address) }}',
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
                    @php
                        $specialStatuses = ['disposed', 'condemned', 'defective'];
                        $isSpecialStatus = in_array(strtolower($printer->status), $specialStatuses);
                    @endphp

                    @if($isSpecialStatus)
                        <div class="mb-6 border-t pt-4">
                            <label class="block text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">STATUS & ASSIGNMENT</label>
                            <div class="p-4 rounded-lg {{ strtolower($printer->status) === 'disposed' ? 'bg-red-50' : 'bg-yellow-50' }} border border-{{ strtolower($printer->status) === 'disposed' ? 'red' : 'yellow' }}-200">
                                <div class="flex items-center">
                                    <span class="px-3 py-1 text-sm font-bold uppercase rounded-full 
                                        {{ strtolower($printer->status) === 'disposed' ? 'bg-red-600 text-white' : 'bg-yellow-500 text-white' }}">
                                        {{ strtoupper($printer->status) }}
                                    </span>
                                    <span class="ml-3 text-sm text-gray-600">
                                        This unit is currently <strong>{{ strtoupper($printer->status) }}</strong> and cannot be reassigned. To change the status, please use the appropriate action from the details page.
                                    </span>
                                </div>
                                <input type="hidden" name="assignment_type" value="STANDBY">
                            </div>
                        </div>
                    @else
                        <div class="mb-6 border-t pt-4" x-data="{ assignmentType: '{{ $printer->employee_id ? 'ASSIGN' : 'STANDBY' }}' }">
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

                            <div x-show="assignmentType === 'ASSIGN'" class="bg-gray-50 p-4 rounded-md">
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SELECT EMPLOYEE</label>
                                <select name="employee_id" x-init="new Choices($el, { searchPlaceholderValue: 'SEARCH NAME, DEPARTMENT...' })" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- CHOOSE EMPLOYEE --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $printer->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ strtoupper($employee->full_name) }} &mdash; {{ strtoupper($employee->department ?? 'N/A') }} / {{ strtoupper($employee->division ?? 'N/A') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('printers.index') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            ← Back
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Printer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


