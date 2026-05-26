<x-guest-layout>
    <div class="rs-text-center rs-mb-6">
        <h2 class="rs-card-title">Reset password</h2>
        <p class="rs-card-subtitle" style="margin-top: 0.5rem; text-align: left;">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="rs-mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="rs-form-group">
            <x-input-label for="email" value="Email address" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="rs-flex rs-items-center rs-justify-between rs-mt-6">
            <a href="{{ route('login') }}" class="rs-btn rs-btn-secondary rs-btn-sm">Back</a>
            <x-primary-button>
                Email Password Reset Link
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
