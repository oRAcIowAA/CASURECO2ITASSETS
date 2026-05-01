<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

class UpdateEmployeeRequest extends FormRequest
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

        // Map 'group' to 'location' if it comes from the UI as group
        if ($this->has('group') && !$this->has('location')) {
            $this->merge(['location' => $this->group]);
        }

        // Convert string fields to UPPERCASE for consistency
        $this->merge(array_map(function($value) {
            return is_string($value) ? strtoupper($value) : $value;
        }, $this->all()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'emp_id')->ignore($this->route('employee')),
            ],
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => ['required', Rule::in(Organization::DEPARTMENTS)],
            'group' => ['required', Rule::in(Organization::LOCATIONS)],
            'division' => ['required', Rule::in(Organization::DIVISIONS)],
            // Database columns for mass assignment
            'emp_id' => 'sometimes',
            'fname' => 'sometimes',
            'lname' => 'sometimes',
            'mname' => 'sometimes',
            'location' => 'sometimes',
        ];
    }
}
