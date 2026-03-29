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

                <form method="POST" action="{{ route('printers.update', $printer) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                        <div class="flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-700 sm:text-sm font-semibold">
                                CAS-PR-
                            </span>
                            <input type="text" name="asset_tag_number" value="{{ old('asset_tag_number', Str::afterLast($printer->asset_tag, '-')) }}"
                                   class="flex-1 min-w-0 block w-full px-4 py-2 border-gray-300 rounded-none rounded-r-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('asset_tag') border-red-500 @enderror"
                                   placeholder="001" required>
                        </div>
                        @error('asset_tag')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND</label>
                            <input type="text" name="brand" value="{{ old('brand', $printer->brand) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL</label>
                            <input type="text" name="model" value="{{ old('model', $printer->model) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   required>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Department</label>
                            <select name="department" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT DEPARTMENT</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department', $printer->department) == $dept ? 'selected' : '' }}>{{ strtoupper($dept) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Division</label>
                            <select name="division" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                                <option value="">SELECT DIVISION</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division }}" {{ old('division', $printer->division) == $division ? 'selected' : '' }}>{{ strtoupper($division) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Group</label>
                            <select name="group" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT GROUP</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('group', $printer->group) == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                            HAS NETWORK PORT?
                        </label>
                        <div class="mb-4" x-data="{ hasNetwork: '{{ old('has_network_port', $printer->has_network_port) }}' }">
                            <div class="flex items-center space-x-4 mb-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="has_network_port" id="network_yes" value="1" 
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           x-model="hasNetwork"
                                           @click="hasNetwork = '1'"
                                           {{ old('has_network_port', $printer->has_network_port) == '1' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Yes (Network/WiFi)</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="has_network_port" id="network_no" value="0"
                                           class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           x-model="hasNetwork"
                                           @click="hasNetwork = '0'"
                                           {{ old('has_network_port', $printer->has_network_port) == '0' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">No (USB Only)</span>
                                </label>
                            </div>

                            <!-- IP Address Input (Conditional) -->
                            <div x-show="hasNetwork == '1'" x-transition>
                                <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">IP ADDRESS</label>
                                <input type="text" name="ip_address" value="{{ old('ip_address', $printer->ip_address) }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="192.168.1.xxx">
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
                                <input type="hidden" name="assignment_type" value="standby">
                            </div>
                        </div>
                    @else
                        <div class="mb-6 border-t pt-4" x-data="{ assignmentType: '{{ $printer->employee_id ? 'assign' : 'standby' }}' }">
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
