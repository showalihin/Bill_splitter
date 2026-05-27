<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\EmailOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.verify-otp');
    }

    /**
     * Send (or resend) the OTP to the user's email.
     */
    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        $otp = EmailOtp::generateFor($request->user()->email);
        $user = $request->user();

        dispatch(function () use ($user, $otp) {
            try {
                \Illuminate\Support\Facades\Http::post(env('OTP_WEBHOOK_URL'), [
                    'secret' => env('OTP_WEBHOOK_SECRET', 'super_secret_bill_splitter_key_99!'),
                    'email' => $user->email,
                    'name' => $user->name,
                    'otp' => $otp->otp,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Background OTP resend failed: ' . $e->getMessage());
            }
        })->afterResponse();

        return back()->with('status', 'otp-sent');
    }

    /**
     * Verify the submitted OTP.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        $otpRecord = EmailOtp::where('email', $request->user()->email)
            ->latest()
            ->first();

        // No OTP found
        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'No verification code found. Please request a new one.']);
        }

        // Expired
        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            return back()->withErrors(['otp' => 'This code has expired. Please request a new one.']);
        }

        // Too many attempts
        if ($otpRecord->hasExceededAttempts()) {
            $otpRecord->delete();
            return back()->withErrors(['otp' => 'Too many failed attempts. Please request a new code.']);
        }

        // Wrong code
        if ($otpRecord->otp !== $request->otp) {
            $otpRecord->increment('attempts');
            $remaining = 5 - $otpRecord->attempts;
            return back()->withErrors(['otp' => "Invalid code. {$remaining} attempt(s) remaining."]);
        }

        // ✅ OTP is correct — verify the user
        $request->user()->markEmailAsVerified();
        $otpRecord->delete();

        return redirect()->intended(route('dashboard'))->with('status', 'Email verified successfully!');
    }
}
