@props(['name', 'show' => false, 'maxWidth' => '2xl'])

<div id="{{ $name }}" class="rs-modal-overlay {{ $show ? 'show' : '' }}" style="display: {{ $show ? 'flex' : '' }}">
    <div class="rs-modal-content" style="max-width: {{ $maxWidth === 'sm' ? '300px' : ($maxWidth === 'md' ? '500px' : ($maxWidth === 'lg' ? '800px' : '600px')) }}">
        {{ $slot }}
    </div>
</div>
