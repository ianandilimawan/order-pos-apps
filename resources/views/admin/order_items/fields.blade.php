fields/select.stub
fields/select.stub
fields/text.stub
fields/text.stub
fields/text.stub
fields/textarea.stub

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
  0 => 'notes',
);
        $selectFields = array (
  0 => 'order_id',
  1 => 'product_id',
);
        $currencyFields = array (
  0 => 'price',
  1 => 'subtotal',
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush