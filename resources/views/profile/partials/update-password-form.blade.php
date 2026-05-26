<section>
    <header class="rs-mb-6">
        <h3 class="rs-card-title">Update Password</h3>
        <p class="rs-card-subtitle">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="rs-form-group">
            <x-input-label for="update_password_current_password" value="Current Password" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="rs-form-group">
            <x-input-label for="update_password_password" value="New Password" />
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="rs-form-group">
            <x-input-label for="update_password_password_confirmation" value="Confirm Password" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="rs-flex rs-items-center rs-gap-4 rs-mt-6">
            <x-primary-button>Save</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="rs-text-sm rs-text-secondary" style="color: var(--primary-color);">Saved.</p>
            @endif
        </div>
    </form>
</section>
