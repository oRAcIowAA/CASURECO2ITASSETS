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
                    
                    <form method="POST" action="{{ route('employees.update', $employee) }}" 
                          x-data="{ 
                              department: '{{ old('department_id', $employee->department_id) }}', 
                              division: '{{ old('division_id', $employee->division_id) }}',
                              deptDivisions: @js($deptDivisions),
                              get filteredDivisions() {
                                  return this.department ? (this.deptDivisions[this.department] || {}) : {};
                              }
                          }">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Employee ID -->
                            <div>
                                <label for="employee_id" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Employee ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="employee_id" id="employee_id" 
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 uppercase @error('employee_id') border-red-500 @enderror"
                                       value="{{ old('employee_id', $employee->employee_id) }}" placeholder="e.g. EMP-2024-001" 
                                       oninput="this.value = this.value.toUpperCase()" required>
                                @error('employee_id')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="full_name" 
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 uppercase @error('full_name') border-red-500 @enderror"
                                       value="{{ old('full_name', $employee->full_name) }}" placeholder="e.g. JOHN DOE" 
                                       oninput="this.value = this.value.toUpperCase()" required>
                                @error('full_name')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <!-- Position -->
                            <label for="position" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Position
                            </label>
                            <input type="text" name="position" id="position" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 uppercase"
                                   value="{{ old('position', $employee->position) }}" placeholder="e.g. SYSTEMS ANALYST"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Department -->
                            <div>
                                <label for="department_id" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select name="department_id" id="department_id" x-model="department" @change="division = ''"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 @error('department_id') border-red-500 @enderror"
                                        required>
                                    <option value="">-- SELECT DEPARTMENT --</option>
                                    @foreach($departments as $id => $name)
                                        <option value="{{ $id }}">{{ strtoupper($name) }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Division -->
                            <div>
                                <label for="division_id" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Division <span class="text-red-500">*</span>
                                </label>
                                <select name="division_id" id="division_id" x-model="division"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 @error('division_id') border-red-500 @enderror"
                                        :disabled="!department"
                                        required>
                                    <option value="">-- SELECT DIVISION --</option>
                                    <template x-for="(name, id) in filteredDivisions" :key="id">
                                        <option :value="id" x-text="name.toUpperCase()" :selected="division == id"></option>
                                    </template>
                                </select>
                                <p x-show="!department" class="text-xs text-gray-500 mt-2 italic">Please select a department first.</p>
                                @error('division_id')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-8">
                            <!-- Group / Location -->
                            <label for="location_id" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Assignment Group/Location <span class="text-red-500">*</span>
                            </label>
                            <select name="location_id" id="location_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 @error('location_id') border-red-500 @enderror"
                                    required>
                                <option value="">-- SELECT LOCATION --</option>
                                @foreach($groups as $id => $name)
                                    <option value="{{ $id }}" {{ old('location_id', $employee->location_id) == $id ? 'selected' : '' }}>
                                        {{ strtoupper($name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition duration-150">
                                &larr; BACK TO LIST
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform active:scale-95 transition duration-150">
                                Update Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


