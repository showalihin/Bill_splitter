<x-app-layout>
    <x-slot name="header">
        <div class="rs-flex rs-justify-between rs-items-center">
            <h2>Bill Sessions</h2>
            <a href="{{ route('bills.create') }}" class="rs-btn rs-btn-primary">
                + New Split
            </a>
        </div>
    </x-slot>

    <div class="rs-slide-up">
        @if(session('status'))
            <div class="rs-alert rs-alert-success">
                ✅ {{ session('status') }}
            </div>
        @endif

        @if($bills->isEmpty())
            <div class="rs-card rs-text-center">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🧾</div>
                <h3 class="rs-card-title">No bills yet</h3>
                <p class="rs-card-subtitle rs-mb-6">Start splitting a bill to see it here.</p>
                <a href="{{ route('bills.create') }}" class="rs-btn rs-btn-primary">
                    Create your first Bill Session
                </a>
            </div>
        @else
            <div class="rs-grid rs-grid-auto rs-gap-4">
                @foreach($bills as $bill)
                    <a href="{{ route('bills.show', $bill) }}" class="rs-card" style="text-decoration: none; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)';">
                        <div class="rs-flex rs-justify-between rs-items-start rs-mb-4">
                            <div>
                                <h4 class="rs-font-bold rs-text-primary" style="margin: 0 0 0.25rem; font-size: 1.1rem;">
                                    {{ $bill->name }}
                                </h4>
                                <p class="rs-text-secondary rs-text-sm" style="margin: 0;">
                                    {{ $bill->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            @if($bill->status === 'settled')
                                <span class="rs-badge rs-badge-primary">Settled</span>
                            @else
                                <span class="rs-badge rs-badge-accent">Open</span>
                            @endif
                        </div>

                        <div class="rs-flex rs-justify-between rs-items-center" style="border-top: 1px solid var(--border-light); padding-top: 1rem;">
                            <span class="rs-text-sm rs-text-secondary">
                                @if($bill->restaurant)
                                    🍽 {{ $bill->restaurant->name }}
                                @else
                                    🍽 Custom Bill
                                @endif
                            </span>
                            <span class="rs-font-bold rs-text-primary" style="font-family: var(--font-serif); font-size: 1.1rem;">
                                ৳{{ number_format($bill->calculated_grand_total, 2) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
