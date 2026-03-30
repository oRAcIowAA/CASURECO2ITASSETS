<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

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
            'device_type' => 'required|in:Desktop,Laptop,Server,All-in-One',
            'asset_tag' => [
                'required',
                'string',
                new \App\Rules\GlobalUniqueAssetTag($this->route('pc_unit') ? $this->route('pc_unit')->id : null, \App\Models\PcUnit::class),
            ],
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
            'ip_type' => 'required|in:Static,Dynamic',
            'ip_address' => [
                'nullable',
                Rule::when($this->ip_type === 'Static', ['ipv4']),
                new \App\Rules\GlobalUniqueIp($this->route('pc_unit') ? $this->route('pc_unit')->id : null, \App\Models\PcUnit::class),
            ],
            'mac_address' => [
                'nullable',
                'string',
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
                new \App\Rules\GlobalUniqueMac($this->route('pc_unit') ? $this->route('pc_unit')->id : null, \App\Models\PcUnit::class),
            ],
            'network_segment' => 'nullable|string',
            'assignment_type' => 'required|in:standby,assign',
        ];
    }
}
