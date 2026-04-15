<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePowerUtilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:UPS,AVR',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'capacity' => 'nullable|string|max:255',
            'input_voltage' => 'nullable|string|max:255',
            'output_voltage' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'assignment_type' => 'required|in:AVAILABLE,ASSIGN',
            'employee_id' => 'required_if:assignment_type,ASSIGN|nullable|exists:employees,id',
            'date_issued' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'spare_parts' => 'nullable|string',
        ];
    }
}
