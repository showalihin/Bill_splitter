<x-app-layout>
    <x-slot name="header">
        <h2>{{ $restaurant->name }}</h2>
    </x-slot>

    <div class="rs-slide-up" style="display: flex; flex-direction: column; gap: 1.5rem;">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rs-alert rs-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rs-alert rs-alert-error">{{ session('error') }}</div>
        @endif

        {{-- ============================================================ --}}
        {{-- Restaurant Info Header Card                                   --}}
        {{-- ============================================================ --}}
        <div class="rs-card">
            <div class="rs-flex rs-justify-between" style="align-items: flex-start; gap: 1rem; flex-wrap: wrap;">

                {{-- Left: Info --}}
                <div class="rs-flex rs-items-start rs-gap-4">
                    <div style="width: 3.5rem; height: 3.5rem; border-radius: var(--radius-md); background-color: var(--primary-light); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; flex-shrink: 0;">
                        🍽️
                    </div>
                    <div>
                        <h3 class="rs-font-bold rs-text-primary" style="margin: 0 0 0.5rem; font-size: 1.5rem; font-family: var(--font-serif);">{{ $restaurant->name }}</h3>

                        <div class="rs-flex rs-gap-2 rs-mb-2" style="flex-wrap: wrap;">
                            @if($restaurant->isGlobal())
                                <span class="rs-badge rs-badge-primary">🌐 Global</span>
                            @else
                                <span class="rs-badge rs-badge-neutral">🔒 Private</span>
                            @endif

                            @if($restaurant->status === 'pending')
                                <span class="rs-badge rs-badge-accent">⏳ Awaiting Admin Approval</span>
                            @elseif($restaurant->status === 'rejected')
                                <span class="rs-badge" style="background-color: #FFF5F5; color: var(--danger-color); border: 1px solid rgba(181,90,90,0.2);">✕ Global Request Rejected</span>
                            @endif
                        </div>

                        <div class="rs-text-secondary rs-text-sm rs-flex rs-gap-3" style="flex-wrap: wrap;">
                            @if($restaurant->cuisine)
                                <span>🍴 {{ $restaurant->cuisine }}</span>
                            @endif
                            @if($restaurant->address)
                                <span>📍 {{ $restaurant->address }}</span>
                            @endif
                            @if($restaurant->phone)
                                <span>📞 {{ $restaurant->phone }}</span>
                            @endif
                        </div>

                        @if($restaurant->description)
                            <p class="rs-text-secondary rs-mt-2" style="font-size: 0.95rem; max-width: 60ch;">
                                {{ $restaurant->description }}
                            </p>
                        @endif

                        {{-- Rejection reason --}}
                        @if($restaurant->status === 'rejected' && $restaurant->rejection_reason)
                            <div class="rs-mt-2" style="padding: 0.75rem; background-color: #FFF5F5; border: 1px solid rgba(181,90,90,0.2); border-radius: var(--radius-sm); font-size: 0.85rem; color: var(--danger-color);">
                                <strong>Admin note:</strong> {{ $restaurant->rejection_reason }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right: Action Buttons --}}
                <div class="rs-flex rs-gap-2" style="flex-wrap: wrap; flex-shrink: 0;">
                    @can('update', $restaurant)
                        <a href="{{ route('restaurants.edit', $restaurant) }}" class="rs-btn rs-btn-secondary rs-btn-sm">✏️ Edit</a>
                    @endcan

                    @can('requestGlobal', $restaurant)
                        <form method="POST" action="{{ route('restaurants.request-global', $restaurant) }}">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-primary rs-btn-sm"
                                    onclick="return confirm('Request this restaurant to be listed globally?')">
                                🌐 Request Global
                            </button>
                        </form>
                    @endcan

                    <a href="{{ route('restaurants.index') }}" class="rs-btn rs-btn-secondary rs-btn-sm">← Back</a>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- Menu Items                                                    --}}
        {{-- ============================================================ --}}
        <div class="rs-card">
            <div class="rs-flex rs-justify-between rs-items-center rs-mb-6" style="flex-wrap: wrap; gap: 0.75rem;">
                <h3 class="rs-card-title" style="margin: 0;">
                    📋 Menu  <span class="rs-text-secondary" style="font-weight: 400; font-size: 0.95rem;">({{ $menuItems->count() }} items)</span>
                </h3>

                @if(Auth::user()->isAdmin() || $restaurant->isOwnedBy(Auth::user()))
                    <a href="{{ route('restaurants.menu-items.create', $restaurant) }}" class="rs-btn rs-btn-primary rs-btn-sm">
                        + Add Item
                    </a>
                @endif
            </div>

            @if($menuItems->isEmpty())
                <div class="rs-text-center rs-text-secondary" style="padding: 3rem 1rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.5;">🍽️</div>
                    <p style="margin: 0 0 1rem; font-size: 1.1rem;">No menu items yet.</p>
                    @if(Auth::user()->isAdmin() || $restaurant->isOwnedBy(Auth::user()))
                        <a href="{{ route('restaurants.menu-items.create', $restaurant) }}" class="rs-text-brand" style="text-decoration: underline;">Add the first item →</a>
                    @endif
                </div>
            @else
                {{-- Group by category --}}
                @php
                    $grouped = $menuItems->groupBy(fn($item) => $item->category ?: 'Other');
                    $sortedKeys = $grouped->keys()->sort()->values();
                @endphp

                <div class="rs-flex-col rs-gap-6">
                    @foreach($sortedKeys as $category)
                        <div>
                            <div class="rs-font-bold" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--accent-color); margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
                                {{ $category }}
                            </div>
                            <div class="rs-flex-col rs-gap-3">
                                @foreach($grouped[$category] as $item)
                                <div class="rs-flex rs-justify-between rs-items-center" style="gap: 1rem; padding: 1rem; background-color: var(--surface-alt); border: 1px solid var(--border-color); border-radius: var(--radius-md); flex-wrap: wrap;">
                                    <div style="min-width: 0;">
                                        <div class="rs-font-semibold rs-text-primary" style="font-size: 1rem;">
                                            {{ $item->name }}
                                            @if($item->unit)
                                                <span class="rs-text-secondary" style="font-size: 0.85rem; font-weight: 400;"> / {{ $item->unit }}</span>
                                            @endif
                                        </div>
                                        @if($item->description)
                                            <div class="rs-text-secondary rs-text-sm" style="margin-top: 0.25rem;">{{ $item->description }}</div>
                                        @endif
                                    </div>
                                    <div class="rs-flex rs-items-center rs-gap-4" style="flex-shrink: 0;">
                                        <span class="rs-font-bold" style="color: var(--primary-color); font-size: 1.1rem;">
                                            {{ $item->formattedPrice() }}
                                        </span>
                                        @if(Auth::user()->isAdmin() || $restaurant->isOwnedBy(Auth::user()))
                                            <div class="rs-flex rs-gap-2">
                                                <a href="{{ route('restaurants.menu-items.edit', [$restaurant, $item]) }}" class="rs-btn rs-btn-secondary rs-btn-sm" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Edit</a>
                                                <form method="POST" action="{{ route('restaurants.menu-items.destroy', [$restaurant, $item]) }}"
                                                      onsubmit="return confirm('Remove {{ $item->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rs-btn rs-btn-danger rs-btn-sm" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">✕</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
