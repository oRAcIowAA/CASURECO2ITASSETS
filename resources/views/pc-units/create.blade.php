<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New PC Unit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success/Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('pc-units.store') }}">
                        @csrf
                        
                        @php
                            $selectedType = old('device_type', $type ?? 'Desktop');
                        @endphp

                        <!-- Device Type (dropdown: PC / Laptop / Server) -->
                        <div class="mb-6">
                            <label for="device_type" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                DEVICE TYPE
                            </label>
                            <select
                                id="device_type"
                                name="device_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                required
                            >
                                <option value="Desktop" {{ $selectedType === 'Desktop' ? 'selected' : '' }}>DESKTOP</option>
                                <option value="All-in-One" {{ $selectedType === 'All-in-One' ? 'selected' : '' }}>ALL-IN-ONE</option>
                                <option value="Laptop" {{ $selectedType === 'Laptop' ? 'selected' : '' }}>LAPTOP</option>
                                <option value="Server" {{ $selectedType === 'Server' ? 'selected' : '' }}>SERVER</option>
                            </select>
                        </div>

                        <!-- Asset Tag -->
                        <div class="mb-6">
                            <label for="asset_tag_number" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                ASSET TAG <span class="text-red-500">*</span>
                            </label>
                            <div class="flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-700 sm:text-sm font-semibold">
                                    CAS-PC-
                                </span>
                                <input type="text" name="asset_tag_number" id="asset_tag_number" 
                                       class="flex-1 min-w-0 block w-full px-4 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @error('asset_tag') border-red-500 @enderror" 
                                       value="{{ old('asset_tag_number') }}" 
                                       placeholder="001" 
                                       required>
                            </div>
                            @error('asset_tag')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Model -->
                        <div class="mb-6">
                            <label for="model" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                MODEL <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="model" id="model" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('model') border-red-500 @enderror" 
                                   value="{{ old('model') }}" 
                                   placeholder="Dell OptiPlex 7010" 
                                   required>
                            @error('model')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hardware Specs -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Processor -->
                            <div x-data="{ procType: '{{ old('processor') }}', customProc: '' }">
                                <label for="processor_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    PROCESSOR
                                </label>
                                @php
                                    $procOptions = ['Intel i3', 'Intel i5', 'Intel i7', 'Intel i9', 'AMD Ryzen 3', 'AMD Ryzen 5', 'AMD Ryzen 7', 'Apple M1', 'Apple M2', 'Apple M3'];
                                    $oldProc = old('processor');
                                    $isCustomProc = $oldProc && !in_array($oldProc, $procOptions);
                                @endphp
                                <select x-model="procType" id="processor_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(procType !== 'Other') { customProc = ''; $refs.hiddenProc.value = procType } else { $refs.hiddenProc.value = customProc }">
                                    <option value="">SELECT PROCESSOR</option>
                                    @foreach($procOptions as $opt)
                                        <option value="{{ $opt }}" {{ $oldProc === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomProc ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="procType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customProc" @input="$refs.hiddenProc.value = customProc"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Specify Processor"
                                           x-init="if('{{ $isCustomProc }}' == '1') { customProc = '{{ $oldProc }}'; }">
                                </div>
                                <input type="hidden" name="processor" x-ref="hiddenProc" value="{{ old('processor') }}">
                            </div>

                            <!-- RAM -->
                            <div x-data="{ ramType: '{{ old('ram') }}', customRam: '' }">
                                <label for="ram_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    RAM
                                </label>
                                @php
                                    $ramOptions = ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB'];
                                    $oldRam = old('ram');
                                    $isCustomRam = $oldRam && !in_array($oldRam, $ramOptions);
                                @endphp
                                <select x-model="ramType" id="ram_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(ramType !== 'Other') { customRam = ''; $refs.hiddenRam.value = ramType } else { $refs.hiddenRam.value = customRam }">
                                    <option value="">SELECT RAM</option>
                                    @foreach($ramOptions as $opt)
                                        <option value="{{ $opt }}" {{ $oldRam === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomRam ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="ramType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customRam" @input="$refs.hiddenRam.value = customRam"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Specify RAM"
                                           x-init="if('{{ $isCustomRam }}' == '1') { customRam = '{{ $oldRam }}'; }">
                                </div>
                                <input type="hidden" name="ram" x-ref="hiddenRam" value="{{ old('ram') }}">
                            </div>

                            <!-- Storage -->
                            <div x-data="{ storageType: '{{ old('storage') }}', customStorage: '' }">
                                <label for="storage_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    STORAGE
                                </label>
                                @php
                                    $storageOptions = ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD', '500GB HDD', '1TB HDD', '2TB HDD', 'Hybrid'];
                                    $oldStorage = old('storage');
                                    $isCustomStorage = $oldStorage && !in_array($oldStorage, $storageOptions);
                                @endphp
                                <select x-model="storageType" id="storage_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(storageType !== 'Other') { customStorage = ''; $refs.hiddenStorage.value = storageType } else { $refs.hiddenStorage.value = customStorage }">
                                    <option value="">SELECT STORAGE</option>
                                    @foreach($storageOptions as $opt)
                                        <option value="{{ $opt }}" {{ $oldStorage === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomStorage ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="storageType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customStorage" @input="$refs.hiddenStorage.value = customStorage"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="Specify Storage"
                                           x-init="if('{{ $isCustomStorage }}' == '1') { customStorage = '{{ $oldStorage }}'; }">
                                </div>
                                <input type="hidden" name="storage" x-ref="hiddenStorage" value="{{ old('storage') }}">
                            </div>
                        </div>

                        <!-- Network Details -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Network Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="ip_address" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    IP ADDRESS
                                </label>
                                <input type="text" name="ip_address" id="ip_address" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ip_address') border-red-500 @enderror" 
                                       value="{{ old('ip_address') }}" 
                                       placeholder="192.168.1.100">
                                @error('ip_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
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
                                @error('mac_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="network_segment" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    NETWORK SEGMENT
                                </label>
                                <input type="text" name="network_segment" id="network_segment" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       value="{{ old('network_segment') }}" 
                                       placeholder="VLAN 10 / Backend">
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="mb-6">
                            <label for="department" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select name="department" id="department"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('department') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT DEPARTMENT</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}" {{ old('department') === $department ? 'selected' : '' }}>
                                        {{ strtoupper($department) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Division -->
                        <div class="mb-6">
                            <label for="division" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Division
                            </label>
                            <select name="division" id="division"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('division') border-red-500 @enderror">
                                <option value="">SELECT DIVISION</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division }}" {{ old('division') === $division ? 'selected' : '' }}>
                                        {{ strtoupper($division) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Group -->
                        <div class="mb-6">
                            <label for="group" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Group <span class="text-red-500">*</span>
                            </label>
                            <select name="group" id="group"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('group') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT GROUP</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('group') === $group ? 'selected' : '' }}>
                                        {{ strtoupper($group) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assignment Section -->
                        <div class="mb-6 border-t pt-4" x-data="{ assignmentType: '{{ old('assignment_type', 'standby') }}' }">
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

                            <div x-show="assignmentType === 'assign'" class="bg-gray-50 p-4 rounded-md mb-4">
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

                            <!-- Hidden Status Field (Defaults to 'available', controller handles 'assigned') -->
                            <input type="hidden" name="status" value="available">
                        </div>

                        <!-- Date Received -->
                        <div class="mb-6">
                            <label for="date_received" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                DATE RECEIVED
                            </label>
                            <input type="date" name="date_received" id="date_received" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('date_received') }}">
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                REMARKS
                            </label>
                            <textarea name="remarks" id="remarks" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                      rows="3" 
                                      placeholder="Additional notes about this PC unit">{{ old('remarks') }}</textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pc-units.index') }}" 
                               class="text-gray-600 hover:text-gray-800 font-medium">
                                ← Back to PC Units
                            </a>
                            <div class="flex space-x-3">
                                <button type="reset" 
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Clear
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create PC Unit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>