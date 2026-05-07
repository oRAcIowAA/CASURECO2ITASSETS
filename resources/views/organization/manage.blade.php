<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            {{ __('Manage Organizational Structure') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'departments', modalOpen: false, modalType: '', modalAction: '', currentItem: {} }">
        <div class="w-full sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-lg shadow-sm mb-6 border border-gray-200 overflow-hidden">
                <div class="flex border-b border-gray-200">
                    <button @click="activeTab = 'departments'" :class="activeTab === 'departments' ? 'border-blue-500 text-blue-600 bg-blue-50 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex-1 py-4 px-6 text-center border-b-2 transition-all duration-200 uppercase tracking-wider text-xs">
                        Departments
                    </button>
                    <button @click="activeTab = 'divisions'" :class="activeTab === 'divisions' ? 'border-blue-500 text-blue-600 bg-blue-50 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex-1 py-4 px-6 text-center border-b-2 transition-all duration-200 uppercase tracking-wider text-xs">
                        Divisions
                    </button>
                    <button @click="activeTab = 'locations'" :class="activeTab === 'locations' ? 'border-blue-500 text-blue-600 bg-blue-50 font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'" class="flex-1 py-4 px-6 text-center border-b-2 transition-all duration-200 uppercase tracking-wider text-xs">
                        Locations
                    </button>
                </div>

                <div class="p-6">
                    <!-- Departments Tab -->
                    <div x-show="activeTab === 'departments'" x-transition>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900 uppercase">Registered Departments</h3>
                            <button @click="modalType = 'department'; modalAction = 'create'; currentItem = {name: ''}; modalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Department
                            </button>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($departments as $dept)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ str_pad($dept->id, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 uppercase">{{ $dept->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="modalType = 'department'; modalAction = 'edit'; currentItem = {id: '{{ $dept->id }}', name: '{{ $dept->name }}'}; modalOpen = true" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold uppercase text-xs">Edit</button>
                                            <form action="{{ route('organization.departments.destroy', $dept->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this department? Action cannot be undone.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold uppercase text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Divisions Tab -->
                    <div x-show="activeTab === 'divisions'" x-transition style="display: none;">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900 uppercase">Registered Divisions</h3>
                            <button @click="modalType = 'division'; modalAction = 'create'; currentItem = {name: '', department_id: ''}; modalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Division
                            </button>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Department</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($divisions as $div)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ str_pad($div->id, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 uppercase">{{ $div->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold uppercase">
                                                {{ $div->department->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="modalType = 'division'; modalAction = 'edit'; currentItem = {id: '{{ $div->id }}', name: '{{ $div->name }}', department_id: '{{ $div->department_id }}'}; modalOpen = true" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold uppercase text-xs">Edit</button>
                                            <form action="{{ route('organization.divisions.destroy', $div->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this division?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold uppercase text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Locations Tab -->
                    <div x-show="activeTab === 'locations'" x-transition style="display: none;">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900 uppercase">Registered Locations</h3>
                            <button @click="modalType = 'location'; modalAction = 'create'; currentItem = {name: ''}; modalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-sm transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Add Location
                            </button>
                        </div>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($locations as $loc)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ str_pad($loc->id, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 uppercase">{{ $loc->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="modalType = 'location'; modalAction = 'edit'; currentItem = {id: '{{ $loc->id }}', name: '{{ $loc->name }}'}; modalOpen = true" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold uppercase text-xs">Edit</button>
                                            <form action="{{ route('organization.locations.destroy', $loc->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this location?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold uppercase text-xs">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unified Modal -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 uppercase" x-text="modalAction === 'create' ? 'Add New ' + modalType : 'Edit ' + modalType"></h3>
                            
                            <form :action="modalAction === 'create' ? '{{ url('organization') }}/' + modalType + 's' : '{{ url('organization') }}/' + modalType + 's/' + currentItem.id" method="POST" class="mt-6">
                                @csrf
                                <template x-if="modalAction === 'edit'">
                                    <input type="hidden" name="_method" value="PATCH">
                                </template>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Name</label>
                                        <input type="text" name="name" x-model="currentItem.name" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 font-bold text-sm uppercase h-12">
                                    </div>

                                    <template x-if="modalType === 'division'">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Department</label>
                                            <select name="department_id" x-model="currentItem.department_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-900 font-bold text-sm h-12">
                                                <option value="">SELECT DEPARTMENT</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-8 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-6 py-3 bg-blue-600 text-base font-bold text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest">
                                        Save Changes
                                    </button>
                                    <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm uppercase tracking-widest">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
