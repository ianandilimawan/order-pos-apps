<!-- Hidden timestamp fields -->
<input type="hidden" name="deleted_at" value="{{$category->deleted_at ?? ''}}">
<x-input-floating type="text" name="name" label="Name" value="{{ $category->name ?? '' }}" />

<x-input-floating type="text" name="slug" label="Slug" value="{{ $category->slug ?? '' }}" />

<x-textarea-floating name="description" label="Description" value="{{ $category->description ?? '' }}" />

        <x-filepond name="image" label="Image" :defaultFile="isset($fileUrls['image']) ? $fileUrls['image'] : null" />
<x-input-floating type="number" name="sort" label="Sort" value="{{ $category->sort ?? '' }}" />

<x-toggle name="show" label="Show" :checked="$category->show ?? false" />


@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = true;
        $hasSlugField = true;
        $slugSourceField = 'name';
        $tagifyFields = array (
);
        $textareaFields = array (
  0 => 'description',
);
        $selectFields = array (
);
        $currencyFields = array (
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush