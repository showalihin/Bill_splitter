@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rs-alert rs-alert-success']) }}>
        {{ $status }}
    </div>
@endif
