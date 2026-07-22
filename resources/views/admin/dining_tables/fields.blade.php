<!-- Hidden timestamp fields -->
<input type="hidden" name="deleted_at" value="{{$diningTable->deleted_at ?? ''}}">
<x-input-floating type="text" name="number" label="Number" value="{{ $diningTable->number ?? '' }}" />

<x-input-floating type="number" name="capacity" label="Capacity" value="{{ $diningTable->capacity ?? '' }}" />

<x-select name="status" label="Status" value="{{ $diningTable->status ?? '' }}" :options="['available' => 'Available', 'occupied' => 'Occupied', 'reserved' => 'Reserved', ]" />


<x-toggle name="show" label="Show" :checked="$diningTable->show ?? false" />


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
  0 => 'status',
);
        $currencyFields = array (
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush