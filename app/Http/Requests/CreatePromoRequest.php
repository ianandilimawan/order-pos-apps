<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePromoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->valid_until) {
            // Replace slashes with hyphens so PHP/Carbon parses it as d-m-Y instead of m/d/Y
            $formatted = str_replace('/', '-', $this->valid_until);
            
            try {
                // Ensure it's fully parsable and converted to standard DB format
                $parsed = \Carbon\Carbon::parse($formatted);
                $this->merge([
                    'valid_until' => $parsed->format('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                // If it still fails, just merge the replaced string and let the validator handle it
                $this->merge([
                    'valid_until' => $formatted,
                ]);
            }
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
            'code' => ['required', 'string', 'max:255', 'unique:promos,code'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_purchase' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['nullable', 'date'],
        ];
    }
}
