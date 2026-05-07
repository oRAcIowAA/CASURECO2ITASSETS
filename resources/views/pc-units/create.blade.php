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

                    <form method="POST" action="{{ route('pc-units.store') }}" x-data="{ 
                        deviceType: '{{ old('device_type', $type ?? 'Desktop') }}',
                        assignmentType: '{{ old('assignment_type', 'STANDBY') }}',
                        location: '{{ old('location') }}'
                    }">
                        @csrf
                        
                        @php
                            $selectedType = old('device_type', $type ?? 'Desktop');
                        @endphp

                        <!-- Device Type (dropdown: PC / Laptop / Server) -->
                        <div class="mb-6">
                            <label for="device_type" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                DEVICE TYPE <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="device_type"
                                name="device_type"
                                x-model="deviceType"
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
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                            <div class="flex rounded-md shadow-sm">
                                <input type="text" name="asset_tag" value="{{ $nextAssetTag }}" readonly
                                       class="flex-1 min-w-0 block w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300"
                                       title="This tag is automatically generated">
                            </div>
                            <p class="text-xs text-gray-500 mt-1 italic">Automatically generated based on the latest record.</p>
                        </div>

                        <!-- Model -->
                        <div class="mb-6">
                            <label for="model" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                MODEL <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="model" id="model" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('model') border-red-500 @enderror" 
                                   value="{{ old('model') }}" 
                                   placeholder="Dell OptiPlex 7010" 
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
                                   value="{{ old('serial_number') }}" 
                                   placeholder="ENTER SERIAL NUMBER" 
                                   oninput="this.value = this.value.toUpperCase()">
                            @error('serial_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hardware Specs -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <!-- Operating System -->
                            <div x-data="{ osType: '{{ old('os_version') }}', customOs: '' }">
                                <label for="os_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    OPERATING SYSTEM
                                </label>
                                @php
                                    $osOptions = ['WINDOWS 11', 'WINDOWS 10', 'WINDOWS 7', 'LINUX', 'MACOS'];
                                    $oldOs = old('os_version');
                                    $isCustomOs = $oldOs && !in_array(strtoupper($oldOs), $osOptions);
                                @endphp
                                <select x-model="osType" id="os_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(osType !== 'Other') { customOs = ''; $refs.hiddenOs.value = osType } else { $refs.hiddenOs.value = customOs }">
                                    <option value="">SELECT OS</option>
                                    @foreach($osOptions as $opt)
                                        <option value="{{ $opt }}" {{ strtoupper($oldOs) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomOs ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="osType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customOs" @input="$refs.hiddenOs.value = customOs.toUpperCase(); customOs = customOs.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify OS"
                                           x-init="if('{{ $isCustomOs }}' == '1') { customOs = '{{ $oldOs }}'; }">
                                </div>
                                <input type="hidden" name="os_version" x-ref="hiddenOs" value="{{ old('os_version') }}">
                            </div>

                            <!-- Processor -->
                            <div x-data="{ procType: '{{ old('processor') }}', customProc: '' }">
                                <label for="processor_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    PROCESSOR
                                </label>
                                @php
                                    $procOptions = ['INTEL I3', 'INTEL I5', 'INTEL I7', 'INTEL I9', 'AMD RYZEN 3', 'AMD RYZEN 5', 'AMD RYZEN 7', 'APPLE M1', 'APPLE M2', 'APPLE M3'];
                                    $oldProc = old('processor');
                                    $isCustomProc = $oldProc && !in_array(strtoupper($oldProc), $procOptions);
                                @endphp
                                <select x-model="procType" id="processor_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(procType !== 'Other') { customProc = ''; $refs.hiddenProc.value = procType } else { $refs.hiddenProc.value = customProc }">
                                    <option value="">SELECT PROCESSOR</option>
                                    @foreach($procOptions as $opt)
                                        <option value="{{ $opt }}" {{ strtoupper($oldProc) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomProc ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="procType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customProc" @input="$refs.hiddenProc.value = customProc.toUpperCase(); customProc = customProc.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
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
                                    $isCustomRam = $oldRam && !in_array(strtoupper($oldRam), $ramOptions);
                                @endphp
                                <select x-model="ramType" id="ram_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="if(ramType !== 'Other') { customRam = ''; $refs.hiddenRam.value = ramType } else { $refs.hiddenRam.value = customRam }">
                                    <option value="">SELECT RAM</option>
                                    @foreach($ramOptions as $opt)
                                        <option value="{{ $opt }}" {{ strtoupper($oldRam) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                    <option value="Other" {{ $isCustomRam ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <div x-show="ramType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customRam" @input="$refs.hiddenRam.value = customRam.toUpperCase(); customRam = customRam.toUpperCase()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify RAM"
                                           x-init="if('{{ $isCustomRam }}' == '1') { customRam = '{{ $oldRam }}'; }">
                                </div>
                                <input type="hidden" name="ram" x-ref="hiddenRam" value="{{ old('ram') }}">
                            </div>

                            <!-- Storage -->
                            @php
                                $storageOptions = ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD', '500GB HDD', '1TB HDD', '2TB HDD'];
                                $oldStorage = old('storage');
                                $isHybrid = str_contains($oldStorage, '/');
                                $isCustom = $oldStorage && !in_array(strtoupper($oldStorage), $storageOptions) && !$isHybrid;
                                
                                $hybridPrimary = '';
                                $hybridSecondary = '';
                                if ($isHybrid) {
                                    $parts = explode('/', $oldStorage);
                                    $hybridPrimary = $parts[0] ?? '';
                                    $hybridSecondary = $parts[1] ?? '';
                                }
                            @endphp

                            <div x-data="{ 
                                storageType: '{{ $isHybrid ? 'Hybrid' : ($isCustom ? 'Other' : $oldStorage) }}', 
                                customStorage: '{{ $isCustom ? $oldStorage : '' }}',
                                hybridPrimary: '{{ $hybridPrimary && in_array(strtoupper($hybridPrimary), $storageOptions) ? $hybridPrimary : ($hybridPrimary ? 'Other' : '') }}',
                                hybridSecondary: '{{ $hybridSecondary && in_array(strtoupper($hybridSecondary), $storageOptions) ? $hybridSecondary : ($hybridSecondary ? 'Other' : '') }}',
                                customHybridPrimary: '{{ !in_array(strtoupper($hybridPrimary), $storageOptions) ? $hybridPrimary : '' }}',
                                customHybridSecondary: '{{ !in_array(strtoupper($hybridSecondary), $storageOptions) ? $hybridSecondary : '' }}',
                                updateHidden() {
                                    if (this.storageType === 'Hybrid') {
                                        let p = this.hybridPrimary === 'Other' ? this.customHybridPrimary : this.hybridPrimary;
                                        let s = this.hybridSecondary === 'Other' ? this.customHybridSecondary : this.hybridSecondary;
                                        this.$refs.hiddenStorage.value = (p || '') + '/' + (s || '');
                                    } else if (this.storageType === 'Other') {
                                        this.$refs.hiddenStorage.value = this.customStorage.toUpperCase();
                                    } else {
                                        this.$refs.hiddenStorage.value = this.storageType;
                                    }
                                }
                            }" x-init="updateHidden()">
                                <label for="storage_select" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    STORAGE
                                </label>
                                
                                <select x-model="storageType" id="storage_select"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-2 uppercase"
                                        @change="updateHidden()">
                                    <option value="">SELECT STORAGE</option>
                                    @foreach($storageOptions as $opt)
                                        <option value="{{ $opt }}" {{ strtoupper($oldStorage) === $opt ? 'selected' : '' }}>{{ strtoupper($opt) }}</option>
                                    @endforeach
                                    <option value="Hybrid" {{ $isHybrid ? 'selected' : '' }}>HYBRID</option>
                                    <option value="Other" {{ $isCustom ? 'selected' : '' }}>OTHER...</option>
                                </select>
                                
                                <!-- Hybrid Inputs -->
                                <div x-show="storageType === 'Hybrid'" class="grid grid-cols-2 gap-6 mt-4 mb-2" style="display: none;">
                                    <div>
                                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase">Primary Storage</label>
                                        <select x-model="hybridPrimary" @change="updateHidden()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-indigo-500 mb-2">
                                            <option value="">SELECT</option>
                                            @foreach($storageOptions as $opt)
                                                <option value="{{ $opt }}">{{ strtoupper($opt) }}</option>
                                            @endforeach
                                            <option value="Other">OTHER...</option>
                                        </select>
                                        <input type="text" x-show="hybridPrimary === 'Other'" x-model="customHybridPrimary" @input="updateHidden()"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-indigo-500" placeholder="Specify Primary">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase">Secondary Storage</label>
                                        <select x-model="hybridSecondary" @change="updateHidden()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-indigo-500 mb-2">
                                            <option value="">SELECT</option>
                                            @foreach($storageOptions as $opt)
                                                <option value="{{ $opt }}">{{ strtoupper($opt) }}</option>
                                            @endforeach
                                            <option value="Other">OTHER...</option>
                                        </select>
                                        <input type="text" x-show="hybridSecondary === 'Other'" x-model="customHybridSecondary" @input="updateHidden()"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-indigo-500" placeholder="Specify Secondary">
                                    </div>
                                </div>

                                <div x-show="storageType === 'Other'" style="display: none;">
                                    <input type="text" x-model="customStorage" @input="updateHidden()"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                           placeholder="Specify Storage">
                                </div>

                                <input type="hidden" name="storage" x-ref="hiddenStorage" value="{{ $oldStorage }}">
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
                                           value="{{ old('monitor_brand') }}" 
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
                                           value="{{ old('monitor_serial') }}" 
                                           placeholder="SERIAL NUMBER"
                                           oninput="this.value = this.value.toUpperCase()">
                                    @error('monitor_serial')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Network Details -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Network Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase" 
                                       value="{{ old('network_segment') }}" 
                                       placeholder="VLAN 10 / Backend"
                                       oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>

                        <!-- MS Office Details -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4 uppercase tracking-tighter font-bold">MS Office Details</h3>
                        <div x-data="{ officeType: '{{ old('ms_office_licensed', 'UNLICENSED') }}' }" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    LICENSING
                                </label>
                                <select name="ms_office_licensed" x-model="officeType"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                                    <option value="LICENSED" {{ old('ms_office_licensed') === 'LICENSED' ? 'selected' : '' }}>LICENSED</option>
                                    <option value="UNLICENSED" {{ old('ms_office_licensed', 'UNLICENSED') === 'UNLICENSED' ? 'selected' : '' }}>UNLICENSED</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                    MS Office Version
                                </label>
                                <input type="text" name="ms_office_version" value="{{ old('ms_office_version') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                       placeholder="e.g. MS Office 2021"
                                       oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div x-show="officeType === 'LICENSED'" style="display: none;">
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase font-bold text-indigo-600">
                                    MS OFFICE Email
                                </label>
                                <input type="text" name="ms_office_email" value="{{ old('ms_office_email') }}"
                                       class="w-full px-4 py-2 border border-indigo-200 bg-indigo-50 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="account@email.com">
                            </div>

                            <div x-show="officeType === 'LICENSED'" style="display: none;">
                                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase font-bold text-indigo-600">
                                    MS OFFICE Password
                                </label>
                                <input type="text" name="ms_office_password" value="{{ old('ms_office_password') }}"
                                       class="w-full px-4 py-2 border border-indigo-200 bg-indigo-50 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="PASSWORD">
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-6">
                            <label for="location_id" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Location <span class="text-red-500">*</span>
                            </label>
                            <select name="location_id" id="location_id" x-model="location"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('location_id') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $id => $name)
                                    <option value="{{ $id }}" {{ old('location_id') == $id ? 'selected' : '' }}>
                                        {{ strtoupper($name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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

                            <div x-show="assignmentType === 'ASSIGN'" class="bg-gray-50 p-4 rounded-md mb-4"
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
                                
                                @error('employee_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hidden Status Field (Defaults to 'available', controller handles 'assigned') -->
                            <input type="hidden" name="status" value="available">
                        </div>

                        <!-- Date Issued -->
                        <div class="mb-6">
                            <label for="date_issued" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                DATE ISSUED
                            </label>
                            <input type="date" name="date_issued" id="date_issued" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('date_issued') }}">
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                REMARKS
                            </label>
                            <textarea name="remarks" id="remarks" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase" 
                                      rows="3" 
                                      placeholder="Additional notes about this PC unit"
                                      oninput="this.value = this.value.toUpperCase()">{{ old('remarks') }}</textarea>
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


