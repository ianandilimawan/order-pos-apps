<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashOpnameRequest extends FormRequest
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
            'user_id' => 'required',
            'opname_date' => 'required',
            'expected_cash' => 'nullable|numeric',
            'actual_cash' => 'nullable|numeric',
            'expected_qris' => 'nullable|numeric',
            'actual_qris' => 'nullable|numeric',
            'expected_transfer' => 'nullable|numeric',
            'actual_transfer' => 'nullable|numeric',
            'difference' => 'nullable|numeric',
            'status' => ['required', \Illuminate\Validation\Rule::enum(\App\Enums\CashOpnameStatusEnum::class)],
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Add custom validation messages here
        ];
    }
}
