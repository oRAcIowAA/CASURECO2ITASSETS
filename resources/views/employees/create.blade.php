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
                    
                    <form method="POST" action="{{ route('employees.store') }}" 
                          x-data="{ 
                              department: '{{ old('department') }}', 
                              division: '{{ old('division') }}',
                              deptDivisions: @js($deptDivisions),
                              get filteredDivisions() {
                                  return this.department ? (this.deptDivisions[this.department] || []) : [];
                              }
                          }">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Employee ID -->
                            <div>
                                <label for="employee_id" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Employee ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="employee_id" id="employee_id" 
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 uppercase @error('employee_id') border-red-500 @enderror"
                                       value="{{ old('employee_id') }}" placeholder="e.g. EMP-2024-001" 
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
                                       value="{{ old('full_name') }}" placeholder="e.g. JOHN DOE" 
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
                                   value="{{ old('position') }}" placeholder="e.g. SYSTEMS ANALYST"
                                   oninput="this.value = this.value.toUpperCase()">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select name="department" id="department" x-model="department" @change="division = ''"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 @error('department') border-red-500 @enderror"
                                        required>
                                    <option value="">-- SELECT DEPARTMENT --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}">{{ strtoupper($dept) }}</option>
                                    @endforeach
                                </select>
                                @error('department')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Division -->
                            <div>
                                <label for="division" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                    Division <span class="text-red-500">*</span>
                                </label>
                                <select name="division" id="division" x-model="division"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 @error('division') border-red-500 @enderror"
                                        :disabled="!department"
                                        required>
                                    <option value="">-- SELECT DIVISION --</option>
                                    <template x-for="div in filteredDivisions" :key="div">
                                        <option :value="div" x-text="div.toUpperCase()" :selected="division === div"></option>
                                    </template>
                                </select>
                                <p x-show="!department" class="text-xs text-gray-500 mt-2 italic">Please select a department first.</p>
                                @error('division')
                                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-8">
                            <!-- Group / Location -->
                            <label for="group" class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Assignment Group/Location <span class="text-red-500">*</span>
                            </label>
                            <select name="group" id="group"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 @error('group') border-red-500 @enderror"
                                    required>
                                <option value="">-- SELECT LOCATION --</option>
                                @foreach($groups as $grp)
                                    <option value="{{ $grp }}" {{ old('group') === $grp ? 'selected' : '' }}>
                                        {{ strtoupper($grp) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('group')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 transition duration-150">
                                &larr; BACK TO LIST
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform active:scale-95 transition duration-150">
                                Create Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


