<?php

namespace App\Http\Controllers\admin;

use App\Exports\AdminDashboardReportExport;
use App\Http\Controllers\Controller;
use App\Models\AccessCodeEmbibe;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use App\Models\Role;
use App\Models\Schools;
use App\Models\SubscriptionPurchase;
use App\Models\TransactionLog;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserLoginLog;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public $data = [];
    public function dashboardView()
    {
        $logs = Activity::latest()->paginate(10);
        // dd($logs);
        return view('admin.dashboard.index-coming-soon');
    }
    public function userActivityLog()
    {
        $logs = Activity::latest()->paginate(10);
        // dd($logs);
        return view('admin.dashboard.activity-log', compact('logs'));
    }
    public function dashboard(Request $request)
    {
        // Unified date handling - use consistent date variables throughout
        // $startDate = $request->input('start_date', now()->startOfYear()->toDateString());
        $startDate = $request->input('start_date', '2025-01-01');
        $endDate   = $request->input('end_date', now()->endOfYear()->toDateString());

        $loginStartDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfDay();
        $loginEndDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfDay();

        // Today login counts
        $this->data['loginCounts'] = UserLoginLog::whereBetween('login_at', [$loginStartDate, $loginEndDate])
            ->select('role', \DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Live sessions (users who haven’t logged out yet)
        $this->data['liveSessionCounts'] = UserLoginLog::whereNull('logout_at')
            ->whereDate('login_at', $loginStartDate)
            ->select('role', \DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Get Schools
        $this->data['totalSchools']         = Schools::where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])->count();
        $this->data['groupSchoolsCount'] = Schools::where('school_type', 'group')->where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_verified_by_admin', 1)->count();
        $this->data['individualSchoolsCount'] = Schools::where('school_type', 'individual')->where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_verified_by_admin', 1)->count();
        $this->data['demoSchoolsCount'] = Schools::where('school_type', 'demo')->where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])
            ->where('is_verified_by_admin', 1)->count();

        $this->data['accessCodesCount'] = AccessCodeEmbibe::whereBetween('created_at', [$startDate, $endDate])->count();
        $this->data['accessCodesTeachliteCount'] = AccessCodeEmbibe::where('type', 'teachlite')->whereBetween('created_at', [$startDate, $endDate])->count();
        $this->data['accessCodesMittLensCount'] = AccessCodeEmbibe::where('type', 'mittlense')->whereBetween('created_at', [$startDate, $endDate])->count();
        $this->data['schoolAccessCodesCount'] = AccessCodeEmbibe::whereNotNull('school_id')->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('school_id')
            ->count('school_id');

        // // count of the d2c ourses Define the sub-category IDs and their matching MediaFile type
        $subCategoryConfigs = [
            'OlympiadContentCount' => ['sub_category_id' => 35, 'type' => 'course_chapter'],
            'JPKitContentCount' => ['sub_category_id' => 36, 'type' => 'course_chapter'],
            'ActivityWorksheetsContentCount' => ['sub_category_id' => 37, 'type' => 'activity_worksheet_link'],
        ];

        foreach ($subCategoryConfigs as $key => $config) {
            $this->data[$key] = MediaFiles::where('type', $config['type'])
                ->whereIn('tbl_id', function ($query) use ($config, $startDate, $endDate) {
                    $query->select('id')
                        ->from('course_chapters')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->whereIn('course_id', function ($subQuery) use ($config) {
                            $subQuery->select('id')
                                ->from('courses')
                                ->where('category_id', 1)
                                ->where('sub_category_id', $config['sub_category_id']);
                        });
                })
                ->count();
        }

        // Total D2C count
        $this->data['totalD2cContent'] = array_sum([
            $this->data['JPKitContentCount'],
            $this->data['OlympiadContentCount'],
            $this->data['ActivityWorksheetsContentCount'],
        ]);
        // User Counts Query
        $this->data['userCounts'] = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereIn('user_roles.role_slug', ['school_student', 'b2c_student', 'd2c_user', 'school_teacher'])
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(CASE WHEN user_roles.role_slug IN ('school_student', 'b2c_student') THEN 1 END) as total_students,
                COUNT(CASE WHEN user_roles.role_slug IN ('school_student', 'b2c_student') AND users.status = 1 THEN 1 END) as total_active_students,
                COUNT(CASE WHEN user_roles.role_slug IN ('school_student', 'b2c_student') AND users.status = 0 THEN 1 END) as total_inactive_students,

                COUNT(CASE WHEN user_roles.role_slug = 'school_student' THEN 1 END) as total_school_students,
                COUNT(CASE WHEN user_roles.role_slug = 'school_student' AND users.status = 1 THEN 1 END) as total_active_school_students,
                COUNT(CASE WHEN user_roles.role_slug = 'school_student' AND users.status = 0 THEN 1 END) as total_inactive_school_students,

                COUNT(CASE WHEN user_roles.role_slug = 'b2c_student' THEN 1 END) as total_b2c_students,
                COUNT(CASE WHEN user_roles.role_slug = 'b2c_student' AND users.status = 1 THEN 1 END) as total_active_b2c_students,
                COUNT(CASE WHEN user_roles.role_slug = 'b2c_student' AND users.status = 0 THEN 1 END) as total_inactive_b2c_students,

                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id IN (35,36,37) THEN 1 END) as total_d2c_users,
                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id = 35 THEN 1 END) as total_d2c_users_olympiad,
                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id = 36 THEN 1 END) as total_d2c_users_JPKit,
                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id = 37 THEN 1 END) as total_d2c_users_worksheets,

                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' THEN 1 END) as total_school_teachers,
                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' AND users.status = 1 THEN 1 END) as total_active_school_teachers,
                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' AND users.status = 0 THEN 1 END) as total_inactive_school_teachers
              ")
            ->first();


        //FreeTrail Subscription Data
        $this->data['freeTrailUserCounts'] = SubscriptionPurchase::join('subscription_plans', 'subscription_purchases.plan_id', '=', 'subscription_plans.id')
            ->whereBetween('subscription_purchases.created_at', [$startDate, $endDate])
            ->where('subscription_plans.is_free_trial', 1)
            ->selectRaw("
                COUNT(DISTINCT subscription_purchases.user_id) as total_subscription_students,
                COUNT(DISTINCT CASE WHEN subscription_purchases.status = 'active' THEN subscription_purchases.user_id END) as total_active_students,
                COUNT(DISTINCT CASE WHEN subscription_purchases.status = 'expired' THEN subscription_purchases.user_id END) as total_inactive_students
             ")
            ->first();
        //Total Subscription Data
        $this->data['totalSubscriptionUserCounts'] = SubscriptionPurchase::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(DISTINCT user_id) as total_subscription_students,
                COUNT(DISTINCT CASE WHEN status = 'active' THEN user_id END) as total_active_students,
                COUNT(DISTINCT CASE WHEN status = 'expired' THEN user_id END) as total_inactive_students
             ")
            ->first();

        // Total Revenue
        $this->data['totalRevenue'] = TransactionLog::where('payment_state', 'success')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // For View Chart
        $currentYear         = Carbon::now()->year;
        $verifiedSchoolsData = Schools::where('is_verified_by_admin', 1)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('count(*) as total, YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->get()
            ->reduce(function ($carry, $item) {
                $carry[$item->year][$item->month] = $item->total;
                return $carry;
            }, []);

        $b2cStudentsData    = $this->getMonthlyData('b2c_student');
        $schoolStudentsData = $this->getMonthlyData('school_student');
        // Get total chapter count

        // Get count of videos and files in one query
        // $mediaCounts = MediaFiles::whereBetween('created_at', [$startDate, $endDate])
        //     ->selectRaw("
        //     COUNT(CASE WHEN file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') THEN 1 END) as video_count,
        //      COUNT(CASE WHEN file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') THEN 1 END) as file_count
        // ")
        //     ->where('type', 'course_chapter')
        //     ->first();

        // $this->data['videoCount'] = $mediaCounts->video_count ?? 0;
        // $this->data['fileCount'] = $mediaCounts->file_count ?? 0;


        $this->data['digitalContentCount'] = CourseChapter::whereBetween('created_at', [$startDate, $endDate])->count();
        // $this->data['digitalContentCount'] = CourseChapter::whereBetween('created_at', [$startDate, $endDate])
        //     ->whereHas('course', function ($query) {
        //         $query->where('category_id', 1)
        //             ->whereNotIn('sub_category_id', [35, 36, 37])
        //             ->where('is_active', 1);
        //     })
        //     ->count();

        $mediaCounts = MediaFiles::whereBetween('media_files.created_at', [$startDate, $endDate])
            ->leftJoin('course_chapters', function ($join) {
                $join->on('media_files.tbl_id', '=', 'course_chapters.id')
                    ->where('media_files.type', 'course_chapter');
            })
            ->leftJoin('courses', 'course_chapters.course_id', '=', 'courses.id')
            // OR courses.category_id IS NULL
            ->selectRaw("
            COUNT(CASE WHEN media_files.file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') AND (courses.category_id = 1 ) AND (courses.sub_category_id NOT IN (35, 36, 37) OR courses.sub_category_id IS NULL) THEN 1 END) as academic_video_count,
            COUNT(CASE WHEN media_files.file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') AND (courses.category_id = 1 OR courses.category_id IS NULL) THEN 1 END) as academic_file_count,
            COUNT(CASE WHEN media_files.file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') AND courses.category_id = 2 THEN 1 END) as talent_video_count,
            COUNT(CASE WHEN media_files.file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') AND courses.category_id = 2 THEN 1 END) as talent_file_count,
            COUNT(CASE WHEN media_files.file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') THEN 1 END) as total_video_count,
            COUNT(CASE WHEN media_files.file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') THEN 1 END) as total_file_count
        ")
            ->first();

        // Assign the values to the data array
        $this->data['academicVideoCount'] = $mediaCounts->academic_video_count ?? 0;
        $this->data['academicFileCount'] = $mediaCounts->academic_file_count ?? 0;
        $this->data['talentVideoCount'] = $mediaCounts->talent_video_count ?? 0;
        $this->data['talentFileCount'] = $mediaCounts->talent_file_count ?? 0;
        $this->data['totalVideoCount'] = $mediaCounts->total_video_count ?? 0; // For verification
        $this->data['totalFileCount'] = $mediaCounts->total_file_count ?? 0; // For verification



        $this->data['bookTitles'] = Course::whereBetween('created_at', [$startDate, $endDate])->with('metadata')->where('category_id', 1)->count() ?? 0;
        $this->data['bookSeries'] = Course::whereBetween('created_at', [$startDate, $endDate])
            ->with('metadata')
            ->where('category_id', 1)
            ->whereHas('metadata', function ($query) {
                $query->where('field_name', 'series');
            })
            ->get()
            ->pluck('metadata')
            ->flatten()
            ->where('field_name', 'series')
            ->unique('field_value')->count() ?? 0;



        $this->data['chartData'] = [
            'verifiedSchools' => $verifiedSchoolsData,
            'b2cStudents'     => $b2cStudentsData,
            'schoolStudents'  => $schoolStudentsData,
        ];

        $this->data['topPurchasedCourses'] = DB::select("SELECT c.id AS course_id, c.course_name, c.sub_category_id, cat.name AS category_name,
            COUNT(sp.id) AS sold_items FROM subscription_purchases sp JOIN courses c ON JSON_CONTAINS(sp.courses_json, JSON_OBJECT('id', c.id), '$.academic_courses')
            OR JSON_CONTAINS(sp.courses_json, JSON_OBJECT('id', c.id), '$.non_academic_courses') JOIN categories cat ON c.sub_category_id = cat.id
            GROUP BY c.id, c.course_name, c.sub_category_id, cat.name ORDER BY sold_items DESC LIMIT 5 ");

        $this->data['newlyAddedCourses'] = Course::latest('created_at')->where('is_active', 1)->with('getSubCategory')->take(5)->get();

        $selectedRole = $request->get('roles'); // role_slug from dropdown

        $this->data['newlyAddedUsers'] = User::latest('created_at')
            ->where('status', 1)->where('email', 'not like', '%@guest.com')
            ->whereHas('userRole.role', function ($query) use ($selectedRole) {
                $query->whereIn('role_slug', ['school_student', 'b2c_student', 'school_admin', 'school_teacher', 'd2c_user']);

                if ($selectedRole) {
                    $query->where('role_slug', $selectedRole);
                }
            })
            ->when(
                !$selectedRole || $selectedRole === 'school_admin',
                function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->whereHas('userRole.role', function ($q) {
                            $q->where('role_slug', '!=', 'school_admin');
                        })->orWhereHas('schoolDetails', function ($q) {
                            $q->where('is_verified_by_admin', 1);
                        });
                    });
                }
            )
            ->with(['userRole.role', 'schoolDetails']) // include school relation if needed
            ->take(10)
            ->get();



        $this->data['roles'] = Role::whereIn('role_slug', ['school_student', 'b2c_student', 'school_admin', 'school_teacher', 'd2c_user'])->pluck('role_name', 'role_slug')->toArray();

        return view('admin.dashboard.index', $this->data);
    }
    public function viewLoginUsers(Request $request, $role)
    {
        $type = $request->input('type', 'logged-in'); // default = logged-in

        // Use consistent date handling with dashboard
        $startDate = $request->input('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->input('end_date', now()->endOfYear()->toDateString());

        $loginStartDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : now()->startOfDay();
        $loginEndDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : now()->endOfDay();


        $query = UserLoginLog::with(['user', 'schools', 'state', 'district', 'schoolName', 'category'])->where('role', $role);

        if ($type === 'live') {
            // Only live sessions logged in today
            $query->whereNull('logout_at')->whereBetween('login_at', [$loginStartDate, $loginEndDate]);
        } else {
            // Today’s login sessions (or filtered range)
            $query->whereBetween('login_at', [$loginStartDate, $loginEndDate]);
        }

        $logins = $query->orderByDesc('login_at')->get();
        // dd($logins);

        return view('admin.dashboard.login-users', compact('logins', 'role', 'startDate', 'endDate', 'type'));
    }

    // public function viewLoginUsers(Request $request, $role)
    // {
    //     $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : now()->startOfDay();
    //     $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();

    //     $logins = UserLoginLog::with('user') // assuming relation with User
    //         ->where('role', $role)
    //         ->whereBetween('login_at', [$startDate, $endDate])
    //         ->orderByDesc('login_at')
    //         ->get();

    //     return view('admin.dashboard.login-users', compact('logins', 'role', 'startDate', 'endDate'));
    // }
    public function getMonthlyData($roleSlug = null)
    {
        $currentYear = Carbon::now()->year;
        $query       = UserRole::query();
        if ($roleSlug) {
            $query->where('role_slug', $roleSlug);
        }
        return $query->join('users', 'user_roles.user_id', '=', 'users.id')
            ->where('users.status', 1)
            ->whereYear('users.created_at', $currentYear)
            ->selectRaw('count(*) as total, YEAR(users.created_at) as year, MONTH(users.created_at) as month')
            ->groupBy('year', 'month')
            ->get()
            ->reduce(function ($carry, $item) {
                $carry[$item->year][$item->month] = $item->total;
                return $carry;
            }, []);
    }
    public function dashboardReportExport(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate   = $request->query('end_date');
        $date      = now()->format('Y-m-d');
        $fileName  = "dashboard-report-{$date}.xlsx";

        $file = Excel::raw(new AdminDashboardReportExport($startDate, $endDate), \Maatwebsite\Excel\Excel::XLSX);
        return Response::make($file, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
    public function dateFilterDataForFullDashboard(Request $request)
    {
        // Get date range from request or default to the current year
        $startDate = $request->input('start_date', now()->startOfYear()->toDateString());
        $endDate   = $request->input('end_date', now()->endOfYear()->toDateString());

        // Get Schools
        $this->data['totalSchools']    = Schools::whereBetween('created_at', [$startDate, $endDate])->count();
        $this->data['verifiedSchools'] = Schools::whereBetween('created_at', [$startDate, $endDate])
            ->where('is_verified_by_admin', 1)->count();
        $this->data['unVerifiedSchools'] = $this->data['totalSchools'] - $this->data['verifiedSchools'];

        // User Counts Query
        $this->data['userCounts'] = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereIn('user_roles.role_slug', ['school_student', 'b2c_student', 'school_teacher'])
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(CASE WHEN user_roles.role_slug IN ('school_student', 'b2c_student') THEN 1 END) as total_students,
                COUNT(CASE WHEN user_roles.role_slug IN ('school_student', 'b2c_student') AND users.status = 1 THEN 1 END) as total_active_students,
                COUNT(CASE WHEN user_roles.role_slug IN ('school_student', 'b2c_student') AND users.status = 0 THEN 1 END) as total_inactive_students,

                COUNT(CASE WHEN user_roles.role_slug = 'school_student' THEN 1 END) as total_school_students,
                COUNT(CASE WHEN user_roles.role_slug = 'school_student' AND users.status = 1 THEN 1 END) as total_active_school_students,
                COUNT(CASE WHEN user_roles.role_slug = 'school_student' AND users.status = 0 THEN 1 END) as total_inactive_school_students,

                COUNT(CASE WHEN user_roles.role_slug = 'b2c_student' THEN 1 END) as total_b2c_students,
                COUNT(CASE WHEN user_roles.role_slug = 'b2c_student' AND users.status = 1 THEN 1 END) as total_active_b2c_students,
                COUNT(CASE WHEN user_roles.role_slug = 'b2c_student' AND users.status = 0 THEN 1 END) as total_inactive_b2c_students,

                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' THEN 1 END) as total_school_teachers,
                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' AND users.status = 1 THEN 1 END) as total_active_school_teachers,
                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' AND users.status = 0 THEN 1 END) as total_inactive_school_teachers
            ")
            ->first();

        // Optimized Subscription Data Query
        $this->data['freeTrailUserCounts'] = SubscriptionPurchase::join('subscription_plans', 'subscription_purchases.plan_id', '=', 'subscription_plans.id')
            ->whereBetween('subscription_purchases.created_at', [$startDate, $endDate])
            ->where('subscription_plans.is_free_trial', 1)
            ->selectRaw("
                COUNT(DISTINCT subscription_purchases.user_id) as total_subscription_students,
                COUNT(DISTINCT CASE WHEN subscription_purchases.status = 'active' THEN subscription_purchases.user_id END) as total_active_students,
                COUNT(DISTINCT CASE WHEN subscription_purchases.status = 'expired' THEN subscription_purchases.user_id END) as total_inactive_students
            ")
            ->first();

        $this->data['totalSubscriptionUserCounts'] = SubscriptionPurchase::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(DISTINCT user_id) as total_subscription_students,
                COUNT(DISTINCT CASE WHEN status = 'active' THEN user_id END) as total_active_students,
                COUNT(DISTINCT CASE WHEN status = 'expired' THEN user_id END) as total_inactive_students
            ")
            ->first();

        // Optimized Total Revenue Calculation
        $this->data['totalRevenue'] = TransactionLog::where('payment_state', 'success')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Optimized Verified Schools Data for Charts
        $currentYear         = now()->year;
        $verifiedSchoolsData = Schools::where('is_verified_by_admin', 1)
            ->whereYear('created_at', $currentYear)
            ->selectRaw('COUNT(*) as total, YEAR(created_at) as year, MONTH(created_at) as month')
            ->groupBy('year', 'month')
            ->pluck('total', 'month');

        // Monthly User Data
        $b2cStudentsData    = $this->getMonthlyData('b2c_student');
        $schoolStudentsData = $this->getMonthlyData('school_student');

        // Chart Data
        $this->data['chartData'] = [
            'verifiedSchools' => $verifiedSchoolsData,
            'b2cStudents'     => $b2cStudentsData,
            'schoolStudents'  => $schoolStudentsData,
        ];

        // Optimized Top Purchased Courses Query
        $this->data['topPurchasedCourses'] = DB::select("
            SELECT
                c.id AS course_id,
                c.course_name,
                c.sub_category_id,
                cat.name AS category_name,
                COUNT(sp.id) AS sold_items
            FROM subscription_purchases sp
            JOIN courses c
                ON JSON_CONTAINS(sp.courses_json, JSON_OBJECT('id', c.id), '$.academic_courses')
                OR JSON_CONTAINS(sp.courses_json, JSON_OBJECT('id', c.id), '$.non_academic_courses')
            JOIN categories cat
                ON c.sub_category_id = cat.id
            WHERE sp.created_at BETWEEN ? AND ?
            GROUP BY c.id, c.course_name, c.sub_category_id, cat.name
            ORDER BY sold_items DESC
            LIMIT 5
        ", [$startDate, $endDate]);

        // Optimized Queries for Newly Added Users & Courses
        $this->data['newlyAddedCourses'] = Course::where('is_active', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest('created_at')
            ->with('getSubCategory')
            ->take(5)
            ->get();

        $selectedRole = $request->get('roles'); // role_slug from dropdown
        $this->data['newlyAddedUsers'] = User::latest('created_at')
            ->where('status', 1)->where('email', 'not like', '%@guest.com')
            ->whereHas('userRole.role', function ($query) use ($selectedRole) {
                $query->whereIn('role_slug', ['school_student', 'b2c_student', 'school_admin', 'school_teacher', 'd2c_user']);

                if ($selectedRole) {
                    $query->where('role_slug', $selectedRole);
                }
            })
            ->when(
                !$selectedRole || $selectedRole === 'school_admin',
                function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->whereHas('userRole.role', function ($q) {
                            $q->where('role_slug', '!=', 'school_admin');
                        })->orWhereHas('schoolDetails', function ($q) {
                            $q->where('is_verified_by_admin', 1);
                        });
                    });
                }
            )
            ->with(['userRole.role', 'schoolDetails']) // include school relation if needed
            ->take(10)
            ->get();


        return view('admin.dashboard.index', $this->data);
    }
    public function profile()
    {
        $user = User::where('id', Auth::id())->with('additionalDetails')->first();
        if ($user) {
            return view('admin.dashboard.profile', compact('user'));
        }
    }
    public function changePasswordSave(Request $request)
    {
        // Validate the request
        $request->validate([
            'password' => 'required', // Current password
            'newpassword' => 'required|min:8|confirmed', // New password with confirmation
        ]);
        // Get the currently authenticated user
        $user = Auth::user();

        // Verify the current password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The current password is incorrect.']);
        }

        // Check if the new password is the same as the old password
        if (Hash::check($request->newpassword, $user->password)) {
            return back()->withErrors(['newpassword' => 'The new password cannot be the same as the current password.']);
        }

        // Update the user's password
        $user->password = Hash::make($request->newpassword);
        $user->validate_string = $request->newpassword;
        $user->save();

        // Return success response
        return back()->with('status', 'Password successfully changed!');
    }


    public function profileUpdate(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('profile_image')) {

            if ($user && $user->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $user->image)) {
                Storage::disk('public')->delete('uploads/user/profile_image/' . $user->image);
            }
            $profileImage = $request->file('profile_image');
            $extension    = $profileImage->getClientOriginalExtension();
            $fileName     = time() . '.' . $extension;
            $filePath     = 'uploads/user/profile_image/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($profileImage));
            $user->image = $fileName;
        }

        $userAdditonalDetails = UserAdditionalDetail::where('user_id', $user->id)->first();

        $user->name = $request->input('fullName');
        $user->mobile_no = $request->input('phone');
        $user->email = $request->input('email');
        $user->save();
        if ($userAdditonalDetails) {
            $userAdditonalDetails->address = $request->input('address');
            $userAdditonalDetails->about = $request->input('about');
            $userAdditonalDetails->designation = $request->input('job');
            $userAdditonalDetails->country = $request->input('country');
            $userAdditonalDetails->customer_type = $request->input('company');
            $userAdditonalDetails->save();
        } else {
            UserAdditionalDetail::create([
                'user_id' => $user->id,
                'address' => $request->input('address'),
                'about' => $request->input('about'),
                'designation' => $request->input('job'),
                'country' => $request->input('country'),
                'customer_type' => $request->input('company'),
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
