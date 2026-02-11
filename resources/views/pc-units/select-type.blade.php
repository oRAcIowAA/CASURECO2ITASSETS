<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choose Device Type to Create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- PC -->
                <a href="{{ route('pc-units.create', ['type' => 'PC']) }}" class="bg-white overflow-hidden shadow-md rounded-xl p-8 flex flex-col items-center justify-center hover:bg-blue-50 transition-all border-2 border-transparent hover:border-blue-500 group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Desktop PC</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Standard workstation unit</p>
                </a>

                <!-- Laptop -->
                <a href="{{ route('pc-units.create', ['type' => 'Laptop']) }}" class="bg-white overflow-hidden shadow-md rounded-xl p-8 flex flex-col items-center justify-center hover:bg-blue-50 transition-all border-2 border-transparent hover:border-blue-500 group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M5 18h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2zM9 21h6"></path></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Laptop</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Portable computing device</p>
                </a>

                <!-- Printer -->
                <a href="{{ route('pc-units.create', ['type' => 'Printer']) }}" class="bg-white overflow-hidden shadow-md rounded-xl p-8 flex flex-col items-center justify-center hover:bg-blue-50 transition-all border-2 border-transparent hover:border-blue-500 group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Printer</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Network or local printer</p>
                </a>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('pc-units.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                    ← Back to Inventory
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
