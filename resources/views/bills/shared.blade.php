<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - {{ $bill->name }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        
        <script>
            function copyToClipboard(text, btnElement) {
                const onSuccess = function() {
                    const originalText = btnElement.innerText;
                    btnElement.innerText = "Copied!";
                    btnElement.classList.add('rs-btn-success');
                    btnElement.classList.remove('rs-btn-secondary');
                    setTimeout(function() {
                        btnElement.innerText = originalText;
                        btnElement.classList.remove('rs-btn-success');
                        btnElement.classList.add('rs-btn-secondary');
                    }, 2000);
                };

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(text).then(onSuccess);
                } else {
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    textArea.style.position = "fixed";
                    textArea.style.opacity = "0";
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        onSuccess();
                    } catch (err) {
                        alert('Failed to copy.');
                    }
                    document.body.removeChild(textArea);
                }
            }
            
            function toggleBreakdown(id) {
                const el = document.getElementById(id);
                if (el.style.display === 'none') {
                    el.style.display = 'block';
                    el.style.animation = 'slideUp 0.3s ease-out forwards';
                } else {
                    el.style.display = 'none';
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased" style="background-color: var(--bg-color); color: var(--text-color); min-height: 100vh; padding: 1rem;">
        
        <div style="max-width: 600px; margin: 0 auto; padding-top: 2rem; padding-bottom: 4rem;" class="rs-slide-up">
            
            {{-- Header --}}
            <div class="rs-text-center rs-mb-6">
                <h1 style="font-family: var(--font-serif); font-size: 2.5rem; font-weight: 700; color: var(--primary-color); margin: 0;">
                    {{ $bill->name }}
                </h1>
                <p class="rs-text-secondary" style="margin: 0.5rem 0;">
                    @if($bill->restaurant) 🍽 {{ $bill->restaurant->name }} @else 🍽 Custom Split @endif
                    <br>
                    <span style="font-size: 0.85rem;">Organized by {{ $bill->user->name }} • {{ $bill->created_at->format('M d, Y') }}</span>
                </p>
            </div>

            {{-- Mobile Payments Alert --}}
            @if($bill->user->bkash_number || $bill->user->nagad_number)
                <div class="rs-card rs-mb-6" style="background: var(--surface-alt); border-color: var(--accent-color);">
                    <h3 class="rs-font-bold rs-text-primary rs-mb-3" style="font-size: 1.1rem; margin-top:0;">💸 Pay {{ $bill->user->name }} via Mobile Banking</h3>
                    <div class="rs-flex-col rs-gap-3">
                        @if($bill->user->bkash_number)
                            <div class="rs-flex rs-justify-between rs-items-center" style="background: var(--surface-color); padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                <div>
                                    <div class="rs-font-bold rs-text-sm" style="color: #e2136e;">bKash</div>
                                    <div class="rs-font-mono rs-text-primary">{{ $bill->user->bkash_number }}</div>
                                </div>
                                <button onclick="copyToClipboard('{{ $bill->user->bkash_number }}', this)" class="rs-btn rs-btn-secondary rs-btn-sm" style="padding: 0.25rem 0.75rem;">Copy</button>
                            </div>
                        @endif
                        @if($bill->user->nagad_number)
                            <div class="rs-flex rs-justify-between rs-items-center" style="background: var(--surface-color); padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                                <div>
                                    <div class="rs-font-bold rs-text-sm" style="color: #ed1c24;">Nagad</div>
                                    <div class="rs-font-mono rs-text-primary">{{ $bill->user->nagad_number }}</div>
                                </div>
                                <button onclick="copyToClipboard('{{ $bill->user->nagad_number }}', this)" class="rs-btn rs-btn-secondary rs-btn-sm" style="padding: 0.25rem 0.75rem;">Copy</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- The Receipt --}}
            <div class="rs-card" style="background-color: var(--surface-color); border: 1px solid var(--border-light);">
                <div class="rs-card-header rs-text-center">
                    <h3 class="rs-card-title rs-text-accent" style="margin: 0; font-family: var(--font-serif); font-size: 1.75rem;">🧾 The Receipt</h3>
                </div>

                <div class="rs-flex-col rs-gap-4 rs-mb-6">
                    @foreach($bill->participants as $participant)
                        <div class="rs-flex rs-justify-between rs-items-start" style="border-bottom: 1px dashed var(--border-color); padding-bottom: 0.75rem;">
                            <div>
                                <div class="rs-font-bold" style="font-size: 1.1rem;">{{ $participant->name }}</div>
                                <div class="rs-text-xs rs-text-secondary">
                                    Sub: ৳{{ number_format($participant->subtotal, 2) }} 
                                    | Tax: ৳{{ number_format($participant->vat_amount + $participant->service_charge_amount, 2) }}
                                </div>
                                <button onclick="toggleBreakdown('breakdown-{{ $participant->id }}')" class="rs-text-xs rs-text-brand" style="margin-top: 0.25rem; padding: 0; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                    View Cash Breakdown
                                </button>
                            </div>
                            
                            <div class="rs-flex-col rs-items-end rs-gap-1">
                                <div class="rs-font-bold rs-text-primary" style="font-size: 1.25rem;">
                                    ৳{{ number_format($participant->total_owed, 2) }}
                                </div>
                                
                                @if($participant->amount_paid > 0)
                                    @if($participant->net_balance > 0)
                                        <span class="rs-text-xs rs-text-danger" style="font-weight: 600;">Still owes: ৳{{ number_format($participant->remaining_owed, 2) }}</span>
                                    @elseif($participant->net_balance == 0)
                                        <span class="rs-text-xs rs-text-success" style="font-weight: 600;">✅ Fully Paid</span>
                                    @else
                                        <span class="rs-text-xs rs-text-brand" style="font-weight: 600;">Gets back: ৳{{ number_format($participant->return_amount, 2) }}</span>
                                    @endif
                                @else
                                    <span class="rs-text-xs rs-text-secondary" style="font-weight: 600;">Not paid yet</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- BDT Note Breakdown (Hidden by default) --}}
                        <div id="breakdown-{{ $participant->id }}" style="display: none; background: var(--surface-alt); padding: 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--border-light); margin-top: -0.5rem; margin-bottom: 0.5rem;">
                            @php
                                $breakdownAmount = $participant->amount_paid > $participant->total_owed ? $participant->return_amount : $participant->remaining_owed;
                                $isReturn = $participant->amount_paid > $participant->total_owed;
                            @endphp
                            <div class="rs-text-xs rs-font-semibold rs-mb-2">
                                {{ $isReturn ? 'Notes to get back (Approx):' : 'Notes to hand over (Approx):' }}
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

                <div class="rs-flex-col rs-gap-2" style="background: var(--surface-alt); padding: 1rem; border-radius: var(--radius-md);">
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
                </div>
                
            </div>
            
            <div class="rs-text-center rs-mt-6 rs-text-xs rs-text-secondary">
                Created with Bill Splitter
            </div>
            
        </div>
    </body>
</html>
