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
        // No longer using asset_tag_number input
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
            'device_type' => 'required|in:router,switch',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'network_ports' => 'required|integer',
            'network_speed' => 'required|in:gigabit,non_gigabit',
            'switch_type' => 'nullable|in:managed,unmanaged',
            'has_ip' => 'nullable|boolean',
            'ip_address' => [
                'nullable',
                'ipv4',
                new \App\Rules\GlobalUniqueIp($this->route('network_device') ? $this->route('network_device')->id : null, \App\Models\NetworkDevice::class),
            ],
            'group' => ['required', Rule::in(Organization::GROUPS)],
            'department' => ['required', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['required', Rule::in(Organization::DIVISIONS)],
            'employee_id' => 'nullable|exists:employees,id',
            'assignment_type' => 'required|in:standby,assign',
        ];
    }
}
