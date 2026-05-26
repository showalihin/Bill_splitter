<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'attempts',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Check if this OTP has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if max attempts exceeded (5 attempts max).
     */
    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= 5;
    }

    /**
     * Generate and store a new OTP for the given email.
     */
    public static function generateFor(string $email): self
    {
        // Delete any existing OTPs for this email
        static::where('email', $email)->delete();

        return static::create([
            'email' => $email,
            'otp' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
        ]);
    }
}
