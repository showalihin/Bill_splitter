<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Profile') }}</h2>
    </x-slot>

    <div class="rs-grid rs-gap-6" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">
        <div class="rs-card">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="rs-card">
            @include('profile.partials.update-password-form')
        </div>

        <div class="rs-card" style="border-color: rgba(181, 90, 90, 0.3);">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
