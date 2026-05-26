<x-app-layout>
    <x-slot name="header">
        <h2>New Restaurant</h2>
    </x-slot>

    <div class="rs-slide-up" style="max-width: 640px; margin: 0 auto;">
        <div class="rs-card">

            <div class="rs-mb-6">
                <h3 class="rs-card-title">
                    {{ Auth::user()->isAdmin() ? '🌐 Create a Global Restaurant' : '🔒 Create a Private Restaurant' }}
                </h3>
                <p class="rs-card-subtitle">
                    @if(Auth::user()->isAdmin())
                        This restaurant will be immediately visible to all users.
                    @else
                        This restaurant will be private to you. You can later request the admin to make it global.
                    @endif
                </p>
            </div>

            <form method="POST" action="{{ route('restaurants.store') }}">
                @csrf

                @include('restaurants._form')

                <div class="rs-flex rs-justify-end rs-gap-3 rs-mt-8">
                    <a href="{{ route('restaurants.index') }}" class="rs-btn rs-btn-secondary">Cancel</a>
                    <button type="submit" class="rs-btn rs-btn-primary">
                        Create Restaurant
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
