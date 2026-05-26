{{-- Shared form fields for create & edit restaurant --}}
<div class="rs-flex-col rs-gap-4">

    {{-- Name --}}
    <div class="rs-form-group">
        <label class="rs-label" for="name">Restaurant Name <span style="color: var(--primary-color);">*</span></label>
        <input type="text" id="name" name="name" class="rs-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               value="{{ old('name', $restaurant->name ?? '') }}"
               placeholder="e.g. Kacchi Bhai, Pizza Hut" required>
        @error('name') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Cuisine --}}
    <div class="rs-form-group">
        <label class="rs-label" for="cuisine">Cuisine Type</label>
        <input type="text" id="cuisine" name="cuisine" class="rs-input {{ $errors->has('cuisine') ? 'is-invalid' : '' }}"
               value="{{ old('cuisine', $restaurant->cuisine ?? '') }}"
               placeholder="e.g. Bengali, Italian, Fast Food">
        @error('cuisine') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Address --}}
    <div class="rs-form-group">
        <label class="rs-label" for="address">Address</label>
        <input type="text" id="address" name="address" class="rs-input {{ $errors->has('address') ? 'is-invalid' : '' }}"
               value="{{ old('address', $restaurant->address ?? '') }}"
               placeholder="e.g. Banani, Dhaka">
        @error('address') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Phone --}}
    <div class="rs-form-group">
        <label class="rs-label" for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" class="rs-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
               value="{{ old('phone', $restaurant->phone ?? '') }}"
               placeholder="e.g. 01711-000000">
        @error('phone') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

    {{-- Description --}}
    <div class="rs-form-group">
        <label class="rs-label" for="description">Description</label>
        <textarea id="description" name="description" class="rs-input {{ $errors->has('description') ? 'is-invalid' : '' }}"
                  rows="3" placeholder="A short description of the restaurant...">{{ old('description', $restaurant->description ?? '') }}</textarea>
        @error('description') <span class="rs-input-error">{{ $message }}</span> @enderror
    </div>

</div>
