<?php

namespace App\Providers;

use App\Models\AccessCodeOlympiad;
use App\Models\Board;
use App\Models\Medium;
use App\Models\NotificationAlert;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\State;
use App\Models\TeacherDevelopmentContent;
use App\Models\User;
use App\Models\UserClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        Schema::defaultStringLength(191);


        app()->booted(function () {
            $path = trim(request()->path(), '/');
            // Only force lowercase for "mom" route
            if (strcasecmp($path, 'mom') === 0 && $path !== 'mom') {
                return redirect(url('/mom'), 301)->send();
            }
        });
        // app()->booted(function () {
        //     if (request()->path() !== strtolower(request()->path())) {
        //         abort(redirect(strtolower(request()->path()), 301));
        //     }
        // });


        Blade::if('isPermission', function ($perm) {
            return isPermission($perm);
        });

        // Share current user and their additional details globally
        // View::composer('schoolPortal.layouts.master', function ($view) {
        View::composer('*', function ($view) {

            $links                      = Setting::pluck('field_value', 'field_name')->toArray();
            $adminSidebarUserRoles      = Role::where('role_slug', 'like', '%school%')->orWhere('role_slug', 'b2c_student')->orWhere('role_slug', 'd2c_user')->get();
            $adminSidebarsytemUserRoles = Role::where('role_slug', 'not like', '%school%')->where('role_slug', 'not like', 'b2c_student')->where('role_slug', 'not like', 'd2c_user')->where('role_slug', 'not like', 'super_admin')->get();

            $currentUser = Auth::check()
                ? User::with('userAdditionalDetail', 'studentDetails', 'schoolDetails')->find(Auth::id())
                : null;
            $states = State::pluck('name', 'id');
            $studentSelectClasses = SchoolClass::pluck('name', 'id');

            $schoolMediumOptions = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
            $schoolBoardOptions  = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
            $notificationAlerts  = '';
            if (Auth::check() && in_array(optional(Auth::user()->role)->role_slug, ['school_admin', 'school_teacher', 'school_student', 'b2c_student', 'd2c_user'])) {
                $userRole = Auth::user()->role->role_slug;

                $notificationAlerts = NotificationAlert::where('is_active', 1)
                    ->where(function ($query) use ($userRole) {
                        $query->where('role_visibility', 'LIKE', "%{$userRole}%");
                    })
                    ->first();
            }



            //it for the olympiad user data for the header of the user portal 
            $studentProle = getUserRoles();
            $studentPcategory = UserClass::where('user_id', Auth::id())->value('category_id');
            $olympiadSubscribedCourses = AccessCodeOlympiad::where('user_id', Auth::id())->count();


            // Teacher Development Content visibility (Sidebar)
            $hasTdcContent = false;

            if (Auth::check()) {

                $role = getUserRoles();

                $schoolId = null;

                if ($role === 'school_admin') {
                    $schoolId = Auth::id();
                } elseif ($role === 'school_teacher') {
                    $schoolId = Auth::user()->school_id ?? null;
                }

                if ($schoolId) {
                    $hasTdcContent = TeacherDevelopmentContent::where('is_active', 1)
                        ->where(function ($q) use ($schoolId) {
                            $q->where('is_for_all_schools', 1)
                                ->orWhereHas('schools', function ($q2) use ($schoolId) {
                                    $q2->where('schools.user_id', $schoolId);
                                });
                        })
                        ->exists(); // important for performance
                }
            }

            $view->with([
                'links' => $links,
                'studentProle' => $studentProle,
                'studentPcategory' => $studentPcategory,
                'olympiadSubscribedCourses' => $olympiadSubscribedCourses,
                'adminSidebarUserRoles' => $adminSidebarUserRoles,
                'adminSidebarsytemUserRoles' => $adminSidebarsytemUserRoles,
                'currentUser' => $currentUser,
                'schoolMediumOptions' => $schoolMediumOptions,
                'schoolBoardOptions' => $schoolBoardOptions,
                'states' => $states,
                'notificationAlerts' => $notificationAlerts,
                'studentSelectClasses' => $studentSelectClasses,
                'hasTdcContent' => $hasTdcContent,
            ]);
        });
    }
}
