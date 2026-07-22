<x-input-floating type="text" name="name" label="Name" value="{{ $chargeSetting->name ?? '' }}" />

<x-select name="type" label="Type" value="{{ $chargeSetting->type ?? '' }}" :options="['percentage' => 'Percentage', 'fixed' => 'Fixed', ]" />

<x-input-floating type="number" name="value" label="Value" value="{{ $chargeSetting->value ?? '' }}" />

<x-select name="applies_to" label="Applies to" value="{{ $chargeSetting->applies_to ?? '' }}" :options="['all' => 'All', 'dine_in' => 'Dine_in', 'take_away' => 'Take_away', ]" />

<x-toggle name="is_active" label="Is active" :checked="$chargeSetting->is_active ?? false" />

<x-input-floating type="number" name="sort" label="Sort" value="{{ $chargeSetting->sort ?? '' }}" />


@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = true;
        $hasSlugField = false;
        $slugSourceField = 'name';
        $tagifyFields = array (
);
        $textareaFields = array (
);
        $selectFields = array (
  0 => 'type',
  1 => 'applies_to',
);
        $currencyFields = array (
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush