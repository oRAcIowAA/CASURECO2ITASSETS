<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transfer Mobile Device') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 flex items-center p-4 bg-indigo-50 rounded-lg">
                        <div class="mr-4 bg-indigo-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-indigo-900 uppercase">Transferring Unit</h3>
                            <p class="text-sm text-indigo-700 font-bold uppercase tracking-tight">{{ $mobileDevice->asset_tag }} &mdash; {{ $mobileDevice->brand }} {{ $mobileDevice->model }}</p>
                        </div>
                    </div>



                    <form action="{{ route('mobile-devices.reassign', $mobileDevice) }}" method="POST" x-data="{
                    }">
                        @csrf
                        <div class="mb-6"
                             x-data="{ 
                                search: '', 
                                open: false, 
                                selectedId: '{{ old('employee_id') }}',
                                employees: @js($employees->map(fn($e) => [
                                    'id' => $e->id,
                                    'name' => strtoupper($e->full_name),
                                    'dept' => strtoupper($e->department ?? 'N/A'),
                                    'div' => strtoupper($e->division ?? 'N/A')
                                ])),
                                get filteredEmployees() {
                                    return this.employees.filter(e => {
                                        const matchesSearch = e.name.toLowerCase().includes(this.search.toLowerCase());
                                        const isNotCurrent = e.id !== '{{ $mobileDevice->employee_id }}';
                                        return matchesSearch && isNotCurrent;
                                    }).slice(0, 10);
                                }
                             }">
                            <label for="employee_id" class="block text-sm font-bold text-gray-700 uppercase mb-2">Select New Employee</label>
                            
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
                            <p class="mt-2 text-xs text-gray-500 uppercase italic">The current owner is: {{ $mobileDevice->employee_id ? strtoupper($mobileDevice->employee->full_name) : 'NONE (AVAILABLE)' }}</p>
                        </div>

                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-bold text-gray-700 uppercase mb-2">Transfer Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" placeholder="REASON FOR TRANSFER, CONDITION UPON TURNOVER..."></textarea>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t">
                            <a href="{{ route('mobile-devices.show', $mobileDevice) }}" class="text-sm font-bold text-gray-600 hover:text-gray-900 uppercase">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                Confirm Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


