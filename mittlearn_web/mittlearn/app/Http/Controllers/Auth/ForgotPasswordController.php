<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpSession;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    public function forgotPassword()
    {
        return view("auth.passwords.email");
    }

    public function resetOtpFill(Request $request)
    {
        $userId = $request->id;
        $emailPattern = '/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/';
        $mobilePattern = '/^\d{10}$/';

        // Check if user ID matches email or mobile number pattern
        if (preg_match($emailPattern, $userId)) {
            $user = User::where('email', $userId)->first();
        } elseif (preg_match($mobilePattern, $userId)) {
            $user = User::where('mobile_no', $userId)->first();
        } else {
            return redirect()->route('forgot_password')->with('error', 'The username must be a valid Email or Mobile Number.');
        }

        if (!$user) {
            return back()->with('error', 'Email/Mobile Number is not registered');
        }
        if ($user->status == 0) {
            return back()->with('error', '❌ Your account is deactivated. Please contact the Mittlearn team. 🌧️');
        }

        $otpSession = OtpSession::where('session_id', $user->id)->first();
        if (!$otpSession) {
            $otp_session = new OtpSession;
            $otp_session->session_id = $user->id;
        }


        $otp = rand(100000, 999999);

        $otpSession = new OtpSession;
        $otpSession->session_id = $user->id;
        $otpSession->mobile_email = preg_match($emailPattern, $userId) ? $user->email : $user->mobile_no;
        $otpSession->otp = $otp;
        $otpSession->expire_at = now()->addMinutes(10);
        $otpSession->save();

        $sent = sendSms($user->mobile_no, $otp, 'User');

        if (!$sent) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
        return view('auth.passwords.forgot_password_otp_fill', ['userId' => $userId]);
    }

    public function forgotPasswordOtpCheck(Request $request)
    {
        $userId = $request->id;
        $otpArray = $request->input('otp');
        $otp = implode('', $otpArray);

        $attempt = OtpSession::where('mobile_email', $userId)
            ->where('otp_verified', 0)
            ->where('otp', $otp)
            ->where('expire_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();
        if ($attempt == null) {
            $error = 'Please enter a valid OTP or your OTP has expired.';
            return view("auth.passwords.forgot_password_otp_fill", ['userId' => $userId, 'error' => $error]);
        }

        return view('auth.passwords.reset', ['id' => $userId]);
    }
}
