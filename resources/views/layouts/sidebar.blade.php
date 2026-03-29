<div class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-900 border-r border-blue-800 transition-transform duration-300 ease-in-out transform md:relative md:translate-x-0 flex flex-col min-h-screen"
     :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    <div class="flex items-center justify-between px-4 h-20 shadow-md bg-blue-900">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/casureco-logo.png') }}" alt="Casureco Logo" class="h-10 w-auto bg-white rounded-full p-1" style="max-height: 40px;">
            <span class="text-white text-xl font-bold tracking-wider">IT ASSETS</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-white hover:text-gray-300 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    
    <div class="flex-grow overflow-y-auto">
        <nav class="px-4 py-6 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('dashboard') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Asset Management</p>
            </div>

            <!-- Create Unit -->
            <a href="{{ route('pc-units.create') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-green-500 hover:text-white mb-4 bg-green-600 shadow-md">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="font-bold">Create New Unit</span>
            </a>

            <!-- PC Units -->
            <a href="{{ route('pc-units.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('pc-units.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">PC Units</span>
            </a>

            <!-- Printers -->
            <a href="{{ route('printers.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('printers.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="font-medium">Printers</span>
            </a>

            <!-- Networking Devices -->
            <a href="{{ route('network-devices.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('network-devices.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"></path>
                </svg>
                <span class="font-medium">Networking</span>
            </a>

            <!-- Parts Management (Disposed Units) -->
            <a href="{{ route('parts.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('parts.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Parts</span>
            </a>

            <!-- QR Codes -->
            <a href="{{ route('qr-assets.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('qr-assets.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <span class="font-medium">QR</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Organization & Administration</p>
            </div>

            <!-- Administrators -->
            <a href="{{ route('admins.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('admins.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">System Admins</span>
            </a>

            <!-- Org Chart -->
            <a href="{{ route('organization.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('organization.index') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Org Chart</span>
            </a>

            <!-- Employees -->
            <a href="{{ route('employees.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('employees.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                 <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">Employees</span>
            </a>
            
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</p>
            </div>

            <!-- Reports -->
            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('reports.index') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">Master List</span>
            </a>

            <!-- Department Reports -->
            <a href="{{ route('reports.department') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('reports.department') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <span class="font-medium">Department Assets</span>
            </a>

            <!-- Activity Log -->
            <a href="{{ route('activities.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('activities.index') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Activity Log</span>
            </a>
        </nav>
    </div>

    <!-- User Profile (Bottom) -->
    <div class="border-t border-blue-800 p-4 bg-blue-900">
        <div class="flex items-center w-full justify-between">
            <div class="flex items-center">
                <div class="relative w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center text-blue-900 font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                </div>
            </div>
            
            <!-- Log Out Form (Icon only) -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-white focus:outline-none" title="Log Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
