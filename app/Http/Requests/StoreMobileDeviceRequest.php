<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

class StoreMobileDeviceRequest extends FormRequest
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
        $data = $this->all();
        foreach ($data as $key => $value) {
            if (is_string($value)) {
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
            'type' => 'required|in:CELLPHONE',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'ram' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'location' => ['nullable', Rule::in(Organization::LOCATIONS)],
            'department' => ['nullable', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['nullable', Rule::in(Organization::DIVISIONS)],
            'assignment_type' => 'required|in:AVAILABLE,ASSIGN',
            'employee_id' => 'required_if:assignment_type,ASSIGN|nullable|exists:employees,emp_id',
            'date_issued' => 'required|date',
            'status' => 'nullable|string|max:255',
            'spare_parts' => 'nullable|string',
        ];
    }
}
