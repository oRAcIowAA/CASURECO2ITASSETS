<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transfer Network Device: ') . $networkDevice->brand . ' ' . $networkDevice->model }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900">Current Assignment</h3>
                        <p class="text-gray-600 mt-1">
                            @if($networkDevice->employee)
                                Currently assigned to: <strong>{{ $networkDevice->employee->full_name }}</strong> 
                                ({{ strtoupper($networkDevice->employee->department ?? 'N/A') }} / {{ strtoupper($networkDevice->employee->division ?? 'N/A') }} / {{ strtoupper($networkDevice->employee->group ?? 'N/A') }})
                            @else
                                Currently <strong>Unassigned</strong>
                            @endif
                        </p>
                    </div>



                    <form method="POST" action="{{ route('network-devices.reassign', $networkDevice) }}" x-data="{
                    }">
                        @csrf
                        
                        <!-- New Employee -->
                        <div class="mb-6"
                             x-data="{ 
                                search: '', 
                                open: false, 
                                selectedId: '{{ old('employee_id') }}',
                                employees: @js($employees->map(fn($e) => [
                                    'id' => $e->emp_id,
                                    'name' => strtoupper($e->full_name),
                                    'dept' => strtoupper($e->department ?? 'N/A'),
                                    'div' => strtoupper($e->division ?? 'N/A')
                                ])),
                                get filteredEmployees() {
                                    return this.employees.filter(e => {
                                        const matchesSearch = e.name.toLowerCase().includes(this.search.toLowerCase());
                                        const isNotCurrent = e.id !== '{{ $networkDevice->employee_id }}';
                                        return matchesSearch && isNotCurrent;
                                    }).slice(0, 10);
                                }
                             }">
                            <label for="employee_id" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Transfer To <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="relative">
                                <input type="text" 
                                       x-model="search" 
                                       @focus="open = true" 
                                       @click.away="open = false"
                                       @keydown.escape="open = false"
                                       placeholder="TYPE TO SEARCH NEW OWNER..." 
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
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-gray-700 text-sm font-medium mb-2">
                                Transfer Remarks/Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Reason for transfer..."></textarea>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('network-devices.show', $networkDevice) }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Confirm Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


