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
                Rule::unique('employees', 'employee_id')->ignore($this->route('employee')),
            ],
            'full_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => ['required', Rule::in(Organization::DEPARTMENTS)],
            'group' => ['required', Rule::in(Organization::LOCATIONS)],
            'division' => ['required', Rule::in(Organization::DIVISIONS)],
        ];
    }
}
