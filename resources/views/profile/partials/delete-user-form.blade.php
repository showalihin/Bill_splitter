<section>
    <header class="rs-mb-6">
        <h3 class="rs-card-title" style="color: var(--danger-color);">Delete Account</h3>
        <p class="rs-card-subtitle">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
    </header>

    <x-danger-button data-modal-target="confirm-user-deletion">
        Delete Account
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h3 class="rs-card-title">Are you sure you want to delete your account?</h3>
            <p class="rs-text-sm rs-text-secondary rs-mb-6">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <div class="rs-form-group">
                <x-input-label for="password" value="Password" class="sr-only" style="display: none;" />
                <x-text-input id="password" name="password" type="password" placeholder="Password" />
                <x-input-error :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="rs-flex rs-justify-end rs-gap-4 rs-mt-6">
                <x-secondary-button data-modal-close="true">
                    Cancel
                </x-secondary-button>

                <x-danger-button>
                    Delete Account
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
