<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'order_number' => 'required',
            'dining_table_id' => 'nullable',
            'order_type' => 'required',
            'status' => 'required',
            'payment_status' => 'required',
            'payment_method' => 'nullable',
            'subtotal' => 'required',
            'total' => 'required',
            'notes' => 'nullable',
            'paid_at' => 'nullable',
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
