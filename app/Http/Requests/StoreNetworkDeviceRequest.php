<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNetworkDeviceRequest extends FormRequest
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
            'asset_tag' => ['required', 'string', new \App\Rules\GlobalUniqueAssetTag(null, \App\Models\NetworkDevice::class)],
            'device_type' => 'required|in:ROUTER,SWITCH', // Changed to match form
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'network_ports' => 'required|integer|min:1', // Match form field
            'network_speed' => 'required|in:GIGABIT,NON_GIGABIT', // Match form field
            'switch_type' => 'nullable|in:MANAGED,UNMANAGED', // Match form field
            'has_ip' => 'nullable|boolean', // Match form field
            'ip_address' => ['nullable', 'ipv4', new \App\Rules\GlobalUniqueIp(null, \App\Models\NetworkDevice::class)],
            'location_id' => 'required|exists:locations,id',
            'department_id' => 'nullable|exists:departments,id',
            'division_id' => 'nullable|exists:divisions,id',
            'location' => 'nullable|string',
            'department' => 'nullable|string',
            'division' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,emp_id',
            'date_issued' => 'nullable|date',
            'assignment_type' => 'required|in:STANDBY,ASSIGN',
        ];
    }
}
