@props(['name', 'label' => null, 'value' => null, 'options' => [], 'multiple' => false])

<div>
    @if ($label)
        <label for="{{ $name }}"
            class="block text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400 mb-2">{{ $label }}</label>
    @endif
    <div class="relative">
        @if (isset($iconSlot))
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                {{ $iconSlot }}
            </div>
        @endif
        <select name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $name }}"
            {{ $multiple ? 'multiple' : '' }}
            {{ $attributes->merge(['class' => 'block w-full rounded-xl border border-transparent bg-gray-50 dark:bg-gray-800/80 text-gray-900 dark:text-white shadow-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 py-3.5 transition-all duration-200 ' . (isset($iconSlot) ? 'pl-11 pr-4' : 'px-4')]) }}>
            @if (!$multiple)
                <option value="">Select {{ $label }}</option>
            @endif
            @foreach ($options as $key => $optionLabel)
                <option value="{{ $key }}"
                    {{ (is_array(old($name, $value)) ? in_array($key, old($name, $value)) : old($name, $value) == $key) ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
            {{ $slot }}
        </select>
    </div>
    @error($name)
        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
    @enderror
</div>
