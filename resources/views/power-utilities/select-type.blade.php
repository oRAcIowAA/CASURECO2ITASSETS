<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choose Power Utility Type') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- UPS -->
                <a href="{{ route('power-utilities.create', ['type' => 'UPS']) }}" class="bg-white overflow-hidden shadow-md rounded-xl p-8 flex flex-col items-center justify-center hover:bg-blue-50 transition-all border-2 border-transparent hover:border-blue-500 group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">UPS</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Uninterruptible Power Supply</p>
                </a>

                <!-- AVR -->
                <a href="{{ route('power-utilities.create', ['type' => 'AVR']) }}" class="bg-white overflow-hidden shadow-md rounded-xl p-8 flex flex-col items-center justify-center hover:bg-blue-50 transition-all border-2 border-transparent hover:border-blue-500 group">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">AVR</span>
                    <p class="text-sm text-gray-500 text-center mt-2">Automatic Voltage Regulator</p>
                </a>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('power-utilities.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                    ← Back to List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>


