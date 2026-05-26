<x-guest-layout>
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 3rem; height: 3rem; background: rgba(249, 115, 22, 0.15); border-radius: 50%; margin-bottom: 0.75rem;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>
        <h2 class="auth-title">Confirm password</h2>
        <p class="auth-subtitle" style="margin-bottom: 0;">This is a secure area. Please confirm your password before continuing.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input id="password" class="form-input @error('password') error @enderror" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-lg">
            Confirm
        </button>
    </form>
</x-guest-layout>
