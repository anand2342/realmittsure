<?php

namespace App\Http\Controllers\Api\user;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Models\AccessCode;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends BaseController
{
    public function validateAccessCode(Request $request)
    {
        try {
            $request->validate([
                'access_code' => 'required|string',
            ]);
            $token = $request->bearerToken();
            if (!PersonalAccessToken::findToken($token)) {
                return $this->sendError(config('constants.API_MSG.INVALLID_TOKEN'),  406);

            }
            $user_id =  Auth::user()->id;
            $checkAlreadyUser = AccessCode::where('user_id', $user_id)->first();
            if ($checkAlreadyUser) {
                return $this->sendError(config('constants.API_MSG.ACCESS_CODE_ALREADY_USED'),  406);
            }
            $accessCode = AccessCode::where('access_code', $request->access_code)->first();
            if ($accessCode && !$accessCode->user_id) {
                $accessCode->user_id = auth()->id();
                $accessCode->status = 'active'; 
                $accessCode->save();
                return $this->sendSuccess([], config('constants.API_MSG.ACCESS_CODE_VALLIDATED'));
            }
            return $this->sendError(config('constants.API_MSG.ACCESS_CODE_INVALLID'),  406);
        }catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        }catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    
}
