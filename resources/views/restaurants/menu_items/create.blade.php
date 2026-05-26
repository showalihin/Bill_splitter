<x-app-layout>
    <x-slot name="header">
        <h2>Add Menu Item</h2>
    </x-slot>

    <div class="rs-slide-up" style="max-width: 600px; margin: 0 auto;">
        <div class="rs-card">

            <div class="rs-mb-6">
                <h3 class="rs-card-title">
                    Add to: <span style="color: var(--primary-color);">{{ $restaurant->name }}</span>
                </h3>
                <p class="rs-card-subtitle">
                    Fill in the item details below.
                </p>
            </div>

            <form method="POST" action="{{ route('restaurants.menu-items.store', $restaurant) }}">
                @csrf

                @include('restaurants.menu_items._form')

                <div class="rs-flex rs-justify-end rs-gap-3 rs-mt-8">
                    <a href="{{ route('restaurants.show', $restaurant) }}" class="rs-btn rs-btn-secondary">Cancel</a>
                    <button type="submit" class="rs-btn rs-btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
