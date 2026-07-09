<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpSession;
use App\Models\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function resetPassword()
    {
        return view('auth.passwords.reset');
    }

    public function resetPasswordSubmit(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $otpSession = OtpSession::where('mobile_email', $request->username)->first();

        $user = User::where('status',1)->find($otpSession->session_id);
        if($user){
            $user->password = Hash::make($request->password);
            $user->validate_string = $request->password;
            $user->save();
            return redirect()->route('login')->with(['success' => config('constants.FLASH_RESET_PASS_SUCCESS')]);
        }else{
            return redirect()->route('login')->with(['error' => config('constants.FLASH_ACCOUNT_DEACTIVATED')]);
        }

    }

}
