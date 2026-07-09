<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\FileController;
use App\Models\AccessCodeOlympiad;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use App\Models\OnlineClass;
use App\Models\OtpSession;
use App\Models\Planner;
use App\Models\PlannerOff;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolCompletedPlanner;
use App\Models\SchoolPlannerVisibility;
use App\Models\Schools;
use App\Models\StudentDetails;
use App\Models\SubscriptionPurchase;
use App\Models\TrackUserVideoProgress;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserApiController extends BaseController
{
    public $data     = [];
    public $res      = [];
    public $coreCtrl = '';
    public $fileCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
        $this->fileCtrl = FileController::class;
    }

    public function dashboard(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $this->data['onlineClasses']    = $this->coreCtrl::getUserDashboard($request);
            $this->data['courses']          = $this->coreCtrl::getUserMyCourses($request);
            $this->data['continueWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);

            $courses                               = $this->coreCtrl::getUserMyCourses($request);
            $this->data['totalAcadCourses']        = $courses['academic_courses']->count();
            $this->data['totalNonAcadCourses']     = $courses['nonacademic_courses']->count();
            $this->data['completedAcadCourses']    = 0;
            $this->data['completedNonAcadCourses'] = 0;
            foreach ($courses['academic_courses'] as $course) {
                // dd();
                $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id']);
                // dd("1");
                $totalVideoDuration   = $userProgress->sum('video_duration');
                $totalWatchedDuration = $userProgress->sum('watched_duration');
                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $this->data['completedAcadCourses']++;
                }
            }
            foreach ($courses['nonacademic_courses'] as $course) {
                $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id']);
                $totalVideoDuration   = $userProgress->sum('video_duration');
                $totalWatchedDuration = $userProgress->sum('watched_duration');
                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $this->data['completedNonAcadCourses']++;
                }
            }
            $this->data['acadCompletionPercentage'] = ($this->data['totalAcadCourses'] > 0)
                ? ($this->data['completedAcadCourses'] / $this->data['totalAcadCourses']) * 100
                : 0;

            $this->data['nonAcadCompletionPercentage'] = ($this->data['totalNonAcadCourses'] > 0)
                ? ($this->data['completedNonAcadCourses'] / $this->data['totalNonAcadCourses']) * 100
                : 0;

            // $this->data['subscribedCourses'] = SubscriptionPurchase::where('user_id', Auth::id())->where('status', 'active')->first();
            // if ($this->data['subscribedCourses']->transaction_id == 'ios-in-app') {
            //     $this->data['subscribedCourses'] = SubscriptionPurchase::where('user_id', Auth::id())->where('status', 'active')->get();
            // }

            // if ($this->data['subscribedCourses']) {
            //     $courses                              = json_decode($this->data['subscribedCourses']->courses_json, true);
            //     $this->data['totalSubscribedCourses'] = count($courses['academic_courses']) + count($courses['non_academic_courses']);
            // }

            $this->data['subscribedCourses'] = SubscriptionPurchase::where('user_id', Auth::id())
                ->where('status', 'active')
                ->first();

            // Check if subscription exists before checking transaction_id
            if ($this->data['subscribedCourses'] && $this->data['subscribedCourses']->transaction_id == 'ios-in-app') {
                $this->data['subscribedCourses'] = SubscriptionPurchase::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->get();
            }

            // Handle totalSubscribedCourses calculation
            if (!empty($this->data['subscribedCourses'])) {
                // If it's a collection (from ios-in-app case)
                if ($this->data['subscribedCourses'] instanceof \Illuminate\Support\Collection) {
                    $total = 0;

                    foreach ($this->data['subscribedCourses'] as $subscription) {
                        $courses = json_decode($subscription->courses_json, true);
                        $academic = isset($courses['academic_courses']) ? count($courses['academic_courses']) : 0;
                        $nonAcademic = isset($courses['non_academic_courses']) ? count($courses['non_academic_courses']) : 0;
                        $total += $academic + $nonAcademic;
                    }

                    $this->data['totalSubscribedCourses'] = $total;
                } else {
                    // If it's a single model instance
                    $courses = json_decode($this->data['subscribedCourses']->courses_json, true);
                    $academic = isset($courses['academic_courses']) ? count($courses['academic_courses']) : 0;
                    $nonAcademic = isset($courses['non_academic_courses']) ? count($courses['non_academic_courses']) : 0;

                    $this->data['totalSubscribedCourses'] = $academic + $nonAcademic;
                }
            }


            $courses = $this->coreCtrl::getUserMyCourses($request);

            $totalAcadCourses    = $courses['academic_courses']->count();
            $totalNonAcadCourses = $courses['nonacademic_courses']->count();

            $completedAcadCourses    = 0;
            $completedNonAcadCourses = 0;

            $academicWatchTime    = [];
            $nonAcademicWatchTime = [];

            // Initialize empty data array for all 12 months
            for ($i = 1; $i <= 12; $i++) {
                $academicWatchTime[$i]    = 0;
                $nonAcademicWatchTime[$i] = 0;
            }

            // Process Academic Courses
            foreach ($courses['academic_courses'] as $course) {
                $totalVideoDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id'])
                    ->sum('video_duration');
                $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id'])
                    ->sum('watched_duration');

                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $completedAcadCourses++;
                }

                // Fetch watch time per month
                $monthlyWatchTime = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id'])
                    ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
                    ->groupBy('month')
                    ->get();

                foreach ($monthlyWatchTime as $record) {
                    $academicWatchTime[$record->month] += round($record->total_watched / 60, 2); // Convert to minutes
                }
            }

            // Process Non-Academic Courses
            foreach ($courses['nonacademic_courses'] as $course) {
                $totalVideoDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id'])
                    ->sum('video_duration');
                $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id'])
                    ->sum('watched_duration');

                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $completedNonAcadCourses++;
                }

                // Fetch watch time per month
                $monthlyWatchTime = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id'])
                    ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
                    ->groupBy('month')
                    ->get();

                foreach ($monthlyWatchTime as $record) {
                    $nonAcademicWatchTime[$record->month] += round($record->total_watched / 60, 2); // Convert to minutes
                }
            }
            $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                ->sum('watched_duration'); // Total watched seconds

            $this->data['totalHours']   = floor($totalWatchedDuration / 3600);        // Convert seconds to hours
            $this->data['totalMinutes'] = floor(($totalWatchedDuration % 3600) / 60); // Remaining minutes

            // Prepare Data for Highcharts
            $this->data['timeSpendingsData'] = [
                'academic'     => array_values($academicWatchTime),
                'non_academic' => array_values($nonAcademicWatchTime),
            ];

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));

            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }


    public function vallidateAccessCode(Request $request)
    {
        try {
            $request->validate([
                'access_code' => 'required'
            ]);

            $matchAccessCode = AccessCodeOlympiad::where('access_code', $request->access_code)->first();

            if (!$matchAccessCode) {
                return $this->sendError('Oops! That access code doesn’t match our records. Please double-check and try again. 🔐', 406);
            }

            if ($matchAccessCode->status == 'active') {
                return $this->sendError('Heads up! 🚨 This access code has already been used. Try a different one.', 406);
            }

            $userClassData = [
                'user_id' => Auth::id(),
                'class_id' => $matchAccessCode->class_id,
                'category_id' => '35',
                'user_role' => 'd2c_user',
            ];

            UserClass::create($userClassData);

            $matchAccessCode->update([
                'user_id' => Auth::id(),
                'status' => 'active'
            ]);

            return $this->sendSuccess([],  '✅ Access code validated successfully! You’ve unlocked new content.');
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function myCourses(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $this->data['courses']          = $this->coreCtrl::getUserMyCourses($request);
            $this->data['continueWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));

            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function myCoursesListing(Request $request)
    {
        try {
            $request->validate([
                'slug' => 'required',
            ]);

            $request->merge(['from' => 'app']);
            $slug                  = $request->slug;
            $this->data['courses'] = $this->coreCtrl::getUserMyCoursesListing($request, $slug);
            // dd($this->data['courses']);
            $this->data['continueWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
            // $this->fileCtrl::saveUserVideoDurationOnPageLoad($this->data['data']['coursesChapter']);

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));

            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function onlineClasses(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $this->data['onlineClasses'] = $this->coreCtrl::getUserOnlineClass($request);
            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function getUserOnlineClassContent(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $id                                 = $request->id;
            $this->data['data']                 = OnlineClass::where('id', $id)->with(['instructor', 'class', 'subject'])->get();
            $this->data['onlineClassesContent'] = $this->coreCtrl::getUserOnlineClassContent($request, $id);
            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function digitalContent(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $this->data['digitalContent'] = $this->coreCtrl::getdigitalContent($request);

            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function digitalContentFiles(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $id                                = $request->id;
            $this->data['digitalContentFiles'] = $this->coreCtrl::getdigitalContentFiles($request, $id);

            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function userProfileDetails(Request $request)
    {
        try {
            $request->merge(['from' => 'app']);
            $user                                  = User::with('userAdditionalDetail')->where('id', Auth::id())->first();
            $school                                = Schools::where('user_id', $user->userAdditionalDetail->school_id)->first();
            $this->data['continueWatching']        = $this->coreCtrl::getUserMyCoursesContinueWatching($request);
            $courses                               = $this->coreCtrl::getUserMyCourses($request);
            $this->data['completedAcadCourses']    = 0;
            $this->data['completedNonAcadCourses'] = 0;

            // Ensure academic and non-academic courses are always set
            $courses = [
                'academic_courses'    => collect($courses['academic_courses'] ?? []),
                'nonacademic_courses' => collect($courses['nonacademic_courses'] ?? []),
            ];
            foreach ($courses['academic_courses'] as $course) {
                $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id']);
                $totalVideoDuration   = $userProgress->sum('video_duration');
                $totalWatchedDuration = $userProgress->sum('watched_duration');
                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $this->data['completedAcadCourses']++;
                }
            }

            foreach ($courses['nonacademic_courses'] as $course) {
                $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                    ->where('course_id', $course['id']);
                $totalVideoDuration   = $userProgress->sum('video_duration');
                $totalWatchedDuration = $userProgress->sum('watched_duration');
                if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                    $this->data['completedNonAcadCourses']++;
                }
            }

            // Handle subscription data safely
            $this->data['subscribedCourses'] = SubscriptionPurchase::where('user_id', Auth::id())
                ->where('status', 'active')
                ->first();
            $totalSubscribedCourses = 0;
            $totalCompletedCourses  = 0;
            if ($this->data['subscribedCourses']) {
                $subscribedCourses      = json_decode($this->data['subscribedCourses']->courses_json, true);
                $totalSubscribedCourses =
                    count($subscribedCourses['academic_courses'] ?? []) +
                    count($subscribedCourses['non_academic_courses'] ?? []);
            }
            $totalCompletedCourses = $this->data['completedAcadCourses'] + $this->data['completedNonAcadCourses'];

            if ($user) {
                return $this->sendSuccess(compact('user', 'school', 'totalSubscribedCourses', 'totalCompletedCourses'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            // dd($e);
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function updateUserProfileDetails(Request $request)
    {

        try {
            $request->validate([
                // 'address' => 'required',
                'name'    => 'required',
                // 'dob'     => 'required|date|before:today',
            ]);
            $userId         = Auth::id();
            $dob            = Carbon::parse($request->dob)->format('Y-m-d');
            $studentDetails = StudentDetails::where('user_id', $userId)->first();
            $userAdditonalDetails = UserAdditionalDetail::where('user_id', $userId)->first();
            $user = User::find($userId);
            if (!$studentDetails) {
                return redirect()->route('up.dashboard')->with('error', 'No details found for the Student.');
            }
            $postalCode = $request->postal_code ?? $request->pin ?? $request->pin_code;

            if ($studentDetails) {
                $studentDetails->dob = $dob;
                $studentDetails->postal_code = $postalCode;
                $studentDetails->state = $request->state;
                $studentDetails->city = $request->city;
                $studentDetails->save();
            }

            if ($userAdditonalDetails) {
                $userAdditonalDetails->dob = $dob;
                $userAdditonalDetails->address = $request->address;
                $userAdditonalDetails->postal_code = $postalCode;
                $userAdditonalDetails->state = $request->state;
                $userAdditonalDetails->city = $request->city;
                $userAdditonalDetails->save();
            } else {
                $newdetail = new UserAdditionalDetail;
                $newdetail->user_id = $userId;
                $newdetail->dob = $dob;
                $newdetail->address = $request->address;
                $newdetail->postal_code = $postalCode;
                $newdetail->state = $request->state;
                $newdetail->city = $request->city;
                $newdetail->save();
            }

            if ($user) {
                $user->name = $request->name;
                $user->save();
            }


            return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function subscription(Request $request)
    {
        try {
            $this->data['data'] = $this->coreCtrl::getUserSubscription($request);
            return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_ADD_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function upgradePlanOtp(Request $request)
    {
        try {
            $userId = Auth::user();
            if (! $userId) {
                return response()->json(['error' => 'Mobile number is not registered.'], 400);
            }
            $otpSession               = new OtpSession();
            $otp = rand(100000, 999999);
            $otpSession->otp          = $otp;
            $otpSession->session_id   = $userId->id;
            $otpSession->mobile_email = $userId->mobile_no;
            $otpSession->expire_at    = now()->addMinutes(10);
            $otpSession->save();
            $mobile = $userId->mobile_no;
            $sent = sendSms($mobile, $otp, 'User');
            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }

            return $this->sendSuccess(compact('otp', 'mobile'), 'OTP sent successfully on your ' . $mobile);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.OTP_SENT_FAILED'), $e->getMessage(), 406);
        }
    }

    public function subscriptionOtpCheck(Request $request)
    {
        try {
            $enteredOtp = (int) $request->otp; // Ensure it's numeric

            $otp = OtpSession::where('session_id', Auth::id())
                ->where('otp', $enteredOtp)->where('otp_verified', 0)->first();
            if (! $otp) {
                return $this->sendError(config('constants.API_MSG.OTP_INVALID_EXPIRED'), 406);
            }
            // Mark as verified and save
            $otp->otp_verified = 1;
            $otp->save();
            return $this->sendSuccess([Auth::user()], config('constants.API_MSG.OTP_VERIFIED_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }


    public function subscriptionResendOtp(Request $request)
    {
        try {
            $user    = Auth::user();
            $attempt = OtpSession::where('mobile_email', $user->mobile_no)->orderBy('created_at', 'desc')->first();

            $newOtp = rand(100000, 999999);
            session(['otp_value' => $newOtp]);
            $attempt->otp        = $newOtp;
            $attempt->updated_at = now();
            $attempt->save();
            $mobile = $user->mobile_no;
            $sent = sendSms($mobile, $newOtp, 'User');
            if (!$sent) {
                return redirect()->back()->with('error', 'Failed to send OTP. Please try again.');
            }
            return $this->sendSuccess(compact('newOtp', 'mobile'), 'OTP sent successfully on your ' . $mobile);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.OTP_SENT_FAILED'), $e->getMessage(), 406);
        }
    }

    public function userPlanner(Request $request)
    {
        try {

            $role     = getUserRoles();
            $schoolId = Auth::user()->userAdditionalDetail->school_id;
            $classId  = StudentDetails::where('user_id', Auth::id())
                ->value('class');
            $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->orderBy('series_id', 'asc')
                ->get();

            $mappedSubjects = []; // subject_id => series_id

            if ($schoolAssignedDigitalContent->isNotEmpty()) {
                foreach ($schoolAssignedDigitalContent as $content) {
                    $seriesId = $content->series_id;
                    $subjectIds = explode(',', $content->subject_id);

                    foreach ($subjectIds as $subjectId) {
                        if (!isset($mappedSubjects[$subjectId])) {
                            $mappedSubjects[$subjectId] = $seriesId;
                        }
                    }
                }
            }

            // Optional: extract just subject IDs (used in whereIn etc.)
            $schoolAssignedSubjects = array_keys($mappedSubjects);

            $classes = Planner::with(['class', 'subject', 'chapter'])
                ->when($role === "school_student", function ($query) use ($classId) {
                    // dd($classId);
                    if (! empty($classId)) {
                        $query->where('class_id', $classId);
                    }
                    if (! empty($schoolAssignedSubjects)) {
                        $query->whereIn('subject_id', $schoolAssignedSubjects);
                    }
                })
                ->orderBy('class_id')->get();
            // dd($classes);
            if ($classes->isEmpty()) {
                return $this->sendSuccess([], 'Your class planner not ready just yet! Please check back soon to see it in action!');
            }

            $getPlannerType = Planner::where('class_id', $classId)->whereIn('subject_id', $schoolAssignedSubjects)
                ->first();

            $plannerType = $getPlannerType ? $getPlannerType->type : null;
            if ($plannerType == null) {
                return $this->sendSuccess([], 'Your class planner not ready just yet! Please check back soon to see it in action!');
            }
            // View Daily Planner
            if ($plannerType == 'daily') {
                $userSeries   = getUserSchoolSeries();
                $plannerDates = Planner::where(function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId)
                        ->orWhereNull('school_id');
                })
                    ->where('series_id', $userSeries)
                    ->where('class_id', $classId)
                    ->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // If no planner dates are found, fallback to the current month's start and end date
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                $totalPlannerDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
                $currentDate      = now();

                // Weekday names for the header
                $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                // Get all dates between start and end date, excluding Sundays
                $allDates = [];
                $current  = new DateTime($startDate);
                $end      = new DateTime($endDate);

                while ($current <= $end) {
                    if ($current->format('w') != 0) { // Exclude Sunday (0 corresponds to Sunday)
                        $allDates[] = clone $current;     // Add non-Sunday date
                    }
                    $current->modify('+1 day');
                }

                $totalDays = count($allDates); // Total number of non-Sunday days

                // Fetch planner data
                $plannerData = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId)
                            ->orWhereNull('school_id');
                    })
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->get();

                // Fetch planner_offs data for the authenticated school
                $plannerOffs = PlannerOff::whereHas('planner', function ($query) use ($schoolId) {
                    $query->where('school_id', $schoolId)
                        ->orWhereNull('school_id'); // Include universal planners' holidays if needed
                })->pluck('date')->toArray();

                // Process planner data into day-wise structure
                $dayWiseData = [];
                foreach ($plannerData as $item) {
                    $plannedDate    = new DateTime($item->start_date);
                    $completionDate = new DateTime($item->completion_date);

                    for ($i = 0; $i < $item->allotted_days; $i++) {
                        $boxDate          = (clone $plannedDate)->modify("+$i days");
                        $boxDateFormatted = $boxDate->format('Y-m-d');

                        if ($boxDate->format('w') != 0 && ! in_array($boxDateFormatted, $plannerOffs)) { // Exclude Sunday and holiday dates
                            $day = array_search($boxDateFormatted, array_map(fn($date) => $date->format('Y-m-d'), $allDates));

                            if ($day !== false) {
                                $day += 1; // Convert to 1-based index for Blade usage

                                $boxClass = 'shiftBox';
                                if ($currentDate >= $boxDate && $currentDate <= $completionDate) {
                                    $boxClass .= ' lightgreen';
                                } elseif ($currentDate < $boxDate) {
                                    $boxClass .= ' lightorange';
                                } else {
                                    $boxClass .= ' lightred';
                                }

                                $existingChapters = $dayWiseData[$day][$item->subject_id] ?? [];
                                $duplicate        = false;

                                foreach ($existingChapters as $existingChapter) {
                                    if ($existingChapter['chapter_id'] == $item->chapter_id) {
                                        $duplicate = true;
                                        break;
                                    }
                                }

                                if (! $duplicate) {
                                    $title       = $item->chapter->chapter_name ?? 'No Chapter Name';
                                    $subjectName = $item->subject->name ?? 'No Subject';

                                    $dayWiseData[$day][$item->subject_id][] = [
                                        'subject_id' => $item->subject_id,
                                        'subject'    => $subjectName,
                                        'chapter_id' => $item->chapter_id,
                                        'title'      => $title,
                                        'class'      => $boxClass,
                                    ];
                                }
                            }
                        }
                    }
                }

                $subjects = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->get();
                return $this->sendSuccess(compact('plannerType', 'schoolId', 'classes', 'totalPlannerDays', 'dayWiseData', 'subjects', 'weekDays', 'allDates', 'totalDays', 'startDate', 'endDate'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($plannerType == 'weekly') {
                // Get planner data grouped by week
                $plannerDates = Planner::where('class_id', $classId)->whereIn('subject_id', $schoolAssignedSubjects)
                    ->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // Define start and end dates
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                // Get weekly breakdown
                $weeks       = [];
                $currentDate = Carbon::parse($startDate);
                while ($currentDate <= Carbon::parse($endDate)) {
                    $weekNumber = $currentDate->copy()->startOfWeek()->format('W');
                    if (! isset($weeks[$weekNumber])) {
                        $weeks[$weekNumber] = [
                            'start' => $currentDate->copy()->startOfWeek()->format('Y-m-d'),
                            'end'   => $currentDate->copy()->endOfWeek()->format('Y-m-d'),
                        ];
                    }
                    $currentDate->addWeek();
                }

                // Fetch planner data
                $plannerData = Planner::with(['class', 'subject', 'chapter'])
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->get();

                // Organize data into weeks
                $weekWiseData = [];
                foreach ($plannerData as $item) {
                    $chapterIds = explode(',', $item->chapter_id);                                                   // Convert string to array
                    $chapters   = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    $startOfWeek = Carbon::parse($item->start_date)->startOfWeek()->format('W');
                    $subjectName = $item->subject->name ?? 'No Subject';

                    $weekWiseData[$startOfWeek][$item->subject_id][] = [
                        'palnner_id' => $item->palnner_id,
                        'subject_id' => $item->subject_id,
                        'subject'    => $subjectName,
                        'chapter_id' => $chapterIds, // Store array of IDs
                        'titles'     => $chapters,   // Store an array of chapter names
                        'class'      => 'shiftBox',
                    ];
                }

                // Fetch subjects
                $subjects = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->get();
                return $this->sendSuccess(compact('plannerType', 'schoolId', 'classes', 'weeks', 'weekWiseData', 'subjects'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($plannerType == 'monthly') {
                $monthName = $request->month;
                if ($monthName) {
                    $monthNum = date('m', strtotime($monthName));
                    $startOfMonth = now()->setMonth($monthNum)->startOfMonth()->format('Y-m-d');
                    $endOfMonth   = now()->setMonth($monthNum)->endOfMonth()->format('Y-m-d');
                } else {
                    $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                    $endOfMonth   = now()->endOfMonth()->format('Y-m-d');
                }

                // Fetch all classes with monthly planners
                $classesWithMonthlyPlanners = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId) {
                        $query->where('school_id', $schoolId)
                            ->orWhereNull('school_id'); // Include universal planners if no school-specific data
                    })
                    ->where('type', 'monthly')
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                        $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])  // Starts in current month
                            ->orWhereBetween('completion_date', [$startOfMonth, $endOfMonth]) // Completes in current month
                            ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                                // Spans across the month (started before and ends after)
                                $query->where('start_date', '<', $startOfMonth)
                                    ->where('completion_date', '>', $endOfMonth);
                            });
                    })
                    ->get();

                // Group the data by class
                $classPlannerData = [];
                foreach ($classesWithMonthlyPlanners as $planner) {
                    $classId     = $planner->class_id;
                    $subjectName = $planner->subject->name ?? 'No Subject';
                    $chapterIds  = explode(',', $planner->chapter_id);                                                // Convert string to array
                    $chapters    = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    // Structure the data for display
                    $classPlannerData[$classId][] = [
                        'subject'         => $subjectName,
                        'subject_id'      => $planner->subject_id,
                        'chapter_id'      => $chapterIds, // Store array of IDs
                        'titles'          => $chapters,   // Store an array of chapter names
                        'planner_id'      => $planner->id,
                        'start_date'      => $planner->start_date,
                        'completion_date' => $planner->completion_date,
                    ];
                }

                $subjects = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->whereIn('subject_id', $schoolAssignedSubjects)
                    ->get();

                return $this->sendSuccess(compact('plannerType', 'classes', 'classPlannerData', 'startOfMonth', 'subjects', 'endOfMonth'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function plannerChapterDetails(Request $request)
    {
        try {
            $plannerLesson = Planner::whereRaw("FIND_IN_SET(?, chapter_id)", [$request->chapter_id])
                ->with('details', 'class', 'subject', 'board', 'medium', 'series')
                ->first();

            if (! $plannerLesson) {
                return $this->sendError('Planner details are not available for this chapter.');
            }

            // Check if details exist before grouping
            $groupedDetails = $plannerLesson->details ? $plannerLesson->details->groupBy('type') : collect([]);

            $this->data['groupedDetails'] = $groupedDetails;
            $this->data['plannerLesson']  = $plannerLesson;

            $this->data['digitalContent'] = CourseChapter::with('chapters', 'folder', 'documents', 'resources')
                ->where('id', $request->chapter_id)
                ->first();

            if (! $this->data['digitalContent']) {
                return $this->sendError('Digital content not found for this chapter.');
            }

            $this->data['supportingFiles'] = MediaFiles::where('tbl_id', $this->data['digitalContent']->supporting_folder_id)
                ->where('type', 'content_upload')
                ->get();

            $this->data['folderId'] = $this->data['digitalContent']->supporting_folder_id;

            $actualPercentage = 0;
            $markasCompletePerc = 0;
            $plannerId = $plannerLesson->id;
            if ($plannerId) {
                $schoolId                = Auth::user()->userAdditionalDetail->school_id;
                $userClass = Auth::user()->studentDetails->class ?? null;
                $plannerSetting = SchoolPlannerVisibility::where('school_id', $schoolId)->where('class_id', $userClass)->value('type');
                $planner = Planner::find($plannerId);
                $actualplanner = SchoolCompletedPlanner::where('planner_id', $plannerId)->where('school_id', $schoolId)->first();
                if ($planner) {
                    $startDate = Carbon::parse($planner->start_date);
                    $completionDate = Carbon::parse($planner->completion_date);
                    $today = Carbon::today();

                    $totalWorkingDays = $startDate->diffInDaysFiltered(
                        fn($date) => $date->dayOfWeek !== Carbon::SUNDAY,
                        $completionDate
                    );

                    if ($today->lt($startDate)) {
                        $actualPercentage = 0;
                    } elseif ($today->gt($completionDate)) {
                        $actualPercentage = 100;
                    } else {
                        $elapsedWorkingDays = $startDate->diffInDaysFiltered(
                            fn($date) => $date->dayOfWeek !== Carbon::SUNDAY,
                            $today
                        );
                        $actualPercentage = $totalWorkingDays > 0
                            ? round(($elapsedWorkingDays / $totalWorkingDays) * 100, 2)
                            : 0;
                    }
                }

                // Handle markasCompletePerc based on planner setting type
                if ($actualplanner && $plannerSetting) {
                    $completionDate = Carbon::parse($actualplanner->completion_date);
                    $today = Carbon::today();

                    switch ($plannerSetting) {
                        case 'daily':
                            // For daily, check if completion date is today
                            $markasCompletePerc = $completionDate->lte($today) ? 100 : 0;
                            break;

                        case 'weekly':
                            $endOfWeek = $startDate->copy()->addDays(7);
                            $markasCompletePerc = ($today->gte($endOfWeek) && $completionDate) ? 100 : 0;
                            break;

                        case 'fortnightly':
                            // For fortnightly, check if completion is within 15 days of start date
                            $endOfFortnight = $startDate->copy()->addDays(15);
                            $markasCompletePerc = ($today->gte($endOfFortnight) && $completionDate) ? 100 : 0;
                            break;

                        case 'monthly':
                            // For monthly, check if completion is within 30 days of start date
                            $endOfMonth = $startDate->copy()->addDays(30);
                            $markasCompletePerc = ($today->gte($endOfMonth) && $completionDate) ? 100 : 0;

                            break;

                        default:
                            // Default case (shouldn't normally happen)
                            $markasCompletePerc = 0;
                    }
                } else {
                    $markasCompletePerc = 0;
                }
            }

            $this->data['estimatedPercentage'] = $actualPercentage;
            $this->data['actualPercentage'] = $markasCompletePerc;

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }

    public function mediaGallery(Request $request)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getmediaGallery($request);
            $this->coreCtrl::storeStudentOverviewSection($request);

            if ($this->data['data']) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function mediaGalleryFiles(Request $request)
    {
        try {
            $request->merge(['from' => 'web']);
            $this->data['data'] = $this->coreCtrl::getmediaGalleryFiles($request, $request->id);
            $this->coreCtrl::storeStudentOverviewSection($request);
            if ($this->data['data']) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}
