{{-- Shared form fields for create & edit menu item --}}
<div class="rs-flex-col rs-gap-4">

    {{-- Name --}}
    <div class="rs-form-group">
        <label class="rs-label" for="name">Item Name <span style="color: var(--primary-color);">*</span></label>
        <input type="text" id="name" name="name" class="rs-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{ old('name', $menuItem->name ?? '') }}"
               placeholder="e.g. Chicken Kacchi, Pepperoni Pizza" required>
        @error('name') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Category + Unit (row) --}}
    <div class="rs-grid rs-grid-cols-2 rs-gap-4">
        <div class="rs-form-group">
            <label class="rs-label" for="category">Category</label>
            <input type="text" id="category" name="category" class="rs-input {{ $errors->has('category') ? 'is-invalid' : '' }}"
                   value="{{ old('category', $menuItem->category ?? '') }}"
                   placeholder="e.g. Main Course, Drinks">
            @error('category') <span class="rs-input-error">{{ $message }}</span> @enderror
        </div>
        <div class="rs-form-group">
            <label class="rs-label" for="unit">Unit</label>
            <input type="text" id="unit" name="unit" class="rs-input {{ $errors->has('unit') ? 'is-invalid' : '' }}"
                   value="{{ old('unit', $menuItem->unit ?? '') }}"
                   placeholder="e.g. plate, glass, piece">
            @error('unit') <span class="rs-input-error">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Price --}}
    <div class="rs-form-group">
        <label class="rs-label" for="price">Price (BDT) <span style="color: var(--primary-color);">*</span></label>
        <div style="position: relative;">
            <span style="position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%); color: var(--text-secondary); font-size: 0.9rem; pointer-events: none;">৳</span>
            <input type="number" id="price" name="price" class="rs-input {{ $errors->has('price') ? 'is-invalid' : '' }}"
                   value="{{ old('price', $menuItem->price ?? '') }}"
                   style="padding-left: 2rem;"
                   placeholder="0.00" step="0.01" min="0" required>
        </div>
        @error('price') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Description --}}
    <div class="rs-form-group">
        <label class="rs-label" for="description">Description</label>
        <textarea id="description" name="description" class="rs-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                  rows="2" placeholder="Short description (optional)...">{{ old('description', $menuItem->description ?? '') }}</textarea>
        @error('description') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Available toggle --}}
    <div class="rs-flex rs-items-center rs-gap-2">
        <input type="hidden" name="is_available" value="0">
        <input type="checkbox" id="is_available" name="is_available" value="1" class="rs-checkbox"
               {{ old('is_available', ($menuItem->is_available ?? true)) ? 'checked' : '' }}>
        <label for="is_available" class="rs-label" style="margin: 0; cursor: pointer; font-weight: normal;">
            Currently available
        </label>
    </div>

</div>
