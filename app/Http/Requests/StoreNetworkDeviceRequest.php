<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

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
        if ($this->has('asset_tag_number') && $this->asset_tag_number !== null) {
            $this->merge([
                'asset_tag' => 'CAS-ND-' . $this->asset_tag_number,
            ]);
        }
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
            'device_type' => 'required|in:router,switch', // Changed to match form
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'network_ports' => 'required|integer', // Match form field
            'network_speed' => 'required|in:gigabit,non_gigabit', // Match form field
            'switch_type' => 'nullable|in:managed,unmanaged', // Match form field
            'has_ip' => 'nullable|boolean', // Match form field
            'ip_address' => ['nullable', 'ipv4', new \App\Rules\GlobalUniqueIp(null, \App\Models\NetworkDevice::class)],
            'group' => ['required', Rule::in(Organization::GROUPS)],
            'department' => ['required', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['required', Rule::in(Organization::DIVISIONS)],
            'employee_id' => 'nullable|exists:employees,id',
            'assignment_type' => 'required|in:standby,assign',
        ];
    }
}
