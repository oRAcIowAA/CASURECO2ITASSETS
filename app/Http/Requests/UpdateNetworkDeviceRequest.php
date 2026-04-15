<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

class UpdateNetworkDeviceRequest extends FormRequest
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
        $this->merge(array_map(function ($value) {
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
            'asset_tag' => [
                'required',
                'string',
                new \App\Rules\GlobalUniqueAssetTag($this->route('network_device') ? $this->route('network_device')->id : null, \App\Models\NetworkDevice::class),
            ],
            'device_type' => 'required|in:ROUTER,SWITCH',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'network_ports' => 'required|integer|min:1|max:50',
            'network_speed' => 'required|in:GIGABIT,NON_GIGABIT',
            'switch_type' => 'nullable|in:MANAGED,UNMANAGED',
            'has_ip' => 'nullable|boolean',
            'ip_address' => [
                'nullable',
                'ipv4',
                new \App\Rules\GlobalUniqueIp($this->route('network_device') ? $this->route('network_device')->id : null, \App\Models\NetworkDevice::class),
            ],
            'group' => ['required', Rule::in(Organization::LOCATIONS)],
            'department' => ['nullable', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['nullable', Rule::in(Organization::DIVISIONS)],
            'employee_id' => 'nullable|exists:employees,id',
            'date_issued' => 'nullable|date',
            'assignment_type' => 'required|in:STANDBY,ASSIGN',
        ];
    }
}
