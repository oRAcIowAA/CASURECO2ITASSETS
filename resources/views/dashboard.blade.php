<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <!-- Welcome Banner -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-900 to-blue-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-bold">Welcome back, {{ Auth::user()->name }}!</h3>
                        <p class="text-blue-100 mt-2 text-lg">Here's what's happening with your inventory today.</p>
                    </div>
                    <div class="hidden md:block bg-white/20 p-4 rounded-full backdrop-blur-sm">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- PC Units Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 hover:shadow-md transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Units</p>
                        <p class="text-3xl font-bold mt-2 text-gray-900 group-hover:text-blue-600 transition-colors">{{ \App\Models\PcUnit::count() }}</p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg group-hover:bg-blue-100 transition-colors">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Employees Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-emerald-500 hover:shadow-md transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Employees</p>
                        <p class="text-3xl font-bold mt-2 text-gray-900 group-hover:text-emerald-600 transition-colors">{{ \App\Models\Employee::count() }}</p>
                    </div>
                    <div class="bg-emerald-50 p-3 rounded-lg group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Branches Card -->
            <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-yellow-400 hover:shadow-md transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Branches</p>
                        <p class="text-3xl font-bold mt-2 text-gray-900 group-hover:text-yellow-600 transition-colors">{{ \App\Models\Branch::count() }}</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg group-hover:bg-yellow-100 transition-colors">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-4 0h-4m-4 0H3"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
             <!-- Available Units Card -->
             <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-purple-500 hover:shadow-md transition-all duration-300 group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase tracking-wide">Available</p>
                        <p class="text-3xl font-bold mt-2 text-gray-900 group-hover:text-purple-600 transition-colors">{{ \App\Models\PcUnit::where('status', 'available')->count() }}</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg group-hover:bg-purple-100 transition-colors">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Activity Feed -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Recent Activity Log</h3>
                    <a href="{{ route('pc-history.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</a>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach(\App\Models\PcHistory::with(['pcUnit', 'employee', 'createdBy'])->latest()->take(5)->get() as $history)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if($history->action === 'assigned')
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                    </span>
                                @elseif($history->action === 'returned')
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-orange-100">
                                        <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100">
                                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                            <div class="ml-4 w-full">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($history->action) }} PC Unit <span class="font-bold text-gray-700">{{ $history->pcUnit->asset_tag ?? 'Unknown' }}</span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    @if($history->employee)
                                        To {{ $history->employee->full_name }}
                                    @endif
                                    <span class="mx-1">•</span> {{ $history->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-4">
                    <a href="{{ route('pc-units.create') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all group">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg group-hover:bg-blue-200 transition-colors">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">Register New PC</p>
                                <p class="text-xs text-gray-500">Add a new unit to inventory</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="{{ route('employees.create') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition-all group">
                        <div class="flex items-center">
                            <div class="bg-emerald-100 p-2 rounded-lg group-hover:bg-emerald-200 transition-colors">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">Add Employee</p>
                                <p class="text-xs text-gray-500">Register new staff member</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('pc-history.report') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-400 hover:bg-yellow-50 transition-all group">
                        <div class="flex items-center">
                             <div class="bg-yellow-100 p-2 rounded-lg group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-bold text-gray-900">Generate Report</p>
                                <p class="text-xs text-gray-500">Download assignment logs</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>