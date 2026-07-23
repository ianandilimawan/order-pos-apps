<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoRequest extends FormRequest
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
            try {
                \Carbon\Carbon::parse($this->valid_until);
            } catch (\Exception $e) {
                try {
                    // Attempt to parse d/m/Y H:i (often sent by fallback text inputs on Safari)
                    $parsed = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $this->valid_until);
                    $this->merge([
                        'valid_until' => $parsed->format('Y-m-d H:i:s'),
                    ]);
                } catch (\Exception $e2) {
                    // Let it fail validation if it can't be parsed
                }
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
            'code' => ['required', 'string', 'max:255', 'unique:promos,code,' . $this->route('promo')],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_purchase' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['nullable', 'date'],
        ];
    }
}
