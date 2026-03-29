<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="employee_id" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                EMPLOYEE ID *
                            </label>
                            <input type="text" name="employee_id" id="employee_id" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_id') border-red-500 @enderror"
                                   value="{{ old('employee_id') }}" placeholder="EMP-2024-001" required>
                            @error('employee_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="full_name" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                FULL NAME *
                            </label>
                            <input type="text" name="full_name" id="full_name" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('full_name') border-red-500 @enderror"
                                   value="{{ old('full_name') }}" required>
                            @error('full_name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="position" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                POSITION
                            </label>
                            <input type="text" name="position" id="position" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('position') }}">
                        </div>

                        <div class="mb-4">
                            <label for="department" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Department *
                            </label>
                            <select name="department" id="department"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT DEPARTMENT</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}" {{ old('department') === $department ? 'selected' : '' }}>
                                        {{ strtoupper($department) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="division" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Division *
                            </label>
                            <select name="division" id="division"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('division') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT DIVISION</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division }}" {{ old('division') === $division ? 'selected' : '' }}>
                                        {{ strtoupper($division) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('division')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="group" class="block text-gray-700 text-sm font-bold mb-2 uppercase">
                                Group *
                            </label>
                            <select name="group" id="group"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('group') border-red-500 @enderror"
                                    required>
                                <option value="">SELECT GROUP</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group }}" {{ old('group') === $group ? 'selected' : '' }}>
                                        {{ strtoupper($group) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Create Employee
                            </button>
                            <a href="{{ route('employees.index') }}" class="text-gray-600 hover:text-gray-800">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>