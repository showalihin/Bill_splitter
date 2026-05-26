<x-guest-layout>
    <div class="rs-text-center rs-mb-6">
        <h2 class="rs-card-title">Reset password</h2>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="rs-form-group">
            <x-input-label for="email" value="Email address" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="rs-form-group">
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="rs-form-group">
            <x-input-label for="password_confirmation" value="Confirm Password" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="rs-flex rs-items-center rs-justify-end rs-mt-4">
            <x-primary-button>
                Reset Password
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
