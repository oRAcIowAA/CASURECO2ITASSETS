<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Manage Organization Structure') }}
            </h2>
            <div>
                <a href="{{ route('admins.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to System Admins
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        activeTab: 'departments',
        showModal: false,
        modalType: '',
        modalData: {},
        openModal(type, data = {}) {
            this.modalType = type;
            this.modalData = data;
            this.showModal = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6 font-bold">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'departments'"
                        :class="activeTab === 'departments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase transition-colors">
                        Departments
                    </button>
                    <button @click="activeTab = 'divisions'"
                        :class="activeTab === 'divisions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase transition-colors">
                        Divisions
                    </button>
                    <button @click="activeTab = 'locations'"
                        :class="activeTab === 'locations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-bold text-sm uppercase transition-colors">
                        Locations
                    </button>
                </nav>
            </div>

            <!-- TAB: DEPARTMENTS -->
            <div x-show="activeTab === 'departments'" class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Departments List</h3>
                    <button @click="openModal('add_department')"
                        class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 text-sm font-bold transition-all">
                        + Add Department
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(array_keys($deptDivisions) as $dept)
                        <div
                            class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex justify-between items-center group hover:shadow-md transition-shadow">
                            <span class="font-medium text-gray-800 uppercase">{{ $dept }}</span>
                            <div class="flex space-x-2 transition-opacity">
                                <button @click="openModal('edit_department', { name: '{{ $dept }}' })"
                                    class="text-blue-500 hover:text-blue-700 p-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                    </svg>
                                </button>
                                <button @click="openModal('delete_department', { name: '{{ $dept }}' })"
                                    class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB: DIVISIONS -->
            <div x-show="activeTab === 'divisions'" class="space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Divisions by Department</h3>
                </div>

                <div class="space-y-6">
                    @foreach($deptDivisions as $dept => $divisions)
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 shadow-sm">
                            <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-300">
                                <h4 class="font-bold text-gray-900 uppercase">{{ $dept }}</h4>
                                <button @click="openModal('add_division', { department: '{{ $dept }}' })"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Division
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @forelse($divisions as $div)
                                    <div
                                        class="bg-white p-3 rounded-md shadow-sm border border-gray-100 flex justify-between items-center group hover:border-blue-200 transition-colors">
                                        <span class="text-sm text-gray-700 uppercase">{{ $div }}</span>
                                        <div class="flex space-x-1 transition-opacity">
                                            <button
                                                @click="openModal('edit_division', { department: '{{ $dept }}', name: '{{ $div }}' })"
                                                class="text-blue-500 hover:text-blue-700 p-1">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                </svg>
                                            </button>
                                            <button
                                                @click="openModal('delete_division', { department: '{{ $dept }}', name: '{{ $div }}' })"
                                                class="text-red-500 hover:text-red-700 p-1">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full py-4 text-center text-gray-400 italic text-sm">No divisions added
                                        yet.</div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB: LOCATIONS -->
            <div x-show="activeTab === 'locations'" class="space-y-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Locations List</h3>
                    <button @click="openModal('add_location')"
                        class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 text-sm font-bold transition-all">
                        + Add Location
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($locations as $loc)
                        <div
                            class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex justify-between items-center group hover:shadow-md transition-shadow">
                            <span class="font-medium text-gray-800 uppercase">{{ $loc }}</span>
                            <div class="flex space-x-2 transition-opacity">
                                <button @click="openModal('edit_location', { name: '{{ $loc }}' })"
                                    class="text-blue-500 hover:text-blue-700 p-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                    </svg>
                                </button>
                                <button @click="openModal('delete_location', { name: '{{ $loc }}' })"
                                    class="text-red-500 hover:text-red-700 p-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- MODAL -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <form action="{{ route('organization.update-structure') }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" :value="modalType">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <!-- TITLE -->
                            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" x-text="
                                modalType === 'add_department' ? 'Add New Department' :
                                modalType === 'edit_department' ? 'Edit Department' :
                                modalType === 'delete_department' ? 'Delete Department' :
                                modalType === 'add_division' ? 'Add Division' :
                                modalType === 'edit_division' ? 'Edit Division' :
                                modalType === 'delete_division' ? 'Delete Division' :
                                modalType === 'add_location' ? 'Add New Location' :
                                modalType === 'edit_location' ? 'Edit Location' :
                                modalType === 'delete_location' ? 'Delete Location' : ''
                            "></h3>

                            <!-- DEPT FORMS -->
                            <template x-if="modalType === 'add_department'">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 uppercase">Department
                                        Name</label>
                                    <input type="text" name="name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                                </div>
                            </template>

                            <template x-if="modalType === 'edit_department'">
                                <div>
                                    <input type="hidden" name="old_name" :value="modalData.name">
                                    <label class="block text-sm font-medium text-gray-700 uppercase">New Department
                                        Name</label>
                                    <input type="text" name="new_name" :value="modalData.name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                                </div>
                            </template>

                            <template x-if="modalType === 'delete_department'">
                                <div>
                                    <input type="hidden" name="name" :value="modalData.name">
                                    <p class="text-sm text-gray-500 flex items-start gap-2">
                                        <svg class="h-10 w-10 text-red-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Are you sure you want to delete <span class="font-bold text-gray-900"
                                            x-text="modalData.name"></span>? This will also remove all its divisions
                                        from the lists.
                                    </p>
                                </div>
                            </template>

                            <!-- DIVISION FORMS -->
                            <template x-if="modalType === 'add_division'">
                                <div>
                                    <input type="hidden" name="department" :value="modalData.department">
                                    <label class="block text-sm font-medium text-gray-700 uppercase">Division
                                        Name</label>
                                    <input type="text" name="name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                                </div>
                            </template>

                            <template x-if="modalType === 'edit_division'">
                                <div>
                                    <input type="hidden" name="department" :value="modalData.department">
                                    <input type="hidden" name="old_name" :value="modalData.name">
                                    <label class="block text-sm font-medium text-gray-700 uppercase">New Division
                                        Name</label>
                                    <input type="text" name="new_name" :value="modalData.name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                                </div>
                            </template>

                            <template x-if="modalType === 'delete_division'">
                                <div>
                                    <input type="hidden" name="department" :value="modalData.department">
                                    <input type="hidden" name="name" :value="modalData.name">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete <span class="font-bold text-gray-900"
                                            x-text="modalData.name"></span> from <span class="font-bold text-gray-900"
                                            x-text="modalData.department"></span>?
                                    </p>
                                </div>
                            </template>

                            <!-- LOCATION FORMS -->
                            <template x-if="modalType === 'add_location'">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 uppercase">Location
                                        Name</label>
                                    <input type="text" name="name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                                </div>
                            </template>

                            <template x-if="modalType === 'edit_location'">
                                <div>
                                    <input type="hidden" name="old_name" :value="modalData.name">
                                    <label class="block text-sm font-medium text-gray-700 uppercase">New Location
                                        Name</label>
                                    <input type="text" name="new_name" :value="modalData.name" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                                </div>
                            </template>

                            <template x-if="modalType === 'delete_location'">
                                <div>
                                    <input type="hidden" name="name" :value="modalData.name">
                                    <p class="text-sm text-gray-500 flex items-start gap-2">
                                        <svg class="h-10 w-10 text-red-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Are you sure you want to delete location <span class="font-bold text-gray-900"
                                            x-text="modalData.name"></span>?
                                    </p>
                                </div>
                            </template>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                :class="modalType.includes('delete') ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-bold text-white focus:outline-none sm:ml-3 sm:w-auto sm:text-sm uppercase transition-all">
                                <span x-text="modalType.includes('delete') ? 'Delete' : 'Save Changes'"></span>
                            </button>
                            <button @click="showModal = false" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm uppercase transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


