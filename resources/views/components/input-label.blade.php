@props(['value'])

<label {{ $attributes->merge(['class' => 'rs-label']) }}>
    {{ $value ?? $slot }}
</label>
