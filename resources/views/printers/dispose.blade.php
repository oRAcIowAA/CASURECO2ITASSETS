<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Condemn / Dispose Printer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Warning: This action will permanently mark the device as condemned or defective and remove it from active inventory. It cannot be undone easily.
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
                            Device: <strong>{{ $printer->brand }} {{ $printer->model }}</strong><br>
                            Current Status: {{ ucfirst($printer->status) }}
                        </p>
                    </div>

                    <form action="{{ route('printers.condemn', $printer) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Classification</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @if(strtolower($printer->status) === 'condemned')
                                    <option value="Disposed" selected>Disposed (Permanently Archived)</option>
                                @else
                                    <option value="Defective">Defective (Repairable/For Inspection)</option>
                                    <option value="Condemned">Condemned (Beyond Repair/Waste)</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Technical Findings / Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" required placeholder="Describe the defect or reason for condemnation..." oninput="this.value = this.value.toUpperCase()"></textarea>
                        </div>

                        @if(strtolower($printer->status) === 'condemned')
                        <div class="mb-4">
                            <label for="spare_parts" class="block text-sm font-medium text-gray-700">Spare Parts salvaged (If any)</label>
                            <textarea name="spare_parts" id="spare_parts" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase" placeholder="List components that can still be used (e.g. Memory, Screen, HDD)..." oninput="this.value = this.value.toUpperCase()"></textarea>
                        </div>
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('printers.show', $printer) }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to proceed?');">
                                {{ strtolower($printer->status) === 'condemned' ? 'Disposed' : 'Confirm' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
