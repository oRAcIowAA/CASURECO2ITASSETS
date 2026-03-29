<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Asset QR Codes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Search & Filters -->
            <div class="mb-6 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
                <form method="GET" action="{{ route('qr-assets.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="search" placeholder="SEARCH..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 placeholder-gray-500 uppercase font-semibold text-xs shadow-sm"
                                       value="{{ request('search') }}">
                            </div>
                        </div>
 
                        <div>
                            <select name="group" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900">
                                <option value="">ALL GROUPS</option>
                                @foreach($groups ?? [] as $group)
                                    <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>{{ strtoupper($group) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="division" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900">
                                <option value="">ALL DIVISIONS</option>
                                @foreach($divisions ?? [] as $division)
                                    <option value="{{ $division }}" {{ request('division') == $division ? 'selected' : '' }}>{{ strtoupper($division) }}</option>
                                @endforeach
                            </select>
                        </div>
 
                        <div>
                            <select name="department" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900">
                                <option value="">ALL DEPARTMENTS</option>
                                @foreach($departments ?? [] as $department)
                                    <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>{{ strtoupper($department) }}</option>
                                @endforeach
                            </select>
                        </div>
 
                        <div>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900">
                                <option value="">ALL STATUSES</option>
                                <option value="Assigned" {{ request('status') == 'Assigned' ? 'selected' : '' }}>ASSIGNED</option>
                                <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>AVAILABLE</option>
                                <option value="Defective" {{ request('status') == 'Defective' ? 'selected' : '' }}>DEFECTIVE</option>
                                <option value="Condemned" {{ request('status') == 'Condemned' ? 'selected' : '' }}>CONDEMNED</option>
                                <option value="Disposed" {{ request('status') == 'Disposed' ? 'selected' : '' }}>DISPOSED</option>
                            </select>
                        </div>
                    </div>
 
                    <div class="flex flex-wrap items-center gap-3">
                       <button type="submit" class="w- px-12 py-3 text-lg bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-sm transition-colors uppercase">
    SEARCH
</button>
                        <a href="{{ route('qr-assets.index', array_merge(request()->query(), ['status' => 'Available'])) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                            Show Available (Standby)
                        </a>
                        @if(request()->anyFilled(['search', 'group', 'division', 'department', 'status']))
                            <a href="{{ route('qr-assets.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Clear All Filters</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('qr-assets.print') }}" method="POST" target="_blank" onsubmit="return validateSelection()">
                        @csrf
                        
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Select Assets for QR Printing</h3>
                                <p class="text-sm text-gray-500">Choose the units you want to generate QR labels for.</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                    Generate Labels for Printing
                                </button>
                            </div>
                        </div>

                        <div class="space-y-8">
                            <!-- PC Units Group -->
                            <div>
                                <div class="flex items-center justify-between bg-blue-50 p-4 rounded-t-lg border-x border-t border-blue-200">
                                    <h4 class="text-blue-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        PC Units ({{ count($pcUnits) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 select-all-group" data-target="pc-checkbox">
                                        <span class="ml-2 text-sm font-medium text-blue-800">Select All PC Units</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($pcUnits as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="pc:{{ $unit->id }}" class="pc-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->device_type }} - {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-xs font-mono text-gray-400">{{ strtoupper($unit->department) }} / {{ strtoupper($unit->division) }} / {{ strtoupper($unit->group) }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Printers Group -->
                            <div>
                                <div class="flex items-center justify-between bg-orange-50 p-4 rounded-t-lg border-x border-t border-orange-200">
                                    <h4 class="text-orange-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                        Printers ({{ count($printers) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500 select-all-group" data-target="printer-checkbox">
                                        <span class="ml-2 text-sm font-medium text-orange-800">Select All Printers</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($printers as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="printer:{{ $unit->id }}" class="printer-checkbox rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->brand }} {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-xs font-mono text-gray-400">{{ strtoupper($unit->department) }} / {{ strtoupper($unit->division) }} / {{ strtoupper($unit->group) }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Networking Group -->
                            <div>
                                <div class="flex items-center justify-between bg-purple-50 p-4 rounded-t-lg border-x border-t border-purple-200">
                                    <h4 class="text-purple-900 font-bold uppercase tracking-wider flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"></path>
                                        </svg>
                                        Networking Devices ({{ count($networkDevices) }})
                                    </h4>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 select-all-group" data-target="network-checkbox">
                                        <span class="ml-2 text-sm font-medium text-purple-800">Select All Networking</span>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                                    <div class="max-h-60 overflow-y-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($networkDevices as $unit)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-3 whitespace-nowrap w-10">
                                                        <input type="checkbox" name="selected_assets[]" value="network:{{ $unit->id }}" class="network-checkbox rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500">
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm font-bold text-gray-900">{{ $unit->asset_tag }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-sm text-gray-600">{{ $unit->device_type }} - {{ $unit->brand }} {{ $unit->model }}</span>
                                                    </td>
                                                    <td class="px-6 py-3 whitespace-nowrap">
                                                        <span class="text-xs font-mono text-gray-400">{{ $unit->department }} / {{ $unit->division }}</span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function validateSelection() {
            const checkedCount = document.querySelectorAll('input[name="selected_assets[]"]:checked').length;
            if (checkedCount === 0) {
                alert('Please select at least one unit to generate labels.');
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAllCheckboxes = document.querySelectorAll('.select-all-group');
            
            selectAllCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const targetClass = this.getAttribute('data-target');
                    const targetCheckboxes = document.querySelectorAll('.' + targetClass);
                    
                    targetCheckboxes.forEach(target => {
                        target.checked = this.checked;
                    });
                });
            });

            // Update "Select All" state when individual checkboxes change
            const individualCheckboxes = document.querySelectorAll('input[name="selected_assets[]"]');
            individualCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const groupClass = Array.from(this.classList).find(cls => cls.endsWith('-checkbox'));
                    const groupCheckboxes = document.querySelectorAll('.' + groupClass);
                    const groupSelectAll = document.querySelector(`.select-all-group[data-target="${groupClass}"]`);
                    
                    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(groupCheckboxes).some(cb => cb.checked);
                    
                    groupSelectAll.checked = allChecked;
                    groupSelectAll.indeterminate = someChecked && !allChecked;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>