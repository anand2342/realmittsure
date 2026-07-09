<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Models\OtpSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MittSubscriptionController extends Controller
{
    public $data = [];
    public $coreCtrl = '';
    public $planId;
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function subscription(Request $request)
    {
        $request->merge(['from' => 'web']);
        $this->data['data'] = $this->coreCtrl::getUserSubscription($request);

        if (!empty($this->data['data']['upgradedPlan'])) {
            session(['plan_id' => $this->data['data']['upgradedPlan']]);
        } else {
            session()->forget('plan_id');
        }

        if (!empty($this->data['data']['recomendedPlan'])) {
            session(['recomendedPlanId' => $this->data['data']['recomendedPlan']->id]);
        } else {
            session()->forget('recomendedPlanId');
        }
        return view('mittBunny.subscription.subscription', $this->data);
    }

    public function upgradePlanOtp(Request $request)
    {
        $userId = Auth::user();

        if (!$userId) {
            return response()->json(['error' => 'Mobile number is not registered.'], 400);
        }
        $otpSession = new OtpSession;
        $otp = rand(10000, 99999);
        $otpSession->otp = $otp;
        $otpSession->session_id = $userId->id;
        $otpSession->mobile_email = $userId->mobile_no;
        $otpSession->expire_at = now()->addMinutes(10);
        $otpSession->save();
        return response()->json(['otp' => $otp, 'message' => 'OTP has been sent to your mobile number.']);
    }

    public function subscriptionOtpCheck(Request $request)
    {
        $enteredOtp = $request->otp;
        $otp = OtpSession::where('session_id', Auth::id())->where('otp', $enteredOtp)->value('otp');
        $planId = $request->plan_id;
        if (!$otp || $enteredOtp != $otp) {
            return response()->json(['error' => 'Invalid OTP. Please try again.'], 400);
        }
        return response()->json(['success' => true, 'message' => 'OTP verified successfully. Redirecting...',  'planId' => base64_encode($planId)]);
    }

    public function resendOtp(Request $request)
    {
        $userId =  Auth::user();
        $attempt = OtpSession::where('mobile_email', $userId->mobile_no)
            ->orderBy('created_at', 'desc')
            ->first();
        if (now()->isAfter($attempt->expire_at)) {
            $newOtp = rand(10000, 99999);
            session(['otp_value' => $newOtp]);

            $attempt->otp = $newOtp;
            $attempt->updated_at = now();
            $attempt->save();

            // Return the new OTP
            return response()->json(['message' => 'OTP has been resend to your mobile number.', 'success' => true, 'otp' => $newOtp]);
        } else {
            return response()->json(['success' => false, 'message' => 'OTP is still valid. Please check your messages.']);
        }
    }
}
