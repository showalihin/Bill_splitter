<x-guest-layout>
    <div class="rs-text-center rs-mb-6">
        <h2 class="rs-card-title">Check your email</h2>
        <p class="rs-card-subtitle" style="margin-top: 0.5rem; text-align: left;">We've sent a 6-digit verification code to <strong>{{ session('email') }}</strong>. Please enter it below to verify your account.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="rs-mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="rs-alert rs-alert-error rs-mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.otp.verify') }}">
        @csrf

        <!-- 6-Digit OTP Field -->
        <div class="rs-form-group">
            <x-input-label for="otp" value="Verification Code" />
            <x-text-input id="otp" 
                          type="text" 
                          name="otp" 
                          required 
                          autofocus 
                          maxlength="6"
                          style="letter-spacing: 0.5em; text-align: center; font-size: 1.5rem; font-family: var(--font-mono);" 
                          placeholder="••••••" />
            <x-input-error :messages="$errors->get('otp')" />
        </div>

        <div class="rs-mt-6">
            <x-primary-button class="rs-btn-full rs-btn-lg">
                Verify Account
            </x-primary-button>
        </div>
    </form>

    <!-- Resend OTP -->
    <div class="rs-mt-6 rs-text-center" style="font-size: 0.9rem; color: var(--text-secondary); border-top: 1px solid var(--border-light); padding-top: 1.5rem;">
        Didn't receive the code?
        <form method="POST" action="{{ route('verification.otp.send') }}" style="display: inline;">
            @csrf
            <button type="submit" style="color: var(--primary-color); font-weight: 500; cursor: pointer; border: none; background: none; font-family: inherit; font-size: inherit; padding: 0;">
                Resend Code
            </button>
        </form>
    </div>
</x-guest-layout>
