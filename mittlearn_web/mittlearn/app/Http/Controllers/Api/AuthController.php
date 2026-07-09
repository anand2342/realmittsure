<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\LoginRequest;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Language;
use App\Models\Medium;
use App\Models\OtpSession;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\Setting;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserLoginLog;
use App\Models\UserRole;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'           => 'required|string',
                'mobile_no'      => 'required|min:10|max:10|unique:users,mobile_no',
                'email'          => 'nullable|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
                'user_type'      => 'required',
                'password'       => 'required|min:8|confirmed',
                'terms_accepted' => 'required|accepted',
            ]);

            if ($validator->fails()) {
                // Get first validation error message only
                $firstError = collect($validator->errors()->all())->first();

                return response()->json([
                    'status'  => false,
                    'message' => $firstError,
                    'data'    => null,
                ], 422);
            }

            $existingUser = User::where('mobile_no', $request->mobile_no)->where('status', 1)->first();

            if ($existingUser) {
                return $this->handleExistingUser($existingUser);
            }
            if ($request->filled('guest_user_id')) {
                $data = User::find($request->guest_user_id);

                if (!$data) {
                    return ApiResponseService::error(404, 'Guest user not found.');
                }
            } else {
                $data = new User;
            }

            $data->name = $request->name;
            $data->mobile_no = $request->mobile_no;
            $data->user_type = $request->user_type;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->validate_string = $request->password;
            $data->is_verified = 0;
            $data->source = 'register';

            $data->save();

            $selectedUserType = $request->input('user_type');
            $newData = $request->input('school_name');


            if ($selectedUserType == 'school_admin' && $newData) {
                $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                $schoolSelected = Schools::where('name', $newData)->first();
                if ($schoolSelected) {
                    return $this->sendError(config('constants.API_MSG.ALREADY_ACCOUNT_ERROR'), 422);
                } else {
                    $request->validate([
                        'school_name' => 'required|string|unique:schools,name'
                    ]);
                    $SchoolUserData = new User;
                    $SchoolUserData->name = $newData;
                    $SchoolUserData->is_verified = 0;
                    $SchoolUserData->save();

                    if (!$SchoolUserData) {
                        return redirect()->back()->with(['error' => 'Something went wrong']);
                    }

                    $userRole = new UserRole();
                    $userRole->user_id = $SchoolUserData->id;
                    $userRole->role_slug = 'school_admin';
                    $userRole->save();

                    $school = new Schools();
                    $school->user_id = $SchoolUserData->id;
                    $school->name = $newData;
                    $school->save();
                }

                UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $school->user_id, 'role' => 'b2c_student']);
            } elseif ($selectedUserType == 'school_teacher'  && $newData) {
                $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                $schoolSelected = Schools::where('name', $newData)->first();

                if ($schoolSelected) {
                    $schoolUserId = Schools::where('id', $schoolSelected->id)->value('user_id');
                } else {
                    $request->validate([
                        'school_name' => 'required|string|unique:schools,name'
                    ]);
                    $SchoolUserData = new User;
                    $SchoolUserData->name = $newData;
                    $SchoolUserData->is_verified = 0;
                    $SchoolUserData->save();

                    if (!$SchoolUserData) {
                        return redirect()->back()->with(['error' => 'Something went wrong']);
                    }

                    $userRole = new UserRole();
                    $userRole->user_id = $SchoolUserData->id;
                    $userRole->role_slug = 'school_admin';
                    $userRole->save();

                    $school = new Schools();
                    $school->user_id = $SchoolUserData->id;
                    $school->name = $newData;
                    $school->save();

                    $schoolUserId = $school->user_id;
                }
                UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'b2c_student']);
            } elseif ($selectedUserType === 'school_student' && $newData) {
                $roleSlug = Role::where('role_slug', 'school_student')->first();
                $schoolSelected = Schools::where('name', $newData)->first();

                if ($schoolSelected) {
                    $schoolUserId = Schools::where('id', $schoolSelected->id)->value('user_id');
                } else {
                    $request->validate([
                        'school_name' => 'required|string|unique:schools,name'
                    ]);
                    $SchoolUserData = new User;
                    $SchoolUserData->name = $newData;
                    $SchoolUserData->is_verified = 0;
                    $SchoolUserData->save();

                    if (!$SchoolUserData) {
                        return redirect()->back()->with(['error' => 'Something went wrong']);
                    }

                    $userRole = new UserRole();
                    $userRole->user_id = $SchoolUserData->id;
                    $userRole->role_slug = 'school_admin';
                    $userRole->save();

                    $school = new Schools();
                    $school->user_id = $SchoolUserData->id;
                    $school->name = $newData;
                    $school->save();

                    $schoolUserId = $school->user_id;
                }
                StudentDetails::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'parent_id' => $schoolUserId, 'class' => $request->class_id]);
                UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'school_student']);
            } else {
                $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                if ($request->you_are_here_for == 'for_academic_content') {
                    StudentDetails::create(['user_id' => $data->id, 'class' => $request->class_id]);
                } else if ($request->you_are_here_for == 'both') {
                    StudentDetails::create(['user_id' => $data->id, 'class' => $request->class_id]);
                } else {
                    StudentDetails::create(['user_id' => $data->id]);
                }
                UserAdditionalDetail::create(['user_id' => $data->id, 'role' => 'b2c_student']);
            }
            $userRole            = new UserRole();
            $userRole->user_id   = $data->id;
            $userRole->role_slug = $roleSlug->role_slug ?? 'b2c_student';
            $userRole->save();

            $landingUi = getUserClassLandingUi() ?? null;
            $roleSlugFinal  = $data->userRole->role_slug ?? null;


            if ($data->save()) {
                $this->sendOtpMobile($data->mobile_no);
                return ApiResponseService::success(config('constants.API_MSG.MOBILE_NO_VERIFY'), [
                    'user'        => $data,
                    'role'        => $roleSlugFinal,
                    'landing_ui'  => $landingUi,
                    'otp_message' => 'OTP sent successfully on your ' . $data->mobile_no,
                ]);
            }

            return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.REGISTRAION_ERROR'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.REGISTRAION_ERROR') . $e->getMessage());
        }
    }
    public function registerGuestUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'           => 'required|string',
                'mobile_no'      => 'required|unique:users,mobile_no',
                'email'          => 'nullable|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
                // 'user_type'      => 'required',
                'password'       => 'required|min:8|confirmed',
                'terms_accepted' => 'required|accepted',
            ]);

            if ($validator->fails()) {
                // Get first validation error message only
                $firstError = collect($validator->errors()->all())->first();

                return response()->json([
                    'status'  => false,
                    'message' => $firstError,
                    'data'    => null,
                ], 422);
            }

            $existingUser = User::where('mobile_no', $request->mobile_no)->where('status', 1)->first();

            if ($existingUser) {
                return $this->handleExistingUser($existingUser);
            }
            if ($request->filled('guest_user_id')) {
                $data = User::find($request->guest_user_id);

                if (!$data) {
                    return ApiResponseService::error(404, 'Guest user not found.');
                }
            } else {
                $data = new User;
            }
            $request->merge(['user_type' => 'b2c_student']);

            $data->name = $request->name;
            $data->mobile_no = $request->mobile_no;
            $data->user_type = $request->user_type;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->validate_string = $request->password;
            $data->is_verified = 0;
            $data->source = 'register';

            $data->save();

            $selectedUserType = $request->input('user_type');
            $newData = $request->input('school_name');


            if ($selectedUserType == 'school_admin' && $newData) {
                $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                $schoolSelected = Schools::where('name', $newData)->first();
                if ($schoolSelected) {
                    return $this->sendError(config('constants.API_MSG.ALREADY_ACCOUNT_ERROR'), 422);
                } else {
                    $request->validate([
                        'school_name' => 'required|string|unique:schools,name'
                    ]);
                    $SchoolUserData = new User;
                    $SchoolUserData->name = $newData;
                    $SchoolUserData->is_verified = 0;
                    $SchoolUserData->save();

                    if (!$SchoolUserData) {
                        return redirect()->back()->with(['error' => 'Something went wrong']);
                    }

                    $userRole = new UserRole();
                    $userRole->user_id = $SchoolUserData->id;
                    $userRole->role_slug = 'school_admin';
                    $userRole->save();

                    $school = new Schools();
                    $school->user_id = $SchoolUserData->id;
                    $school->name = $newData;
                    $school->save();
                }

                UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $school->user_id, 'role' => 'b2c_student']);
            } elseif ($selectedUserType == 'school_teacher'  && $newData) {
                $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                $schoolSelected = Schools::where('name', $newData)->first();

                if ($schoolSelected) {
                    $schoolUserId = Schools::where('id', $schoolSelected->id)->value('user_id');
                } else {
                    $request->validate([
                        'school_name' => 'required|string|unique:schools,name'
                    ]);
                    $SchoolUserData = new User;
                    $SchoolUserData->name = $newData;
                    $SchoolUserData->is_verified = 0;
                    $SchoolUserData->save();

                    if (!$SchoolUserData) {
                        return redirect()->back()->with(['error' => 'Something went wrong']);
                    }

                    $userRole = new UserRole();
                    $userRole->user_id = $SchoolUserData->id;
                    $userRole->role_slug = 'school_admin';
                    $userRole->save();

                    $school = new Schools();
                    $school->user_id = $SchoolUserData->id;
                    $school->name = $newData;
                    $school->save();

                    $schoolUserId = $school->user_id;
                }
                UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'b2c_student']);
            } elseif ($selectedUserType === 'school_student' && $newData) {
                $roleSlug = Role::where('role_slug', 'school_student')->first();
                $schoolSelected = Schools::where('name', $newData)->first();

                if ($schoolSelected) {
                    $schoolUserId = Schools::where('id', $schoolSelected->id)->value('user_id');
                } else {
                    $request->validate([
                        'school_name' => 'required|string|unique:schools,name'
                    ]);
                    $SchoolUserData = new User;
                    $SchoolUserData->name = $newData;
                    $SchoolUserData->is_verified = 0;
                    $SchoolUserData->save();

                    if (!$SchoolUserData) {
                        return redirect()->back()->with(['error' => 'Something went wrong']);
                    }

                    $userRole = new UserRole();
                    $userRole->user_id = $SchoolUserData->id;
                    $userRole->role_slug = 'school_admin';
                    $userRole->save();

                    $school = new Schools();
                    $school->user_id = $SchoolUserData->id;
                    $school->name = $newData;
                    $school->save();

                    $schoolUserId = $school->user_id;
                }
                StudentDetails::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'parent_id' => $schoolUserId, 'class' => $request->class_id]);
                UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'school_student']);
            } else {
                $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                if ($request->you_are_here_for == 'for_academic_content') {
                    StudentDetails::create(['user_id' => $data->id, 'class' => $request->class_id]);
                } else if ($request->you_are_here_for == 'both') {
                    StudentDetails::create(['user_id' => $data->id, 'class' => $request->class_id]);
                } else {
                    StudentDetails::create(['user_id' => $data->id]);
                }
                UserAdditionalDetail::create(['user_id' => $data->id, 'role' => 'b2c_student']);
            }
            $userRole            = new UserRole();
            $userRole->user_id   = $data->id;
            $userRole->role_slug = $roleSlug->role_slug ?? 'b2c_student';
            $userRole->save();

            $landingUi = getUserClassLandingUi() ?? null;
            $roleSlugFinal  = $data->userRole->role_slug ?? null;


            if ($data->save()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Registration completed successfully!',
                    'data'    => [
                        'token'      => $data->createToken($data->name . 'AuthToken')->plainTextToken,
                        'user'       => $data,
                        'role'       => $roleSlugFinal,
                        'landing_ui' => $landingUi,
                    ],
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => config('constants.API_MSG.REGISTRAION_ERROR'),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error.',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $this->formatCredentials($request->only('username', 'password'));

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                // Check if user account is inactive
                if ($user->status == 0) {
                    return ApiResponseService::error(
                        config('constants.API_STATUS_CODE.ERROR'),
                        '❌ Your account has been deactivated. Please contact the Mittlearn support team.'
                    );
                }
                // Restrict login if user cannot access digital content
                if ($user->can_login == 0) {
                    return ApiResponseService::error(
                        config('constants.API_STATUS_CODE.ERROR'),
                        '❌ Your account is restricted because you do not have access to the digital content.'
                    );
                }
                if (isset($credentials['email']) && ! $user->is_email_verified) {
                    $this->sendOtpEmail($user->email);
                    return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.EMAIL_ADDRESS_EXIST'), [
                        'otp_message' => 'OTP sent successfully on your ' . $user->email,
                    ]);
                }
                $userRole  = $user->userRole->role_slug ?? null;

                $allowedSingleLoginRoles = ['d2c_user', 'b2c_user', 'school_student'];
                $multipleLoginEnabled = Setting::where('field_name', 'multiple_login_enabled')->value('field_value');
                $token = $user->createToken($user->name . 'AuthToken')->plainTextToken;
                if ($multipleLoginEnabled == 0 && in_array($userRole, $allowedSingleLoginRoles)) {
                    // If already logged in on web, kill session
                    if ($user->platform === 'web' && $user->session_id) {
                        DB::table('sessions')->where('id', $user->session_id)->delete();
                        $user->session_id = null;
                    }
                }
                $user->api_token = $token ?? null;
                $user->platform = 'app';
                $user->save();


                $landingUi = getUserClassLandingUi() ?? null;
                $allowedRoles = ['school_admin', 'school_teacher', 'school_student', 'b2c_student', 'd2c_user'];

                if (!in_array($userRole, $allowedRoles)) {
                    return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.ADMIN_ACCOUNT_LOGGIN_FAILED'));
                }
                logUserLogin($user, $userRole, $request, 'app');
                $userArray = $user->toArray();

                if (!empty($userArray['image'])) {
                    $userArray['image'] = 'https://mittlearn.com/storage/uploads/user/profile_image/' . $userArray['image'];
                } else {
                    $userArray['image'] = null; // or a default image URL if you want
                }

                return ApiResponseService::success(
                    config('constants.API_MSG.ACCOUNT_LOGGIN_SUCCESS'),
                    array_merge($userArray, [
                        'auth_token' => $token,
                        'role'       => $userRole,
                        'landing_ui' => $landingUi,
                    ])
                );
            }

            return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.INVALID_CREDENTIAL'));
        } catch (\Exception $e) {
            return ApiResponseService::error(500, message: config('constants.API_MSG.ACCOUNT_LOGGIN_FAILED') . $e->getMessage());
        }
    }

    public function loginOtp(Request $request)
    {
        try {
            $request->validate(['username' => 'required|string']);

            $username = $request->username;

            $user = $this->findUser($username);
            $userRole  = $user->userRole->role_slug ?? null;
            if ($userRole == 'super_admin') {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.ADMIN_ACCOUNT_LOGGIN_FAILED'));
            }
            if (! $user) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.NOT_FOUND_USER'));
            }
            // Check status and can_login
            if ($user->status == 0) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), 'Your account has been deactivated. Please contact the admin.');
            }

            if ($user->can_login == 0) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), 'Your account is restricted from accessing digital content.');
            }
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $otp = $this->sendOtpEmail($username);
            } else {
                $otp = $this->sendOtpMobile($username);
            }

            return ApiResponseService::success('OTP sent successfully on your ' . $username, [
                'username' => $username,
                'otp'      => $otp,
            ]);
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.OTP_SENT_FAILED') . $e->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username'           => 'required|string',
                'otp'      => 'required|digits:6',

            ]);

            if ($validator->fails()) {
                // Get first validation error message only
                $firstError = collect($validator->errors()->all())->first();

                return response()->json([
                    'status'  => false,
                    'message' => $firstError,
                    'data'    => null,
                ], 422);
            }

            $username = $request->username;
            $user     = $this->findUser($username);
            if (! $user) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.NOT_FOUND_USER'));
            }
            // Check status and can_login
            if ($user->status == 0) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), 'Your account has been deactivated. Please contact the admin.');
            }

            if ($user->can_login == 0) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), 'Your account is restricted from accessing digital content. Please contact the admin.');
            }


            $otpSession = OtpSession::where('mobile_email', $username)
                ->where('otp_verified', 0)
                ->where('expire_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if (! $otpSession) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.OTP_INVALID_EXPIRED'));
            }

            if ($otpSession->otp === $request->otp) {
                $otpSession->otp_verified = 1;
                $otpSession->save();
                Auth::login($user);
                $token     = $user->createToken($user->name . 'AuthToken')->plainTextToken;
                $landingUi = getUserClassLandingUi() ?? null;
                $userRole  = $user->userRole->role_slug ?? null;
                logUserLogin($user, $userRole, $request, 'app');
                if (!empty($user['image'])) {
                    $user['image'] = 'https://mittlearn.com/storage/uploads/user/profile_image/' . $user['image'];
                } else {
                    $user['image'] = null; // or a default image URL if you want
                }
                $user->is_mobile_verified = 1; // Mark mobile as verified


                $allowedSingleLoginRoles = ['d2c_user', 'b2c_user', 'school_student'];
                $multipleLoginEnabled = Setting::where('field_name', 'multiple_login_enabled')->value('field_value');
                if ($multipleLoginEnabled == 0 && in_array($userRole, $allowedSingleLoginRoles)) {
                    // If already logged in on web, kill session
                    if ($user->platform === 'web' && $user->session_id) {
                        DB::table('sessions')->where('id', $user->session_id)->delete();
                        $user->session_id = null;
                    }
                }
                $user->api_token = $token ?? null;
                $user->platform = 'app';
                $user->save();

                return ApiResponseService::success(config('constants.API_MSG.ACCOUNT_LOGGIN_SUCCESS'), [
                    'user'       => $user,
                    'role'       => $userRole,
                    'landing_ui' => $landingUi,
                    'auth_token' => $token,
                ]);
            }

            return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.OTP_INVALID_EXPIRED'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.OTP_INVALID_EXPIRED') . $e->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['username' => 'required|string']);

            $username = $request->username;
            $user     = $this->findUser($username);
            if (! $user) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.NOT_FOUND_USER'));
            }

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $otp = $this->sendOtpEmail($username);
            } else {
                $otp = $this->sendOtpMobile($username);
            }

            return ApiResponseService::success('OTP sent successfully on your ' . $username, [
                'otp' => $otp,
            ]);
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'username'     => 'required|string',
                'otp'          => 'required|digits:6',
                'new_password' => 'nullable|string|min:8|confirmed',
            ]);

            $username = $request->username;
            $user     = $this->findUser($username);
            if (! $user) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.NOT_FOUND_USER'));
            }

            $otpSession = OtpSession::where('mobile_email', $username)
                ->where('otp_verified', 0)
                ->where('expire_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($otpSession && $otpSession->otp === $request->otp) {
                $otpSession->otp_verified = 1;
                $otpSession->save();

                if ($request->filled('new_password')) {
                    $user->password        = Hash::make($request->new_password);
                    $user->validate_string = $request->new_password;
                    $user->save();
                    return ApiResponseService::success(config('constants.API_MSG.PASSWORD_RESET_SUCCESS'));
                }

                return ApiResponseService::success(config('constants.API_MSG.OTP_VERIFIED_SUCCESS'), [
                    'username' => $username,
                ]);
            }

            return ApiResponseService::error(config('constants.API_STATUS_CODE.ERROR'), config('constants.API_MSG.OTP_INVALID_EXPIRED'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user(); // Authenticated user

            // Log logout time if login log ID is stored
            // if ($request->hasHeader('login-log-id')) {
            //     $logId = $request->header('login-log-id');
            //     UserLoginLog::where('id', $logId)->update(['logout_at' => now()]);
            // }

            // If you are using single-login logic
            $multipleLoginEnabled = Setting::where('field_name', 'multiple_login_enabled')->value('field_value');
            $allowedSingleLoginRoles = ['d2c_user', 'b2c_student', 'school_student'];
            $userRole = getUserRoles($user); // Adjust this if your helper accepts user

            if ($multipleLoginEnabled == 0 && in_array($userRole, $allowedSingleLoginRoles)) {
                $user->session_id = null;
                $user->save();
            }

            // Delete current access token
            $user->currentAccessToken()->delete();

            return ApiResponseService::success(config('constants.API_MSG.SUCCESS_LOGOUT'));
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.FAILED_LOGOUT') . ' ' . $e->getMessage());
        }
    }


    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password'    => 'required|string|min:8',           // Current password
                'newpassword' => 'required|string|min:8|confirmed', // New password with confirmation
            ]);

            // Get the bearer token from the request
            $token = $request->bearerToken();
            if (! PersonalAccessToken::findToken($token)) {
                return ApiResponseService::error(500, config('constants.API_STATUS_CODE.CALL_FAILED'));
            }

            $user = Auth::user();

            if (! Hash::check($request->password, $user->password)) {
                return ApiResponseService::error(500, config('constants.API_STATUS_CODE.CALL_FAILED'));
            }

            $user->update(['password' => Hash::make($request->newpassword), 'validate_string' => $request->newpassword]);
            return ApiResponseService::success(config('constants.API_MSG.PASSWORD_RESET_SUCCESS'), $user);
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }
    public function verifyEmailMobile(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
            ]);

            $token = $request->bearerToken();
            if (! PersonalAccessToken::findToken($token)) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), config('constants.API_MSG.INVALLID_TOKEN'));
            }

            $user     = Auth::user();
            $username = $request->username;

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                // Verify Email
                if (! $user->is_email_verified) {
                    $otp = $this->sendOtpEmail($username);
                    return ApiResponseService::success('OTP sent successfully on your ' . $username, [
                        'otp_message' => 'OTP sent successfully on your ' . $username,
                        'otp'         => $otp,
                    ]);
                }
                return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), 'Email is already verified.');
            } else {
                // Verify Mobile
                if (! $user->is_mobile_verified) {
                    $otp = $this->sendOtpMobile($username);
                    return ApiResponseService::success('OTP sent successfully on your ' . $username, [
                        'otp_message' => 'OTP sent successfully on your ' . $username,
                        'otp'         => $otp,
                    ]);
                }
                return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), 'Mobile number is already verified.');
            }
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_STATUS_CODE.CALL_FAILED') . $e->getMessage());
        }
    }

    public function verifyEmailMobileOtp(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'otp'      => 'required|digits:6',
            ]);

            $token = $request->bearerToken();
            if (! PersonalAccessToken::findToken($token)) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), config('constants.API_MSG.INVALLID_TOKEN'));
            }

            $user     = Auth::user();
            $username = $request->username;

            $otpSession = OtpSession::where('mobile_email', $username)
                ->where('otp_verified', 0)
                ->where('expire_at', '>', now())
                ->orderBy('created_at', 'desc')
                ->first();

            if (! $otpSession) {
                return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), config('constants.API_MSG.OTP_INVALID_EXPIRED'));
            }

            // Verify OTP
            if ($otpSession->otp === $request->otp) {
                $otpSession->otp_verified = 1;
                $otpSession->save();

                // Update the user's verification status based on the username type (email or mobile)
                if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                    $user->is_email_verified = 1; // Mark email as verified
                } else {
                    $user->is_mobile_verified = 1; // Mark mobile as verified
                }

                $user->save();
                $userRole = $user->userRole->role_slug ?? null;
                logUserLogin($user, $userRole, $request, 'app');

                return ApiResponseService::success(config('constants.API_MSG.OTP_VERIFIED_SUCCESS') . 'and ' . (filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile number') . ' verified.', $user);
            }

            return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), config('constants.API_MSG.OTP_INVALID_EXPIRED'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_STATUS_CODE.CALL_FAILED') . $e->getMessage());
        }
    }

    // Helper methods
    private function sendOtpEmail($email)
    {
        try {
            return $this->sendOtp($email, 'email');
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.OTP_SENT_FAILED') . $e->getMessage());
        }
    }

    private function sendOtpMobile($mobileNo)
    {
        try {
            return $this->sendOtp($mobileNo, 'mobile');
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.OTP_SENT_FAILED') . $e->getMessage());
        }
    }

    private function sendOtp($recipient, $type)
    {
        try {
            $otp = rand(100000, 999999);
            OtpSession::create([
                'session_id'   => session()->getId(),
                'otp'          => $otp,
                'mobile_email' => $recipient,
                'otp_verified' => 0,
                'expire_at'    => now()->addMinutes(5),
            ]);
            $sent = sendSms($recipient, $otp, 'User');
            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
            return $otp;
        } catch (\Exception $e) {
            throw new \Exception(config('constants.API_MSG.OTP_SENT_FAILED') . $e->getMessage());
        }
    }

    private function findUser($username)
    {
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $username)->first();
        } else {
            return User::where('mobile_no', $username)->first();
        }
    }

    private function handleExistingUser($user)
    {
        if ($user->is_mobile_verified == 1) {
            return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), config('constants.API_MSG.MOBILE_NO_EXIST'));
        } else {
            $this->sendOtpMobile($user->mobile_no);
            return ApiResponseService::error(config('constants.API_STATUS_CODE.CALL_FAILED'), config('constants.API_MSG.MOBILE_NO_VERIFY'), [
                'otp_message' => 'OTP sent successfully on your ' . $user->mobile_no,
            ]);
        }
    }

    // private function formatCredentials($credentials)
    // {
    //     if (filter_var($credentials['username'], FILTER_VALIDATE_EMAIL)) {
    //         return ['email' => $credentials['username'], 'password' => $credentials['password']];
    //     } else {
    //         return ['mobile_no' => $credentials['username'], 'password' => $credentials['password']];
    //     }
    // }


    private function formatCredentials($credentials)
    {
        $username = $credentials['username'];
        $password = $credentials['password'];

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $username, 'password' => $password];
        } elseif (preg_match('/^\d{10,15}$/', $username)) {
            return ['mobile_no' => $username, 'password' => $password];
        } else {
            return ['username' => $username, 'password' => $password];
        }
    }

    public function allSchoolList(Request $request)
    {
        try {
            $data = Schools::get(['name', 'id']);
            return ApiResponseService::success(config('constants.API_MSG.REC_FETCHED_SUCCESS'), $data);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }
    public function getMasterData(Request $request)
    {
        try {
            // Fetch data from master tables
            $masterData = [
                'classes'    => SchoolClass::whereBetween('id', [1, 23])->where('is_active', 1)->select('id', 'name')->get(),
                // 'classes'    => SchoolClass::where('is_active', 1)->select('id', 'name')->get(),
                'subjects'   => Subject::where('is_active', 1)->select('id', 'name')->get(),
                'boards'     => Board::where('is_active', 1)->select('id', 'name')->get(),
                'mediums'    => Medium::where('is_active', 1)->select('id', 'name')->get(),
                'bookSeries' => BookSeries::where('is_active', 1)->select('id', 'name')->get(),
                'languages'  => Language::where('is_active', 1)->select('id', 'name')->get(),
            ];

            return ApiResponseService::success(config('constants.API_MSG.REC_FETCHED_SUCCESS'), $masterData);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }
}
