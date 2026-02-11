<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organization Chart') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ activeBranch: '{{ $branches->first()->id ?? 0 }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Branch Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    @foreach($branches as $branch)
                        <button 
                            @click="activeBranch = '{{ $branch->id }}'"
                            :class="{ 'border-blue-500 text-blue-600': activeBranch == '{{ $branch->id }}', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeBranch != '{{ $branch->id }}' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            {{ $branch->branch_name }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <!-- Branch Content -->
            @foreach($branches as $branch)
                <div x-show="activeBranch == '{{ $branch->id }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    
                    @if($branch->departments->count() > 0)
                        <div class="space-y-4">
                            @foreach($branch->departments as $department)
                                <div class="bg-white overflow-hidden shadow-sm rounded-lg" x-data="{ open: false }">
                                    <!-- Department Header (Folder) -->
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
                                                <h3 class="text-lg font-medium text-gray-900">{{ $department->department_name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $department->employees->count() }} Employees</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Chevron -->
                                        <svg class="h-5 w-5 text-gray-400 transform transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Employee List (Collapsible) -->
                                    <div x-show="open" x-collapse class="border-t border-gray-100 bg-gray-50">
                                        @if($department->employees->count() > 0)
                                            <ul class="divide-y divide-gray-100">
                                                @foreach($department->employees as $employee)
                                                    <li class="px-6 py-4 flex items-center hover:bg-gray-100 transition-colors">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                                            {{ substr($employee->full_name, 0, 1) }}
                                                        </div>
                                                        <div class="ml-4 flex-1">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <div class="text-sm font-medium text-gray-900">{{ $employee->full_name }}</div>
                                                                    <div class="text-sm text-gray-500">{{ $employee->position }}</div>
                                                                </div>
                                                                
                                                                <!-- Assigned Units -->
                                                                <div class="flex space-x-2">
                                                                    @forelse($employee->pcUnits as $unit)
                                                                        <a href="{{ route('pc-units.show', $unit) }}" 
                                                                           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors"
                                                                           title="{{ $unit->device_type }} - {{ $unit->model }}">
                                                                            @if(strtolower($unit->device_type) == 'laptop')
                                                                                <svg class="mr-1.5 h-3 w-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                                                </svg>
                                                                            @else
                                                                                <svg class="mr-1.5 h-3 w-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                                                </svg>
                                                                            @endif
                                                                            {{ $unit->asset_tag }}
                                                                        </a>
                                                                    @empty
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                            No Devices
                                                                        </span>
                                                                    @endforelse
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
                                            <div class="px-6 py-4 text-sm text-gray-500 italic">No employees assigned to this department.</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-sm p-6 text-center text-gray-500">
                            No departments found for this branch.
                        </div>
                    @endif
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
