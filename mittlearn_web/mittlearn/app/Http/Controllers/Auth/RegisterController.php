<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Models\AccessCodeOlympiad;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Course;
use App\Models\D2cAccessCode;
use App\Models\D2CDigitalContent;
use App\Models\Medium;
use App\Models\OtpSession;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolClass;
use App\Models\Schools;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\SubscriptionPurchase;
use App\Models\TrackUserVideoProgress;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use App\Models\UserRole;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */
    public $coreCtrl = '';
    use RegistersUsers;
    public $data = [];

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }


    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'validate_string' => $data['password'],
        ]);
    }

    public function index()
    {
        // $schools = Schools::where('is_verified_by_admin', 1)->get(['name', 'id']);
        $schools = Schools::get(['name', 'id']);
        $classes = SchoolClass::whereBetween('id', [1, 23])->pluck('name', 'id');
        return view("auth.register_after_user_type_add", ['schools' => $schools, 'classes' => $classes]);
    }
    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
    protected function store(Request $request)
    {
        if ($request->isOlympiadUser) {
            return $this->olympiadRegisterSubmit($request);
        } else {
            // dd($request->all());
            $request->validate([
                'name' => 'required|string',
                'mobile' => 'required|digits:10|unique:users,mobile_no',
                'userType' => 'required',
                'email' => 'nullable|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
                'password' => 'min:8',
                'password_confirmation' => 'required_with:password|same:password|min:8',
                'captcha' => 'required|captcha',
            ], ['captcha.captcha' => 'Invalid captcha code.']);
            try {
                if (!$request->terms_accepted) {
                    return redirect()->back()->with(['error' => 'You must accept the terms and conditions.']);
                }

                $userQuery = User::orderBy('id', 'DESC');
                if ($request->email) {
                    $userQuery->where('email', $request->email);
                }
                if ($request->mobile) {
                    $userQuery->where('mobile_no', $request->mobile);
                }
                if ($request->email && $request->mobile) {
                    $userQuery->where('email', $request->email);
                    $userQuery->orWhere('mobile_no', $request->mobile);
                }
                $user = $userQuery->first();

                if ($user) {
                    if ($user->is_mobile_verified == 0) {

                        $otpSession = OtpSession::where('session_id', $user->id)->first();
                        if (!$otpSession) {
                            $otpSession = new OtpSession;
                            $otpSession->session_id = $user->id;
                        }

                        $otp = rand(100000, 999999);
                        session(['otp_value' => $otp, 'id' => $user->mobile_no]);

                        $otpSession->otp = $otp;
                        $otpSession->mobile_email = $user->mobile_no;
                        $otpSession->expire_at = now()->addMinutes(10);
                        $otpSession->save();
                        // Send OTP to user mobile
                        $sent = sendSms($user->mobile_no, $otp, 'User');

                        if (!$sent) {
                            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
                        }
                        if ($otpSession) {
                            return view("auth.mobile-verify", ['data' => $user->mobile_no, 'userForm' => 'd2c_user']);
                        }
                    } else {
                        return redirect()->route('register')->with(['error' => 'User Already Registered']);
                    }
                }

                $data = new User;
                $data->name = $request->name;
                $data->mobile_no = $request->mobile;
                $data->user_type = $request->userType;
                $data->email = $request->email;
                $data->password = Hash::make($request->password);
                $data->validate_string = $request->password;
                $data->is_verified = 0;
                $data->source = 'register';

                $data->save();

                $selectedUserType = $request->input('userType');
                $newData = $request->input('schoolNameSearch');

                if ($selectedUserType == 'school_admin' && $newData) {
                    $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                    $schoolSelected = Schools::where('name', $newData)->first();
                    if ($schoolSelected) {
                        return redirect()->route('register')->with(['error' => 'You already have an account. Please log in to proceed.']);
                    } else {
                        $request->validate([
                            'schoolNameSearch' => 'required|string|unique:schools,name'
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

                    // StudentDetails::create(['user_id' => $data->id, 'school_id' => $school->id, 'role' => 'school_student', 'class' => $request->className]);
                    UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $school->user_id, 'role' => 'b2c_student']);
                } elseif ($selectedUserType == 'school_teacher'  && $newData) {
                    $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                    $schoolSelected = Schools::where('name', $newData)->first();

                    if ($schoolSelected) {
                        $schoolUserId = Schools::where('id', $schoolSelected->id)->value('user_id');
                    } else {
                        $request->validate([
                            'schoolNameSearch' => 'required|string|unique:schools,name'
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
                            'schoolNameSearch' => 'required|string|unique:schools,name'
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
                    StudentDetails::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'parent_id' => $schoolUserId, 'class' => $request->className]);
                    UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'school_student']);
                } else {
                    $roleSlug = Role::where('role_slug', 'b2c_student')->first();
                    if ($request->userComeFor == 'for_academic_content') {
                        StudentDetails::create(['user_id' => $data->id, 'class' => $request->className]);
                    } else if ($request->userComeFor == 'both') {
                        StudentDetails::create(['user_id' => $data->id, 'class' => $request->className]);
                    } else {
                        StudentDetails::create(['user_id' => $data->id]);
                    }
                    UserAdditionalDetail::create(['user_id' => $data->id, 'role' => 'b2c_student']);
                }
                $userRole = new UserRole();
                $userRole->user_id = $data->id;
                $userRole->role_slug = $roleSlug->role_slug;
                $userRole->save();


                $otpSession = OtpSession::where('session_id', $data->id)->where('otp_verified', 0)->where('expire_at', '>', now())->orderBy('created_at', 'desc')->first();

                if (!$otpSession) {
                    $otpSession = new OtpSession;
                    $otpSession->session_id = $data->id;
                }

                $otp = rand(100000, 999999);
                session(['otp_value' => $otp, 'id' => $data->mobile_no]);
                $otpSession->otp = $otp;
                $otpSession->mobile_email = $data->mobile_no;
                $otpSession->expire_at = now()->addMinutes(10);
                $otpSession->save();
                // Send OTP to user mobile
                $sent = sendSms($data->mobile_no, $otp, 'User');

                if (!$sent) {
                    return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
                }
                if ($otpSession) {
                    return view("auth.mobile-verify", ['data' => $data->mobile_no, 'userForm' => 'd2c_user']);
                }
            } catch (Exception $e) {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        }
    }

    public function registerOtpCheck(Request $request)
    {
        $data = $request->id;
        $otpArray = $request->input('otp');
        $otp = implode('', $otpArray);

        $attempt = OtpSession::where('mobile_email', $data)
            ->where('otp_verified', 0)
            ->where('otp', $otp)
            ->where('expire_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($attempt == null) {
            $error = 'Please enter a valid OTP or your OTP has expired.';
            return view("auth.mobile-verify", ['data' => $data, 'error' => $error, 'userForm' => 'd2c_user']);
        }
        $mobileVerification = User::where('mobile_no', $data)
            ->where('is_mobile_verified', 0)
            ->update(['is_mobile_verified' => 1]);

        OtpSession::where('mobile_email', $data)->where('otp_verified', 0)
            ->update(['otp_verified' => 1]);
        if ($request->userForm == 'd2c_user') {
            $user = User::where('mobile_no', $data)->where('status', 1)->first();
            Auth::login($user);

            $sent = sendSms($user->mobile_no, '', $user);

            $landingUi = getUserClassLandingUi();
            if ($landingUi == 'mittbunny') {
                $this->storeStudentClass();
                return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
            } else {
                $this->storeStudentOverview($request);
                return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
            }
        } else {
            $user = User::where('mobile_no', $data)->where('status', 1)->first();
            Auth::login($user);
            $sent = sendSms($user->mobile_no, '', $user);

            $landingUi = getUserClassLandingUi();
            if ($landingUi == 'mittbunny') {
                $this->storeStudentClass();
                return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
            } else {
                $this->storeStudentOverview($request);
                return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
            }
        }
        return redirect()->route('login')->with('success', 'Registered Successfully');
    }

    public function qrSignup()
    {
        // $schools = Schools::where('is_verified_by_admin', 1)->get(['name', 'id']);
        $schools = Schools::get(['name', 'id']);
        $classes = SchoolClass::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
        $series = BookSeries::where('is_active', 1)->pluck('name', 'id');
        $subjects = Subject::where('is_active', 1)->pluck('name', 'id');
        return view("auth.signup", ['schools' => $schools, 'classes' => $classes, 'series' => $series, 'subjects' => $subjects]);
    }


    public function getClasses(Request $request)
    {
        $schoolUserId = Schools::where('id', $request->input('school_id'))->value('user_id');
        $schoolClassIds = SchoolAssignedClass::where('school_id', $schoolUserId ?? null)->pluck('class_id')->toArray();
        $schoolClasses = SchoolClass::whereIn('id', $schoolClassIds)->where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
        if ($schoolUserId == '') {
            $allClasses = SchoolClass::pluck('name', 'id');
            return response()->json(['classes' => $allClasses]);
        }
        $classes = $schoolClasses;
        return response()->json(['classes' => $classes]);
    }

    public function fetchSeries(Request $request)
    {
        $schoolUserId = Schools::where('id', $request->input('school_id'))->value('user_id');
        if ($schoolUserId == '') {
            $allSeries = BookSeries::pluck('name', 'id');
            return response()->json(['series' => $allSeries]);
        } else {
            $seriesId = SchoolAssignedDigitalContent::where('school_id', $schoolUserId ?? null)->where('class_id', $request->class_id)->value('series_id');
            $series = BookSeries::where('id', $seriesId)->where('is_active', 1)->pluck('name', 'id');
            return response()->json(['series' => $series]);
        }
    }


    public function fetchSubjects(Request $request)
    {
        $schoolId = $request->school_id; // school table id
        $classId  = $request->class_id;
        $seriesId = $request->series_id;

        $subjects = collect();

        // ── Step 1: Try SchoolAssignedDigitalContent ──────────────────────────
        if ($schoolId) {
            $schoolUserId = Schools::where('id', $schoolId)->value('user_id');

            if ($schoolUserId) {
                $content = SchoolAssignedDigitalContent::where('school_id', $schoolUserId)->get();

                if ($content->isNotEmpty()) {
                    $subjectIds = [];
                    foreach ($content as $item) {
                        $ids        = explode(',', $item->subject_id);
                        $subjectIds = array_merge($subjectIds, $ids);
                    }
                    $subjectIds = array_unique(array_filter($subjectIds));
                    $subjects   = Subject::whereIn('id', $subjectIds)->pluck('name', 'id');
                }
            }
        }

        // ── Step 2: Fallback — use BookSeries JSON (class + series based) ─────
        if ($subjects->isEmpty() && $classId && $seriesId) {

            $bookSeries = BookSeries::where('id', $seriesId)->first();

            if ($bookSeries && !empty($bookSeries->class_subjects)) {
                // class_subjects column stores JSON like:
                // [{"class_id":"1","subject_ids":["1","2"]}, ...]
                $data = is_array($bookSeries->class_subjects)
                    ? $bookSeries->class_subjects
                    : json_decode($bookSeries->class_subjects, true);

                $subjectIds = [];
                foreach ($data as $entry) {
                    if ((string)$entry['class_id'] === (string)$classId) {
                        $subjectIds = array_merge($subjectIds, $entry['subject_ids']);
                    }
                }

                $subjectIds = array_unique(array_filter($subjectIds));

                if (!empty($subjectIds)) {
                    $subjects = Subject::whereIn('id', $subjectIds)->pluck('name', 'id');
                }
            }
        }

        return response()->json(['subjects' => $subjects]);
    }

    protected function storeQrRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:10|numeric',
            'email' => 'nullable|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8',
            'captcha' => 'required|captcha',
        ], ['captcha.captcha' => 'Invalid captcha code.']);
        if (!$request->terms_accepted) {
            return redirect()->back()->with(['error' => 'You must accept the terms and conditions.']);
        }

        $userQuery = User::orderBy('id', 'DESC');
        if ($request->email) {
            $userQuery->where('email', $request->email);
        }
        if ($request->mobile) {
            $userQuery->where('mobile_no', $request->mobile);
        }
        if ($request->email && $request->mobile) {
            $userQuery->where('email', $request->email);
            $userQuery->orWhere('mobile_no', $request->mobile);
        }
        $user = $userQuery->first();

        if ($user) {
            if ($user->is_mobile_verified == 0) {
                $existingClassQuery = UserClass::where('user_id', $user->id)
                    ->where('class_id', $request->className)
                    ->where('book_series_id', $request->seriesName)
                    ->where('subject_id', $request->subject);

                $existingClass = $existingClassQuery->first();

                if (!$existingClass) {
                    $userClassData = [
                        'user_id' => $user->id,
                        'class_id' => $request->className,
                        'book_series_id' => $request->seriesName,
                        'subject_id' => $request->subject,
                        'user_role' => 'school_student',
                    ];

                    UserClass::create($userClassData);
                }
                $otpSession = OtpSession::where('session_id', $user->id)->first();
                if (!$otpSession) {
                    $otpSession = new OtpSession;
                    $otpSession->session_id = $user->id;
                }

                $otp = rand(100000, 999999);
                session(['otp_value' => $otp, 'id' => $user->mobile_no]);
                $otpSession->otp = $otp;
                $otpSession->mobile_email = $user->mobile_no;
                $otpSession->expire_at = now()->addMinutes(10);
                $otpSession->save();
                $sent = sendSms($user->mobile_no, $otp, 'User');

                if (!$sent) {
                    return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
                }
                return view("auth.mobile-verify", ['data' => $user->mobile_no, 'userForm' => 'd2c_user']);
            } else {
                $existingClassQuery = UserClass::where('user_id', $user->id)
                    ->where('class_id', $request->className)
                    ->where('book_series_id', $request->seriesName)
                    ->where('subject_id', $request->subject);

                $existingClass = $existingClassQuery->first();
                // dd($existingClass);

                if (!$existingClass) {
                    $userClassData = [
                        'user_id' => $user->id,
                        'class_id' => $request->className,
                        'book_series_id' => $request->seriesName,
                        'subject_id' => $request->subject,
                        'user_role' => 'school_student',
                    ];

                    UserClass::create($userClassData);

                    $user = User::where('mobile_no', $user->mobile_no)->where('status', 1)->first();
                    $user->password = Hash::make($request->password);
                    $user->validate_string = $request->password;
                    $user->save();

                    Auth::login($user);
                    $landingUi = getUserClassLandingUi();
                    if ($landingUi == 'mittbunny') {
                        $this->storeStudentClass();
                        return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
                    } else {
                        $this->storeStudentOverview($request);
                        return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
                    }
                }
                return redirect()->route('register')->with(['error' => ' Already Registered']);
            }
        }
        $data = new User;
        $data->name = $request->name;
        $data->mobile_no = $request->mobile;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->validate_string = $request->password;
        $data->is_verified = 0;
        $data->source = 'signup';


        $data->save();

        $selectedOption = $request->input('schoolName');
        $newData = $request->input('schoolNameSearch');

        if ($selectedOption == null && $newData) {
            $roleSlug = Role::where('role_slug', 'school_student')->first();
            $schoolSelected = Schools::where('name', $newData)->first();

            if ($schoolSelected) {
                $schoolUserId = Schools::where('id', $schoolSelected->id)->value('user_id');
            } else {
                $request->validate([
                    'schoolNameSearch' => 'required|string|unique:schools,name'
                ]);

                $SchoolUserData = new User;
                $SchoolUserData->name = $newData;
                $SchoolUserData->is_verified = 0;
                $SchoolUserData->source = 'signup';
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
            StudentDetails::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'parent_id' => $schoolUserId, 'class' => $request->className]);
            UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'school_student']);
        } elseif ($selectedOption !== null && $selectedOption !== 'add-new') {
            $schoolUserId = Schools::where('id', $selectedOption)->value('user_id');
            $roleSlug = Role::where('role_slug', 'school_student')->first();
            UserAdditionalDetail::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'role' => 'school_student', 'series_id' => $request->seriesName]);
            StudentDetails::create(['user_id' => $data->id, 'school_id' => $schoolUserId, 'class' => $request->className]);
        } elseif ($selectedOption === null && $newData === null) {
            $roleSlug = Role::where('role_slug', 'b2c_student')->first();
            StudentDetails::create(['user_id' => $data->id,]);
            UserAdditionalDetail::create(['user_id' => $data->id, 'role' => 'b2c_student']);
        }

        $userRole = new UserRole();
        $userRole->user_id = $data->id;
        $userRole->role_slug = $roleSlug->role_slug;
        $userRole->save();

        $userClassData = [
            'user_id' => $data->id,
            'class_id' => $request->className,
            'book_series_id' => $request->seriesName,
            'subject_id' => $request->subject,
            'user_role' => 'school_student',
        ];

        UserClass::create($userClassData);


        $otpSession = OtpSession::where('session_id', $data->id)->where('otp_verified', 0)->where('expire_at', '>', now())->orderBy('created_at', 'desc')->first();

        if (!$otpSession) {
            $otpSession = new OtpSession;
            $otpSession->session_id = $data->id;
        }

        $otp = rand(100000, 999999);
        session(['otp_value' => $otp, 'id' => $data->mobile_no]);
        $otpSession->otp = $otp;
        $otpSession->mobile_email = $data->mobile_no;
        $otpSession->expire_at = now()->addMinutes(10);
        $otpSession->save();
        $sent = sendSms($data->mobile_no, $otp, 'User');

        if (!$sent) {
            return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
        }
        return view("auth.mobile-verify", ['data' => $data->mobile_no, 'userForm' => 'd2c_user']);
    }

    public function d2cMQrRegister($category, $medium, $class)
    {
        $sn = request()->query('msn', 1);
        $this->data['matchedCategory'] = Category::where('status', 1)->where('parent_id', 1)
            ->get()
            ->firstWhere(function ($cat) use ($category) {
                return Str::slug(Str::substr(preg_replace('/[^a-zA-Z]/', '', $cat->name), 0, 4)) === $category;
            });

        if (!$this->data['matchedCategory']) {
            abort(404, 'Category not found');
        }

        $className = base64_decode($class);
        $this->data['matchedClass'] = Classes::where('name', $className)->first();
        $this->data['matchedMedium'] = Medium::where('name', $medium)->first();
        $this->data['userForm'] = 'd2c_user';
        $this->data['sn'] = $sn; // Pass SN to view

        if (!$this->data['matchedClass']) {
            abort(404, 'Class not found');
        }

        return view("otherUsers.register", $this->data);
    }

    public function d2cQrRegister($category, $class)
    {
        if ($category == '2')
            $sn = request()->query('msn', 1);
        $this->data['matchedCategory'] = Category::where('status', 1)->where('parent_id', 1)
            ->get()
            ->firstWhere(function ($cat) use ($category) {
                return Str::slug(Str::substr(preg_replace('/[^a-zA-Z]/', '', $cat->name), 0, 4)) === $category;
            });

        if (!$this->data['matchedCategory']) {
            abort(404, 'Category not found');
        }

        $className = base64_decode($class);
        $this->data['matchedClass'] = Classes::where('name', $className)->first();
        $this->data['matchedMedium'] = null; // No medium for this route
        $this->data['userForm'] = 'd2c_user';
        $this->data['sn'] = $sn ?? 1; // Pass SN to view

        if (!$this->data['matchedClass']) {
            abort(404, 'Class not found');
        }

        return view("otherUsers.register", $this->data);
    }

    public function d2cQrRegisterSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:10|numeric',
            'email' => 'nullable|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'sometimes|min:8',
            'password_confirmation' => 'required_with:password|same:password|min:8',
            'captcha' => 'required|captcha',
            'sn' => 'nullable|integer|min:1', // Validate SN
        ], ['captcha.captcha' => 'Invalid captcha code.']);

        try {
            if (!$request->terms_accepted) {
                return redirect()->back()->with(['error' => 'You must accept the terms and conditions.']);
            }

            // Get SN from request or default to 1
            $sn = $request->input('sn', 1);

            // Fetch user if exists
            $user = User::where(function ($q) use ($request) {
                $q->where('mobile_no', $request->mobile);
                if ($request->email) {
                    $q->orWhere('email', $request->email);
                }
            })
                ->orderBy('id', 'DESC')
                ->first();

            if ($user) {
                // User exists but not verified
                if ($user->is_mobile_verified == 0) {
                    // Check if user class exists with medium and SN condition
                    $existingClassQuery = UserClass::where('user_id', $user->id)
                        ->where('class_id', $request->class_id)
                        ->where('category_id', $request->category_id)
                        ->where('sn', $sn); // Add SN condition

                    if ($request->filled('medium_id')) {
                        $existingClassQuery->where('medium_id', $request->medium_id);
                    } else {
                        $existingClassQuery->whereNull('medium_id');
                    }

                    $existingClass = $existingClassQuery->first();

                    if (!$existingClass) {
                        $userClassData = [
                            'user_id' => $user->id,
                            'class_id' => $request->class_id,
                            'category_id' => $request->category_id,
                            'user_role' => 'd2c_user',
                            'sn' => $sn, // Store SN
                        ];

                        if ($request->filled('medium_id')) {
                            $userClassData['medium_id'] = $request->medium_id;
                        }

                        UserClass::create($userClassData);
                    }

                    // Generate or update OTP
                    $otp = rand(100000, 999999);
                    session(['otp_value' => $otp, 'id' => $user->mobile_no]);

                    OtpSession::updateOrCreate(
                        ['session_id' => $user->id],
                        [
                            'otp' => $otp,
                            'mobile_email' => $user->mobile_no,
                            'expire_at' => now()->addMinutes(10),
                        ]
                    );
                    $sent = sendSms($user->mobile_no, $otp, 'User');

                    if (!$sent) {
                        return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
                    }
                    return view("auth.mobile-verify", ['data' => $user->mobile_no, 'userForm' => 'd2c_user']);
                }

                // User already verified — just handle class mapping if new
                $existingClassQuery = UserClass::where('user_id', $user->id)
                    ->where('class_id', $request->class_id)
                    ->where('category_id', $request->category_id)
                    ->where('sn', $sn); // Add SN condition

                if ($request->filled('medium_id')) {
                    $existingClassQuery->where('medium_id', $request->medium_id);
                } else {
                    $existingClassQuery->whereNull('medium_id');
                }

                $existingClass = $existingClassQuery->first();

                if (!$existingClass) {
                    $userClassData = [
                        'user_id' => $user->id,
                        'class_id' => $request->class_id,
                        'category_id' => $request->category_id,
                        'user_role' => 'd2c_user',
                        'sn' => $sn, // Store SN
                    ];

                    if ($request->filled('medium_id')) {
                        $userClassData['medium_id'] = $request->medium_id;
                    }

                    UserClass::create($userClassData);

                    $user = User::where('mobile_no', $user->mobile_no)->where('status', 1)->first();
                    $user->password = Hash::make($request->password);
                    $user->validate_string = $request->password;
                    $user->save();

                    Auth::login($user);
                    $landingUi = getUserClassLandingUi();
                    if ($landingUi == 'mittbunny') {
                        $this->storeStudentClass();
                        return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
                    } else {
                        $this->storeStudentOverview($request);
                        return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
                    }
                }

                return redirect()->back()->with(['error' => 'You are already registered. Please log in.']);
            }

            // New user
            $data = new User;
            $data->name = $request->name;
            $data->mobile_no = $request->mobile;
            $data->email = $request->email;
            $data->password = Hash::make($request->password);
            $data->validate_string = $request->password;
            $data->status = 1;
            $data->user_type = 'd2c_user';
            $data->is_verified = 1;
            $data->category_id = $request->category_id;
            $data->source = 'd2c_qr_code';
            $data->save();

            UserRole::create([
                'user_id' => $data->id,
                'role_slug' => 'd2c_user'
            ]);

            StudentDetails::create([
                'user_id' => $data->id,
                'class' => $request->class_id
            ]);

            UserAdditionalDetail::create([
                'user_id' => $data->id,
                'role' => 'd2c_user'
            ]);

            $userClassData = [
                'user_id' => $data->id,
                'class_id' => $request->class_id,
                'category_id' => $request->category_id,
                'user_role' => 'd2c_user',
                'sn' => $sn, // Store SN
            ];

            if ($request->filled('medium_id')) {
                $userClassData['medium_id'] = $request->medium_id;
            }

            UserClass::create($userClassData);

            $otp = rand(100000, 999999);
            session(['otp_value' => $otp, 'id' => $data->mobile_no]);

            OtpSession::updateOrCreate(
                ['session_id' => $data->id],
                [
                    'otp' => $otp,
                    'mobile_email' => $data->mobile_no,
                    'expire_at' => now()->addMinutes(10),
                ]
            );
            $sent = sendSms($data->mobile_no, $otp, 'User');

            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
            return view("auth.mobile-verify", ['data' => $data->mobile_no, 'userForm' => 'd2c_user']);
        } catch (\Exception $e) {
            \Log::error('D2C QR Registration Error: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Something went wrong']);
        }
    }

    public function storeStudentClass()
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }
        $studentDetails = [
            'class' => Auth::user()?->studentDetails?->className?->name ?? null,
        ];

        Session::put('student_class', $studentDetails);
    }
    public function storeStudentOverview(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $request->merge(['from' => 'web']);
        $courses = $this->coreCtrl::getUserMyCourses($request);
        if (! empty($courses)) {
            $totalAcadCourses    = $courses['academic_courses']->count();
            $totalNonAcadCourses = $courses['nonacademic_courses']->count();
        } else {
            $totalAcadCourses    = 0;
            $totalNonAcadCourses = 0;
        }

        $completedAcadCourses    = 0;
        $completedNonAcadCourses = 0;

        if (! empty($courses)) {
            foreach ($courses['academic_courses'] as $course) {
                $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id);
                $totalVideoDuration   = $userProgress->sum('video_duration');
                $totalWatchedDuration = $userProgress->sum('watched_duration');
                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $completedAcadCourses++;
                }
            }
            foreach ($courses['nonacademic_courses'] as $course) {
                $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course->id);
                $totalVideoDuration   = $userProgress->sum('video_duration');
                $totalWatchedDuration = $userProgress->sum('watched_duration');
                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $completedNonAcadCourses++;
                }
            }
        }
        $acadCompletionPercentage = ($totalAcadCourses > 0)
            ? ($completedAcadCourses / $totalAcadCourses) * 100
            : 0;

        $nonAcadCompletionPercentage = ($totalNonAcadCourses > 0)
            ? ($completedNonAcadCourses / $totalNonAcadCourses) * 100
            : 0;

        $subscribedCourses = SubscriptionPurchase::where('user_id', Auth::id())->where('status', 'active')->first();
        if ($subscribedCourses) {
            $courses                = json_decode($subscribedCourses->courses_json, true);
            $totalSubscribedCourses = count($courses['academic_courses']) + count($courses['non_academic_courses']);
        } else {
            $totalSubscribedCourses = 0;
        }

        $studentDetails = [
            'name'                        => ucwords($user->name),
            'image'                       => $user->image ? Storage::url('uploads/user/profile_image/' . $user->image) : asset('frontend/images/default-image.jpg'),
            'class'                       => Auth::user()?->studentDetails?->className?->name ?? null,
            'plan_start'                  => '12/02/2023',
            'plan_expiry'                 => '12/02/2023',
            'parent_name'                 => optional($user->studentDetails)->parent_name ? ucwords($user->studentDetails->parent_name) : 'N/A',
            'subscribed_courses'          => 4,
            'completed_tasks'             => 12,
            'totalSubscribedCourses'      => $totalSubscribedCourses ?? '0',
            'subscribedCourses'           => $subscribedCourses,
            'totalAcadCourses'            => $totalAcadCourses,
            'totalNonAcadCourses'         => $totalNonAcadCourses,
            'completedAcadCourses'        => $completedAcadCourses,
            'completedNonAcadCourses'     => $completedNonAcadCourses,
            'acadCompletionPercentage'    => round($acadCompletionPercentage, 2),
            'nonAcadCompletionPercentage' => round($nonAcadCompletionPercentage, 2),
        ];

        Session::put('student_overview', $studentDetails);
    }

    public function olympiadRegister()
    {
        return view("otherUsers.olympiad-register", $this->data);
    }

    public function olympiadRegisterSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:10|numeric',
            'email' => 'nullable|regex:/(.+)@(.+)\.(.+)/i',
            'access_code' => 'required',
        ]);

        try {

            if (!$request->terms_accepted) {
                return redirect()->back()->with(['error' => 'You must accept the terms and conditions.']);
            }

            $matchAccessCode = AccessCodeOlympiad::where('access_code', $request->access_code)->first();

            if (!$matchAccessCode) {
                return redirect()->back()->with([
                    'error' => 'Oops! That access code doesn’t match our records. Please double-check and try again. 🔐'
                ]);
            }

            // if ($matchAccessCode->status == 'active') {
            //     return redirect()->back()->with([
            //         'error' => 'Heads up! 🚨 This access code has already been used. Try a different one.'
            //     ]);
            // }


            // Fetch user if exists
            $user = User::where(function ($q) use ($request) {
                $q->where('mobile_no', $request->mobile);
                if ($request->email) {
                    $q->orWhere('email', $request->email);
                }
            })
                ->orderBy('id', 'DESC')
                ->first();

            if ($matchAccessCode->status == 'active') {
                // If access code already assigned to this user, allow direct login
                if ($matchAccessCode->user_id && isset($user) && $matchAccessCode->user_id == $user->id) {
                    Auth::login($user);
                    $landingUi = getUserClassLandingUi();
                    if ($landingUi == 'mittbunny') {
                        $this->storeStudentClass();
                        return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
                    } else {
                        $this->storeStudentOverview($request);
                        return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
                    }
                }

                // Otherwise, show error (used by another user)
                return redirect()->back()->with([
                    'error' => 'Heads up! 🚨 This access code has already been used by another user. Try a different one.'
                ]);
            }


            if ($user) {
                // User exists but not verified
                if ($user->is_mobile_verified == 0) {
                    // Check if user class exists with medium condition
                    $existingClassQuery = UserClass::where('user_id', $user->id)
                        ->where('class_id', $matchAccessCode->class_id)
                        ->where('category_id', '35');

                    $existingClass = $existingClassQuery->first();

                    if (!$existingClass) {
                        $userClassData = [
                            'user_id' => $user->id,
                            'class_id' => $matchAccessCode->class_id,
                            'category_id' => '35',
                            'user_role' => 'd2c_user',
                        ];

                        UserClass::create($userClassData);
                    }

                    // Generate or update OTP
                    $otp = rand(100000, 999999);
                    session(['otp_value' => $otp, 'id' => $user->mobile_no]);

                    OtpSession::updateOrCreate(
                        ['session_id' => $user->id],
                        [
                            'otp' => $otp,
                            'mobile_email' => $user->mobile_no,
                            'expire_at' => now()->addMinutes(10),
                        ]
                    );
                    $sent = sendSms($user->mobile_no, $otp, 'User');

                    if (!$sent) {
                        return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
                    }
                    return view("auth.mobile-verify", ['data' => $user->mobile_no, 'userForm' => 'd2c_user']);
                }

                $existingClassQuery = UserClass::where('user_id', $user->id)
                    ->where('class_id', $matchAccessCode->class_id)
                    ->where('category_id', '35');

                $existingClass = $existingClassQuery->first();
                // dd($existingClass);

                if (!$existingClass) {
                    $userClassData = [
                        'user_id' => $user->id,
                        'class_id' => $matchAccessCode->class_id,
                        'category_id' => '35',
                        'user_role' => 'd2c_user',
                    ];

                    UserClass::create($userClassData);

                    AccessCodeOlympiad::where('access_code', $matchAccessCode->access_code)->update([
                        'user_id' => $user->id,
                        'status' => 'active'
                    ]);

                    $user = User::where('mobile_no', $user->mobile_no)->where('status', 1)->first();
                    Auth::login($user);
                    $landingUi = getUserClassLandingUi();
                    if ($landingUi == 'mittbunny') {
                        $this->storeStudentClass();
                        return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
                    } else {
                        $this->storeStudentOverview($request);
                        return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
                    }
                }

                return redirect()->back()->with(['error' => 'You are already registered. Please log in.']);
            }

            // New user
            $data = new User;
            $data->name = $request->name;
            $data->mobile_no = $request->mobile;
            $data->email = $request->email;
            $data->password = Hash::make('Mitt@123');
            $data->validate_string = 'Mitt@123';
            $data->status = 1;
            $data->user_type = 'd2c_user';
            $data->category_id = '35';
            $data->is_verified = 1;
            $data->source = 'olympiad_register_form';
            $data->save();

            UserRole::create([
                'user_id' => $data->id,
                'role_slug' => 'd2c_user'
            ]);

            StudentDetails::create([
                'user_id' => $data->id,
                'class' => $matchAccessCode->class_id
            ]);

            UserAdditionalDetail::create([
                'user_id' => $data->id,
                'role' => 'd2c_user'
            ]);

            AccessCodeOlympiad::where('access_code', $matchAccessCode->access_code)->update([
                'user_id' => $data->id,
                'status' => 'active'
            ]);

            $userClassData = [
                'user_id' => $data->id,
                'class_id' => $matchAccessCode->class_id,
                'category_id' => '35',
                'user_role' => 'd2c_user',
            ];

            UserClass::create($userClassData);

            $otp = rand(100000, 999999);
            session(['otp_value' => $otp, 'id' => $data->mobile_no]);

            OtpSession::updateOrCreate(
                ['session_id' => $data->id],
                [
                    'otp' => $otp,
                    'mobile_email' => $data->mobile_no,
                    'expire_at' => now()->addMinutes(10),
                ]
            );
            $sent = sendSms($data->mobile_no, $otp, 'User');

            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
            return view("auth.mobile-verify", ['data' => $data->mobile_no, 'userForm' => 'd2c_user']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'Something went wrong']);
        }
    }




    // Demo for view manoj Mittl Access code
    public function d2cQrRegisterDemo()
    {
        $this->data['matchedClass'] = '24';
        $this->data['matchedCategory'] = '36';
        $this->data['matchedMedium'] = '';
        $this->data['userForm'] = 'd2c_user';
        if (!$this->data['matchedClass']) {
            abort(404, 'Class not found');
        }
        return view("otherUsers.demo-register", $this->data);
    }


    public function d2cQrRegisterDemoSubmit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|min:10|numeric',
            'email' => 'nullable|regex:/(.+)@(.+)\.(.+)/i',
            'access_code' => 'required',
        ]);

        try {
            if (!$request->terms_accepted) {
                return redirect()->back()->with(['error' => 'You must accept the terms and conditions.']);
            }

            $d2cDigitalContentId = D2CDigitalContent::where('class_id', $request->class_id)
                ->where('sub_category_id', $request->category_id)
                ->value('id');

            $matchAccessCode = D2cAccessCode::where('access_code', $request->access_code)->first();

            if (!$matchAccessCode) {
                return redirect()->back()->with([
                    'error' => 'Oops! That access code doesn’t match our records. Please double-check and try again. 🔐'
                ]);
            }

            // Check if access code belongs to another class/category
            if ($matchAccessCode->d2c_digital_content_id != $d2cDigitalContentId) {
                return redirect()->back()->with([
                    'error' => 'This access code belongs to a different content. 🚫 Please enter the correct code.'
                ]);
            }

            // if ($matchAccessCode->status == 1) {
            //     return redirect()->back()->with([
            //         'error' => 'Heads up! 🚨 This access code has already been used. Try a different one.'
            //     ]);
            // }

            // Fetch user if exists
            $user = User::where(function ($q) use ($request) {
                $q->where('mobile_no', $request->mobile);
                if ($request->email) {
                    $q->orWhere('email', $request->email);
                }
            })
                ->orderBy('id', 'DESC')
                ->first();


            if ($matchAccessCode->status == 'active') {
                // If access code already assigned to this user, allow direct login
                if ($matchAccessCode->user_id && isset($user) && $matchAccessCode->user_id == $user->id) {
                    Auth::login($user);
                    $landingUi = getUserClassLandingUi();
                    if ($landingUi == 'mittbunny') {
                        $this->storeStudentClass();
                        return redirect()->route('mittbunny.dashboard')->with('success', 'Login Successfully');
                    } else {
                        $this->storeStudentOverview($request);
                        return redirect()->route('up.dashboard')->with('success', 'Login Successfully');
                    }
                }

                // Otherwise, show error (used by another user)
                return redirect()->back()->with([
                    'error' => 'Heads up! 🚨 This access code has already been used by another user. Try a different one.'
                ]);
            }

            // New user
            $data = new User;
            $data->name = $request->name;
            $data->mobile_no = $request->mobile;
            $data->email = $request->email;
            $data->password = Hash::make('Mitt@123');
            $data->validate_string = 'Mitt@123';
            $data->status = 1;
            $data->user_type = 'd2c_user';
            $data->is_verified = 1;
            $data->category_id = $request->category_id;
            $data->source = 'd2c_qr_code_demo';
            $data->save();

            UserRole::create([
                'user_id' => $data->id,
                'role_slug' => 'd2c_user'
            ]);

            StudentDetails::create([
                'user_id' => $data->id,
                'class' => $request->class_id
            ]);

            UserAdditionalDetail::create([
                'user_id' => $data->id,
                'role' => 'd2c_user'
            ]);

            D2cAccessCode::updateOrCreate(
                ['id' => $matchAccessCode->id],
                [
                    'status' => 1,
                    'user_id' => $data->id,
                ]
            );

            $userClassData = [
                'user_id' => $data->id,
                'class_id' => $request->class_id,
                'category_id' => $request->category_id,
                'user_role' => 'd2c_user',
            ];

            if ($request->filled('medium_id')) {
                $userClassData['medium_id'] = $request->medium_id;
            }

            UserClass::create($userClassData);

            $otp = rand(100000, 999999);
            session(['otp_value' => $otp, 'id' => $data->mobile_no]);

            OtpSession::updateOrCreate(
                ['session_id' => $data->id],
                [
                    'otp' => $otp,
                    'mobile_email' => $data->mobile_no,
                    'expire_at' => now()->addMinutes(10),
                ]
            );
            $sent = sendSms($data->mobile_no, $otp, 'User');

            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
            return view("auth.mobile-verify", ['data' => $data->mobile_no, 'userForm' => 'd2c_user']);
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with(['error' => 'Something went wrong']);
        }
    }
}
