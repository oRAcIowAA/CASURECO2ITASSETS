<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Condemn/Dispose Power Utility: ') . $powerUtility->asset_tag }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="mb-6 flex items-center p-4 bg-red-50 rounded-lg border border-red-100">
                        <div class="mr-4 bg-red-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-900">Lifecycle Status Change</h3>
                            <p class="text-sm text-red-700 font-bold tracking-tight">{{ $powerUtility->asset_tag }} &mdash; {{ $powerUtility->brand }} {{ $powerUtility->model }}</p>
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
                                <div class="ml-3 font-bold text-red-800 uppercase">
                                    <h3 class="text-sm">There were {{ $errors->count() }} errors with your submission</h3>
                                    <div class="mt-2 text-sm">
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

                    <form action="{{ route('power-utilities.condemn', $powerUtility) }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Classification <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 font-semibold" required>
                                @if(strtolower($powerUtility->status) === 'condemned')
                                    <option value="Disposed" selected>Disposed (Permanently Archived)</option>
                                @else
                                    <option value="Defective" {{ $powerUtility->status === 'Defective' ? 'selected' : '' }}>Defective (Repairable/Pending Check)</option>
                                    <option value="Condemned" {{ $powerUtility->status === 'Condemned' ? 'selected' : '' }}>Condemned (Beyond Repair/Disposal)</option>
                                @endif
                            </select>
                            <div class="mt-2 p-3 bg-gray-50 rounded text-xs text-gray-600 italic leading-relaxed">
                                <p><strong>Defective:</strong> Device is unusable but remains assigned to the current employee if applicable.</p>
                                <p class="mt-1"><strong>Condemned/Disposed:</strong> Device will be unassigned (removed from employee) and marked as inactive.</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-bold text-gray-700 mb-2">Reason / Remarks <span class="text-red-500">*</span></label>
                            <textarea name="remarks" id="remarks" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 font-semibold uppercase" placeholder="Describe the defect or reason for this change..." required oninput="this.value = this.value.toUpperCase()"></textarea>
                        </div>

                        @if(strtolower($powerUtility->status) === 'condemned')
                        <div class="mb-6">
                            <label for="spare_parts" class="block text-sm font-bold text-gray-700 mb-2">Spare Parts Salvaged (If any)</label>
                            <textarea name="spare_parts" id="spare_parts" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 font-semibold uppercase" placeholder="List components that can still be used (e.g. Battery, Capacitors)..." oninput="this.value = this.value.toUpperCase()">{{ $powerUtility->spare_parts }}</textarea>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t">
                            <a href="{{ route('power-utilities.show', $powerUtility) }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-red-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
                                {{ strtolower($powerUtility->status) === 'condemned' ? 'Disposed' : 'Confirm Change' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
