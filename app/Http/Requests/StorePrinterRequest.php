<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePrinterRequest extends FormRequest
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
            'asset_tag' => ['required', 'string', new \App\Rules\GlobalUniqueAssetTag(null, \App\Models\Printer::class)],
            'type' => 'required|in:PRINTER,SCANNER,PORTABLE PRINTER',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required_if:type,PORTABLE PRINTER|nullable|string|max:255',
            'location_id' => 'required|exists:locations,id',
            'department_id' => 'nullable|exists:departments,id',
            'division_id' => 'nullable|exists:divisions,id',
            'location' => 'nullable|string',
            'department' => 'nullable|string',
            'division' => 'nullable|string',
            'has_network_port' => 'required|boolean',
            'ip_type' => 'required|in:STATIC,DYNAMIC',
            'ip_address' => [
                'nullable',
                Rule::when($this->ip_type === 'STATIC', ['ipv4']),
                new \App\Rules\GlobalUniqueIp(null, \App\Models\Printer::class)
            ],
            'mac_address' => [
                'nullable', 
                'string', 
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', 
                new \App\Rules\GlobalUniqueMac(null, \App\Models\Printer::class)
            ],
            'network_segment' => 'nullable|string|max:255',
            'employee_id' => 'nullable|exists:employees,emp_id',
            'date_issued' => 'nullable|date',
            'assignment_type' => 'required|in:STANDBY,ASSIGN', // Added to match form
        ];
    }
}
