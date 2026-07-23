<!-- Hidden timestamp fields -->
<input type="hidden" name="deleted_at" value="{{ $product->deleted_at ?? '' }}">
<x-select-floating name="category_id" label="Category id" value="{{ $product->category_id ?? '' }}" :options="$categories" />

<x-input-floating type="text" name="name" label="Name" value="{{ $product->name ?? '' }}" />

<x-input-floating type="text" name="slug" label="Slug" value="{{ $product->slug ?? '' }}" />

<x-textarea-floating name="description" label="Description" value="{{ $product->description ?? '' }}" />

<x-input-floating type="text" name="price" label="Price" value="{{ $product->price ?? '' }}" :isCurrency="true" />

<x-filepond name="image" label="Image" :defaultFile="isset($fileUrls['image']) ? $fileUrls['image'] : null" />
<x-toggle name="is_available" label="Is available" :checked="$product->is_available ?? false" />

<x-input-floating type="number" name="sort" label="Sort" value="{{ $product->sort ?? '' }}" />

<x-toggle name="show" label="Show" :checked="$product->show ?? false" />


@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = true;
        $hasSlugField = true;
        $slugSourceField = 'name';
        $tagifyFields = [];
        $textareaFields = [];
        $selectFields = [
            0 => 'category_id',
        ];
        $currencyFields = [
            0 => 'price',
        ];
        $passwordFields = [];
    @endphp
    @include('admin.partials.form-scripts')
@endpush
