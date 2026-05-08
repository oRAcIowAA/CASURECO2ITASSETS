<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            if (is_string($value) && !str_ends_with($key, '_id')) {
                $data[$key] = strtoupper($value);
            }
        }

        if ($this->has('location_id')) {
            $locationName = \Illuminate\Support\Facades\DB::table('locations')->where('id', $this->location_id)->value('name');
            $data['location'] = $locationName;
        }

        if ($this->has('department_id')) {
            $deptName = \Illuminate\Support\Facades\DB::table('departments')->where('id', $this->department_id)->value('name');
            $data['department'] = $deptName;
        }

        if ($this->has('division_id')) {
            $divName = \Illuminate\Support\Facades\DB::table('divisions')->where('id', $this->division_id)->value('name');
            $data['division'] = $divName;
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
            'location_id' => 'required|exists:locations,id',
            'department_id' => 'nullable|exists:departments,id',
            'division_id' => 'nullable|exists:divisions,id',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'division' => 'nullable|string|max:255',
            'assignment_type' => 'required|in:AVAILABLE,ASSIGN',
            'employee_id' => 'required_if:assignment_type,ASSIGN|nullable|exists:employees,id',
            'date_issued' => 'nullable|date',
            'status' => 'nullable|string|max:255',
            'spare_parts' => 'nullable|string',
        ];
    }
}
