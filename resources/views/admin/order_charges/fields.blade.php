fields/select.stub
fields/select.stub
fields/text.stub
fields/select.stub
fields/text.stub
fields/text.stub

@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = false;
        $hasSlugField = false;
        $slugSourceField = null;
        $tagifyFields = array (
);
        $textareaFields = array (
);
        $selectFields = array (
  0 => 'order_id',
  1 => 'charge_setting_id',
  2 => 'charge_type',
);
        $currencyFields = array (
  0 => 'charge_name',
  1 => 'charge_rate',
  2 => 'charge_amount',
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush