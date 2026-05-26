<section>
    <header class="rs-mb-6">
        <h3 class="rs-card-title">Profile Information</h3>
        <p class="rs-card-subtitle">Update your account's profile information and email address.</p>
    </header>


    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="rs-form-group">
            <x-input-label for="name" value="Name" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="rs-form-group">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>
        
        <h4 class="rs-font-bold rs-mt-6 rs-mb-4" style="font-size: 1.1rem; color: var(--primary-color);">📱 Mobile Payments (Optional)</h4>
        <p class="rs-text-sm rs-text-secondary rs-mb-4">Add your mobile banking numbers so friends can easily pay you back.</p>

        <div class="rs-form-group">
            <x-input-label for="bkash_number" value="bKash Number" />
            <x-text-input id="bkash_number" name="bkash_number" type="text" :value="old('bkash_number', $user->bkash_number)" placeholder="e.g. 017XXXXXXXX" />
            <x-input-error class="mt-2" :messages="$errors->get('bkash_number')" />
        </div>

        <div class="rs-form-group">
            <x-input-label for="nagad_number" value="Nagad Number" />
            <x-text-input id="nagad_number" name="nagad_number" type="text" :value="old('nagad_number', $user->nagad_number)" placeholder="e.g. 019XXXXXXXX" />
            <x-input-error class="mt-2" :messages="$errors->get('nagad_number')" />
        </div>

        <div class="rs-flex rs-items-center rs-gap-4 rs-mt-6">
            <x-primary-button>Save</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="rs-text-sm rs-text-secondary" style="color: var(--primary-color);">Saved.</p>
            @endif
        </div>
    </form>
</section>
