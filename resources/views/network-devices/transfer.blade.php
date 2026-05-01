<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transfer Network Device: ') . $networkDevice->brand . ' ' . $networkDevice->model }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="font-medium text-gray-900">Current Assignment</h3>
                        <p class="text-gray-600 mt-1">
                            @if($networkDevice->employee)
                                Currently assigned to: <strong>{{ $networkDevice->employee->full_name }}</strong> 
                                ({{ strtoupper($networkDevice->employee->department ?? 'N/A') }} / {{ strtoupper($networkDevice->employee->division ?? 'N/A') }} / {{ strtoupper($networkDevice->employee->group ?? 'N/A') }})
                            @else
                                Currently <strong>Unassigned</strong>
                            @endif
                        </p>
                    </div>

                    <form method="POST" action="{{ route('network-devices.reassign', $networkDevice) }}">
                        @csrf
                        
                        <!-- New Employee -->
                        <div class="mb-6" x-data>
                            <label for="employee_id" class="block text-gray-700 text-sm font-medium mb-2">
                                Transfer To <span class="text-red-500">*</span>
                            </label>
                            <select name="employee_id" id="employee_id" x-init="new Choices($el, { searchPlaceholderValue: 'SEARCH NAME, DEPARTMENT...' })" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                <option value="">SELECT NEW OWNER</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $networkDevice->employee_id == $employee->id ? 'disabled' : '' }}>
                                        {{ strtoupper($employee->full_name) }} &mdash; {{ strtoupper($employee->department ?? 'N/A') }} / {{ strtoupper($employee->division ?? 'N/A') }}
                                        {{ $networkDevice->employee_id == $employee->id ? '(CURRENT OWNER)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-gray-700 text-sm font-medium mb-2">
                                Transfer Remarks/Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Reason for transfer..."></textarea>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('network-devices.show', $networkDevice) }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Confirm Transfer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


