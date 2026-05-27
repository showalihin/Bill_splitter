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
        $user = $request->user();

        dispatch(function () use ($user, $otp) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpVerificationMail($otp->otp, $user->name));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Background OTP resend failed: ' . $e->getMessage());
            }
        })->afterResponse();

        return back()->with('status', 'verification-link-sent');
    }
}
