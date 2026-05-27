<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate OTP
        $otp = EmailOtp::generateFor($user->email);

        // Send email in the background via secure Google Apps Script Webhook
        dispatch(function () use ($user, $otp) {
            try {
                \Illuminate\Support\Facades\Http::post(env('OTP_WEBHOOK_URL'), [
                    'secret' => env('OTP_WEBHOOK_SECRET', 'super_secret_bill_splitter_key_99!'),
                    'email' => $user->email,
                    'name' => $user->name,
                    'otp' => $otp->otp,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Background OTP send failed: ' . $e->getMessage());
            }
        })->afterResponse();

        Auth::login($user);

        return redirect(route('verification.notice'));
    }
}
