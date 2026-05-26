<x-app-layout>
    <x-slot name="header">
        <h2>Start a New Bill Session</h2>
    </x-slot>

    <div class="rs-slide-up" style="max-width: 640px; margin: 0 auto;">
        <div class="rs-card">

            <div class="rs-mb-6">
                <h3 class="rs-card-title">Let's Split the Bill</h3>
                <p class="rs-card-subtitle">
                    Select a restaurant to load its menu (optional), and set the default tax rates.
                </p>
            </div>

            <form method="POST" action="{{ route('bills.store') }}">
                @csrf

                <div class="rs-form-group">
                    <label class="rs-label" for="name">Session Name <span style="color: var(--primary-color);">*</span></label>
                    <input type="text" id="name" name="name" class="rs-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name', 'Dinner Split - ' . now()->format('M d, Y')) }}"
                           placeholder="e.g. Friday Night Pizza" required>
                    @error('name') <span class="rs-input-error">{{ $message }}</span> @enderror
                </div>

                <div class="rs-form-group">
                    <label class="rs-label" for="restaurant_id">Select Restaurant (Optional)</label>
                    <select id="restaurant_id" name="restaurant_id" class="rs-input {{ $errors->has('restaurant_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Custom Bill (No Restaurant) --</option>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                {{ $restaurant->name }} {{ $restaurant->scope === 'global' ? '(Global)' : '(Private)' }}
                            </option>
                        @endforeach
                    </select>
                    @error('restaurant_id') <span class="rs-input-error">{{ $message }}</span> @enderror
                    <p class="rs-text-xs rs-text-secondary rs-mt-2">Selecting a restaurant allows you to quickly import items from its menu.</p>
                </div>

                <div class="rs-grid rs-grid-cols-2 rs-gap-4">
                    <div class="rs-form-group">
                        <label class="rs-label" for="vat_percentage">VAT (%)</label>
                        <input type="number" id="vat_percentage" name="vat_percentage" class="rs-input {{ $errors->has('vat_percentage') ? 'is-invalid' : '' }}"
                               value="{{ old('vat_percentage', '0') }}" min="0" max="100" step="0.01">
                        @error('vat_percentage') <span class="rs-input-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="rs-form-group">
                        <label class="rs-label" for="discount_amount">Flat Discount (BDT)</label>
                        <input type="number" id="discount_amount" name="discount_amount" class="rs-input {{ $errors->has('discount_amount') ? 'is-invalid' : '' }}"
                               value="{{ old('discount_amount', '0') }}" min="0" step="0.01">
                        @error('discount_amount') <span class="rs-input-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="rs-grid rs-grid-cols-2 rs-gap-4">
                    <div class="rs-form-group">
                        <label class="rs-label" for="service_charge_percentage">Service Charge (%)</label>
                        <input type="number" id="service_charge_percentage" name="service_charge_percentage" class="rs-input {{ $errors->has('service_charge_percentage') ? 'is-invalid' : '' }}"
                               value="{{ old('service_charge_percentage', '0') }}" min="0" max="100" step="0.01">
                        @error('service_charge_percentage') <span class="rs-input-error">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="rs-form-group">
                        <label class="rs-label" for="service_charge_amount">OR Flat S.Charge (BDT)</label>
                        <input type="number" id="service_charge_amount" name="service_charge_amount" class="rs-input {{ $errors->has('service_charge_amount') ? 'is-invalid' : '' }}"
                               value="{{ old('service_charge_amount', '0') }}" min="0" step="0.01">
                        @error('service_charge_amount') <span class="rs-input-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="rs-flex rs-justify-end rs-gap-3 rs-mt-8">
                    <a href="{{ route('bills.index') }}" class="rs-btn rs-btn-secondary">Cancel</a>
                    <button type="submit" class="rs-btn rs-btn-primary">
                        Start Splitting ➔
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
