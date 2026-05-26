<x-guest-layout>
    <div class="rs-text-center rs-mb-6">
        <h2 class="rs-card-title">Verify Email</h2>
        <p class="rs-card-subtitle" style="margin-top: 0.5rem; text-align: left;">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="rs-alert rs-alert-success rs-mb-4">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="rs-flex rs-items-center rs-justify-between rs-mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Resend Verification Email
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rs-btn rs-btn-secondary rs-btn-sm">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
