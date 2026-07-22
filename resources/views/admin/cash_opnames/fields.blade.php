<div x-data="cashOpnameForm()" x-init="initForm()" class="space-y-6">
    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
        <x-select name="user_id" label="User" value="{{ $cashOpname->user_id ?? '' }}" :options="$users" />
    @else
        <input type="hidden" name="user_id" value="{{ $cashOpname->user_id }}">
        <x-input-floating type="text" name="user_name" label="Kasir" value="{{ auth()->user()->name }}" readonly />
    @endif

    <x-input-floating type="date" name="opname_date" label="Opname date"
        value="{{ old('opname_date', isset($cashOpname->opname_date) && $cashOpname->opname_date ? (is_string($cashOpname->opname_date) ? $cashOpname->opname_date : $cashOpname->opname_date->format('Y-m-d')) : '') }}"
        readonly />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input-floating type="text" name="expected_cash" id="expected_cash" label="Expected cash"
            value="{{ old('expected_cash', $cashOpname->expected_cash ?? 0) }}" :isCurrency="true" readonly />
        <x-input-floating type="text" name="actual_cash" id="actual_cash" label="Actual cash"
            value="{{ old('actual_cash', $cashOpname->actual_cash ?? 0) }}" :isCurrency="true" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input-floating type="text" name="expected_qris" id="expected_qris" label="Expected qris"
            value="{{ old('expected_qris', $cashOpname->expected_qris ?? 0) }}" :isCurrency="true" readonly />
        <x-input-floating type="text" name="actual_qris" id="actual_qris" label="Actual qris"
            value="{{ old('actual_qris', $cashOpname->actual_qris ?? 0) }}" :isCurrency="true" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-input-floating type="text" name="expected_transfer" id="expected_transfer" label="Expected transfer"
            value="{{ old('expected_transfer', $cashOpname->expected_transfer ?? 0) }}" :isCurrency="true" readonly />
        <x-input-floating type="text" name="actual_transfer" id="actual_transfer" label="Actual transfer"
            value="{{ old('actual_transfer', $cashOpname->actual_transfer ?? 0) }}" :isCurrency="true" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <x-input-floating type="text" name="difference" id="difference" label="Difference"
            value="{{ old('difference', $cashOpname->difference ?? 0) }}" :isCurrency="true" readonly />
        <input type="hidden" name="status" id="status" x-bind:value="status">
        <x-input-floating type="text" name="status_display" id="status_display" label="Status"
            x-bind:value="statusDisplay" readonly />
    </div>

    <x-textarea name="notes" label="Notes" value="{{ old('notes', $cashOpname->notes ?? '') }}" />
</div>


@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = false;
        $hasSlugField = false;
        $slugSourceField = null;
        $tagifyFields = [];
        $textareaFields = [
            0 => 'notes',
        ];
        $selectFields = [
            0 => 'user_id',
        ];
        $currencyFields = [
            0 => 'expected_cash',
            1 => 'actual_cash',
            2 => 'expected_qris',
            3 => 'actual_qris',
            4 => 'expected_transfer',
            5 => 'actual_transfer',
            6 => 'difference',
        ];
        $passwordFields = [];
    @endphp
    @include('admin.partials.form-scripts')

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cashOpnameForm', () => ({
                expCash: {{ old('expected_cash', $cashOpname->expected_cash ?? 0) }},
                actCash: {{ old('actual_cash', $cashOpname->actual_cash ?? 0) }},
                expQris: {{ old('expected_qris', $cashOpname->expected_qris ?? 0) }},
                actQris: {{ old('actual_qris', $cashOpname->actual_qris ?? 0) }},
                expTf: {{ old('expected_transfer', $cashOpname->expected_transfer ?? 0) }},
                actTf: {{ old('actual_transfer', $cashOpname->actual_transfer ?? 0) }},

                get diff() {
                    let totalExp = Number(this.expCash) + Number(this.expQris) + Number(this.expTf);
                    let totalAct = Number(this.actCash) + Number(this.actQris) + Number(this.actTf);
                    return totalAct - totalExp;
                },

                get status() {
                    if (this.diff > 0) return 'overage';
                    if (this.diff < 0) return 'shortage';
                    return 'matched';
                },

                get statusDisplay() {
                    if (this.diff > 0) return 'Overage (Kelebihan)';
                    if (this.diff < 0) return 'Shortage (Kurang)';
                    return 'Matched';
                },

                updateDiff() {
                    let diffEl = document.getElementById('difference');
                    if (diffEl && typeof AutoNumeric !== 'undefined') {
                        let instance = AutoNumeric.getAutoNumericElement(diffEl);
                        if (instance) {
                            instance.set(this.diff);
                        }
                    }
                },

                initForm() {
                    const syncValue = (target) => {
                        if (!target || typeof AutoNumeric === 'undefined') return;
                        let instance = AutoNumeric.getAutoNumericElement(target);
                        if (!instance) return;
                        
                        let val = parseFloat(instance.getNumber()) || 0;
                        if (target.id === 'actual_cash') this.actCash = val;
                        if (target.id === 'actual_qris') this.actQris = val;
                        if (target.id === 'actual_transfer') this.actTf = val;
                        if (target.id === 'expected_cash') this.expCash = val;
                        if (target.id === 'expected_qris') this.expQris = val;
                        if (target.id === 'expected_transfer') this.expTf = val;
                    };

                    // Listen to AutoNumeric custom events
                    document.addEventListener('autoNumeric:rawValueModified', (e) => syncValue(e.target));
                    
                    // Also listen to standard events to be safe
                    ['keyup', 'change', 'blur'].forEach(evt => {
                        document.addEventListener(evt, (e) => {
                            if (e.target && ['actual_cash', 'actual_qris', 'actual_transfer'].includes(e.target.id)) {
                                syncValue(e.target);
                            }
                        });
                    });
                    
                    // Alpine $watch doesn't trigger on getters, so we watch the dependencies
                    this.$watch('actCash', () => this.updateDiff());
                    this.$watch('actQris', () => this.updateDiff());
                    this.$watch('actTf', () => this.updateDiff());
                    this.$watch('expCash', () => this.updateDiff());
                    this.$watch('expQris', () => this.updateDiff());
                    this.$watch('expTf', () => this.updateDiff());
                    
                    // Initialize
                    setTimeout(() => this.updateDiff(), 500);
                }
            }));
        });
    </script>
@endpush
