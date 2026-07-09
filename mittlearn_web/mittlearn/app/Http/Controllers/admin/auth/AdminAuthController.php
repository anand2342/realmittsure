<?php

namespace App\Http\Controllers\admin\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Password;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    public function loginShow()
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');
        $remember = $request->has('remember');

        if (preg_match('/@/', $credentials['username'])) {
            $credentials = [
                'email' => $credentials['username'],
                'password' => $credentials['password'],
            ];
        } else {
            $credentials = [
                'mobile_no' => $credentials['username'],
                'password' => $credentials['password'],
            ];
        }
        if ($request->has('remember')) {
            // Store username/email and encrypted password in cookies
            $minutes = 43200; // Cookie expiration time (30 days)
            Cookie::queue('remember_username', $request->username, $minutes);
            Cookie::queue('remember_password', encrypt($credentials['password']), $minutes); // Encrypt the password
        } else {
            // Clear cookies if "Remember Me" is not checked
            Cookie::queue(Cookie::forget('remember_username'));
            Cookie::queue(Cookie::forget('remember_password'));
        }
        // Attempt login with credentials
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if ($user->is_admin) {
                // dd"""
                // NOTE: This is a temporary solution. These commands should be removed when a cron job is registered on the live server.
                Artisan::call('access-code:update-status');
                Artisan::call('subscriptions:update-status');
                Artisan::call('update:online-class-status');
                // Run the auto-logout logic after admin logs in
                Artisan::call('sessions:autologout');

                return redirect()->route('dashboard');
            } else {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors('You are not an admin');
            }
        }
        return redirect()->route('admin.login')->withErrors('Login details are incorrect');
    }

    public function registerShow()
    {
        return view('admin.auth.register');
    }
    public function resetPasswordShow()
    {
        return view('admin.auth.varify');
    }
    // Send Link via mail for Email verification
    public function resetPasswordMail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->Flogfirst();

        if (!$user) {
            return back()->with('error', 'Email is not registered.');
        }

        // Using Laravel's default password broker to send reset link
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? redirect()->route('admin.login')->with('status', 'Password reset link has been sent to your email.')
            : redirect()->route('admin.login')->withErrors(['email' => 'Failed to send reset link. Please try again later.']);
    }

    // Send OTP via SMS for mobile number verification (Dummy)
    public function resetPasswordOtp(Request $request)
    {
        $request->validate(['mobile' => 'required|digits:10']);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user) {
            return back()->with('error', 'Mobile number does not registered.');
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10); // OTP expires in 10 minutes
        $user->save();

        // Here, implement SMS sending logic via a thired party service

        return back()->with('status', 'OTP has been sent to your mobile number.');
    }

    public function logout()
    {
        $adminId = Session::get('admin_id');
        $parentSchool = Session::get('parent_school_id');

        if ($adminId || $parentSchool) {
            Session::forget('admin_id');
            Session::forget('parent_school_id');
        }
        Auth::logout();
        return redirect()->route('login');
    }
}
