<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit PC Unit: ') . $pcUnit->asset_tag }}
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
                    <form method="POST" action="{{ route('pc-units.update', $pcUnit) }}" x-data="{ deviceType: '{{ old('device_type', $pcUnit->device_type) }}' }">
                        @csrf
                        @method('PUT')
                        
                        <!-- Device Type -->
                        <div class="mb-6">
                            <label for="device_type" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                DEVICE TYPE <span class="text-red-500">*</span>
                            </label>
                            <select name="device_type" id="device_type" 
                                    x-model="deviceType"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('device_type') border-red-500 @enderror" 
                                    required>
                                <option value="">SELECT DEVICE TYPE</option>
                                <option value="Desktop" {{ $pcUnit->device_type == 'Desktop' ? 'selected' : '' }}>DESKTOP</option>
                                <option value="All-in-One" {{ $pcUnit->device_type == 'All-in-One' ? 'selected' : '' }}>ALL-IN-ONE</option>
                                <option value="Laptop" {{ $pcUnit->device_type == 'Laptop' ? 'selected' : '' }}>LAPTOP</option>
                                <option value="Server" {{ $pcUnit->device_type == 'Server' ? 'selected' : '' }}>SERVER</option>
                            </select>
                            @error('device_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Asset Tag -->
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                            <input type="text" name="asset_tag" value="{{ $pcUnit->asset_tag }}" readonly
                                   class="w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300">
                            <p class="text-xs text-gray-500 mt-1 italic">Asset tags cannot be modified once created.</p>
                        </div>

                        <!-- Model -->
                        <div class="mb-6">
                            <label for="model" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                MODEL <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="model" id="model" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('model') border-red-500 @enderror" 
                                   value="{{ old('model', $pcUnit->model) }}" 
                                   oninput="this.value = this.value.toUpperCase()"
                                   required>
                            @error('model')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Serial Number -->
                        <div class="mb-6">
                            <label for="serial_number" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                SERIAL NUMBER
                            </label>
                            <input type="text" name="serial_number" id="serial_number" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('serial_number') border-red-500 @enderror" 
                                   value="{{ old('serial_number', $pcUnit->serial_number) }}" 
                                   oninput="this.value = this.value.toUpperCase()">
                            @error('serial_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hardware Specs -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                             <!-- Operating System -->
                             <div x-data="{ osType: '{{ old('os_version', $pcUnit->os_version) }}', customOs: '' }">
                                <label for="os_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    OPERATING SYSTEM
                                </label>
                                @php
                                    $osOptions = ['WINDOWS 11', 'WINDOWS 10', 'WINDOWS 7', 'LINUX', 'MACOS'];
                                    $oldOs = old('os_version', $pcUnit->os_version);
                                    $isCustomOs = $oldOs && !in_array($oldOs, $osOptions);
                                @endphp
                                <select x-model="osType" id="os_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(osType !== 'Other') { customOs = ''; $refs.hiddenOs.value = osType } else { $refs.hiddenOs.value = customOs }">
                                    <option value="">SELECT OS</option>
                                    @foreach($osOptions as $opt)
                                        <option value="{{ $opt }}" {{ $oldOs === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomOs ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="osType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customOs" @input="$refs.hiddenOs.value = customOs.toUpperCase(); customOs = customOs.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify OS"
                                           x-init="if('{{ $isCustomOs }}' == '1') { customOs = '{{ $oldOs }}'; }">
                                </div>
                                <input type="hidden" name="os_version" x-ref="hiddenOs" value="{{ old('os_version', $pcUnit->os_version) }}">
                            </div>

                            <!-- Processor -->
                            <div x-data="{ procType: '{{ old('processor', $pcUnit->processor) }}', customProc: '' }">
                                <label for="processor_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    PROCESSOR
                                </label>
                                @php
                                    $procOptions = ['INTEL i3', 'INTEL i5', 'INTEL i7', 'INTEL i9', 'AMD RYZEN 3', 'AMD RYZEN 5', 'AMD RYZEN 7', 'APPLE M1', 'APPLE M2', 'APPLE M3'];
                                    $oldProc = old('processor', $pcUnit->processor);
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
                                    <input type="text" x-model="customProc" @input="$refs.hiddenProc.value = customProc.toUpperCase(); customProc = customProc.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify Processor"
                                           x-init="if('{{ $isCustomProc }}' == '1') { customProc = '{{ $oldProc }}'; }">
                                </div>
                                <input type="hidden" name="processor" x-ref="hiddenProc" value="{{ old('processor', $pcUnit->processor) }}">
                            </div>
                            
                            <!-- RAM -->
                            <div x-data="{ ramType: '{{ old('ram', $pcUnit->ram) }}', customRam: '' }">
                                <label for="ram_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    RAM
                                </label>
                                @php
                                    $ramOptions = ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB'];
                                    $oldRam = old('ram', $pcUnit->ram);
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
                                    <input type="text" x-model="customRam" @input="$refs.hiddenRam.value = customRam.toUpperCase(); customRam = customRam.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify RAM"
                                           x-init="if('{{ $isCustomRam }}' == '1') { customRam = '{{ $oldRam }}'; }">
                                </div>
                                <input type="hidden" name="ram" x-ref="hiddenRam" value="{{ old('ram', $pcUnit->ram) }}">
                            </div>
                            
                            <!-- Storage -->
                            <div x-data="{ storageType: '{{ old('storage', $pcUnit->storage) }}', customStorage: '' }">
                                <label for="storage_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    STORAGE
                                </label>
                                @php
                                    $storageOptions = ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD', '500GB HDD', '1TB HDD', '2TB HDD', 'Hybrid'];
                                    $oldStorage = old('storage', $pcUnit->storage);
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
                                    <input type="text" x-model="customStorage" @input="$refs.hiddenStorage.value = customStorage.toUpperCase(); customStorage = customStorage.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify Storage"
                                           x-init="if('{{ $isCustomStorage }}' == '1') { customStorage = '{{ $oldStorage }}'; }">
                                </div>

                                <!-- Secondary Storage (if Hybrid) -->
                                <div x-show="storageType === 'Hybrid'" class="mt-2" style="display: none;">
                                    <label class="block text-gray-600 text-[10px] font-bold mb-1 uppercase tracking-tighter">SECOND STORAGE DEVICE</label>
                                    <input type="text" name="storage_secondary" value="{{ old('storage_secondary', $pcUnit->storage_secondary) }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="e.g. 1TB HDD"
                                           oninput="this.value = this.value.toUpperCase()">
                                </div>

                                <input type="hidden" name="storage" x-ref="hiddenStorage" value="{{ old('storage', $pcUnit->storage) }}">
                            </div>
                        </div>

                        <!-- Monitor Information -->
                        <div x-show="deviceType !== 'Laptop' && deviceType !== 'All-in-One'" style="display: none;">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 uppercase tracking-tighter font-bold">Monitor Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="monitor_brand" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                        MONITOR BRAND <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="monitor_brand" id="monitor_brand" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('monitor_brand') border-red-500 @enderror" 
                                           value="{{ old('monitor_brand', $pcUnit->monitor_brand) }}" 
                                           placeholder="e.g. DELL, SAMSUNG, LG"
                                           oninput="this.value = this.value.toUpperCase()">
                                    @error('monitor_brand')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="monitor_serial" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                        MONITOR SERIAL NUMBER
                                    </label>
                                    <input type="text" name="monitor_serial" id="monitor_serial" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('monitor_serial') border-red-500 @enderror" 
                                           value="{{ old('monitor_serial', $pcUnit->monitor_serial) }}" 
                                           placeholder="SERIAL NUMBER"
                                           oninput="this.value = this.value.toUpperCase()">
                                    @error('monitor_serial')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- MS Office Details -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4 uppercase tracking-tighter font-bold">MS Office Details</h3>
                        <div x-data="{ officeType: '{{ old('ms_office_licensed', $pcUnit->ms_office_licensed ?? 'UNLICENSED') }}' }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    LICENSING
                                </label>
                                <select name="ms_office_licensed" x-model="officeType"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                                    <option value="LICENSED" {{ old('ms_office_licensed', $pcUnit->ms_office_licensed) === 'LICENSED' ? 'selected' : '' }}>LICENSED</option>
                                    <option value="UNLICENSED" {{ old('ms_office_licensed', $pcUnit->ms_office_licensed ?? 'UNLICENSED') === 'UNLICENSED' ? 'selected' : '' }}>UNLICENSED</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    MS Office Version
                                </label>
                                <input type="text" name="ms_office_version" value="{{ old('ms_office_version', $pcUnit->ms_office_version) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                       placeholder="e.g. MS Office 2021"
                                       oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div x-show="officeType === 'LICENSED'" style="display: none;">
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase font-bold text-indigo-600">
                                    MS OFFICE Email
                                </label>
                                <input type="text" name="ms_office_email" value="{{ old('ms_office_email', $pcUnit->ms_office_email) }}"
                                       class="w-full px-4 py-2 border border-indigo-200 bg-indigo-50 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="account@email.com">
                            </div>

                            <div x-show="officeType === 'LICENSED'" style="display: none;">
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase font-bold text-indigo-600">
                                    MS OFFICE Password
                                </label>
                                <input type="text" name="ms_office_password" value="{{ old('ms_office_password', $pcUnit->ms_office_password) }}"
                                       class="w-full px-4 py-2 border border-indigo-200 bg-indigo-50 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="PASSWORD">
                            </div>
                        </div>

                        <!-- Network Details -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Network Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div x-data="{ 
                                ipType: '{{ strtoupper(old('ip_type', $pcUnit->ip_type ?? 'STATIC')) }}',
                                ipAddress: '{{ old('ip_address', $pcUnit->ip_address) }}',
                                lastStaticIp: '{{ old('ip_address', $pcUnit->ip_address) }}',
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
                                @error('ip_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div x-data="{ 
                                mac: '{{ old('mac_address', $pcUnit->mac_address) }}',
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
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase" 
                                       value="{{ old('network_segment', $pcUnit->network_segment) }}" 
                                       placeholder="VLAN 10 / Backend"
                                       oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>


                        <!-- Group -->
                        <div class="mb-6">
                            <label for="group" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <select name="group" id="group"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('group') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('group', $pcUnit->group) === $group ? 'selected' : '' }}>
                                        {{ strtoupper($group) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assignment Section -->
                        <!-- Assignment Section -->
                        @php
                            $specialStatuses = ['disposed', 'condemned', 'defective'];
                            $isSpecialStatus = in_array(strtolower($pcUnit->status), $specialStatuses);
                        @endphp

                        @if($isSpecialStatus)
                            <div class="mb-6 border-t pt-4">
                                <label class="block text-lg font-medium text-gray-900 mb-2">Status & Assignment</label>
                                <div class="p-4 rounded-lg {{ strtolower($pcUnit->status) === 'disposed' ? 'bg-red-50' : 'bg-yellow-50' }} border border-{{ strtolower($pcUnit->status) === 'disposed' ? 'red' : 'yellow' }}-200">
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-sm font-bold uppercase rounded-full 
                                            {{ strtolower($pcUnit->status) === 'disposed' ? 'bg-red-600 text-white' : 'bg-yellow-500 text-white' }}">
                                            {{ strtoupper($pcUnit->status) }}
                                        </span>
                                        <span class="ml-3 text-sm text-gray-600">
                                            This unit is currently <strong>{{ strtoupper($pcUnit->status) }}</strong> and cannot be reassigned. To change the status, please use the appropriate action from the details page.
                                        </span>
                                    </div>
                                    <input type="hidden" name="assignment_type" value="standby">
                                </div>
                            </div>
                        @else
                            <div class="mb-6 border-t pt-4" x-data="{ assignmentType: '{{ $pcUnit->employee_id ? 'assign' : 'standby' }}' }">
                                <label class="block text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">ASSIGNMENT</label>
                                
                                <div class="flex space-x-6 mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="assignment_type" value="standby" 
                                               class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                               x-model="assignmentType">
                                        <span class="ml-2 text-gray-700 uppercase font-bold">STORAGE AVAILABLE</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="assignment_type" value="assign" 
                                               class="border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                               x-model="assignmentType">
                                        <span class="ml-2 text-gray-700 uppercase font-bold">DEPLOYMENT</span>
                                    </label>
                                </div>

                                <div x-show="assignmentType === 'assign'" class="bg-gray-50 p-4 rounded-md mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SELECT EMPLOYEE</label>
                                    <select name="employee_id" x-init="new Choices($el, { searchPlaceholderValue: 'SEARCH NAME, DEPARTMENT...' })" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">-- CHOOSE EMPLOYEE --</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id', $pcUnit->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ strtoupper($employee->full_name) }} &mdash; {{ strtoupper($employee->department ?? 'N/A') }} / {{ strtoupper($employee->division ?? 'N/A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Date Issued -->
                        <div class="mb-6">
                            <label for="date_issued" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                DATE ISSUED
                            </label>
                            <input type="date" name="date_issued" id="date_issued" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('date_issued', $pcUnit->date_issued?->format('Y-m-d')) }}">
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                REMARKS
                            </label>
                            <textarea name="remarks" id="remarks" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase" 
                                      rows="3"
                                      oninput="this.value = this.value.toUpperCase()">{{ old('remarks', $pcUnit->remarks) }}</textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('pc-units.index') }}" 
                               class="text-gray-600 hover:text-gray-800 font-medium">
                                ← Back to PC Units
                            </a>
                            <div class="flex space-x-3">
                                <button type="submit" 
                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Update PC Unit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>