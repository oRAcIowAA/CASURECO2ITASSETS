<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-600 leading-tight">
            {{ __('Condemn/Dispose PC Unit: ') . $pcUnit->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Warning: This action will mark the unit as condemned or defective. 
                                    If assigned, it will automatically be unassigned from the employee.
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Device Details</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Device: <strong>{{ $pcUnit->brand ?? 'Unknown' }} {{ $pcUnit->model ?? 'Unknown' }}</strong><br>
                            Current Status: {{ ucfirst($pcUnit->status) }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('pc-units.condemn', $pcUnit) }}">
                        @csrf
                        
                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-gray-700 text-sm font-medium mb-2">
                                Classification <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                                @if(strtolower($pcUnit->status) === 'condemned')
                                    <option value="Disposed" selected>Disposed (Permanently Archived)</option>
                                @else
                                    <option value="defective">Defective (Repairable/Pending Check)</option>
                                    <option value="condemned">Condemned (Beyond Repair/Disposal)</option>
                                @endif
                            </select>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <label for="remarks" class="block text-gray-700 text-sm font-medium mb-2">
                                Reason/Remarks <span class="text-red-500">*</span>
                            </label>
                            <textarea name="remarks" id="remarks" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="e.g., Motherboard failure, expensive repair cost..." required></textarea>
                        </div>

                        @if(strtolower($pcUnit->status) === 'condemned')
                        <div class="mb-6">
                            <label for="spare_parts" class="block text-gray-700 text-sm font-medium mb-2">
                                Spare Parts salvaged (If any)
                            </label>
                            <textarea name="spare_parts" id="spare_parts" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="List components that can still be used (e.g. Memory, Screen, HDD)..."></textarea>
                        </div>
                        @endif

                        <div class="flex items-center justify-between mt-6">
                            <a href="{{ route('pc-units.show', $pcUnit) }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                {{ strtolower($pcUnit->status) === 'condemned' ? 'Finalize Archive' : 'Confirm' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
