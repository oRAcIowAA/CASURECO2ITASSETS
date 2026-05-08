<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Mobile Device') }} - {{ $mobileDevice->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <ul class="list-disc list-inside text-sm uppercase font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



                <form method="POST" action="{{ route('mobile-devices.update', $mobileDevice) }}" x-data="{ 
                    assignmentType: '{{ old('assignment_type', $mobileDevice->employee_id ? 'ASSIGN' : 'AVAILABLE') }}',
                    location: '{{ old('location_id', $mobileDevice->location_id) }}'
                }">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">ASSET TAG</label>
                            <input type="text" value="{{ $mobileDevice->asset_tag }}" readonly
                                   class="w-full px-4 py-2 bg-gray-100 border-gray-300 rounded-md text-gray-600 font-bold focus:ring-0 focus:border-gray-300">
                            <p class="text-xs text-gray-500 mt-1 italic uppercase underline">LOCKED</p>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DEVICE TYPE <span class="text-red-500">*</span></label>
                            <select name="type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="CELLPHONE" {{ old('type', $mobileDevice->type) == 'CELLPHONE' ? 'selected' : '' }}>CELLPHONE</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1 italic uppercase">&nbsp;</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">BRAND</label>
                            <input type="text" name="brand" value="{{ old('brand', $mobileDevice->brand) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. SAMSUNG, APPLE, XIAOMI"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">MODEL</label>
                            <input type="text" name="model" value="{{ old('model', $mobileDevice->model) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. GALAXY S24, IPHONE 15"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">SERIAL NUMBER / IMEI</label>
                        <input type="text" name="serial_number" value="{{ old('serial_number', $mobileDevice->serial_number) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                               placeholder="ENTER SERIAL NUMBER OR IMEI"
                               oninput="this.value = this.value.toUpperCase()">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">PROCESSOR</label>
                            <input type="text" name="processor" value="{{ old('processor', $mobileDevice->processor) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. SNAPDRAGON 8 GEN 3"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">RAM</label>
                            <input type="text" name="ram" value="{{ old('ram', $mobileDevice->ram) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. 12GB"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">STORAGE</label>
                            <input type="text" name="storage" value="{{ old('storage', $mobileDevice->storage) }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                   placeholder="e.g. 256GB"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">Location <span class="text-red-500">*</span></label>
                            <select name="location_id" x-model="location" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">SELECT LOCATION</option>
                                @foreach($groups as $id => $name)
                                    <option value="{{ $id }}" {{ old('location_id', $mobileDevice->location_id) == $id ? 'selected' : '' }}>{{ strtoupper($name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">DATE ISSUED</label>
                            <input type="date" name="date_issued" value="{{ old('date_issued', $mobileDevice->date_issued ? $mobileDevice->date_issued->format('Y-m-d') : '') }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1 uppercase">REMARKS / ACCESSORIES</label>
                        <textarea name="spare_parts" rows="1"
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase"
                                  oninput="this.value = this.value.toUpperCase()">{{ old('spare_parts', $mobileDevice->spare_parts) }}</textarea>
                    </div>

                    <!-- Assignment Section -->
                    <div class="mb-6 border-t pt-4">
                        <label class="block text-lg font-bold text-gray-900 mb-2 uppercase tracking-wide">ASSIGNMENT</label>
                        
                        @php
                            $isDisposed = in_array(strtolower($mobileDevice->status), ['disposed', 'condemned', 'defective']);
                        @endphp

                        @if($isDisposed)
                            <div class="bg-red-50 p-3 rounded-md mb-4 border border-red-200">
                                <p class="text-red-700 font-bold uppercase text-xs">
                                    LOCKED: STATUS IS {{ strtoupper($mobileDevice->status) }}
                                </p>
                                <input type="hidden" name="assignment_type" value="{{ $mobileDevice->employee_id ? 'ASSIGN' : 'AVAILABLE' }}">
                                <input type="hidden" name="employee_id" value="{{ $mobileDevice->employee_id }}">
                            </div>
                        @else
                            <div class="flex space-x-6 mb-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="assignment_type" value="AVAILABLE" 
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

                                <div x-show="assignmentType === 'ASSIGN'" class="bg-gray-50 p-4 rounded-md shadow-inner"
                                     x-data="{ 
                                        search: '{{ old('employee_id') ? ($employees->firstWhere('emp_id', old('employee_id'))->full_name ?? '') : ($mobileDevice->employee->full_name ?? '') }}', 
                                        open: false, 
                                        selectedId: '{{ old('employee_id', $mobileDevice->employee_id) }}',
                                        employees: @js($employees->map(fn($e) => [
                                            'id' => $e->id,
                                            'name' => strtoupper($e->full_name),
                                            'location_id' => $e->location_id,
                                            'dept' => strtoupper($e->department ?? 'N/A'),
                                            'div' => strtoupper($e->division ?? 'N/A')
                                        ])),
                                        get filteredEmployees() {
                                            return this.employees.filter(e => {
                                                const matchesSearch = e.name.toLowerCase().includes(this.search.toLowerCase());
                                                const matchesLoc = !this.location || e.location_id == this.location || !e.location_id || e.id === '{{ $mobileDevice->employee_id }}';
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
                                </div>  </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('mobile-devices.show', $mobileDevice) }}" class="text-gray-600 hover:text-gray-900 text-sm">
                            ← Back
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 uppercase">
                            Update Mobile Device
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>


