<x-app-layout>
    <x-slot name="header">
        <div class="rs-flex rs-justify-between rs-items-center">
            <div>
                <h2 style="margin: 0;">{{ $bill->name }}</h2>
                <p class="rs-text-sm rs-text-secondary" style="margin: 0.25rem 0 0;">
                    @if($bill->restaurant) 🍽 {{ $bill->restaurant->name }} @else 🍽 Custom Bill @endif 
                    | 📅 {{ $bill->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="rs-flex rs-gap-3 rs-items-center">
                <button onclick="copyShareLink()" class="rs-btn rs-btn-secondary rs-btn-sm" style="display: flex; align-items: center; gap: 0.5rem;">
                    🔗 Share Link
                </button>
                @if($bill->status === 'settled')
                    <span class="rs-badge rs-badge-primary">Settled</span>
                @else
                    <span class="rs-badge rs-badge-accent">Open</span>
                @endif
            </div>
        </div>
        
        <script>
            function copyShareLink() {
                const url = '{{ route("bills.shared", $bill->share_token) }}';
                
                // Modern Clipboard API (requires HTTPS or localhost)
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Share link copied to clipboard!');
                    });
                } else {
                    // Fallback for HTTP .test domains
                    const textArea = document.createElement("textarea");
                    textArea.value = url;
                    textArea.style.position = "fixed";
                    textArea.style.opacity = "0";
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        alert('Share link copied to clipboard!');
                    } catch (err) {
                        alert('Failed to copy. Your link is: ' + url);
                    }
                    document.body.removeChild(textArea);
                }
            }
        </script>
    </x-slot>

    <div class="rs-slide-up">
        @if(session('status'))
            <div class="rs-alert rs-alert-success">
                ✅ {{ session('status') }}
            </div>
        @endif

        <div class="rs-grid" style="grid-template-columns: 1fr; gap: 2rem; @media(min-width: 1024px) { grid-template-columns: 2fr 1fr; }">
            
            {{-- LEFT COLUMN: Participants & Items --}}
            <div class="rs-flex-col rs-gap-6">
                
                {{-- Participants Section --}}
                <div class="rs-card">
                    <div class="rs-card-header rs-flex rs-justify-between rs-items-center">
                        <h3 class="rs-card-title" style="margin: 0;">1. Participants</h3>
                    </div>

                    <div class="rs-flex rs-gap-2 rs-mb-4" style="flex-wrap: wrap;">
                        @foreach($bill->participants as $participant)
                            <div class="rs-badge rs-badge-neutral" style="padding: 0.5rem 1rem; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                                👤 {{ $participant->name }}
                                <form method="POST" action="{{ route('bills.participants.destroy', [$bill, $participant]) }}" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: var(--danger-color); cursor: pointer; font-weight: bold; background: none; border: none;">&times;</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('bills.participants.store', $bill) }}" class="rs-flex rs-gap-3 rs-items-end" style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius-md);">
                        @csrf
                        <div class="rs-form-group" style="margin-bottom: 0; flex: 1;">
                            <label class="rs-label" for="participant_name" style="font-size: 0.8rem;">Add Person</label>
                            <input type="text" id="participant_name" name="name" class="rs-input" placeholder="e.g. Alice" required>
                        </div>
                        <button type="submit" class="rs-btn rs-btn-primary">Add</button>
                    </form>
                </div>

                {{-- Session Menu Section --}}
                <div class="rs-card">
                    <div class="rs-card-header rs-flex rs-justify-between rs-items-center">
                        <h3 class="rs-card-title" style="margin: 0;">2. Session Menu</h3>
                        <button data-modal-target="scan-menu-modal" class="rs-btn rs-btn-primary rs-btn-sm" style="display: flex; align-items: center; gap: 0.5rem; background-color: var(--accent-color);">
                            📸 Scan Menu (AI)
                        </button>
                    </div>

                    @error('menu_image')
                        <div class="rs-alert rs-alert-danger rs-mb-4" style="font-size: 0.85rem;">
                            ❌ {{ $message }}
                        </div>
                    @enderror

                    <form method="POST" action="{{ route('bills.menu.add', $bill) }}" class="rs-flex rs-gap-3 rs-items-end rs-mb-4" style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius-md);">
                        @csrf
                        <div class="rs-form-group" style="margin-bottom: 0; flex: 2;">
                            <label class="rs-label" style="font-size: 0.8rem;">Item Name</label>
                            <input type="text" name="name" class="rs-input" placeholder="e.g. Medium Pizza" required>
                        </div>
                        <div class="rs-form-group" style="margin-bottom: 0; flex: 1;">
                            <label class="rs-label" style="font-size: 0.8rem;">Price</label>
                            <input type="number" name="price" class="rs-input" step="0.01" required>
                        </div>
                        <button type="submit" class="rs-btn rs-btn-secondary">Add to Menu</button>
                    </form>

                    <div style="max-height: 250px; overflow-y: auto;">
                        @if($bill->restaurant && $bill->restaurant->menuItems->count() > 0)
                            @foreach($bill->restaurant->menuItems as $menuItem)
                                <div class="rs-flex rs-justify-between rs-items-center" style="border-bottom: 1px solid var(--border-light); padding: 0.5rem 0;">
                                    <div>
                                        <div class="rs-font-medium">{{ $menuItem->name }}</div>
                                        <div class="rs-text-xs rs-text-secondary">৳{{ number_format($menuItem->price, 2) }}</div>
                                    </div>
                                    <form method="POST" action="{{ route('bills.items.store', $bill) }}" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $menuItem->name }}">
                                        <input type="hidden" name="price" value="{{ $menuItem->price }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="rs-btn rs-btn-primary rs-btn-sm">+ Add to Split</button>
                                    </form>
                                </div>
                            @endforeach
                        @endif

                        @if($bill->custom_menu)
                            @foreach($bill->custom_menu as $customItem)
                                <div class="rs-flex rs-justify-between rs-items-center" style="border-bottom: 1px solid var(--border-light); padding: 0.5rem 0;">
                                    <div>
                                        <div class="rs-font-medium">{{ $customItem['name'] }}</div>
                                        <div class="rs-text-xs rs-text-secondary">৳{{ number_format($customItem['price'], 2) }}</div>
                                    </div>
                                    <form method="POST" action="{{ route('bills.items.store', $bill) }}" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $customItem['name'] }}">
                                        <input type="hidden" name="price" value="{{ $customItem['price'] }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="rs-btn rs-btn-primary rs-btn-sm">+ Add to Split</button>
                                    </form>
                                </div>
                            @endforeach
                        @endif

                        @if(!$bill->restaurant && empty($bill->custom_menu))
                            <p class="rs-text-secondary rs-text-center rs-text-sm">Menu is empty. Add an item above to get started.</p>
                        @endif
                    </div>
                </div>

                {{-- Items Section --}}
                <div class="rs-card">
                    <div class="rs-card-header rs-flex rs-justify-between rs-items-center" style="flex-wrap: wrap; gap: 1rem;">
                        <h3 class="rs-card-title" style="margin: 0;">3. Ordered Items (Active Split)</h3>
                        <div class="rs-flex rs-gap-2">
                            <button data-modal-target="add-item-modal" class="rs-btn rs-btn-secondary rs-btn-sm">+ Manual Add</button>
                        </div>
                    </div>

                    @if($bill->items->isEmpty())
                        <p class="rs-text-secondary rs-text-center rs-py-4">No items added yet.</p>
                    @else
                        <div class="rs-table-wrapper">
                            <table class="rs-table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Price</th>
                                        <th>Shared By</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->items as $item)
                                        <tr>
                                            <td>
                                                <div class="rs-font-medium">{{ $item->name }}</div>
                                                <div class="rs-text-xs rs-text-secondary">Qty: {{ $item->quantity }}</div>
                                            </td>
                                            <td>৳{{ number_format($item->total_price, 2) }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('bills.items.assign', [$bill, $item]) }}" id="assign-form-{{ $item->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="rs-flex rs-gap-3" style="flex-wrap: wrap;">
                                                        @foreach($bill->participants as $participant)
                                                            <label class="rs-flex rs-items-center rs-gap-2" style="cursor: pointer; font-size: 0.85rem;">
                                                                <input type="checkbox" name="participants[]" value="{{ $participant->id }}" class="rs-checkbox"
                                                                    onchange="document.getElementById('assign-form-{{ $item->id }}').submit();"
                                                                    {{ $item->participants->contains($participant->id) ? 'checked' : '' }}>
                                                                {{ $participant->name }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="rs-text-right">
                                                <form method="POST" action="{{ route('bills.items.destroy', [$bill, $item]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rs-text-danger" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;">&times;</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            {{-- RIGHT COLUMN: Breakdown & Receipt --}}
            <div class="rs-flex-col rs-gap-6">
                
                {{-- Final Receipt --}}
                <div class="rs-card" style="background-color: var(--surface-alt); border-color: var(--accent-color);">
                    <div class="rs-card-header">
                        <h3 class="rs-card-title rs-text-accent" style="margin: 0; font-family: var(--font-serif); font-size: 1.75rem;">🧾 The Bill</h3>
                    </div>

                    <div class="rs-flex-col rs-gap-4 rs-mb-6">
                        @foreach($bill->participants as $participant)
                            <div class="rs-flex rs-justify-between rs-items-end" style="border-bottom: 1px dashed var(--border-color); padding-bottom: 0.5rem;">
                                <div>
                                    <div class="rs-font-bold">{{ $participant->name }}</div>
                                    <div class="rs-text-xs rs-text-secondary">
                                        Sub: ৳{{ number_format($participant->subtotal, 2) }} 
                                        | Tax: ৳{{ number_format($participant->vat_amount + $participant->service_charge_amount, 2) }}
                                    </div>
                                    <button data-toggle-inline="breakdown-{{ $participant->id }}" class="rs-text-xs rs-text-brand" style="margin-top: 0.25rem; padding: 0; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                        View Cash Breakdown
                                    </button>
                                </div>
                                <div class="rs-flex-col rs-items-end rs-gap-1">
                                    <div class="rs-font-bold rs-text-primary" style="font-size: 1.1rem;">
                                        ৳{{ number_format($participant->total_owed, 2) }}
                                    </div>
                                    <form method="POST" action="{{ route('bills.participants.update', [$bill, $participant]) }}" class="rs-flex rs-items-center rs-gap-1">
                                        @csrf
                                        @method('PUT')
                                        <span class="rs-text-xs rs-text-secondary">Paid:</span>
                                        <input type="number" name="amount_paid" class="rs-input" value="{{ $participant->amount_paid > 0 ? $participant->amount_paid : '' }}" step="0.01" style="width: 5rem; padding: 0.15rem 0.25rem; font-size: 0.85rem;" placeholder="0">
                                        <button type="submit" class="rs-btn rs-btn-primary" style="padding: 0.15rem 0.5rem; font-size: 0.75rem;">Save</button>
                                    </form>
                                    @if($participant->amount_paid > 0)
                                        @if($participant->net_balance > 0)
                                            <span class="rs-text-xs rs-text-danger">Still owes: ৳{{ number_format($participant->remaining_owed, 2) }}</span>
                                        @elseif($participant->net_balance == 0)
                                            <span class="rs-text-xs rs-text-success">✅ Fully Paid</span>
                                        @else
                                            <span class="rs-text-xs rs-text-brand">Gets back: ৳{{ number_format($participant->return_amount, 2) }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            
                            {{-- BDT Note Breakdown (Hidden by default) --}}
                            <div id="breakdown-{{ $participant->id }}" style="display: none; background: var(--surface-color); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-light); margin-top: 0.25rem;">
                                @php
                                    $breakdownAmount = $participant->amount_paid > $participant->total_owed ? $participant->return_amount : $participant->remaining_owed;
                                    $isReturn = $participant->amount_paid > $participant->total_owed;
                                @endphp
                                <div class="rs-text-xs rs-font-semibold rs-mb-2">
                                    {{ $isReturn ? 'Notes to give back to them (Approx):' : 'Notes they need to hand over (Approx):' }}
                                </div>
                                <div class="rs-flex rs-gap-2" style="flex-wrap: wrap;">
                                    @php
                                        $breakdown = \App\Services\BDTCalculator::getNoteBreakdown($breakdownAmount);
                                    @endphp
                                    @foreach($breakdown as $note => $count)
                                        <span class="rs-badge rs-badge-neutral" style="font-size: 0.7rem;">
                                            ৳{{ $note }} &times; {{ $count }}
                                        </span>
                                    @endforeach
                                    @if(empty($breakdown))
                                        <span class="rs-text-xs rs-text-secondary">None</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rs-flex-col rs-gap-2" style="background: var(--surface-color); padding: 1rem; border-radius: var(--radius-md);">
                        <div class="rs-flex rs-justify-between rs-text-sm rs-text-secondary">
                            <span>Subtotal</span>
                            <span>৳{{ number_format($bill->total_subtotal, 2) }}</span>
                        </div>
                        <div class="rs-flex rs-justify-between rs-text-sm rs-text-secondary">
                            <span>VAT ({{ $bill->vat_percentage }}%)</span>
                            <span>+ ৳{{ number_format($bill->total_vat, 2) }}</span>
                        </div>
                        <div class="rs-flex rs-justify-between rs-text-sm rs-text-secondary">
                            <span>Service Charge</span>
                            <span>+ ৳{{ number_format($bill->total_service_charge, 2) }}</span>
                        </div>
                        @if($bill->discount_amount > 0)
                        <div class="rs-flex rs-justify-between rs-text-sm rs-text-danger">
                            <span>Discount</span>
                            <span>- ৳{{ number_format($bill->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="rs-flex rs-justify-between rs-items-center rs-mt-2" style="border-top: 2px solid var(--border-color); padding-top: 0.5rem;">
                            <span class="rs-font-bold rs-text-primary">GRAND TOTAL</span>
                            <span class="rs-font-bold rs-text-accent" style="font-size: 1.5rem; font-family: var(--font-serif);">
                                ৳{{ number_format($bill->calculated_grand_total, 2) }}
                            </span>
                        </div>
                        
                        {{-- Global Change Required --}}
                        @php
                            $returnAmounts = $bill->participants->pluck('return_amount')->filter(function($amount) { return $amount > 0; });
                        @endphp
                        @if($returnAmounts->isNotEmpty())
                        <div class="rs-mt-4" style="background: var(--surface-color); border: 1px dashed var(--accent-color); padding: 0.75rem; border-radius: var(--radius-sm);">
                            <div class="rs-font-bold rs-text-sm rs-text-accent rs-mb-2">🏦 Total Change to Request from Waiter</div>
                            <div class="rs-text-xs rs-text-secondary rs-mb-2">Calculated to perfectly hand back everyone's exact change without awkward sharing.</div>
                            <div class="rs-flex rs-gap-2" style="flex-wrap: wrap;">
                                @php
                                    $globalBreakdown = \App\Services\BDTCalculator::getStrictGlobalBreakdown($returnAmounts);
                                @endphp
                                @foreach($globalBreakdown as $note => $count)
                                    <span class="rs-badge rs-badge-accent" style="font-size: 0.8rem;">
                                        ৳{{ $note }} &times; {{ $count }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    
                </div>
                
                {{-- Bill Settings --}}
                <div class="rs-card">
                    <h4 class="rs-font-bold rs-mb-4" style="font-size: 1rem;">⚙️ Settings</h4>
                    <form method="POST" action="{{ route('bills.update', $bill) }}" class="rs-flex-col rs-gap-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="name" value="{{ $bill->name }}">
                        <input type="hidden" name="status" value="{{ $bill->status }}">
                        
                        <div class="rs-grid rs-grid-cols-2 rs-gap-3">
                            <div class="rs-form-group" style="margin: 0;">
                                <label class="rs-label" style="font-size: 0.75rem;">VAT (%)</label>
                                <input type="number" name="vat_percentage" class="rs-input" style="padding: 0.35rem; font-size: 0.9rem;" value="{{ $bill->vat_percentage }}" step="0.01">
                            </div>
                            <div class="rs-form-group" style="margin: 0;">
                                <label class="rs-label" style="font-size: 0.75rem;">S.Charge (%)</label>
                                <input type="number" name="service_charge_percentage" class="rs-input" style="padding: 0.35rem; font-size: 0.9rem;" value="{{ $bill->service_charge_percentage }}" step="0.01">
                            </div>
                            <div class="rs-form-group" style="margin: 0;">
                                <label class="rs-label" style="font-size: 0.75rem;">Flat S.C (৳)</label>
                                <input type="number" name="service_charge_amount" class="rs-input" style="padding: 0.35rem; font-size: 0.9rem;" value="{{ $bill->service_charge_amount }}" step="0.01">
                            </div>
                            <div class="rs-form-group" style="margin: 0;">
                                <label class="rs-label" style="font-size: 0.75rem;">Discount (৳)</label>
                                <input type="number" name="discount_amount" class="rs-input" style="padding: 0.35rem; font-size: 0.9rem;" value="{{ $bill->discount_amount }}" step="0.01">
                            </div>
                        </div>
                        <button type="submit" class="rs-btn rs-btn-secondary rs-btn-sm rs-btn-full">Update Settings</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- MODALS --}}

    {{-- Add Custom Item Modal --}}
    <div id="add-item-modal" class="rs-modal-overlay">
        <div class="rs-modal-content">
            <div class="rs-flex rs-justify-between rs-items-center rs-mb-4">
                <h3 class="rs-card-title" style="margin: 0;">Add Custom Item</h3>
                <button data-modal-close class="rs-text-secondary" style="font-size: 1.5rem;">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('bills.items.store', $bill) }}">
                @csrf
                <div class="rs-form-group">
                    <label class="rs-label">Item Name</label>
                    <input type="text" name="name" class="rs-input" required>
                </div>
                <div class="rs-grid rs-grid-cols-2 rs-gap-4">
                    <div class="rs-form-group">
                        <label class="rs-label">Price (BDT)</label>
                        <input type="number" name="price" class="rs-input" step="0.01" required>
                    </div>
                    <div class="rs-form-group">
                        <label class="rs-label">Quantity</label>
                        <input type="number" name="quantity" class="rs-input" value="1" min="1" required>
                    </div>
                </div>
                
                <div class="rs-form-group">
                    <label class="rs-label">Assign to (Optional)</label>
                    <div class="rs-flex rs-gap-3" style="flex-wrap: wrap;">
                        @foreach($bill->participants as $participant)
                            <label class="rs-flex rs-items-center rs-gap-2">
                                <input type="checkbox" name="participants[]" value="{{ $participant->id }}" class="rs-checkbox">
                                {{ $participant->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="rs-flex rs-justify-end rs-gap-3 rs-mt-6">
                    <button type="button" data-modal-close class="rs-btn rs-btn-secondary">Cancel</button>
                    <button type="submit" class="rs-btn rs-btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add From Menu Modal --}}
    @if($bill->restaurant && $bill->restaurant->menuItems->count() > 0)
    <div id="menu-modal" class="rs-modal-overlay">
        <div class="rs-modal-content" style="max-width: 700px;">
            <div class="rs-flex rs-justify-between rs-items-center rs-mb-4">
                <h3 class="rs-card-title" style="margin: 0;">{{ $bill->restaurant->name }} Menu</h3>
                <button data-modal-close class="rs-text-secondary" style="font-size: 1.5rem;">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('bills.items.storeFromMenu', $bill) }}">
                @csrf
                <div style="max-height: 60vh; overflow-y: auto; padding-right: 1rem;" class="rs-mb-6">
                    @foreach($bill->restaurant->menuItems as $index => $menuItem)
                        <div class="rs-flex rs-justify-between rs-items-center" style="border-bottom: 1px solid var(--border-light); padding: 0.75rem 0;">
                            <div>
                                <div class="rs-font-medium">{{ $menuItem->name }}</div>
                                <div class="rs-text-xs rs-text-secondary">৳{{ number_format($menuItem->price, 2) }}</div>
                            </div>
                            
                            <div class="rs-flex rs-items-center rs-gap-2">
                                <input type="hidden" name="menu_items[{{ $index }}][id]" value="{{ $menuItem->id }}">
                                <span class="rs-text-sm">Qty:</span>
                                <input type="number" name="menu_items[{{ $index }}][quantity]" class="rs-input" value="0" min="0" style="width: 4rem; padding: 0.25rem 0.5rem;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rs-flex rs-justify-end rs-gap-3">
                    <button type="button" data-modal-close class="rs-btn rs-btn-secondary">Cancel</button>
                    <button type="submit" class="rs-btn rs-btn-primary">Add Selected Items</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Scan Menu Modal --}}
    <div id="scan-menu-modal" class="rs-modal-overlay">
        <div class="rs-modal-content">
            <div class="rs-flex rs-justify-between rs-items-center rs-mb-4">
                <h3 class="rs-card-title" style="margin: 0;">📸 Scan Menu with AI</h3>
                <button data-modal-close class="rs-text-secondary" style="font-size: 1.5rem;">&times;</button>
            </div>
            
            <p class="rs-text-sm rs-text-secondary rs-mb-4">
                Take a picture of the physical menu, and our AI will automatically extract all the items and prices for you!
            </p>

            <form id="scan-form" method="POST" action="{{ route('bills.menu.scan', $bill) }}" enctype="multipart/form-data">
                @csrf
                <div class="rs-form-group">
                    <input type="file" name="menu_image" accept="image/*" capture="environment" class="rs-input" required style="padding: 1rem; border: 2px dashed var(--border-color); background: var(--surface-alt); text-align: center; cursor: pointer;">
                </div>
                
                <div id="scan-loading" style="display: none; text-align: center; margin: 1.5rem 0;">
                    <div style="font-size: 2rem; animation: pulse 1.5s infinite;">🤖</div>
                    <p class="rs-text-sm rs-text-brand rs-font-bold rs-mt-2">AI is reading the menu...</p>
                    <p class="rs-text-xs rs-text-secondary">This usually takes 3-5 seconds.</p>
                </div>

                <div class="rs-flex rs-justify-end rs-gap-3 rs-mt-6" id="scan-actions">
                    <button type="button" data-modal-close class="rs-btn rs-btn-secondary">Cancel</button>
                    <button type="submit" class="rs-btn rs-btn-primary" onclick="document.getElementById('scan-actions').style.display='none'; document.getElementById('scan-loading').style.display='block';">Scan Now</button>
                </div>
            </form>
        </div>
    </div>
    
    <style>
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>

</x-app-layout>
