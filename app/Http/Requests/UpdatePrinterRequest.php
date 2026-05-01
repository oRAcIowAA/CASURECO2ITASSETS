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
        $this->merge(array_map(function($value) {
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
                new \App\Rules\GlobalUniqueAssetTag($this->route('printer') ? $this->route('printer')->id : null, \App\Models\Printer::class),
            ],
            'type' => 'required|in:PRINTER,SCANNER,PORTABLE PRINTER',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required_if:type,PORTABLE PRINTER|nullable|string|max:255',
            'location' => ['required', Rule::in(Organization::LOCATIONS)],
            'department' => ['nullable', Rule::in(Organization::DEPARTMENTS)],
            'division' => ['nullable', Rule::in(Organization::DIVISIONS)],
            'has_network_port' => 'required|boolean',
            'ip_type' => 'required|in:STATIC,DYNAMIC',
            'ip_address' => [
                'nullable',
                Rule::when($this->ip_type === 'STATIC', ['ipv4']),
                new \App\Rules\GlobalUniqueIp($this->route('printer') ? $this->route('printer')->id : null, \App\Models\Printer::class),
            ],
            'mac_address' => [
                'nullable', 
                'string', 
                'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', 
                new \App\Rules\GlobalUniqueMac($this->route('printer') ? $this->route('printer')->id : null, \App\Models\Printer::class)
            ],
            'network_segment' => 'nullable|string|max:255',
            'employee_id' => 'nullable|exists:employees,emp_id',
            'date_issued' => 'required|date',
            'assignment_type' => 'required|in:STANDBY,ASSIGN',
        ];
    }
}
