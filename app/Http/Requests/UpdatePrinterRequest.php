<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Constants\Organization;

class UpdatePrinterRequest extends FormRequest
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
                'asset_tag' => 'CAS-PR-' . $this->asset_tag_number,
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
            'asset_tag' => [
                'required',
                'string',
                new \App\Rules\GlobalUniqueAssetTag($this->route('printer') ? $this->route('printer')->id : null, \App\Models\Printer::class),
            ],
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'group' => ['required', Rule::in(Organization::GROUPS)],
            'department' => ['required', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['required', Rule::in(Organization::DIVISIONS)],
            'has_network_port' => 'required|boolean',
            'ip_address' => [
                'nullable',
                'ipv4',
                new \App\Rules\GlobalUniqueIp($this->route('printer') ? $this->route('printer')->id : null, \App\Models\Printer::class),
            ],
            'employee_id' => 'nullable|exists:employees,id',
            'assignment_type' => 'required|in:standby,assign',
        ];
    }
}
