<x-app-layout>
    <x-slot name="header">
        <h2>Edit Menu Item</h2>
    </x-slot>

    <div class="rs-slide-up" style="max-width: 600px; margin: 0 auto;">
        <div class="rs-card">

            <div class="rs-mb-6">
                <h3 class="rs-card-title">
                    Editing: <span style="color: var(--primary-color);">{{ $menuItem->name }}</span>
                </h3>
                <p class="rs-card-subtitle">
                    In restaurant: {{ $restaurant->name }}
                </p>
            </div>

            <form method="POST" action="{{ route('restaurants.menu-items.update', [$restaurant, $menuItem]) }}">
                @csrf
                @method('PUT')

                @include('restaurants.menu_items._form')

                <div class="rs-flex rs-justify-between rs-items-center rs-mt-8" style="flex-wrap: wrap; gap: 0.75rem;">
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('restaurants.menu-items.destroy', [$restaurant, $menuItem]) }}"
                          onsubmit="return confirm('Delete this item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rs-btn rs-btn-danger rs-btn-sm">🗑 Delete Item</button>
                    </form>

                    <div class="rs-flex rs-gap-3" style="margin-left: auto;">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="rs-btn rs-btn-secondary">Cancel</a>
                        <button type="submit" class="rs-btn rs-btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
