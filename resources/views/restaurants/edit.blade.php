<x-app-layout>
    <x-slot name="header">
        <h2>Edit Restaurant</h2>
    </x-slot>

    <div class="rs-slide-up" style="max-width: 640px; margin: 0 auto;">
        <div class="rs-card">

            <div class="rs-mb-6">
                <h3 class="rs-card-title">
                    Editing: {{ $restaurant->name }}
                </h3>
                <p class="rs-card-subtitle">
                    Update the restaurant's details below.
                </p>
            </div>

            <form method="POST" action="{{ route('restaurants.update', $restaurant) }}">
                @csrf
                @method('PUT')

                @include('restaurants._form')

                <div class="rs-flex rs-justify-between rs-items-center rs-mt-8" style="flex-wrap: wrap; gap: 0.75rem;">
                    {{-- Danger Zone: Delete --}}
                    @can('delete', $restaurant)
                    <form method="POST" action="{{ route('restaurants.destroy', $restaurant) }}"
                          onsubmit="return confirm('Delete {{ $restaurant->name }}? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rs-btn rs-btn-danger rs-btn-sm">🗑 Delete</button>
                    </form>
                    @endcan

                    <div class="rs-flex rs-gap-3" style="margin-left: auto;">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="rs-btn rs-btn-secondary">Cancel</a>
                        <button type="submit" class="rs-btn rs-btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
