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

                <form method="POST" action="{{ route('network-devices.update', $networkDevice) }}">
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
                            DEVICE TYPE
                        </label>
                        <select name="device_type"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                onchange="if(this.value=='router'){ document.getElementById('switch_options').style.display='none'; } else { document.getElementById('switch_options').style.display='block'; }">
                            <option value="router" {{ old('device_type', $networkDevice->device_type) === 'router' ? 'selected' : '' }}>Router</option>
                            <option value="switch" {{ old('device_type', $networkDevice->device_type) === 'switch' ? 'selected' : '' }}>Switch</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND</label>
                            <input type="text" name="brand" value="{{ old('brand', $networkDevice->brand) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL</label>
                            <input type="text" name="model" value="{{ old('model', $networkDevice->model) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">NETWORK PORTS</label>
                            <select name="network_ports"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="4" {{ old('network_ports', $networkDevice->network_ports) == 4 ? 'selected' : '' }}>4</option>
                                <option value="8" {{ old('network_ports', $networkDevice->network_ports) == 8 ? 'selected' : '' }}>8</option>
                                <option value="16" {{ old('network_ports', $networkDevice->network_ports) == 16 ? 'selected' : '' }}>16 (Switch)</option>
                                <option value="24" {{ old('network_ports', $networkDevice->network_ports) == 24 ? 'selected' : '' }}>24 (Switch)</option>
                            </select>
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

                    <div id="switch_options" class="mb-4" style="{{ old('device_type', $networkDevice->device_type) === 'switch' ? '' : 'display:none;' }}">
                            TYPE OF SWITCH
                        </label>
                        <select name="switch_type"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="managed" {{ old('switch_type', $networkDevice->switch_type) === 'managed' ? 'selected' : '' }}>Managed (has IP)</option>
                            <option value="unmanaged" {{ old('switch_type', $networkDevice->switch_type) === 'unmanaged' ? 'selected' : '' }}>Unmanaged (no IP)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            Unmanaged switches: no IP address (Layer 2 forwarding only). Managed switches: have IP for management interface.
                        </p>
                    </div>

                    <div class="mb-4" x-data="{ hasIp: '{{ old('has_ip', $networkDevice->has_ip) ? '1' : '0' }}' }">
                            IP ADDRESS
                        </label>
                        <div class="flex items-center space-x-4 mb-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_ip" value="1"
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       x-model="hasIp"
                                       {{ old('has_ip', $networkDevice->has_ip) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_ip" value="0"
                                       class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                       x-model="hasIp"
                                       {{ !old('has_ip', $networkDevice->has_ip) ? 'checked' : '' }}>
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

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Department</label>
                            <select name="department" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT DEPARTMENT</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department', $networkDevice->department) == $dept ? 'selected' : '' }}>{{ strtoupper($dept) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Division</label>
                            <select name="division" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                                <option value="">SELECT DIVISION</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division }}" {{ old('division', $networkDevice->division) == $division ? 'selected' : '' }}>{{ strtoupper($division) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Group</label>
                            <select name="group" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT GROUP</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('group', $networkDevice->group) == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                                @endforeach
                            </select>
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
                                <input type="hidden" name="assignment_type" value="standby">
                            </div>
                        </div>
                    @else
                        <div class="mb-6 border-t pt-4" x-data="{ assignmentType: '{{ $networkDevice->employee_id ? 'assign' : 'standby' }}' }">
                            <label class="block text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">ASSIGNMENT</label>
                            
                            <div class="flex space-x-6 mb-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="assignment_type" value="standby" 
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           x-model="assignmentType">
                                    <span class="ml-2 text-gray-700 uppercase font-bold">STANDBY (AVAILABLE)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="assignment_type" value="assign" 
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           x-model="assignmentType">
                                    <span class="ml-2 text-gray-700 uppercase font-bold">ASSIGN TO EMPLOYEE</span>
                                </label>
                            </div>

                            <div x-show="assignmentType === 'assign'" class="bg-gray-50 p-4 rounded-md">
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SELECT EMPLOYEE</label>
                                <select name="employee_id" x-init="new Choices($el, { searchPlaceholderValue: 'SEARCH NAME, DEPARTMENT...' })" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- CHOOSE EMPLOYEE --</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id', $networkDevice->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ strtoupper($employee->full_name) }} &mdash; {{ strtoupper($employee->department ?? 'N/A') }} / {{ strtoupper($employee->division ?? 'N/A') }}
                                        </option>
                                    @endforeach
                                </select>
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
