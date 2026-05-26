@props(['align' => 'right', 'width' => '48', 'contentClasses' => ''])

<div class="rs-dropdown-wrapper" style="position: relative; display: inline-block;">
    <div data-dropdown-toggle="true" style="cursor: pointer;">
        {{ $trigger }}
    </div>

    <div class="rs-dropdown-menu {{ $contentClasses }}" style="position: absolute; {{ $align === 'right' ? 'right: 0;' : 'left: 0;' }} top: 100%; margin-top: 0.5rem; width: {{ $width === '48' ? '12rem' : 'auto' }}; z-index: 100;">
        {{ $content }}
    </div>
</div>
