<x-guest-layout>
    <div class="rs-text-center rs-mb-6">
        <h2 class="rs-card-title">Welcome back</h2>
        <p class="rs-card-subtitle">Sign in to continue splitting bills</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="rs-mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="rs-alert rs-alert-error rs-fade-in rs-mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Google Sign In -->
    <a href="{{ route('auth.google') }}" class="rs-btn rs-btn-secondary rs-btn-full rs-mb-4" style="background-color: white;">
        <svg width="20" height="20" viewBox="0 0 24 24">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Continue with Google
    </a>

    <div class="rs-flex rs-items-center rs-justify-center rs-mb-4">
        <span style="color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase;">or</span>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="rs-form-group">
            <x-input-label for="email" value="Email address" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="rs-form-group">
            <div class="rs-flex rs-justify-between rs-items-center rs-mb-2">
                <x-input-label for="password" value="Password" style="margin-bottom: 0;" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.85rem; color: var(--text-secondary);">Forgot?</a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="rs-form-group" style="display: flex; align-items: center; gap: 0.5rem;">
            <input id="remember_me" type="checkbox" class="rs-checkbox" name="remember">
            <label for="remember_me" style="font-size: 0.85rem; color: var(--text-secondary); cursor: pointer;">
                Remember me
            </label>
        </div>

        <!-- Submit -->
        <x-primary-button class="rs-btn-full rs-btn-lg">
            Sign in
        </x-primary-button>
    </form>

    <div class="rs-text-center rs-mt-6" style="font-size: 0.9rem; color: var(--text-secondary);">
        Don't have an account? <a href="{{ route('register') }}" style="font-weight: 500;">Create one</a>
    </div>
</x-guest-layout>
