<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organization Chart') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ activeGroup: '{{ $groups->first()->id ?? 0 }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Group Tabs and Search -->
            <div class="border-b border-gray-200 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-4 md:pb-0">
                <nav class="-mb-px flex space-x-8 overflow-x-auto w-full md:w-auto" aria-label="Tabs">
                    @foreach($groups as $group)
                        <button 
                            @click="activeGroup = '{{ $group->id }}'"
                            :class="{ 'border-blue-500 text-blue-600': activeGroup == '{{ $group->id }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeGroup != '{{ $group->id }}' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm transition-colors duration-200 uppercase">
                            {{ strtoupper($group->group_name) }}
                        </button>
                    @endforeach
                </nav>
                
                <div class="w-full md:w-72 lg:w-96 md:py-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="orgSearch" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out" placeholder="Search division, employee, devices...">
                    </div>
                </div>
            </div>

            <!-- Group Content -->
            @foreach($groups as $group)
                <div x-show="activeGroup == '{{ $group->id }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    
                    @if($group->departments->count() > 0)
                        <div class="space-y-8">
                            @foreach($group->departments as $department)
                                <div class="pt-4 js-department" data-name="{{ strtolower(htmlspecialchars($department->department_name)) }}">
                                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2 uppercase">{{ $department->department_name }}</h3>
                                    
                                    <div class="space-y-4">
                                        @foreach($department->divisions as $division)
                                            <div class="bg-white overflow-hidden shadow-sm rounded-lg js-division" data-name="{{ strtolower(htmlspecialchars($division->division_name)) }}" x-data="{ open: false }" @org-search.window="if($event.detail !== '') { open = true; } else { open = false; }">
                                                <!-- Division Header (Folder) -->
                                                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between bg-white hover:bg-gray-50 transition-colors duration-150 focus:outline-none">
                                                    <div class="flex items-center">
                                                        <!-- Folder Icon -->
                                                        <svg class="h-6 w-6 text-yellow-400 mr-3" :class="{'hidden': open}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                                        </svg>
                                                        <svg class="h-6 w-6 text-yellow-500 mr-3 hidden" :class="{'block': open, 'hidden': !open}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h8a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        
                                                        <div class="text-left">
                                                            <h3 class="text-lg font-medium text-gray-900 uppercase">{{ $division->division_name }}</h3>
                                                            <p class="text-sm text-gray-500">{{ $division->employees->count() }} EMPLOYEES</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Chevron -->
                                                    <svg class="h-5 w-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>

                                                <!-- Employee List (Collapsible) -->
                                                <div x-show="open" x-collapse class="border-t border-gray-100 bg-gray-50">
                                                    @if($division->employees->count() > 0)
                                                        <ul class="divide-y divide-gray-100">
                                                            @foreach($division->employees as $employee)
                                                                    @php
                                                                    $totalUnits = $employee->pcUnits->count() + $employee->printers->count() + $employee->networkDevices->count() + $employee->powerUtilities->count() + $employee->mobileDevices->count();
                                                                    $isManyUnits = $totalUnits > 4; // Lowered threshold for shrinking
                                                                    
                                                                    $badgeBaseClass = "js-unit-badge inline-flex items-center rounded-md font-bold transition-all flex-shrink-0 border border-transparent";
                                                                    $badgeSizeClass = $isManyUnits ? "px-1.5 py-0.5 text-[9px]" : "px-2 py-0.5 text-[10.5px]";
                                                                    $iconSizeClass = $isManyUnits ? "h-2 w-2 mr-1" : "h-2.5 w-2.5 mr-1";

                                                                    $searchTerms = [
                                                                        $employee->full_name,
                                                                        $employee->position,
                                                                    ];
                                                                    foreach($employee->pcUnits as $unit) {
                                                                        $searchTerms[] = $unit->asset_tag;
                                                                        $searchTerms[] = $unit->model;
                                                                        $searchTerms[] = $unit->device_type;
                                                                        $searchTerms[] = $unit->ip_address;
                                                                        $searchTerms[] = $unit->mac_address;
                                                                    }
                                                                    foreach($employee->printers as $printer) {
                                                                        $searchTerms[] = $printer->asset_tag;
                                                                        $searchTerms[] = $printer->brand;
                                                                        $searchTerms[] = $printer->model;
                                                                        $searchTerms[] = $printer->ip_address;
                                                                        $searchTerms[] = $printer->mac_address;
                                                                    }
                                                                    foreach($employee->networkDevices as $device) {
                                                                        $searchTerms[] = $device->asset_tag;
                                                                        $searchTerms[] = $device->brand;
                                                                        $searchTerms[] = $device->model;
                                                                        $searchTerms[] = $device->device_type;
                                                                        $searchTerms[] = $device->ip_address;
                                                                    }
                                                                    foreach($employee->powerUtilities as $power) {
                                                                        $searchTerms[] = $power->asset_tag;
                                                                        $searchTerms[] = $power->brand;
                                                                        $searchTerms[] = $power->model;
                                                                        $searchTerms[] = $power->type;
                                                                    }
                                                                    foreach($employee->mobileDevices as $mobile) {
                                                                        $searchTerms[] = $mobile->asset_tag;
                                                                        $searchTerms[] = $mobile->brand;
                                                                        $searchTerms[] = $mobile->model;
                                                                        $searchTerms[] = $mobile->type;
                                                                        $searchTerms[] = $mobile->serial_number;
                                                                    }
                                                                    $searchString = strtolower(implode(' ', array_filter($searchTerms)));
                                                                @endphp
                                                                <li class="px-6 py-4 flex items-center hover:bg-gray-100 transition-colors js-employee" data-search="{{ htmlspecialchars($searchString) }}">
                                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                                                        {{ substr($employee->full_name, 0, 1) }}
                                                                    </div>
                                                                    <div class="ml-4 flex-1 min-w-0">
                                                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                                                            <div class="min-w-[180px] flex-shrink-0">
                                                                                <div class="text-sm font-bold text-gray-900 truncate">
                                                                                    <a href="{{ route('employees.show', $employee) }}" class="hover:text-blue-600 hover:underline">
                                                                                        {{ $employee->full_name }}
                                                                                    </a>
                                                                                </div>
                                                                                <div class="text-xs text-gray-500 truncate">{{ $employee->position }}</div>
                                                                            </div>
                                                                            
                                                                            <!-- Assigned Units -->
                                                                            <div class="flex flex-wrap gap-1.5 flex-grow justify-start md:justify-end">
                                                                                @foreach($employee->pcUnits as $unit)
                                                                                    <a href="{{ route('pc-units.show', $unit) }}" 
                                                                                       class="{{ $badgeBaseClass }} {{ $badgeSizeClass }} bg-blue-50 text-blue-700 border-blue-100 hover:bg-blue-100"
                                                                                       data-unit-search="{{ strtolower($unit->asset_tag . ' ' . $unit->model . ' ' . $unit->ip_address . ' ' . $unit->mac_address) }}"
                                                                                       title="{{ $unit->device_type }} - {{ $unit->model }} {{ $unit->ip_address ? '('.$unit->ip_address.')' : '' }}">
                                                                                        <svg class="{{ $iconSizeClass }} text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                                                        </svg>
                                                                                        {{ $unit->asset_tag }}
                                                                                    </a>
                                                                                @endforeach
 
                                                                                 @foreach($employee->printers as $printer)
                                                                                    <a href="{{ route('printers.show', $printer) }}" 
                                                                                       class="{{ $badgeBaseClass }} {{ $badgeSizeClass }} bg-purple-50 text-purple-700 border-purple-100 hover:bg-purple-100"
                                                                                       data-unit-search="{{ strtolower($printer->asset_tag . ' ' . $printer->brand . ' ' . $printer->model . ' ' . $printer->ip_address . ' ' . $printer->mac_address) }}"
                                                                                       title="Printer - {{ $printer->brand }} {{ $printer->model }} {{ $printer->ip_address ? '('.$printer->ip_address.')' : '' }}">
                                                                                        <svg class="{{ $iconSizeClass }} text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                                                        </svg>
                                                                                        {{ $printer->asset_tag }}
                                                                                    </a>
                                                                                @endforeach
 
                                                                                 @foreach($employee->networkDevices as $device)
                                                                                    <a href="{{ route('network-devices.show', $device) }}" 
                                                                                       class="{{ $badgeBaseClass }} {{ $badgeSizeClass }} bg-green-50 text-green-700 border-green-100 hover:bg-green-100"
                                                                                       data-unit-search="{{ strtolower($device->asset_tag . ' ' . $device->brand . ' ' . $device->model . ' ' . $device->device_type . ' ' . $device->ip_address) }}"
                                                                                       title="Network - {{ $device->brand }} {{ $device->model }} {{ $device->ip_address ? '('.$device->ip_address.')' : '' }}">
                                                                                        <svg class="{{ $iconSizeClass }} text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 01-2-2v4a2 2 0 012 2h14a2 2 0 012-2v-4a2 2 0 01-2-2m-2-4h.01M17 16h.01"></path>
                                                                                        </svg>
                                                                                        {{ $device->asset_tag }}
                                                                                    </a>
                                                                                @endforeach

                                                                                @foreach($employee->powerUtilities as $power)
                                                                                    <a href="{{ route('power-utilities.show', $power) }}" 
                                                                                       class="{{ $badgeBaseClass }} {{ $badgeSizeClass }} bg-indigo-50 text-indigo-700 border-indigo-100 hover:bg-indigo-100"
                                                                                       data-unit-search="{{ strtolower($power->asset_tag . ' ' . $power->brand . ' ' . $power->model . ' ' . $power->type) }}"
                                                                                       title="Power Utility - {{ $power->type }} - {{ $power->brand }} {{ $power->model }}">
                                                                                        <svg class="{{ $iconSizeClass }} text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                                                        </svg>
                                                                                        {{ $power->asset_tag }}
                                                                                    </a>
                                                                                @endforeach

                                                                                @foreach($employee->mobileDevices as $mobile)
                                                                                    <a href="{{ route('mobile-devices.show', $mobile) }}" 
                                                                                       class="{{ $badgeBaseClass }} {{ $badgeSizeClass }} bg-teal-50 text-teal-700 border-teal-100 hover:bg-teal-100"
                                                                                       data-unit-search="{{ strtolower($mobile->asset_tag . ' ' . $mobile->brand . ' ' . $mobile->model . ' ' . $mobile->type . ' ' . $mobile->serial_number) }}"
                                                                                       title="Mobile Device - {{ $mobile->type }} - {{ $mobile->brand }} {{ $mobile->model }}">
                                                                                        <svg class="{{ $iconSizeClass }} text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                                        </svg>
                                                                                        {{ $mobile->asset_tag }}
                                                                                    </a>
                                                                                @endforeach
 
                                                                                @if($totalUnits === 0)
                                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-gray-50 text-gray-500 border border-gray-100 flex-shrink-0">
                                                                                        No Devices
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-auto text-sm text-gray-500">
                                                                        <!-- Optional: Add link to profile or details -->
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <div class="px-6 py-4 text-sm text-gray-500 italic">No employees assigned to this division.</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-sm p-6 text-center text-gray-500">
                            No departments found for this group.
                        </div>
                    @endif
                </div>
            @endforeach

    </div>

    <style>
        .unit-badge-highlight {
            box-shadow: 0 0 12px rgba(234, 179, 8, 0.8), 0 0 4px rgba(0,0,0,0.1);
            border: 1.5px solid #eab308 !important;
            transform: scale(1.15);
            z-index: 50;
            background-color: #fefce8 !important;
            color: #854d0e !important;
            position: relative;
        }
        .js-unit-badge {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('orgSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    let query = this.value.toLowerCase().trim();
                    
                    document.querySelectorAll('.js-department').forEach(dept => {
                        let deptMatch = false;
                        let deptName = (dept.dataset.name || '').toLowerCase();
                        if (deptName.includes(query)) deptMatch = true;

                        dept.querySelectorAll('.js-division').forEach(div => {
                            let divMatch = false;
                            let divName = (div.dataset.name || '').toLowerCase();
                            if (divName.includes(query)) divMatch = true;

                            div.querySelectorAll('.js-employee').forEach(emp => {
                                let empMatch = false;
                                let empSearchText = (emp.dataset.search || '').toLowerCase();
                                
                                if (empSearchText.includes(query)) empMatch = true;

                                // Individual Unit Highlighting
                                emp.querySelectorAll('.js-unit-badge').forEach(badge => {
                                    let badgeSearch = (badge.dataset.unitSearch || '').toLowerCase();
                                    if (query !== '' && badgeSearch.includes(query)) {
                                        badge.classList.add('unit-badge-highlight');
                                    } else {
                                        badge.classList.remove('unit-badge-highlight');
                                    }
                                });

                                if (query === '') {
                                    emp.style.display = '';
                                } else {
                                    if (deptMatch || divMatch || empMatch) {
                                        emp.style.display = '';
                                        empMatch = true; 
                                    } else {
                                        emp.style.display = 'none';
                                    }
                                }

                                if (empMatch) divMatch = true;
                            });

                            if (query === '') {
                                div.style.display = '';
                            } else {
                                if (divMatch || deptMatch) {
                                    div.style.display = '';
                                    divMatch = true; 
                                } else {
                                    div.style.display = 'none';
                                }
                            }

                            if (divMatch) deptMatch = true;
                        });

                        if (query === '') {
                            dept.style.display = '';
                        } else {
                            dept.style.display = deptMatch ? '' : 'none';
                        }
                    });

                    // Dispatch event for Alpine to trigger open/close for matched nested components
                    window.dispatchEvent(new CustomEvent('org-search', { detail: query }));
                });
            }
        });
    </script>
</x-app-layout>


