<div class="rs-card rs-flex-col rs-gap-4">
    {{-- Header --}}
    <div class="rs-flex rs-items-start rs-gap-3">
        <div style="width: 2.5rem; height: 2.5rem; border-radius: var(--radius-sm); background-color: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
            🍽️
        </div>
        <div style="min-width: 0;">
            <div class="rs-font-semibold rs-text-primary" style="font-size: 1.05rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $restaurant->name }}
            </div>
            <div class="rs-text-secondary rs-text-sm" style="margin-top: 0.1rem;">
                @if($restaurant->cuisine) {{ $restaurant->cuisine }} @endif
                @if($restaurant->cuisine && $restaurant->address) · @endif
                @if($restaurant->address) {{ Str::limit($restaurant->address, 30) }} @endif
            </div>
        </div>
    </div>

    {{-- Badges --}}
    <div class="rs-flex rs-gap-2" style="flex-wrap: wrap;">
        @if($restaurant->isGlobal())
            <span class="rs-badge rs-badge-primary">🌐 Global</span>
        @else
            <span class="rs-badge rs-badge-neutral">🔒 Private</span>
        @endif

        @if($restaurant->status === 'pending')
            <span class="rs-badge rs-badge-accent">⏳ Pending</span>
        @elseif($restaurant->status === 'rejected')
            <span class="rs-badge" style="background-color: #FFF5F5; color: var(--danger-color); border: 1px solid rgba(181,90,90,0.2);">✕ Rejected</span>
        @endif

        @if(Auth::user()->isAdmin() && $restaurant->owner)
            <span class="rs-badge rs-badge-neutral">{{ $restaurant->owner->name }}</span>
        @endif
    </div>

    {{-- Item count --}}
    <div class="rs-text-secondary rs-text-sm">
        {{ $restaurant->menuItems()->count() }} menu item(s)
    </div>

    {{-- Actions --}}
    <div class="rs-flex rs-gap-2 rs-mt-2" style="margin-top: auto;">
        <a href="{{ route('restaurants.show', $restaurant) }}" class="rs-btn rs-btn-secondary rs-btn-sm" style="flex: 1; text-align: center;">View</a>
        @can('update', $restaurant)
            <a href="{{ route('restaurants.edit', $restaurant) }}" class="rs-btn rs-btn-secondary rs-btn-sm">Edit</a>
        @endcan
    </div>
</div>
