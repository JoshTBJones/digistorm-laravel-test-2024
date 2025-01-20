<button
    type="{{ $type }}"
    class="btn btn-{{ $color }} {{ $attributes->get('class') }}"
    {{ $attributes->except('class') }}
>
    {{ $label }}
</button>
