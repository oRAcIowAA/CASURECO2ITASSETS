<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

class StorePcUnitRequest extends FormRequest
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
                'asset_tag' => 'CAS-PC-' . $this->asset_tag_number,
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
            'device_type' => 'required|in:Desktop,Laptop,Server,All-in-One',
            'asset_tag' => ['required', 'string', new \App\Rules\GlobalUniqueAssetTag(null, \App\Models\PcUnit::class)],
            'model' => 'required|string',
            'processor' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'group' => ['required', Rule::in(Organization::GROUPS)],
            'department' => ['required', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['required', Rule::in(Organization::DIVISIONS)],
            'employee_id' => 'nullable|exists:employees,id',
            'date_received' => 'nullable|date',
            'remarks' => 'nullable|string',
            'ip_address' => ['nullable', 'ipv4', new \App\Rules\GlobalUniqueIp(null, \App\Models\PcUnit::class)],
            'mac_address' => ['nullable', 'string', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', new \App\Rules\GlobalUniqueMac(null, \App\Models\PcUnit::class)],
            'network_segment' => 'nullable|string',
            'assignment_type' => 'required|in:standby,assign',
        ];
    }
}
