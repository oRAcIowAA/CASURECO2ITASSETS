<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CondemnDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:Condemned,Defective,condemned,defective,Disposed,disposed', // Handling case sensitivity in rules, though controller often handles it
            'remarks' => 'required|string',
            'spare_parts' => 'nullable|string',
        ];
    }
}
