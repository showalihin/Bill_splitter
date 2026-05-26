@props(['active'])

@php
$classes = ($active ?? false)
            ? 'rs-nav-link active'
            : 'rs-nav-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
