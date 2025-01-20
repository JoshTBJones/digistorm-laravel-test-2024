@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'readonly' => false,
    'pattern' => null,
    'title' => null,
])

<div class="form-input-group sm:col-span-3">
    <label for="{{ $name }}" class="block text-sm/6 font-medium text-gray-900">
        {{ $label }}
    </label>
    <div class="mt-2">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ old($name, $value) }}"
            {{ $readonly ? 'readonly' : '' }}
            {{ $pattern ? "pattern=$pattern" : '' }}
            {{ $title ? "title=$title" : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 
                           outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400
                           focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6',
            ]) }}
        >
    </div>
</div>
