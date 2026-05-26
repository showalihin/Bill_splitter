<x-app-layout>
    <x-slot name="header">
        <h2 style="margin: 0;">Admin Master Dashboard</h2>
        <p class="rs-text-sm rs-text-secondary" style="margin: 0.25rem 0 0;">Platform overview and statistics</p>
    </x-slot>

    <div class="rs-slide-up">
        @if(session('success'))
            <div class="rs-alert rs-alert-success rs-mb-6">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rs-alert rs-alert-danger rs-mb-6">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div class="rs-grid rs-grid-cols-3 rs-gap-6 rs-mb-8" style="@media(max-width: 768px) { grid-template-columns: 1fr; }">
            {{-- Stat Cards --}}
            <div class="rs-card" style="border-left: 4px solid var(--primary-color);">
                <div class="rs-text-sm rs-text-secondary rs-mb-2">Total Registered Users</div>
                <div class="rs-font-bold" style="font-size: 2rem;">{{ number_format($totalUsers) }}</div>
            </div>
            
            <div class="rs-card" style="border-left: 4px solid var(--accent-color);">
                <div class="rs-text-sm rs-text-secondary rs-mb-2">Total Bills Created</div>
                <div class="rs-font-bold" style="font-size: 2rem;">{{ number_format($totalBills) }}</div>
            </div>

            <div class="rs-card" style="border-left: 4px solid var(--brand-color);">
                <div class="rs-text-sm rs-text-secondary rs-mb-2">Total Volume Processed</div>
                <div class="rs-font-bold" style="font-size: 2rem;">৳{{ number_format($totalVolume, 2) }}</div>
            </div>
        </div>

        <div class="rs-grid" style="grid-template-columns: 1fr; gap: 2rem; @media(min-width: 1024px) { grid-template-columns: 2fr 1fr; }">
            
            {{-- Pending Restaurant Approvals --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h3 class="rs-card-title">Pending Restaurant Approvals</h3>
                </div>

                @if($pendingRestaurants->isEmpty())
                    <p class="rs-text-secondary rs-text-center rs-py-4">No pending requests.</p>
                @else
                    <div class="rs-table-wrapper">
                        <table class="rs-table">
                            <thead>
                                <tr>
                                    <th>Restaurant</th>
                                    <th>Requested By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingRestaurants as $restaurant)
                                    <tr>
                                        <td>
                                            <div class="rs-font-medium">{{ $restaurant->name }}</div>
                                            <div class="rs-text-xs rs-text-secondary">{{ $restaurant->cuisine ?? 'No Cuisine' }}</div>
                                        </td>
                                        <td>
                                            {{ $restaurant->owner->name }}
                                            <div class="rs-text-xs rs-text-secondary">{{ $restaurant->created_at->diffForHumans() }}</div>
                                        </td>
                                        <td>
                                            <div class="rs-flex rs-gap-2">
                                                <a href="{{ route('restaurants.show', $restaurant) }}" class="rs-btn rs-btn-secondary rs-btn-sm">View Details</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="rs-card">
                <div class="rs-card-header">
                    <h3 class="rs-card-title">Quick Actions</h3>
                </div>
                <div class="rs-flex-col rs-gap-3">
                    <a href="{{ route('admin.users') }}" class="rs-btn rs-btn-primary" style="display: flex; justify-content: center;">
                        👥 Manage Users
                    </a>
                    <a href="{{ route('restaurants.create') }}" class="rs-btn rs-btn-secondary" style="display: flex; justify-content: center;">
                        🍽 Create Global Restaurant
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
