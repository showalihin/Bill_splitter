<x-app-layout>
    <x-slot name="header">
        <h2>Dashboard</h2>
    </x-slot>

    <div class="rs-card rs-slide-up">
        <div class="rs-flex rs-items-center rs-gap-4 rs-mb-6">
            <div class="rs-avatar" style="width: 4rem; height: 4rem; font-size: 1.5rem;">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <h3 class="rs-font-bold rs-text-primary" style="margin: 0; font-size: 1.5rem; font-family: var(--font-serif);">
                    Welcome back, {{ Auth::user()->name }}! 👋
                </h3>
                <p class="rs-text-secondary" style="margin: 0.25rem 0 0; font-size: 0.95rem;">
                    {{ Auth::user()->email }}
                    @if(Auth::user()->isAdmin())
                        <span class="rs-badge rs-badge-accent" style="margin-left: 0.5rem;">
                            Admin
                        </span>
                    @endif
                </p>
            </div>
        </div>

        <p class="rs-text-secondary rs-mb-6" style="line-height: 1.7; font-size: 1.05rem;">
            You're logged in and ready to split some bills! What would you like to do?
        </p>

        <div class="rs-grid rs-grid-auto rs-gap-4 rs-mb-8">
            <a href="{{ route('restaurants.index') }}" class="rs-card" style="text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
                <div style="font-size: 2rem; margin-bottom: 1rem;">🍽️</div>
                <h4 class="rs-font-bold rs-text-primary" style="margin: 0 0 0.5rem; font-size: 1.1rem;">Restaurants</h4>
                <p class="rs-text-secondary rs-text-sm" style="margin: 0;">Browse and manage restaurant menus</p>
            </a>
            <a href="{{ route('bills.create') }}" class="rs-card" style="text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
                <div style="font-size: 2rem; margin-bottom: 1rem;">💰</div>
                <h4 class="rs-font-bold rs-text-primary" style="margin: 0 0 0.5rem; font-size: 1.1rem;">New Split Bill</h4>
                <p class="rs-text-secondary rs-text-sm" style="margin: 0;">Start a new fair split with VAT & service charges</p>
            </a>
            <a href="{{ route('bills.index') }}" class="rs-card" style="text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
                <div style="font-size: 2rem; margin-bottom: 1rem;">📊</div>
                <h4 class="rs-font-bold rs-text-primary" style="margin: 0 0 0.5rem; font-size: 1.1rem;">History</h4>
                <p class="rs-text-secondary rs-text-sm" style="margin: 0;">Track past splits and settlements</p>
            </a>
        </div>

        <h3 class="rs-font-bold rs-mb-4" style="font-size: 1.25rem;">Analytics Overview</h3>
        <div class="rs-grid rs-gap-4 rs-mb-8" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div class="rs-card" style="text-align: center;">
                <div class="rs-text-secondary rs-text-sm rs-mb-2">Total Bills Split</div>
                <div class="rs-font-bold rs-text-primary" style="font-size: 2rem;">{{ $totalBills }}</div>
            </div>
            <div class="rs-card" style="text-align: center;">
                <div class="rs-text-secondary rs-text-sm rs-mb-2">Total Handled</div>
                <div class="rs-font-bold rs-text-accent" style="font-size: 2rem; font-family: var(--font-serif);">৳{{ number_format($totalVolume) }}</div>
            </div>
            <div class="rs-card" style="text-align: center;">
                <div class="rs-text-secondary rs-text-sm rs-mb-2">Pending Bills</div>
                <div class="rs-font-bold rs-text-danger" style="font-size: 2rem;">{{ $openBills }}</div>
            </div>
        </div>

        @if($recentBills->isNotEmpty())
            <div class="rs-flex rs-justify-between rs-items-center rs-mb-4">
                <h3 class="rs-font-bold" style="font-size: 1.25rem; margin: 0;">Recent Splits</h3>
                <a href="{{ route('bills.index') }}" class="rs-text-sm rs-text-brand" style="text-decoration: none;">View all &rarr;</a>
            </div>
            <div class="rs-flex-col rs-gap-3">
                @foreach($recentBills as $bill)
                    <a href="{{ route('bills.show', $bill) }}" class="rs-flex rs-justify-between rs-items-center" style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius-md); text-decoration: none; border: 1px solid var(--border-light); transition: border-color 0.2s;" onmouseover="this.style.borderColor='var(--brand-color)';" onmouseout="this.style.borderColor='var(--border-light)';">
                        <div>
                            <div class="rs-font-bold rs-text-primary">{{ $bill->name }}</div>
                            <div class="rs-text-xs rs-text-secondary">{{ $bill->created_at->format('M d, Y') }}</div>
                        </div>
                        <div class="rs-font-bold rs-text-accent">
                            ৳{{ number_format($bill->calculated_grand_total, 2) }}
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
