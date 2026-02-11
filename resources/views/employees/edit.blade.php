<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee: ') . $employee->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('employees.update', $employee) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="employee_id" class="block text-gray-700 text-sm font-bold mb-2">
                                Employee ID *
                            </label>
                            <input type="text" name="employee_id" id="employee_id" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('employee_id') border-red-500 @enderror"
                                   value="{{ old('employee_id', $employee->employee_id) }}" required>
                            @error('employee_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="full_name" class="block text-gray-700 text-sm font-bold mb-2">
                                Full Name *
                            </label>
                            <input type="text" name="full_name" id="full_name" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('full_name') border-red-500 @enderror"
                                   value="{{ old('full_name', $employee->full_name) }}" required>
                            @error('full_name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="position" class="block text-gray-700 text-sm font-bold mb-2">
                                Position
                            </label>
                            <input type="text" name="position" id="position" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   value="{{ old('position', $employee->position) }}">
                        </div>

                        <div class="mb-4">
                            <label for="department_id" class="block text-gray-700 text-sm font-bold mb-2">
                                Department *
                            </label>
                            <select name="department_id" id="department_id" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('department_id') border-red-500 @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (old('department_id', $employee->department_id) == $department->id) ? 'selected' : '' }}>
                                        {{ $department->department_name }} ({{ $department->branch->branch_name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Employee
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
