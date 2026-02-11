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
                        
                        <!-- Device Type (Auto-selected) -->
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-medium mb-2">
                                Device Type
                            </label>
                            <div class="flex items-center space-x-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                                @if($type == 'PC')
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                @elseif($type == 'Laptop')
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M5 18h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2zM9 21h6"></path></svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                @endif
                                <span class="font-bold text-gray-900">{{ $type }}</span>
                            </div>
                            <input type="hidden" name="device_type" value="{{ $type }}">
                        </div>

                        <!-- Asset Tag -->
                        <div class="mb-6">
                            <label for="asset_tag" class="block text-gray-700 text-sm font-medium mb-2">
                                Asset Tag <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="asset_tag" id="asset_tag" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('asset_tag') border-red-500 @enderror" 
                                   value="{{ old('asset_tag') }}" 
                                   placeholder="CAS-PC-001" 
                                   required>
                            @error('asset_tag')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Model -->
                        <div class="mb-6">
                            <label for="model" class="block text-gray-700 text-sm font-medium mb-2">
                                Model <span class="text-red-500">*</span>
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
                            <div>
                                <label for="processor" class="block text-gray-700 text-sm font-medium mb-2">
                                    {{ $type == 'Printer' ? 'Printer Type' : 'Processor' }}
                                </label>
                                <input type="text" name="processor" id="processor" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       value="{{ old('processor') }}" 
                                       placeholder="{{ $type == 'Printer' ? 'LaserJet / InkTank' : 'Intel i5' }}">
                            </div>
                            
                            <div>
                                <label for="ram" class="block text-gray-700 text-sm font-medium mb-2">
                                    {{ $type == 'Printer' ? 'Support' : 'RAM' }}
                                </label>
                                <input type="text" name="ram" id="ram" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       value="{{ old('ram') }}" 
                                       placeholder="{{ $type == 'Printer' ? 'A4, Legal, Letter' : '8GB' }}">
                            </div>
                            
                            <div>
                                <label for="storage" class="block text-gray-700 text-sm font-medium mb-2">
                                    {{ $type == 'Printer' ? 'Connectivity' : 'Storage' }}
                                </label>
                                <input type="text" name="storage" id="storage" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       value="{{ old('storage') }}" 
                                       placeholder="{{ $type == 'Printer' ? 'Network/USB/WiFi' : '256GB SSD' }}">
                            </div>
                        </div>

                        <!-- Network Details -->
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Network Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="ip_address" class="block text-gray-700 text-sm font-medium mb-2">
                                    IP Address
                                </label>
                                <input type="text" name="ip_address" id="ip_address" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ip_address') border-red-500 @enderror" 
                                       value="{{ old('ip_address') }}" 
                                       placeholder="192.168.1.100">
                                @error('ip_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="mac_address" class="block text-gray-700 text-sm font-medium mb-2">
                                    MAC Address
                                </label>
                                <input type="text" name="mac_address" id="mac_address" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('mac_address') border-red-500 @enderror" 
                                       value="{{ old('mac_address') }}" 
                                       placeholder="00:1A:2B:3C:4D:5E">
                                @error('mac_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="network_segment" class="block text-gray-700 text-sm font-medium mb-2">
                                    Network Segment
                                </label>
                                <input type="text" name="network_segment" id="network_segment" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       value="{{ old('network_segment') }}" 
                                       placeholder="VLAN 10 / Backend">
                            </div>
                        </div>

                        <!-- Branch -->
                        <div class="mb-6">
                            <label for="branch_id" class="block text-gray-700 text-sm font-medium mb-2">
                                Branch <span class="text-red-500">*</span>
                            </label>
                            <select name="branch_id" id="branch_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('branch_id') border-red-500 @enderror" 
                                    required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div class="mb-6">
                            <label for="department_id" class="block text-gray-700 text-sm font-medium mb-2">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select name="department_id" id="department_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('department_id') border-red-500 @enderror" 
                                    required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }} ({{ $department->branch->branch_name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Employee -->
                        <div class="mb-6">
                            <label for="employee_id" class="block text-gray-700 text-sm font-medium mb-2">
                                Assign to Employee (Optional)
                            </label>
                            <select name="employee_id" id="employee_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Not Assigned (Available)</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} - {{ $employee->position }} ({{ $employee->department->department_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-gray-700 text-sm font-medium mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" 
                                    required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="not_available" {{ old('status') == 'not_available' ? 'selected' : '' }}>Not Available</option>
                                <option value="incoming" {{ old('status') == 'incoming' ? 'selected' : '' }}>Incoming</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date Received -->
                        <div class="mb-6">
                            <label for="date_received" class="block text-gray-700 text-sm font-medium mb-2">
                                Date Received
                            </label>
                            <input type="date" name="date_received" id="date_received" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                   value="{{ old('date_received') }}">
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-gray-700 text-sm font-medium mb-2">
                                Remarks
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