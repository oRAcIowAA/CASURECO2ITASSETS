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
                                                                    $searchTerms = [
                                                                        $employee->full_name,
                                                                        $employee->position,
                                                                    ];
                                                                    foreach($employee->pcUnits as $unit) {
                                                                        $searchTerms[] = $unit->asset_tag;
                                                                        $searchTerms[] = $unit->model;
                                                                        $searchTerms[] = $unit->device_type;
                                                                    }
                                                                    foreach($employee->printers as $printer) {
                                                                        $searchTerms[] = $printer->brand;
                                                                        $searchTerms[] = $printer->model;
                                                                    }
                                                                    foreach($employee->networkDevices as $device) {
                                                                        $searchTerms[] = $device->brand;
                                                                        $searchTerms[] = $device->model;
                                                                        $searchTerms[] = $device->device_type;
                                                                    }
                                                                    $searchString = strtolower(implode(' ', $searchTerms));
                                                                @endphp
                                                                <li class="px-6 py-4 flex items-center hover:bg-gray-100 transition-colors js-employee" data-search="{{ htmlspecialchars($searchString) }}">
                                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                                                        {{ substr($employee->full_name, 0, 1) }}
                                                                    </div>
                                                                    <div class="ml-4 flex-1 min-w-0">
                                                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                                                            <div class="min-w-0">
                                                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                                                    <a href="{{ route('employees.show', $employee) }}" class="hover:text-blue-600 hover:underline">
                                                                                        {{ $employee->full_name }}
                                                                                    </a>
                                                                                </div>
                                                                                <div class="text-sm text-gray-500 truncate">{{ $employee->position }}</div>
                                                                            </div>
                                                                            
                                                                            <!-- Assigned Units -->
                                                                            <div class="flex space-x-2 overflow-x-auto pb-1 max-w-full">
                                                                                @forelse($employee->pcUnits as $unit)
                                                                                    <a href="{{ route('pc-units.show', $unit) }}" 
                                                                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors flex-shrink-0"
                                                                                       title="{{ $unit->device_type }} - {{ $unit->model }}">
                                                                                        <svg class="mr-1.5 h-3 w-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                                                        </svg>
                                                                                        {{ $unit->asset_tag }}
                                                                                    </a>
                                                                                @empty
                                                                                @endforelse
 
                                                                                 @foreach($employee->printers as $printer)
                                                                                    <a href="{{ route('printers.show', $printer) }}" 
                                                                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 hover:bg-purple-200 transition-colors flex-shrink-0"
                                                                                       title="Printer - {{ $printer->brand }} {{ $printer->model }}">
                                                                                        <svg class="mr-1.5 h-3 w-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                                                        </svg>
                                                                                        {{ $printer->asset_tag }}
                                                                                    </a>
                                                                                @endforeach
 
                                                                                 @foreach($employee->networkDevices as $device)
                                                                                    <a href="{{ route('network-devices.show', $device) }}" 
                                                                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors flex-shrink-0"
                                                                                       title="Network - {{ $device->brand }} {{ $device->model }}">
                                                                                        <svg class="mr-1.5 h-3 w-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 01-2 2v4a2 2 0 012 2h14a2 2 0 012-2v-4a2 2 0 01-2-2m-2-4h.01M17 16h.01"></path>
                                                                                        </svg>
                                                                                        {{ $device->asset_tag }}
                                                                                    </a>
                                                                                @endforeach
 
                                                                                @if($employee->pcUnits->isEmpty() && $employee->printers->isEmpty() && $employee->networkDevices->isEmpty())
                                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 flex-shrink-0">
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
