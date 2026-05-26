@props(['active'])

@php
$classes = ($active ?? false)
            ? 'rs-mobile-link active'
            : 'rs-mobile-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
