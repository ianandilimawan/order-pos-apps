@php
    $promoTypes = [
        'percentage' => 'Percentage (%)',
        'fixed' => 'Fixed Amount (Rp)'
    ];
@endphp

<!-- Code Field -->
<x-input-floating type="text" name="code" label="Promo Code" value="{{ old('code', $promo->code ?? '') }}" required />

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <!-- Type Field -->
    <x-select-floating name="type" label="Promo Type" value="{{ old('type', $promo->type ?? '') }}" :options="$promoTypes" required />

    <!-- Value Field -->
    <x-input-floating type="text" name="value" label="Value" value="{{ old('value', $promo->value ?? '') }}" :isCurrency="true" required />
</div>

<!-- Min Purchase Field -->
<div class="mt-6">
    <x-input-floating type="text" name="min_purchase" label="Minimum Purchase (Rp)" value="{{ old('min_purchase', $promo->min_purchase ?? '') }}" :isCurrency="true" />
</div>

@php
    $validUntilVal = old('valid_until', $promo->valid_until ?? '');
    if($validUntilVal) {
        try {
            $validUntilVal = \Carbon\Carbon::parse($validUntilVal)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            // Keep existing format if parse fails
        }
    }
@endphp
<!-- Valid Until Field -->
<div class="mt-6">
    <x-input-floating type="datetime-local" name="valid_until" label="Valid Until" value="{{ $validUntilVal }}" />
</div>

<!-- Is Active Field -->
<div class="mt-6">
    <x-toggle name="is_active" label="Is active" :checked="old('is_active', $promo->is_active ?? true)" />
</div>

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $currencyFields = ['value', 'min_purchase'];
        // Clear selectFields to prevent TomSelect from breaking the floating style
        $selectFields = [];
        $datetimeFields = [];
    @endphp
    @include('admin.partials.form-scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const valueInput = document.getElementById('value');
            
            function updateValueFormat() {
                if (typeof AutoNumeric !== 'undefined' && valueInput) {
                    const anInstance = AutoNumeric.getAutoNumericElement(valueInput);
                    if (anInstance) {
                        if (typeSelect.value === 'percentage') {
                            anInstance.update({
                                currencySymbol: '',
                                maximumValue: '100'
                            });
                        } else {
                            anInstance.update({
                                currencySymbol: 'Rp ',
                                currencySymbolPlacement: 'p',
                                maximumValue: '999999999999'
                            });
                        }
                    }
                }
            }

            if (typeSelect && valueInput) {
                typeSelect.addEventListener('change', updateValueFormat);
                
                const checkInterval = setInterval(() => {
                    if (typeof AutoNumeric !== 'undefined' && AutoNumeric.getAutoNumericElement(valueInput)) {
                        updateValueFormat();
                        clearInterval(checkInterval);
                    }
                }, 100);
            }
        });
    </script>
@endpush