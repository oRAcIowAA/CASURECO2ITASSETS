<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employees') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full sm:px-6 lg:px-8">
            
            <!-- Actions Bar -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">All Employees</h3>
                    <p class="text-sm text-gray-500">Total: {{ $employees->total() }} records</p>
                </div>
                <div>
                    <a href="{{ route('employees.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add New Employee
                    </a>
                </div>
            </div>

            <!-- LIST VIEW -->
                <!-- Search & List Table -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                    <form method="GET" action="{{ route('employees.index') }}" class="flex flex-col md:flex-row gap-4">
                        
                        <div class="flex-grow">
                            <input type="text" name="search" placeholder="SEARCH EMPLOYEES..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900 font-semibold text-xs h-10 uppercase placeholder-gray-400"
                                   value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="px-6 py-2 h-10 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm transition-colors uppercase text-xs">
                            Search
                        </button>
                    </form>
                </div>

                @include('employees.partials.list-table', ['employees' => $employees])

        </div>
    </div>
</x-app-layout>