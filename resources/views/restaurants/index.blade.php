<x-app-layout>
    <x-slot name="header">
        <h2>Restaurants</h2>
    </x-slot>

    <div class="rs-slide-up" style="display: flex; flex-direction: column; gap: 2rem;">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="rs-alert rs-alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="rs-alert rs-alert-error">{{ session('error') }}</div>
        @endif

        {{-- Page header row --}}
        <div class="rs-flex rs-justify-between rs-items-center" style="flex-wrap: wrap; gap: 1rem;">
            <div>
                <p class="rs-text-secondary" style="margin: 0; font-size: 0.95rem;">
                    Browse global restaurants or manage your own private ones.
                </p>
            </div>
            <a href="{{ route('restaurants.create') }}" class="rs-btn rs-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                New Restaurant
            </a>
        </div>

        {{-- ============================================================ --}}
        {{-- ADMIN ONLY: Pending Approval Queue                           --}}
        {{-- ============================================================ --}}
        @if(Auth::user()->isAdmin() && $pendingRestaurants->isNotEmpty())
        <div class="rs-card" style="border-color: var(--accent-color); background-color: rgba(163, 135, 88, 0.05);">
            <h3 class="rs-card-title rs-flex rs-items-center rs-gap-2" style="font-size: 1.1rem; color: var(--accent-hover); margin-bottom: 1.25rem;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--accent-hover); display: inline-block;"></span>
                Pending Global Requests ({{ $pendingRestaurants->count() }})
            </h3>

            <div class="rs-flex-col rs-gap-3">
                @foreach($pendingRestaurants as $restaurant)
                <div class="rs-flex rs-justify-between rs-items-center" style="flex-wrap: wrap; gap: 0.75rem; padding: 1rem; background-color: var(--surface-color); border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                    <div>
                        <div class="rs-font-semibold">{{ $restaurant->name }}</div>
                        <div class="rs-text-sm rs-text-secondary" style="margin-top: 0.2rem;">
                            Requested by <span>{{ $restaurant->owner?->name ?? 'Unknown' }}</span>
                            @if($restaurant->cuisine) · {{ $restaurant->cuisine }} @endif
                        </div>
                    </div>
                    <div class="rs-flex rs-gap-2" style="flex-wrap: wrap;">
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="rs-btn rs-btn-secondary rs-btn-sm">View</a>
                        <form method="POST" action="{{ route('restaurants.approve', $restaurant) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="rs-btn rs-btn-primary rs-btn-sm">✓ Approve</button>
                        </form>
                        <button type="button" class="rs-btn rs-btn-danger rs-btn-sm" data-toggle-inline="reject-form-{{ $restaurant->id }}">
                            ✕ Reject
                        </button>
                    </div>
                </div>
                {{-- Hidden rejection form --}}
                <div id="reject-form-{{ $restaurant->id }}" style="display:none; padding: 1rem; background: #FFF5F5; border: 1px solid rgba(181,90,90,0.2); border-radius: var(--radius-md);">
                    <form method="POST" action="{{ route('restaurants.reject', $restaurant) }}">
                        @csrf
                        <label class="rs-label">Rejection reason (optional)</label>
                        <textarea name="rejection_reason" class="rs-input" rows="2" placeholder="Tell the user why..."></textarea>
                        <div class="rs-flex rs-gap-2 rs-mt-2">
                            <button type="submit" class="rs-btn rs-btn-danger rs-btn-sm">Confirm Reject</button>
                            <button type="button" class="rs-btn rs-btn-secondary rs-btn-sm" data-toggle-inline="reject-form-{{ $restaurant->id }}">Cancel</button>
                        </div>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- Global Restaurants                                            --}}
        {{-- ============================================================ --}}
        <div class="rs-card">
            <h3 class="rs-card-title rs-flex rs-items-center" style="font-size: 1.25rem; margin-bottom: 1.5rem;">
                🌐 Global Restaurants
                <span class="rs-badge rs-badge-neutral" style="margin-left: auto;">{{ $globalRestaurants->count() }}</span>
            </h3>

            @if($globalRestaurants->isEmpty())
                <p class="rs-text-secondary rs-text-center" style="padding: 2rem 0;">
                    No global restaurants yet. Admins can create them or approve user requests.
                </p>
            @else
                <div class="rs-grid rs-grid-auto rs-gap-4">
                    @foreach($globalRestaurants as $restaurant)
                        @include('restaurants._card', ['restaurant' => $restaurant])
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ============================================================ --}}
        {{-- My Private Restaurants                                        --}}
        {{-- ============================================================ --}}
        <div class="rs-card">
            <h3 class="rs-card-title rs-flex rs-items-center" style="font-size: 1.25rem; margin-bottom: 1.5rem;">
                🔒 {{ Auth::user()->isAdmin() ? 'All Private Restaurants' : 'My Private Restaurants' }}
                <span class="rs-badge rs-badge-neutral" style="margin-left: auto;">{{ $privateRestaurants->count() }}</span>
            </h3>

            @if($privateRestaurants->isEmpty())
                <p class="rs-text-secondary rs-text-center" style="padding: 2rem 0;">
                    You haven't created any private restaurants yet.
                    <a href="{{ route('restaurants.create') }}" class="rs-text-brand" style="text-decoration: underline;">Create one now →</a>
                </p>
            @else
                <div class="rs-grid rs-grid-auto rs-gap-4">
                    @foreach($privateRestaurants as $restaurant)
                        @include('restaurants._card', ['restaurant' => $restaurant])
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
