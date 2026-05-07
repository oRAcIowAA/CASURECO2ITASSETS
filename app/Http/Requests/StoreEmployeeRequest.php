<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Map employee_id to emp_id for database consistency
        if ($this->has('employee_id')) {
            $this->merge(['emp_id' => $this->employee_id]);
        }

        // Split Full Name into fname, mname, lname
        if ($this->has('full_name')) {
            $parts = explode(' ', strtoupper($this->full_name));
            if (count($parts) >= 3) {
                $this->merge([
                    'fname' => $parts[0],
                    'mname' => $parts[1],
                    'lname' => implode(' ', array_slice($parts, 2)),
                ]);
            } elseif (count($parts) == 2) {
                $this->merge([
                    'fname' => $parts[0],
                    'lname' => $parts[1],
                    'mname' => '',
                ]);
            } else {
                $this->merge([
                    'fname' => $parts[0] ?? '',
                    'lname' => '',
                    'mname' => '',
                ]);
            }
        }

        // Map 'location_id' to 'group' for backward compatibility if needed, 
        // and map 'location_id' to 'location' if present
        if ($this->has('location_id')) {
            $locationName = \Illuminate\Support\Facades\DB::table('locations')->where('id', $this->location_id)->value('name');
            $this->merge(['location' => $locationName, 'group' => $locationName]);
        }

        if ($this->has('department_id')) {
            $deptName = \Illuminate\Support\Facades\DB::table('departments')->where('id', $this->department_id)->value('name');
            $this->merge(['department' => $deptName]);
        }

        if ($this->has('division_id')) {
            $divName = \Illuminate\Support\Facades\DB::table('divisions')->where('id', $this->division_id)->value('name');
            $this->merge(['division' => $divName]);
        }

        // Convert string fields to UPPERCASE for consistency (excluding IDs)
        $data = $this->all();
        foreach ($data as $key => $value) {
            if (is_string($value) && !str_ends_with($key, '_id')) {
                $data[$key] = strtoupper($value);
            }
        }
        $this->merge($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|string|max:255|unique:employees,emp_id',
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'location_id' => 'required|exists:locations,id',
            'division_id' => 'required|exists:divisions,id',
            // Database columns for mass assignment
            'emp_id' => 'sometimes',
            'fname' => 'sometimes',
            'lname' => 'sometimes',
            'mname' => 'sometimes',
            'location' => 'nullable|string',
            'department' => 'nullable|string',
            'division' => 'nullable|string',
        ];
    }
}
