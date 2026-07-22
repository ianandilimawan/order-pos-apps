@props([
    'name',
    'label',
    'value' => '',
    'required' => false,
    'id' => null,
    'rows' => 3
])

@php
    $id = $id ?? $name;
@endphp

<div class="relative">
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" {{ $required ? 'required' : '' }} placeholder=" "
        {{ $attributes->merge(['class' => 'block px-4 pb-3 pt-4 w-full text-sm text-zinc-900 bg-transparent rounded-xl border-2 border-zinc-200 appearance-none dark:text-white dark:border-zinc-700 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-500 peer transition-colors']) }}>{{ $value }}</textarea>
    <label for="{{ $id }}"
        class="absolute text-sm text-zinc-500 dark:text-zinc-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-800 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 start-2 cursor-text rounded-md">{{ $label }}</label>
</div>
