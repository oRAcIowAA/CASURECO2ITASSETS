<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Networking Device') }}
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

                <form method="POST" action="{{ route('network-devices.store') }}" 
                      x-data="{ 
                          deviceType: '{{ old('device_type', 'router') }}',
                          switchType: '{{ old('switch_type', 'managed') }}',
                          hasIp: '{{ old('has_ip', '1') }}',
                          assignmentType: '{{ old('assignment_type', 'STANDBY') }}'
                      }"
                      x-init="$watch('switchType', (val) => { if(deviceType === 'switch') hasIp = (val === 'managed' ? '1' : '0') }); 
                             $watch('deviceType', (val) => { if(val === 'switch') hasIp = (switchType === 'managed' ? '1' : '0') })">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                        <div class="flex rounded-md shadow-sm">
                            <input type="text" name="asset_tag" value="{{ $nextAssetTag }}" readonly
                                   class="flex-1 min-w-0 block w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300"
                                   title="This tag is automatically generated">
                        </div>
                        <p class="text-xs text-gray-500 mt-1 italic">Automatically generated based on the latest record.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">
                            DEVICE TYPE
                        </label>
                        <select name="device_type" x-model="deviceType"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                            <option value="router">ROUTER</option>
                            <option value="switch">SWITCH</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND</label>
                            <input type="text" name="brand" value="{{ old('brand') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. TP-LINK, CISCO, ASUS"
                                   oninput="this.value = this.value.toUpperCase()"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL</label>
                            <input type="text" name="model" value="{{ old('model') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. TL-WR840N, WS-C2960-24TC-L"
                                   oninput="this.value = this.value.toUpperCase()"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">NETWORK PORTS</label>
                            <input type="number" name="network_ports" value="{{ old('network_ports') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="e.g. 4, 8, 16, 24, 48"
                                   min="1"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">NETWORK SPEED</label>
                            <select name="network_speed"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="gigabit" {{ old('network_speed') === 'gigabit' ? 'selected' : '' }}>Gigabit</option>
                                <option value="non_gigabit" {{ old('network_speed') === 'non_gigabit' ? 'selected' : '' }}>Non-Gigabit</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4" x-show="deviceType === 'switch'" x-transition>
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

                    <!-- Location & Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Location</label>
                            <select name="location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('location') == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DATE ISSUED <span class="text-red-500">*</span></label>
                            <input type="date" name="date_issued" value="{{ old('date_issued') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
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
                            <input type="text" name="ip_address" value="{{ old('ip_address') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="192.168.1.1">
                            <p class="mt-1 text-xs text-gray-500" x-show="deviceType === 'router'">
                                For routers, set an IP so you can manage the device.
                            </p>
                            <p class="mt-1 text-xs text-gray-500" x-show="deviceType === 'switch'">
                                For managed switches, set an IP so you can manage the device.
                            </p>
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

                        <div x-show="assignmentType === 'ASSIGN'" class="bg-gray-50 p-4 rounded-md">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SELECT EMPLOYEE</label>
                            <select name="employee_id" x-init="new Choices($el, { searchPlaceholderValue: 'SEARCH NAME, DEPARTMENT...' })" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- CHOOSE EMPLOYEE --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ strtoupper($employee->full_name) }} &mdash; {{ strtoupper($employee->department ?? 'N/A') }} / {{ strtoupper($employee->division ?? 'N/A') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('pc-units.create') }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            ← Back
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Network Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>




