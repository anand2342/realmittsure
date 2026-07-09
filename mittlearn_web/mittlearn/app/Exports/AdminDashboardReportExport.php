<?php

namespace App\Exports;

use App\Models\AccessCodeEmbibe;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use App\Models\Schools;
use App\Models\User;
use App\Models\SubscriptionPurchase;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdminDashboardReportExport implements FromArray, WithHeadings, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function headings(): array
    {
        return [
            'Total Verified Schools',
            'Group Schools',
            'Individual Schools',
            'Demo Schools',
            'Unverified Schools',

            'Total Students',
            'Active Students',
            'Inactive Students',
            'Total School Students',
            'Active School Students',
            'Inactive School Students',
            'Total B2C Students',
            'Active B2C Students',
            'Inactive B2C Students',
            'Total D2C Users',
            'D2C Olympiad Users',
            'D2C JPKit Users',
            'D2C Worksheets Users',
            'Total Teachers',
            'Active Teachers',
            'Inactive Teachers',

            'Access Codes Count',
            'Teachlite Access Codes',
            'MittLens Access Codes',
            'School Access Codes',

            'Digital Content Count',
            'Academic Video Count',
            'Academic File Count',
            'Talent Video Count',
            'Talent File Count',
            'Total Video Count',
            'Total File Count',

            'Olympiad Content Count',
            'JPKit Content Count',
            'Activity Worksheets Count',
            'Total D2C Content',

            'Book Series Count',
            'Book Titles Count',

            'Total Free Trial Students',
            'Active Free Trial Students',
            'Expired Free Trial Students',
            'Total Subscription Students',
            'Active Subscription Students',
            'Expired Subscription Students',
            'Total Revenue'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Style for the first row (headings)
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF00'], // Yellow background
                ]
            ]
        ];
    }

    public function array(): array
    {
        // Get date range from request or default to current year
        $startDate = $this->startDate ?? now()->startOfYear()->toDateString();
        $endDate   = $this->endDate ?? now()->endOfYear()->toDateString();

        // Fetch Data (Same logic as in dashboard) - Match dashboard exactly
        $totalSchools = Schools::where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])->count();
        $groupSchoolsCount = Schools::where('school_type', 'group')->where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])->count();
        $individualSchoolsCount = Schools::where('school_type', 'individual')->where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])->count();
        $demoSchoolsCount = Schools::where('school_type', 'demo')->where('is_verified_by_admin', 1)->whereBetween('created_at', [$startDate, $endDate])->count();

        // Calculate unverified schools correctly
        $allSchools = Schools::whereBetween('created_at', [$startDate, $endDate])->count();
        $unVerifiedSchoolsCount = $allSchools - $totalSchools;

        // Access Codes data - match dashboard exactly
        $accessCodesCount = AccessCodeEmbibe::whereBetween('created_at', [$startDate, $endDate])->count();
        $accessCodesTeachliteCount = AccessCodeEmbibe::where('type', 'teachlite')->whereBetween('created_at', [$startDate, $endDate])->count();
        $accessCodesMittLensCount = AccessCodeEmbibe::where('type', 'mittlense')->whereBetween('created_at', [$startDate, $endDate])->count();
        $schoolAccessCodesCount = AccessCodeEmbibe::whereNotNull('school_id')->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('school_id')
            ->count('school_id');

        // D2C Content counts - match dashboard exactly
        $subCategoryConfigs = [
            'OlympiadContentCount' => ['sub_category_id' => 35, 'type' => 'course_chapter'],
            'JPKitContentCount' => ['sub_category_id' => 36, 'type' => 'course_chapter'],
            'ActivityWorksheetsContentCount' => ['sub_category_id' => 37, 'type' => 'activity_worksheet_link'],
        ];

        $d2cCounts = [];
        foreach ($subCategoryConfigs as $key => $config) {
            $d2cCounts[$key] = MediaFiles::where('type', $config['type'])
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

        $totalD2cContent = array_sum([
            $d2cCounts['JPKitContentCount'],
            $d2cCounts['OlympiadContentCount'],
            $d2cCounts['ActivityWorksheetsContentCount'],
        ]);

        // User Counts Query - Match dashboard exactly
        $userCounts = User::join('user_roles', 'users.id', '=', 'user_roles.user_id')
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

                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' THEN 1 END) as total_d2c_users,
                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id = 35 THEN 1 END) as total_d2c_users_olympiad,
                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id = 36 THEN 1 END) as total_d2c_users_JPKit,
                COUNT(CASE WHEN user_roles.role_slug = 'd2c_user' AND users.category_id = 37 THEN 1 END) as total_d2c_users_worksheets,

                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' THEN 1 END) as total_school_teachers,
                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' AND users.status = 1 THEN 1 END) as total_active_school_teachers,
                COUNT(CASE WHEN user_roles.role_slug = 'school_teacher' AND users.status = 0 THEN 1 END) as total_inactive_school_teachers
              ")
            ->first();

        $digitalContentCount = CourseChapter::whereBetween('created_at', [$startDate, $endDate])->count();

        // Match dashboard media counts logic exactly
        $mediaCounts = MediaFiles::whereBetween('media_files.created_at', [$startDate, $endDate])
            ->leftJoin('course_chapters', function ($join) {
                $join->on('media_files.tbl_id', '=', 'course_chapters.id')
                    ->where('media_files.type', 'course_chapter');
            })
            ->leftJoin('courses', 'course_chapters.course_id', '=', 'courses.id')
            ->selectRaw("
            COUNT(CASE WHEN media_files.file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') AND (courses.category_id = 1 ) AND (courses.sub_category_id NOT IN (35, 36, 37) OR courses.sub_category_id IS NULL) THEN 1 END) as academic_video_count,
            COUNT(CASE WHEN media_files.file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') AND (courses.category_id = 1 OR courses.category_id IS NULL) THEN 1 END) as academic_file_count,
            COUNT(CASE WHEN media_files.file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') AND courses.category_id = 2 THEN 1 END) as talent_video_count,
            COUNT(CASE WHEN media_files.file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') AND courses.category_id = 2 THEN 1 END) as talent_file_count,
            COUNT(CASE WHEN media_files.file_extension IN ('mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','3gp','m2ts','ogv','ts','mxf') THEN 1 END) as total_video_count,
            COUNT(CASE WHEN media_files.file_extension IN ('pdf', 'docx','doc', 'xlsx','xls', 'jpeg', 'jpg', 'png') THEN 1 END) as total_file_count
        ")
            ->first();

        $bookTitles = Course::whereBetween('created_at', [$startDate, $endDate])->with('metadata')->where('category_id', 1)->count() ?? 0;

        $bookSeries = Course::whereBetween('created_at', [$startDate, $endDate])
            ->with('metadata')
            ->where('category_id', 1)
            ->whereHas('metadata', function ($query) {
                $query->where('field_name', 'series');
            })
            ->get()
            ->pluck('metadata')
            ->flatten()
            ->where('field_name', 'series')
            ->unique('field_value')
            ->count() ?? 0;

        $freeTrialCounts = SubscriptionPurchase::join('subscription_plans', 'subscription_purchases.plan_id', '=', 'subscription_plans.id')
            ->whereBetween('subscription_purchases.created_at', [$startDate, $endDate])
            ->where('subscription_plans.is_free_trial', 1)
            ->selectRaw("
                COUNT(DISTINCT subscription_purchases.user_id) as total_subscription_students,
                COUNT(DISTINCT CASE WHEN subscription_purchases.status = 'active' THEN subscription_purchases.user_id END) as total_active_students,
                COUNT(DISTINCT CASE WHEN subscription_purchases.status = 'expired' THEN subscription_purchases.user_id END) as total_inactive_students
            ")
            ->first();

        $subscriptionCounts = SubscriptionPurchase::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(DISTINCT user_id) as total_subscription_students,
                COUNT(DISTINCT CASE WHEN status = 'active' THEN user_id END) as total_active_students,
                COUNT(DISTINCT CASE WHEN status = 'expired' THEN user_id END) as total_inactive_students
            ")
            ->first();

        $totalRevenue = TransactionLog::where('payment_state', 'success')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        return [
            [
                $totalSchools ?? '-',
                $groupSchoolsCount ?? '-',
                $individualSchoolsCount ?? '-',
                $demoSchoolsCount ?? '-',
                $unVerifiedSchoolsCount ?? '-',

                $userCounts->total_students ?? '-',
                $userCounts->total_active_students ?? '-',
                $userCounts->total_inactive_students ?? '-',
                $userCounts->total_school_students ?? '-',
                $userCounts->total_active_school_students ?? '-',
                $userCounts->total_inactive_school_students ?? '-',
                $userCounts->total_b2c_students ?? '-',
                $userCounts->total_active_b2c_students ?? '-',
                $userCounts->total_inactive_b2c_students ?? '-',
                $userCounts->total_d2c_users ?? '-',
                $userCounts->total_d2c_users_olympiad ?? '-',
                $userCounts->total_d2c_users_JPKit ?? '-',
                $userCounts->total_d2c_users_worksheets ?? '-',
                $userCounts->total_school_teachers ?? '-',
                $userCounts->total_active_school_teachers ?? '-',
                $userCounts->total_inactive_school_teachers ?? '-',

                $accessCodesCount ?? '-',
                $accessCodesTeachliteCount ?? '-',
                $accessCodesMittLensCount ?? '-',
                $schoolAccessCodesCount ?? '-',

                $digitalContentCount ?? 0,
                $mediaCounts->academic_video_count ?? 0,
                $mediaCounts->academic_file_count ?? 0,
                $mediaCounts->talent_video_count ?? 0,
                $mediaCounts->talent_file_count ?? 0,
                $mediaCounts->total_video_count ?? 0,
                $mediaCounts->total_file_count ?? 0,

                $d2cCounts['OlympiadContentCount'] ?? 0,
                $d2cCounts['JPKitContentCount'] ?? 0,
                $d2cCounts['ActivityWorksheetsContentCount'] ?? 0,
                $totalD2cContent ?? 0,

                $bookSeries ?? 0,
                $bookTitles ?? 0,

                $freeTrialCounts->total_subscription_students ?? '-',
                $freeTrialCounts->total_active_students ?? '-',
                $freeTrialCounts->total_inactive_students ?? '-',
                $subscriptionCounts->total_subscription_students ?? '-',
                $subscriptionCounts->total_active_students ?? '-',
                $subscriptionCounts->total_inactive_students ?? '-',
                number_format($totalRevenue ?? 0, 2),
            ]
        ];
    }
}
