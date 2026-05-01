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

                    <form action="{{ route('mobile-devices.reassign', $mobileDevice) }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="employee_id" class="block text-sm font-bold text-gray-700 uppercase mb-2">Select New Employee</label>
                            <select name="employee_id" id="employee_id" x-init="new Choices($el, { searchPlaceholderValue: 'SEARCH NAME OR DEPARTMENT...' })" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase" required>
                                <option value="">-- CHOOSE NEW OWNER --</option>
                                @foreach($employees as $employee)
                                    @if($employee->id !== $mobileDevice->employee_id)
                                        <option value="{{ $employee->id }}">
                                            {{ strtoupper($employee->full_name) }} &mdash; {{ strtoupper($employee->department ?? 'N/A') }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
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


