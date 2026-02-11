<div class="flex flex-col w-64 bg-blue-900 border-r border-gray-200 min-h-screen">
    <div class="flex items-center justify-center h-20 shadow-md bg-blue-900">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <img src="{{ asset('images/casureco-logo.png') }}" alt="Casureco Logo" class="h-10 w-auto bg-white rounded-full p-1" style="max-height: 40px;">
            <span class="text-white text-xl font-bold tracking-wider">DMS</span>
        </a>
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

            <!-- PC Units -->
            <a href="{{ route('pc-units.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('pc-units.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">PC Units</span>
            </a>

            <!-- PC History -->
            <a href="{{ route('pc-history.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('pc-history.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">History Logs</span>
            </a>

            <!-- Reports -->
            <a href="{{ route('reports.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('reports.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="font-medium">Reports</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Organization</p>
            </div>

            <!-- Organization Chart -->
            <a href="{{ route('organization.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('organization.index') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="font-medium">Org Chart</span>
            </a>

            <!-- Employees -->
            <a href="{{ route('employees.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('employees.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="font-medium">Employees</span>
            </a>

            <!-- Departments -->
            <a href="{{ route('departments.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('departments.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="font-medium">Departments</span>
            </a>

            <!-- Branches -->
            <a href="{{ route('branches.index') }}" class="flex items-center px-4 py-3 text-gray-100 transition-colors rounded-lg hover:bg-blue-800 hover:text-yellow-400 {{ request()->routeIs('branches.*') ? 'bg-blue-800 shadow-lg text-yellow-400' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Branches</span>
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
