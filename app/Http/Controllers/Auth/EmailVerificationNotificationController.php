<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $otp = \App\Models\EmailOtp::generateFor($request->user()->email);

        try {
            \Illuminate\Support\Facades\Mail::to($request->user()->email)->send(new \App\Mail\OtpVerificationMail($otp->otp, $request->user()->name));
            return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not resend the OTP email. Please check your SMTP configuration.');
        }
    }
}
