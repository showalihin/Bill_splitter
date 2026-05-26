<button {{ $attributes->merge(['type' => 'submit', 'class' => 'rs-btn rs-btn-primary']) }}>
    {{ $slot }}
</button>
