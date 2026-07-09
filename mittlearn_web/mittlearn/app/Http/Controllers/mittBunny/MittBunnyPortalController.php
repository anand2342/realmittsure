<?php

namespace App\Http\Controllers\mittBunny;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CoreController;
use App\Models\AccessCodeOlympiad;
use App\Models\City;
use App\Models\Setting;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\SubscriptionPurchase;
use App\Models\TrackUserVideoProgress;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MittBunnyPortalController extends Controller
{
    public $data     = [];
    public $res      = [];
    public $coreCtrl = '';
    public function __construct()
    {
        $this->coreCtrl = CoreController::class;
    }
    public function dashboard(Request $request)
    {
        $request->merge(['from' => 'web']);
        $this->data['dashData'] = $this->coreCtrl::getUserDashboard($request);
        $this->data['courses']  = $this->coreCtrl::getUserMyCourses($request);

        $courses = $this->coreCtrl::getUserMyCourses($request);
        $this->data['conWatching'] = $this->coreCtrl::getUserMyCoursesContinueWatching($request);

        // Ensure variables always have valid data
        $this->data['courses']['academic_courses']    = collect($courses['academic_courses'] ?? []);
        $this->data['courses']['nonacademic_courses'] = collect($courses['nonacademic_courses'] ?? []);
        $this->data['courses']['academic_act_courses'] = collect($courses['academic_activity_courses'] ?? []);

        $this->data['totalAcadCourses']        = $this->data['courses']['academic_courses']->count();
        $this->data['totalNonAcadCourses']     = $this->data['courses']['nonacademic_courses']->count();
        $this->data['completedAcadCourses']    = 0;
        $this->data['completedNonAcadCourses'] = 0;

        foreach ($this->data['courses']['academic_courses'] as $course) {
            $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id);
            $totalVideoDuration   = $userProgress->sum('video_duration');
            $totalWatchedDuration = $userProgress->sum('watched_duration');
            if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                $this->data['completedAcadCourses']++;
            }
        }

        foreach ($this->data['courses']['nonacademic_courses'] as $course) {
            $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id);
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

        // dd($this->data);
        $this->data['subscribedCourses'] = SubscriptionPurchase::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if ($this->data['subscribedCourses']) {
            $subscribedCoursesJson                = json_decode($this->data['subscribedCourses']->courses_json, true);
            $this->data['totalSubscribedCourses'] = count($subscribedCoursesJson['academic_courses'] ?? []) + count($subscribedCoursesJson['non_academic_courses'] ?? []);
        }

        $completedAcadCourses    = 0;
        $completedNonAcadCourses = 0;
        $academicWatchTime       = array_fill(1, 12, 0);
        $nonAcademicWatchTime    = array_fill(1, 12, 0);

        foreach ($this->data['courses']['academic_courses'] as $course) {
            $totalVideoDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->sum('video_duration');
            $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->sum('watched_duration');

            if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                $completedAcadCourses++;
            }

            $monthlyWatchTime = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
                ->groupBy('month')
                ->get();

            foreach ($monthlyWatchTime as $record) {
                $academicWatchTime[$record->month] += round($record->total_watched / 60, 2);
            }
        }

        foreach ($this->data['courses']['nonacademic_courses'] as $course) {
            $totalVideoDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->sum('video_duration');
            $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->sum('watched_duration');

            if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                $completedNonAcadCourses++;
            }

            $monthlyWatchTime = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->selectRaw('MONTH(created_at) as month, SUM(watched_duration) as total_watched')
                ->groupBy('month')
                ->get();

            foreach ($monthlyWatchTime as $record) {
                $nonAcademicWatchTime[$record->month] += round($record->total_watched / 60, 2);
            }
        }

        $totalWatchedDuration = TrackUserVideoProgress::where('user_id', Auth::id())->sum('watched_duration');
        $this->data['totalHours']   = floor($totalWatchedDuration / 3600);
        $this->data['totalMinutes'] = floor(($totalWatchedDuration % 3600) / 60);
        $this->data['role'] = getUserRoles();
        $this->data['category'] = UserClass::where('user_id', Auth::id())->value('category_id');
        $this->data['olympiadSubscribedCourses'] = AccessCodeOlympiad::where('user_id', Auth::id())->count();

        $this->data['timeSpendingsData'] = json_encode([
            'academic'     => array_values($academicWatchTime),
            'non_academic' => array_values($nonAcademicWatchTime),
        ]);

        return view('mittBunny.dashboard', $this->data);
    }

    public function showDashClasses(Request $request, $date)
    {
        $request->merge(['from' => 'web']);
        $onlineClasses  = $this->coreCtrl::getUserOnlineClass($request);
        $onlineClasses  = collect($onlineClasses);
        $inputDate      = Carbon::parse($date);
        $date           = $inputDate->format('Y-m-d');
        $dateComparison = $onlineClasses->flatMap(function ($classes) use ($date) {
            return $classes->where('class_date', $date);
        });
        if (request()->ajax()) {
            return response()->json(['dateComparison' => $dateComparison]);
        }
    }
    public function profile(Request $request)
    {
        $this->data['cities'] = City::all();
        $this->data['states'] = State::pluck('name', 'id');

        $request->merge(['from' => 'web']);

        // Ensure $courses always has the necessary keys with empty collections by default
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
                ->where('course_id', $course->id);
            $totalVideoDuration   = $userProgress->sum('video_duration');
            $totalWatchedDuration = $userProgress->sum('watched_duration');
            if ($totalVideoDuration > 0 && $totalVideoDuration == $totalWatchedDuration) {
                $this->data['completedAcadCourses']++;
            }
        }

        foreach ($courses['nonacademic_courses'] as $course) {
            $userProgress = TrackUserVideoProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id);
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

        if ($this->data['subscribedCourses']) {
            $subscribedCourses                    = json_decode($this->data['subscribedCourses']->courses_json, true);
            $this->data['totalSubscribedCourses'] =
                count($subscribedCourses['academic_courses'] ?? []) +
                count($subscribedCourses['non_academic_courses'] ?? []);
        }

        return view('mittBunny.profile', $this->data);
    }

    public function uploadProfileImage(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $user = auth()->user();
            if ($user && $user->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $user->image)) {
                Storage::disk('public')->delete('uploads/user/profile_image/' . $user->image);
            }
            $profileImage = $request->file('profile_image');
            $extension    = $profileImage->getClientOriginalExtension();
            $fileName     = time() . '.' . $extension;
            $filePath     = 'uploads/user/profile_image/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($profileImage));
            $user->image = $fileName;
            $user->save();

            return response()->json([
                'success'  => true,
                'filePath' => asset('storage/' . $filePath),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function changePassword(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'password'    => 'required',                 // Current password
                'newpassword' => 'required|min:8|confirmed', // New password with confirmation
            ]);

            // Get the authenticated user
            $user = Auth::user();

            // Verify the current password
            if (! Hash::check($request->password, $user->password)) {
                return response()->json(['errors' => ['password' => ['The current password is incorrect.']]], 422);
            }

            // Prevent using the same password
            if (Hash::check($request->newpassword, $user->password)) {
                return response()->json(['errors' => ['newpassword' => ['The new password cannot be the same as the current password.']]], 422);
            }

            // Hash and update new password
            $user->password = Hash::make($request->newpassword);
            $user->validate_string = $request->newpassword;
            $user->save();

            // Return JSON success response
            return response()->json(['success' => true, 'message' => 'Password successfully changed!']);
        } catch (\Exception $e) {
            return response()->json(['error' => config('constants.FLASH_TRY_CATCH')], 500);
        }
    }

    public function updateProfileDetails(Request $request)
    {
        $request->validate([
            'address'     => 'required|string|max:255',
            'name'        => 'required|string|max:255',
            'parent_name' => 'required|string|max:255',
            // 'dob'         => 'required|date|before:today',

        ]);
        try {
            $userId               = $request->input('id');
            $studentDetails       = StudentDetails::where('user_id', $userId)->first();
            $userAdditonalDetails = UserAdditionalDetail::where('user_id', $userId)->first();
            $user                 = User::find($userId);
            if (! $studentDetails) {
                return redirect()->route('mittbunny.profile')->with('error', 'No details found for the Student.');
            }
            if ($studentDetails) {
                $studentDetails->dob         = $request->dob;
                $studentDetails->parent_name = $request->parent_name;
                $userAdditonalDetails->postal_code = $request->postal_code;
                $userAdditonalDetails->state       = $request->state;
                $userAdditonalDetails->city        = $request->city;
                $studentDetails->save();
            }
            if ($userAdditonalDetails) {
                $userAdditonalDetails->address     = $request->address;
                $userAdditonalDetails->postal_code = $request->postal_code;
                $userAdditonalDetails->state       = $request->state;
                $userAdditonalDetails->city        = $request->city;
                $userAdditonalDetails->save();
            } else {
                $newdetail              = new UserAdditionalDetail;
                $newdetail->user_id     = $userId;
                $newdetail->address     = $request->address;
                $newdetail->postal_code = $request->postal_code;
                $newdetail->state       = $request->state;
                $newdetail->city        = $request->city;
                $newdetail->save();
            }
            if ($user) {
                $user->name = $request->name;
                $user->save();
            }
            return redirect()->route('mittbunny.profile')->with('success', 'Your Data updated successfully');
        } catch (\TypeError $e) {
            return redirect()->route('mittbunny.profile')->with('error', 'A type error occurred while updating your data. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('mittbunny.profile')->with('error', 'An error occurred while updating your data. Please try again.');
        }
    }
    public function downloadApp(Request $request)
    {
        $this->data['setting'] = Setting::pluck('field_value', 'field_name')->toArray();
        return view('mittBunny.download-app', $this->data);
    }
}
