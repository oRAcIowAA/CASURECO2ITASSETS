<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePcUnitRequest extends FormRequest
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
        $exclude = ['ms_office_password', 'ms_office_email'];

        foreach ($data as $key => $value) {
            if (is_string($value) && !in_array($key, $exclude) && !str_ends_with($key, '_id')) {
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
            'device_type' => 'required|in:DESKTOP,LAPTOP,SERVER,ALL-IN-ONE',
            'asset_tag' => [
                'required',
                'string',
                new \App\Rules\GlobalUniqueAssetTag($this->route('pc_unit') ? $this->route('pc_unit')->id : null, \App\Models\PcUnit::class),
            ],
            'model' => 'required|string',
            'serial_number' => 'nullable|string',
            'monitor_brand' => 'required_if:device_type,DESKTOP,SERVER|nullable|string',
            'monitor_serial' => 'nullable|string',
            'os_version' => 'nullable|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'storage_secondary' => 'nullable|string',
            'ms_office_licensed' => 'nullable|in:LICENSED,UNLICENSED',
            'ms_office_version' => 'nullable|string',
            'ms_office_email' => 'nullable|string',
            'ms_office_password' => 'nullable|string',
            'location_id' => 'required|exists:locations,id',
            'department_id' => 'nullable|exists:departments,id',
            'division_id' => 'nullable|exists:divisions,id',
            'location' => 'nullable|string',
            'department' => 'nullable|string',
            'division' => 'nullable|string',
            'employee_id' => 'nullable|exists:employees,emp_id',
            'date_issued' => 'nullable|date',
            'remarks' => 'nullable|string',
            'ip_type' => 'required|in:STATIC,DYNAMIC',
            'ip_address' => [
                'nullable',
                Rule::when($this->ip_type === 'STATIC', ['ipv4']),
                new \App\Rules\GlobalUniqueIp($this->route('pc_unit') ? $this->route('pc_unit')->id : null, \App\Models\PcUnit::class),
            ],
            'mac_address' => [
                'nullable',
                'string',
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
                new \App\Rules\GlobalUniqueMac($this->route('pc_unit') ? $this->route('pc_unit')->id : null, \App\Models\PcUnit::class),
            ],
            'network_segment' => 'nullable|string',
            'assignment_type' => 'required|in:STANDBY,ASSIGN',
        ];
    }
}
