<?php

use App\Http\Controllers\admin\AcademicSessionController;
use App\Http\Controllers\admin\AccessCodeController;
use App\Http\Controllers\admin\AccessCodeOlympiadController;
use App\Http\Controllers\admin\auth\AdminAuthController;
use App\Http\Controllers\admin\AutomationDashboardController;
use App\Http\Controllers\admin\BlogController;
use App\Http\Controllers\admin\BoardController;
use App\Http\Controllers\admin\BookSeriesController;
use App\Http\Controllers\admin\BookSetController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ClassController;
use App\Http\Controllers\admin\CmsPageController;
use App\Http\Controllers\admin\CouponController;
use App\Http\Controllers\admin\CourseBucketController;
use App\Http\Controllers\admin\CourseController;
use App\Http\Controllers\admin\D2cDigitalController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EmailTemplateController;
use App\Http\Controllers\admin\EnquiriesController;
use App\Http\Controllers\admin\ErpDataSyncController;
use App\Http\Controllers\admin\GradeController;
use App\Http\Controllers\admin\HolidayController;
use App\Http\Controllers\admin\LanguageController;
use App\Http\Controllers\admin\LessonController;
use App\Http\Controllers\admin\LevelController;
use App\Http\Controllers\admin\MediaGallaryController;
use App\Http\Controllers\admin\MediumController;
use App\Http\Controllers\admin\NotificationFlashAlertController;
use App\Http\Controllers\admin\OnlineClassLogsController;
use App\Http\Controllers\admin\PlanController;
use App\Http\Controllers\admin\PlannerController;
use App\Http\Controllers\admin\PrefixController;
use App\Http\Controllers\admin\QuestionTypeController;
use App\Http\Controllers\admin\roles\PermissionController;
use App\Http\Controllers\admin\roles\RoleController;
use App\Http\Controllers\admin\SchoolController;
use App\Http\Controllers\admin\SectionController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\SmsTemplateController;
use App\Http\Controllers\admin\StateDistrictController;
use App\Http\Controllers\admin\SubjectController;
use App\Http\Controllers\admin\TeacherDevContentController;
use App\Http\Controllers\admin\TestPaperGenController;
use App\Http\Controllers\admin\TicketController;
use App\Http\Controllers\admin\UploadedContentController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\UserManualController;
use App\Http\Controllers\admin\websitePagesControllers\HomePageContentController;
use App\Http\Controllers\admin\websitePagesControllers\TestimonialController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\erp\admin\ErpConsessionCategoryController;
use App\Http\Controllers\erp\admin\ErpDemoController;
use App\Http\Controllers\erp\admin\ErpFeesHeaderController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\frontend\DemoCoursesController;
use App\Http\Controllers\frontend\FrontPageController;
use App\Http\Controllers\frontend\PlanSubscriptionController;
use App\Http\Controllers\frontend\TestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\mittBunny\MittBunnyPortalController;
use App\Http\Controllers\mittBunny\MittCoursesController;
use App\Http\Controllers\mittBunny\MittDigitalContentController;
use App\Http\Controllers\mittBunny\MittMediaGalleryController;
use App\Http\Controllers\mittBunny\MittOnlineClassesController;
use App\Http\Controllers\mittBunny\MittPlannerController;
use App\Http\Controllers\mittBunny\MittSubscriptionController;
use App\Http\Controllers\OpenAIVisionController;
use App\Http\Controllers\schoolPortal\DailyPlannerController;
use App\Http\Controllers\schoolPortal\LessonPlannerController;
use App\Http\Controllers\schoolPortal\MediaContentController;
use App\Http\Controllers\schoolPortal\MediaGalleryController;
use App\Http\Controllers\schoolPortal\MyCoursesController;
use App\Http\Controllers\schoolPortal\OnlineClassController;
use App\Http\Controllers\schoolPortal\SchoolPortalProfileController;
use App\Http\Controllers\schoolPortal\SchoolPortalUserController;
use App\Http\Controllers\schoolPortal\SpQuestionBankController;
use App\Http\Controllers\schoolPortal\SpTestPaperGenController;
use App\Http\Controllers\schoolPortal\SpTestReviewController;
use App\Http\Controllers\schoolPortal\SpYourLicenseController;
use App\Http\Controllers\schoolPortal\TeacherDevelopmentContentController;
use App\Http\Controllers\userPortal\MyPlannerController;
use App\Http\Controllers\userPortal\OnlineClassesController;
use App\Http\Controllers\userPortal\UserDigitalContentController;
use App\Http\Controllers\userPortal\UserMediaGalleryController;
use App\Http\Controllers\userPortal\UserMyCoursesController;
use App\Http\Controllers\userPortal\UserPortalController;
use App\Http\Controllers\userPortal\UserProfileController;
use App\Http\Controllers\userPortal\UserSubscriptionController;
use App\Http\Controllers\userPortal\UserTestPaperController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
	*/

Route::post('store-session', function (Request $request) {
    if ($request->user_session_id) {
        Session::put('user_session_id', $request->user_session_id);
    }
    if ($request->user_activeTab) {
        Session::put('user_activeTab', $request->user_activeTab);
    }
})->name('store.browser.session');

Route::post('admin/set-pagination', function (Request $request) {
    if ($request->per_page) {
        Session::put('per_page_records', $request->per_page);
        return response()->json(['success' => true]);
    }
})->name('set.pagination');

Route::get('/select-subscription-plan', function (Request $request) {
    $userId        = $request->query('user_id');
    $decodedUserId = base64_decode($userId);

    $user = User::find($decodedUserId);

    Auth::login($user); // Optional: auto-login the user

    return redirect()->route('/'); // The view where user selects a plan
});

Route::controller(TestController::class)->group(function () {
    Route::get('test-sms', 'sms')->name('test.sms.dev');
    Route::get('test-email', 'email')->name('test.email..dev');
    Route::get('test-qr-code', 'sampleQrCode')->name('qr.dev');
    Route::get('test-multiselect', 'multiSelect')->name('multiselect.dev');
    Route::get('test-lat-long', 'getLatLong')->name('getLatLong.dev');

    Route::get('tands/{category}/{ids}', 'talentAndSkillQrRegister')->name('talentAndSkill.qr.register');
    Route::post('tands/qr-store', 'talentAndSkillQrRegisterSubmit')->name('talentAndSkill.qr.register.store');
});
Route::controller(FrontPageController::class)->group(function () {
    Route::get('', 'index')->name('/');
    Route::get('home', 'index')->name('front.index');
    Route::get('about-us', 'aboutUs')->name('about-us');
    Route::get('blogs', 'blogs')->name('blogs');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('contact-us', 'contactUs')->name('contact-us');
    Route::get('refresh-captcha', 'refreshCaptcha')->name('refreshCaptcha');
    Route::post('contact-us', 'contactUsSave')->name('contact-us.save');
    Route::get('privacy-policy', 'privacypolicy')->name('privacy.policy');
    Route::get('terms-and-conditions', 'termsCondition')->name('terms.condition');

    Route::get('about-academic-course/{slug}', 'aboutAcademicCourse')->name('about-acadcourse');
    Route::get('about-talent-skill-course/{slug}', 'aboutNonAcadCourse')->name('about-nonacadcourse');
    Route::get('courses/{category_slug}', 'showCoursesListing')->name('courses.listing');
    Route::get('course/add-to-cart/{course_id}', 'courseAddToCart')->name('course.add-to-cart');
    Route::get('course/go-to-cart', 'goToCart')->name('course.go-to-cart');
    Route::get('get-errors', 'getErrors')->name('get.errors');

    Route::get('our-offerings', 'ourOfferings')->name('our-offerings');
    Route::get('download-app', 'downloadApp')->name('download.app');
    Route::get('access-denied', 'accessDenied')->name('access-denied');
});

// Olympiad Courses Demo page Access
Route::controller(DemoCoursesController::class)->group(function () {
    Route::get('olympiad', 'index')->name('olympiad.course');
    Route::get('olympiad/{slug}', 'aboutOlympiadCourse')->name('about.olympiad.course');

    Route::get('demo/{series_name}', 'demoCoursesContentView')->name('demo.courses.content.view');
});
// user plan subscription process route
Route::controller(PlanSubscriptionController::class)->group(function () {

    Route::get('plan-detail/{id}', 'planDetails')->name('plan.detail');
    Route::get('get-book-series', 'getBookSeries')->name('get.book.series');
    Route::post('get-subcategories/{id}', 'getSubcategories')->name('get-subcategories');
    Route::post('get-courses/{id}', 'getCoursesByCategory')->name('get-courses');
    Route::post('get-courses-by-subcategory/{id}', 'getCoursesBySubCategory')->name('get-courses-by-subcategory');

    Route::get('cart', 'showCart')->name('cart');
    Route::post('delete-item-from-cart', 'deleteItemFromCart');
    Route::post('cart/checkout/process', 'processCheckout')->name('cart.checkout.process');
    Route::post('razorpay-payment', 'store')->name('razorpay.payment.store');
});

Route::get('/courses/get-classes/{seriesId}', [CourseController::class, 'getClassesBySeries'])->name('courses.get-classes');
Route::get('/courses/get-subjects/{seriesId}/{classId}', [CourseController::class, 'getSubjectsBySeriesAndClass'])->name('courses.get-subjects');

// Auth::routes();
Route::redirect('admin', 'admin/login');
Route::prefix('admin',)->group(function () {
    // Admin Authentication Routes
    Route::controller(AdminAuthController::class)->group(function () {
        Route::middleware('admin.guest')->group(function () {
            Route::get('login', 'loginShow')->name('admin.login');
            Route::post('login', 'login')->name('admin.login.submit');
            Route::get('signup', 'registerShow')->name('admin.register');
            Route::post('register', 'register')->name('admin.register.submit');
            Route::get('reset-password', 'resetPasswordShow')->name('admin.reset-password');
            Route::post('reset-password/mail', 'resetPasswordMail')->name('admin.reset-password.mail');
            Route::post('reset-password/otp', 'resetPasswordOtp')->name('admin.reset-password.otp');
        });
        Route::get('logout', 'logout')->name('admin.logout');
    });

    Route::middleware(['auth'])->group(function () {

        // Role & Permission Management
        Route::controller(RoleController::class)->group(function () {
            Route::resource('roles', RoleController::class);
        });
        Route::controller(PermissionController::class)->group(function () {
            Route::resource('permissions', PermissionController::class);
            Route::get('permission/assign', 'assignPermissions')->name('permissions.assign');
            Route::post('permissions/save', 'saveAssigndPermissions')->name('permissions.save');
            Route::post('permissions/assign-to-role', 'assignToRole')->name('permissions.assign-to-role');
            Route::post('permissions/assign-to-user', 'assignToUser')->name('permissions.assign-to-user');

            Route::get('permission/add', 'addPermission')->name('permissions.add');
            Route::get('permission/all', 'allPermissions')->name('permissions.all');
            Route::post('permission/new/save', 'newPermissionSave')->name('permissions.new.save');
            Route::get('permission/delete/{id}', 'permissionDelete')->name('permission.delete');
            Route::get('permission/edit/{id}', 'editPermission')->name('permission.edit');

            // Route::get('permission/store', 'storePermission')->name('permissions.store');
            Route::get('login-as-user/{id}', 'loginAsUser')->name('superadmin.loginAsUser');
            Route::get('back-to-admin', 'backToAdmin')->name('superadmin.backToAdmin');
        });

        // Plan Routes
        Route::controller(PlanController::class)->group(function () {
            Route::get('plans', 'index')->name('plans.index');
            Route::get('plans/add', 'addPlan')->name('plans.add');
            Route::get('plans/edit/{id}', 'editPlan')->name('plans.edit');
            Route::get('plans/view/{id}', 'viewPlan')->name('plans.view');
            Route::post('plans/save', 'savePlan')->name('plans.save');
            Route::get('plans/delete/{id}', 'destroy')->name('plans.delete');

            Route::get('courses/purchase-report', 'purchaseReport')->name('purchase.report');
        });
        Route::controller(CourseBucketController::class)->group(function () {
            Route::get('course-bucket', 'index')->name('course-bucket.index');
            Route::get('course-bucket/add', 'addPlan')->name('course-bucket.add');
            Route::get('course-bucket/edit/{id}', 'editPlan')->name('course-bucket.edit');
            Route::get('course-bucket/view/{id}', 'viewPlan')->name('course-bucket.view');
            Route::post('course-bucket/save', 'savePlan')->name('course-bucket.save');
            Route::get('course-bucket/delete/{id}', 'destroy')->name('course-bucket.delete');
        });

        // Dashboard Routes
        Route::controller(DashboardController::class)->group(function () {
            Route::get('dashboard', 'dashboard')->name('dashboard');
            Route::get('dashboard/download-report', 'dashboardReportExport')->name('dashboard.download.report');
            Route::get('profile', 'profile')->name('profile');

            Route::get('/login-users/{role}', 'viewLoginUsers')->name('login.users.view');
            Route::post('chnage-password', 'changePasswordSave')->name('admin.change-password.save');
            Route::post('profile-update', 'profileUpdate')->name('admin.profile.update');
            Route::get('activity-logs', 'userActivityLog')->name('admin.activity.logs');
        });
        // web.php
        Route::get('/automation-dashboard', [AutomationDashboardController::class, 'index'])->name('crm.automation.dashboard');
        Route::get('/automation-dashboard/export', [AutomationDashboardController::class, 'export'])->name('crm.automation.dashboard.export');
        Route::get('/automation-log', [AutomationDashboardController::class, 'automationLog'])->name('crm.automation.log');

        Route::controller(UserController::class)->group(function () {
            Route::get('user/create', 'userCreate')->name('user.create');
            Route::post('user/save', 'userSave')->name('user.save');
            Route::get('user/index', 'userShow')->name('user.index');
            // Route::get('user/edit/{id}', 'editUser')->name('user.edit');
            Route::get('user/edit/{id}/{verify?}', 'editUser')->name('user.edit');
            Route::get('user/view/{id}', 'viewUser')->name('user.view');
            Route::get('user/delete/{id}', 'userDelete')->name('user.delete');
            Route::get('user/status/{id}', 'userActiveInactive')->name('user.active.inactive');
            Route::get('user/download-sample/{roleKey}', 'downloadSampleFile')->name('user.download.sample');
            Route::post('user/import', 'uploadUsers')->name('user.import');
            Route::get('/courses/{user_id}/{course_id}', 'deleteCourse')->name('delete.course');

            Route::get('assign-digital-content/{id}', 'assignDigitalContent')->name('school.assign.digital.content');
            Route::post('assign-digital-content/save', 'assignDigitalContentSave')->name('school.assign.digital.content.save');
            // Route::post('assign-digital-content/save', 'assignDigitalContentSave')->name('school.assign.digital.content.save');
            Route::post('school-assigned-class/update', 'schoolAssignedClassSave')->name('school.assigned.class.update');
            //new routes
            Route::get('download-users-data', 'dowanloadUsersData')->name('download.users.data');
            Route::post('send-sms/user', 'sendSmsUser')->name('send.sms.user');

            Route::post('change-user-role', 'changeUserRole')->name('superadmin.changeUserRole');


            // CRM School: Verify with notification
            Route::get('crm/school/verify/{id}', 'crmVerifySchool')->name('crm.school.verify');
            // CRM School: Send SMS to RM
            Route::post('crm/school/sms-rm/{id}', 'crmSendSmsToRM')->name('crm.school.sms.rm');
            Route::post('crm/school/remove', 'crmSchoolRemove')->name('crm.school.remove');
            Route::get('crm/sms-logs', 'smsLogsList')->name('crm.sms.logs');
        });

        Route::controller(AccessCodeController::class)->group(function () {
            Route::get('access-code/create', 'accessCodeCreate')->name('access.code.create');
            Route::post('access-code/save', 'accessCodeSave')->name('access.code.save');
            Route::get('access-code/index', 'index')->name('access.code.index');
            Route::get('access-code-info/{id}', 'showInfo')->name('access-code.info');
            Route::get('access-code/edit/{id}', 'editAccessCode')->name('access.code.edit');
            Route::get('access-code/delete/{id}', 'destroy')->name('access.code.delete');
            Route::get('access-code-activate/{id}', 'accessCodeActivate')->name('access.code.activate');
            // Export routes
            Route::post('access-code/export', 'exportCode')->name('access.code.export');
            Route::get('access-code/print', 'print')->name('access.code.print');
            Route::post('access-code-embibe/export', 'exportCodeEmbibe')->name('access.code.embibe.export');
            Route::get('access-code-embibe/print', 'printEmbibe')->name('access.code.embibe.print');
            Route::get('print/setting', 'printSetting')->name('print.setting');
            Route::post('print/setting/save', 'printSettingSave')->name('print.setting.save');

            Route::post('access-code/send/{type}', 'sendCode')->name('access.code.send');

            Route::post('access-code/assign-to-school', 'assignToSchool')->name('assign.to.school');
            Route::get('getSchools/{state}/{city?}', 'getSchools')->name('sp.getSchools');
            Route::post('/revoke-access-code', 'revokeAccessCode')->name('revoke.access.code');
            //new route
            Route::post('/access-code/send-lens-sms/{schoolId}','sendLensSms')->name('access.code.send.lens.sms');
        });
        Route::controller(AccessCodeOlympiadController::class)->group(function () {
            Route::get('access-code-olympiad/create', 'accessCodeCreate')->name('access.code.olympiad.create');
            Route::post('access-code-olympiad/save', 'accessCodeSave')->name('access.code.olympiad.save');
            Route::get('access-code-olympiad/index', 'index')->name('access.code.olympiad.index');
            Route::get('access-code-olympiad/info/{id}', 'showInfo')->name('access-code.olympiad.info');
            Route::get('access-code-olympiad/edit/{id}', 'editAccessCode')->name('access.code.olympiad.edit');
            Route::get('access-code-olympiad/delete/{id}', 'destroy')->name('access.code.olympiad.delete');
            Route::get('access-code-activate/{id}', 'accessCodeActivate')->name('access.code.olympiad.activate');
            Route::post('access-code-olympiad/export', 'exportCode')->name('access.code.olympiad.export');
            Route::get('olympiad/print', 'olympiadPrintSetting')->name('olympiad.print');
            Route::post('access-code-olympiad/print', 'print')->name('access.code.olympiad.print');
            Route::post('/revoke-access-code-olympiad', 'revokeAccessCode')->name('revoke.access.code.olympiad');
        });

        Route::controller(HomePageContentController::class)->group(function () {
            Route::get('home/page-content/add', 'homeContentAdd')->name('home.page-content');
            Route::post('home/page-content/save', 'homeContentSave')->name('home.page-content.save');
        });

        // Route::controller(InstructorController::class)->group(function () {
        // Route::get('home/instructor/index', 'instructorContentindex')->name('home.instructor.index');
        // Route::get('home/instructor/page-content/add', 'instructorContentAdd')->name('home.instructor.page-content.add');
        // Route::post('home/instructor/page-content/save', 'instructorContentSave')->name('home.instructor.page-content.save');
        // Route::get('home/instructor/page-content/edit/{id}', 'instructorContentEdit')->name('home.instructor.page-content.edit');
        // });

        Route::controller(TestimonialController::class)->group(function () {
            Route::get('testimonial/index', 'testimonialContentindex')->name('testimonial.index');
            Route::get('testimonial/page-content/add', 'testimonialContentAdd')->name('testimonial.page-content.add');
            Route::post('testimonial/page-content/save', 'testimonialContentSave')->name('testimonial.page-content.save');
            Route::get('testimonial/page-content/edit/{id}', 'testimonialContentEdit')->name('testimonial.page-content.edit');
            Route::get('testimonial/page-content/delete/{id}', 'testimonialDelete')->name('testimonial.page-content.delete');
        });

        Route::controller(EmailTemplateController::class)->group(function () {
            Route::get('email-template/index', 'listTemplate')->name('email-template.index');
            Route::get('email-template/add', 'addTemplate')->name('email-template.add');
            Route::get('email-template/edit/{id}', 'editTemplate')->name('email-template.edit');
            Route::post('email-template/save', 'saveTemplate')->name('email-template.save');
        });

        Route::controller(SmsTemplateController::class)->group(function () {
            Route::get('sms-template/index', 'index')->name('sms-template.index');
            Route::get('sms-template/add', 'add')->name('sms-template.add');
            Route::get('sms-template/edit/{id}', 'edit')->name('sms-template.edit');
            Route::post('sms-template/save', 'save')->name('sms-template.save');
        });

        Route::controller(CategoryController::class)->group(function () {
            Route::get('category/index', 'index')->name('category.index');
            Route::get('sub-category/add', 'addShow')->name('sub-category.add');
            Route::get('sub-category/field-add/{id}', 'addFields')->name('sub-category.field-add');
            Route::post('sub-category/save', 'save')->name('sub-category.save');
            Route::get('sub-category/edit/{id}', 'edit')->name('sub-category.edit');
            Route::put('sub-category/update/{id}', 'update')->name('sub-category.update');

            Route::post('/category/form-fields/store', 'storeFormField')->name('category.form-fields.store');
        });

        Route::controller(SettingsController::class)->group(function () {
            Route::get('settings/add', 'add')->name('setting.add');
            Route::post('settings/save', 'save')->name('setting.save');
            Route::post('series/save', 'seriesSave')->name('series.save');
        });
        Route::controller(NotificationFlashAlertController::class)->group(function () {
            Route::get('alerts', 'index')->name('flash.notification.alerts');
            Route::get('alerts/add', 'add')->name('flash.notification.alerts.add');
            Route::get('alerts/edit/{id}', 'edit')->name('flash.notification.alerts.edit');
            Route::post('alerts/save', 'save')->name('flash.notification.alerts.save');
            Route::get('alerts/delete/{id}', 'delete')->name('flash.notification.alerts.delete');
        });
        Route::controller(HolidayController::class)->group(function () {
            Route::get('index/holiday', 'index')->name('index.holiday');
            Route::get('add/holiday', 'add')->name('add.holiday');
            Route::get('edit/holiday/{id}', 'edit')->name('edit.holiday');
            Route::get('delete/holiday/{id}', 'delete')->name('delete.holiday');
            Route::post('save/holiday', 'save')->name('save.holiday');
        });
        Route::controller(CmsPageController::class)->group(function () {
            Route::get('cms/add', 'add')->name('cms.add');
            Route::post('cms/save', 'save')->name('cms.save');
            Route::get('cms/edit/{id}', 'edit')->name('cms.edit');
            Route::get('cms/delete/{id}', 'delete')->name('cms.delete');
            Route::get('cms/index', 'index')->name('cms.index');
            Route::get('cms-faq/index', 'faqIndex')->name('cms-faq.index');
            Route::get('cms-faq/add', 'faqAdd')->name('cms-faq.add');
            Route::post('cms-faq/save', 'faqSave')->name('cms-faq.save');
            Route::get('cms-faq/edit/{id}', 'faqEdit')->name('cms-faq.edit');
            Route::get('cms-faq/delete/{id}', 'faqDelete')->name('cms-faq.delete');
            Route::get('cms-about/index', 'aboutUsIndex')->name('cms-about.index');
            Route::get('cms-about/add', 'aboutUsAdd')->name('cms-about.add');
            Route::post('cms-about/save', 'aboutUsSave')->name('cms-about.save');
            Route::get('cms-about/edit/{id}', 'aboutUsEdit')->name('cms-about.edit');
            Route::get('cms-about/delete/{id}', 'aboutUsDelete')->name('cms-about.delete');

            Route::get('our-offerings/add', 'ourOfferingsAdd')->name('our.offerings.add');
            Route::post('our-offerings/save', 'ourOfferingsSave')->name('our.offerings.save');
        });

        Route::controller(CourseController::class)->group(function () {
            Route::get('course/index/{group}', 'index')->name('course.index');
            Route::get('course/create', 'create')->name('course.create');
            Route::post('course/store', 'saveCourse')->name('course.store');
            Route::get('course/chapter/add/{course_id}', 'createChapter')->name('course.add.chapter');
            Route::post('course/chapter/store', 'saveChapter')->name('course.add.chapter.store');
            Route::get('course/chapter/edit/{id}', 'editChapter')->name('course.chapter.edit');
            Route::put('course/chapter/update/{id}', 'updateChapter')->name('course.chapter.update');
            Route::get('course/chapter/delete/{id}', 'deleteChapter')->name('course.chapter.delete');
            Route::get('course/chapter/file/delete/{id}', 'deleteChapterFiles')->name('course.chapter.file.delete');
            Route::get('course/edit/{id}', 'edit')->name('course.edit');
            Route::put('course/update/{id}', 'update')->name('course.update');
            Route::get('course/delete/{id}', 'delete')->name('course.delete');
            Route::get('course-activate/{id}', 'courseActivate')->name('course.activate');

            Route::get('course/add', 'addShow')->name('course.add');
            Route::get('course/add-more', 'addMore')->name('course.add-more');
            Route::post('course/save-more', 'saveMore')->name('course.save-more');
            // Route::get('course/details/{id}', 'showCourse')->name('course.details');
            Route::get('course/cities/{state}', 'getCities')->name('getCities');
            Route::get('get-schools/{city}', 'getSchools')->name('getSchools');

            Route::get('course/complimentary/index', 'complimentaryIndex')->name('course.complimentary.index');
            Route::post('complimentary/course/store', 'saveComplimentaryCourse')->name('complimentary.course.store');

            Route::get('course/academic-activities/index', 'academicActivitiesIndex')->name('course.academic-activities.index');

            Route::get('course/bulk-upload', 'bulkUpload')->name('courses.bulk-upload');
            Route::get('course/chapter/bulk-upload/{id}', 'chapterBulkUpload')->name('courses.chapter.bulk-upload');

            Route::get('export-courses', 'exportCoursesToExcel')->name('export.courses');

            //course merege routes

            Route::get('merge-course', 'mergeCourse')->name('merge.course');
            Route::post('merge-course-submit', 'mergeCourseSubmit')->name('merge.course.submit');
        });

        Route::controller(TeacherDevContentController::class)->group(function () {
            Route::get('teacher-development', 'index')->name('teacher.development.index');
            Route::get('teacher-development/create', 'create')->name('teacher.development.create');
            Route::post('teacher-development', 'store')->name('teacher.development.store');
            Route::get('teacher-development/{id}/edit', 'edit')->name('teacher.development.edit');
            Route::put('teacher-development/{id}', 'update')->name('teacher.development.update');
            Route::delete('teacher-development/{id}', 'destroy')->name('teacher.development.destroy');

            Route::get('teacher-development/{id}/assign-schools-modal', 'assignSchoolsModal')->name('teacher.development.assignSchools.modal');
            Route::post('teacher-development/{id}/assign-schools', 'saveAssignedSchools')->name('teacher.development.saveAssignedSchools');
        });
        Route::controller(CouponController::class)->group(function () {
            Route::get('coupon/index', 'index')->name('coupon.index');
            Route::get('coupon/create', 'create')->name('coupon.create');
            Route::get('coupon/edit/{id}', 'edit')->name('coupon.edit');
            Route::put('coupon/update/{id}', 'storeAndUpdate')->name('coupon.update');
            Route::post('coupon/store', 'storeAndUpdate')->name('coupon.store');
            Route::delete('coupons/{id}', 'destroyCoupon')->name('coupon.destroy');
        });

        Route::controller(PlannerController::class)->group(function () {
            Route::get('planner/index', 'index')->name('planner.index');
            Route::get('planner/create', 'create')->name('planner.create');
            Route::get('planner/bulk', 'bulkUpload')->name('planner.bulk');
            Route::post('planner/save', 'plannerSave')->name('planner.save');
            Route::get('planner/edit/{id}', 'plannerEdit')->name('planner.edit');
            Route::put('planner/update', 'plannerSave')->name('planner.update');
            Route::put('planner/lesson/update', 'plannerLessonSave')->name('planner.lesson.update');
            Route::get('planner/delete/{id}', 'deletePlanner')->name('planner.delete');
            Route::get('planner/view/{id}', 'viewPlanner')->name('planner.view');
            Route::get('planner/get-chapters', 'getChapters')->name('planner.get.chapters');
            Route::get('/get-batch-by-session/{name}', 'getBatchBySessionByName')->name('academic-session.get-batch');
        });

        Route::controller(TestPaperGenController::class)->group(function () {
            Route::get('test-paper/index', 'index')->name('test-paper.index');
            Route::get('test-paper/create', 'create')->name('test-paper.create');
            Route::get('test-paper/edit/{id}', 'edit')->name('test-paper.edit');
            Route::post('test-paper/save', 'Save')->name('test-paper.save');
            Route::get('test-paper/delete{id}', 'delete')->name('test-paper.delete');
            Route::get('question-bank/create', 'questionBankCreate')->name('question-bank.create');
            Route::get('question-bank/index', 'questionBankIndex')->name('question-bank.index');
            Route::get('question-bank/edit/{id}', 'questionEdit')->name('question-bank.edit');
            Route::get('question-bank/delete/{id}', 'questionDelete')->name('question-bank.delete');
            Route::get('question/add/{id}', 'questionAdd')->name('question.add');
            Route::post('question/save', 'questionSave')->name('question.save');

            Route::get('test-paper/get/book-series', 'getSeries')->name('test-paper.get.book.series');

            Route::get('quill', 'quill')->name('quill');
        });

        Route::controller(BlogController::class)->group(function () {
            Route::get('blog/create', 'blogCreate')->name('blog.create');
            Route::post('blog/store', 'blogSave')->name('blog.save');
            Route::get('blog/index', 'blogShow')->name('blog.index');
            Route::get('blog/edit/{id}', 'blogEdit')->name('blog.edit');
            // Route::post('blog/update/{id}', 'blogUpdate')->name('blog.update');
            Route::get('blog/delete/{id}', 'blogDelete')->name('blog.delete');
            Route::get('blog/category/index', 'blogCategoryShow')->name('blog.category.index');
            Route::get('blog/category/create', 'blogCategoryCreate')->name('blog.category.create');
            Route::post('blog/category/store', 'blogCategorySave')->name('blog.category.save');
            Route::get('blog/sub-category/create/{id}', 'blogSubCategoryCreate')->name('blog.sub_category.create');
            Route::post('blog/sub-category/store', 'blogSubCategoryStore')->name('blog.sub_category.store');
            Route::get('blog/category/delete/{id}', 'blogCategoryDelete')->name('blog.category.delete');
            Route::get('blog/category/edit/{id}', 'blogCategoryEdit')->name('blog.category.edit');
            // Route::put('blog/category/update/{id}', 'blogCategoryUpdate')->name('blog.category.update');
            Route::get('blog/categories/subcategories', 'showSubcategories')->name('blog.category.subcategories');
            Route::get('blog/subcategories/{categoryId}', 'getBlogSubcategories');
        });

        Route::controller(BoardController::class)->group(function () {
            Route::get('boards/create/', 'createBoard')->name('board.create');
            Route::get('boards/edit/{id}', 'editBoard')->name('board.edit');
            Route::post('boards/save', 'boardSave')->name('board.save');
            Route::get('board/index', 'boardShow')->name('board.index');
            Route::get('board/delete/{id}', 'boardDelete')->name('board.delete');
        });

        Route::controller(ClassController::class)->group(function () {
            Route::get('class/create/', 'createClass')->name('class.create');
            Route::get('class/edit/{id}', 'editClass')->name('class.edit');
            Route::post('class/save', 'classSave')->name('class.save');
            Route::get('class/index', 'classShow')->name('class.index');
            Route::get('class/delete/{id}', 'classDelete')->name('class.delete');
        });
        Route::controller(SectionController::class)->group(function () {
            Route::get('section/create/', 'createSection')->name('section.create');
            Route::get('section/edit/{id}', 'editSection')->name('section.edit');
            Route::post('section/save', 'sectionSave')->name('section.save');
            Route::get('section/index', 'sectionShow')->name('section.index');
            Route::get('section/delete/{id}', 'sectionDelete')->name('section.delete');
        });

        Route::controller(BookSetController::class)->group(function () {
            Route::get('bookset/create/', 'createBookSet')->name('bookset.create');
            Route::get('bookset/edit/{id}', 'editBookSet')->name('bookset.edit');
            Route::post('bookset/save', 'bookSetSave')->name('bookset.save');
            Route::get('bookset/index', 'bookSetShow')->name('bookset.index');
            Route::get('bookset/delete/{id}', 'bookSetDelete')->name('bookset.delete');
        });

        Route::controller(LevelController::class)->group(function () {
            Route::get('level/create/', 'createLevel')->name('level.create');
            Route::get('level/edit/{id}', 'editLevel')->name('level.edit');
            Route::post('level/save', 'levelSave')->name('level.save');
            Route::get('level/index', 'levelShow')->name('level.index');
            Route::get('level/delete/{id}', 'levelDelete')->name('level.delete');
        });
        Route::controller(GradeController::class)->group(function () {
            Route::get('grade/create/', 'createGrade')->name('grade.create');
            Route::get('grade/edit/{id}', 'editGrade')->name('grade.edit');
            Route::post('grade/save', 'gradeSave')->name('grade.save');
            Route::get('grade/index', 'gradeShow')->name('grade.index');
            Route::get('grade/delete/{id}', 'gradeDelete')->name('grade.delete');
        });

        Route::controller(LanguageController::class)->group(function () {
            Route::get('language/create/', 'createLanguage')->name('language.create');
            Route::get('language/edit/{id}', 'editLanguage')->name('language.edit');
            Route::post('language/save', 'languageSave')->name('language.save');
            Route::get('language/index', 'languageShow')->name('language.index');
            Route::get('language/delete/{id}', 'languageDelete')->name('language.delete');
        });
        Route::controller(LessonController::class)->group(function () {
            Route::get('lesson/create/', 'createLessonNumber')->name('lesson.create');
            Route::get('lesson/edit/{id}', 'editLessonNumber')->name('lesson.edit');
            Route::post('lesson/save', 'lessonNumberSave')->name('lesson.save');
            Route::get('lesson/index', 'lessonNumberShow')->name('lesson.index');
            Route::get('lesson/delete/{id}', 'lessonNumberDelete')->name('lesson.delete');
        });

        Route::controller(SubjectController::class)->group(function () {
            Route::get('subject/create/', 'createSubject')->name('subject.create');
            Route::get('subject/edit/{id}', 'editSubject')->name('subject.edit');
            Route::post('subject/save', 'subjectSave')->name('subject.save');
            Route::get('subject/index', 'subjectShow')->name('subject.index');
            Route::get('subject/delete/{id}', 'subjectDelete')->name('subject.delete');
        });
        Route::controller(QuestionTypeController::class)->group(function () {
            Route::get('question-type/create/', 'create')->name('question-type.create');
            Route::get('question-type/edit/{id}', 'edit')->name('question-type.edit');
            Route::post('question-type/save', 'save')->name('question-type.save');
            Route::get('question-type/index', 'show')->name('question-type.index');
            Route::get('question-type/delete/{id}', 'delete')->name('question-type.delete');
        });

        Route::controller(MediumController::class)->group(function () {
            Route::get('medium/create/', 'createMedium')->name('medium.create');
            Route::get('medium/edit/{id}', 'editMedium')->name('medium.edit');
            Route::post('medium/save', 'mediumSave')->name('medium.save');
            Route::get('medium/index', 'mediumShow')->name('medium.index');
            Route::get('medium/delete/{id}', 'mediumDelete')->name('medium.delete');
        });
        Route::controller(MediaGallaryController::class)->group(function () {
            Route::get('media/gallery/upload', 'mediaGalleryUpload')->name('media.gallery.upload');
            Route::post('create/folder', 'createFolder')->name('create.folder');
            Route::get('folder/list', 'folderList')->name('folder.list');
            Route::get('media/gallery/folder/view/{id}', 'mediaGalleryFolderView')->name('media.gallery.folder.view');
            Route::post('store/files', 'storeFile')->name('store.files');
            Route::get('media/gallery/delete/{id}', 'mediaGalleryDelete')->name('media.gallery.delete');
            Route::get('media/gallery-file/delete/{id}', 'fileDelete')->name('media.gallery.file.delete');
            Route::get('class/media/gallery/folder', 'classmediaGalleryFolder')->name('class.media.gallery.folder');
            Route::post('media-gallery/distribute', 'mediaGalleryDistribute')->name('media.gallery.distribute');
            Route::get('get/user-to-assign-deck', 'getUserToAssignDeck')
                ->name('get.user.to.assign.deck');
            Route::get('media/folder/{id}/remove-role/{role}', 'removeAssignedRole')->name('media.folder.remove.role');
            Route::get('media/folder/{id}/remove-school/{schoolId}', 'removeAssignedSchool')->name('media.folder.remove.school');
            Route::get('media/folder/{id}/remove-teacher/{teacherId}', 'removeAssignedTeacher')->name('media.folder.remove.teacher');
            Route::get('media/folder/{id}/remove-series/{seriesId}', 'removeAssignedSeries')->name('media.folder.remove.series');
        });

        Route::controller(BookSeriesController::class)->group(function () {
            Route::get('book-series/create/', 'createBookseries')->name('book.series.create');
            Route::get('book-series/edit/{id}', 'editBookSeries')->name('book.series.edit');
            Route::post('book-series/save', 'bookSeriesSave')->name('book.series.save');
            Route::get('book-series/index', 'bookSeriesShow')->name('book.series.index');
            Route::get('book-series/delete/{id}', 'bookSeriesDelete')->name('book.series.delete');
        });
        //new routes
        Route::controller(StateDistrictController::class)->group(function () {
            Route::get('state-district/create/', 'createState')->name('state.district.create');
            Route::get('state-district/edit/{id}', 'editState')->name('state.district.edit');
            Route::post('state-district/save', 'stateSave')->name('state.district.save');
            Route::get('state-district/index', 'stateShow')->name('state.district.index');
            Route::get('state-district/delete/{id}', 'stateDelete')->name('state.district.delete');

            Route::get('city-district/create/{id}', 'createDistrict')->name('district.create');
            Route::get('city-district/edit/{id}', 'editDistrict')->name('district.edit');
            Route::post('city-district/save', 'districtSave')->name('district.save');
            Route::get('city-district/index/{id}', 'districtShow')->name('district.index');
            Route::get('city-district/delete/{id}', 'districtDelete')->name('district.delete');
        });

        Route::controller(AcademicSessionController::class)->group(function () {
            Route::get('academic-session/create/', 'createAcademicSession')->name('academic.session.create');
            Route::get('academic-session/edit/{id}', 'editAcademicSession')->name('academic.session.edit');
            Route::post('academic-session/save', 'academicSessionSave')->name('academic.session.save');
            Route::get('academic-session/index', 'academicSessionShow')->name('academic.session.index');
            Route::get('academic-session/delete/{id}', 'academicSessionDelete')->name('academic.session.delete');
        });

        Route::controller(EnquiriesController::class)->group(function () {
            Route::get('enquiries', 'allEnquiries')->name('enquiries');
            Route::get('enquiry/view/{id}', 'enquiryView')->name('enquiry.view');
            Route::post('enquiry/save/{id}', 'enquirySave')->name('enquiry.save');
        });
        Route::controller(SchoolController::class)->group(function () {
            Route::get('school-list', 'schoolList')->name('school.list');
            Route::get('school-verify/{id}', 'schoolVerify')->name('school.verify');
            Route::get('school-access-code/{id}', 'schoolAccessCode')->name('school.access.code');
            Route::get('school-edit/{id}', 'schoolEdit')->name('school.edit');
            Route::post('school-update', 'schoolUpdate')->name('school.update');
            Route::get('school-access-code/delete/{id}', 'schoolAccessDeleted')->name('school.access.code.delete');
            // Export routes
            Route::get('school-access-code/export/excel/{classId}', 'exportExcel')->name('class.access.code.export.excel');
            Route::get('school-access-code/export/csv/{classId}', 'exportCSV')->name('class.access.code.export.csv');
            Route::get('school-access-code/export/print/{classId}', 'exportPrint')->name('class.access.code.export.print');
            Route::get('school-users', 'schoolUsers')->name('school.users');
            Route::get('school-users/details/{id}', 'schoolUsersDetails')->name('school.users.details');
            // new route
            Route::get('all-school-export', 'allSchoolsExport')->name('all-school-export');
            Route::get('sp/cities/{state}', 'getCities')->name('school.getCities');
        });
        Route::controller(PrefixController::class)->group(function () {
            Route::get('prefix-list', 'prefixList')->name('prefix.list');
            Route::get('prefix-edit/{id}', 'prefixEdit')->name('prefix.edit');
            Route::post('prefix-update', 'prefixUpdate')->name('prefix.update');
            Route::get('prefix-access-code/delete/{id}', 'prefixDelete')->name('prefix.delete');
        });
        Route::controller(D2cDigitalController::class)->group(function () {
            Route::get('d2c-user-category/index', 'd2cCategoryIndex')->name('d2c-category.index');
            Route::get('d2c-user-content/assginment/{id}', 'd2cDigitalContent')->name('d2c-content.assginment');
            Route::post('d2c-user-content/class/update', 'd2cClassUpdate')->name('d2c-content.class.update');
            Route::post('d2c-user-content/courses', 'd2cCourses')->name('d2c-content.courses');
            Route::get('/download-qr/{filename}', 'download')->name('qr.download');

            Route::post('d2c-user-content/class-content/update', 'd2cClassContentUpdate')->name('d2c-content.class.course.update');

            Route::get('talent-skill-content/assginment/{id}', 'talentDigitalContent')->name('talent-skill.assginment');
        });

        Route::controller(OnlineClassLogsController::class)->group(function () {
            Route::get('online/class/logs', 'onlineClassLogs')->name('online.class.logs');
            Route::get('online/class/log/details/{id}', 'onlineClassLogDetails')->name('online.class.log.details');
        });
        Route::controller(UploadedContentController::class)->group(function () {
            Route::get('folder/index', 'folderListing')->name('folder.index');
            Route::get('folder/school-teacher/{id}', 'getTeacheListBySchool')->name('folder.teacher');
            Route::get('files/index/{id}', 'filesListing')->name('files.index');
        });

        // Erp Data routes
        Route::prefix('erp-data')->controller(ErpDataSyncController::class)->group(function () {
            Route::get('schools', 'schoolsIndex')->name('erp-data.schools.index');
            Route::get('add/schools/{id}', 'addSchools')->name('erp-data.add.schools');
            Route::post('save/schools', 'saveSchool')->name('erp-data.save.schools');
            Route::get('teachers', 'teachersIndex')->name('erp-data.teachers.index');
            Route::post('save/teachers', 'saveTeacher')->name('erp-data.save.teachers');
            Route::get('students', 'studentsIndex')->name('erp-data.students.index');
            Route::post('move-to-lms', 'moveToLms')->name('erp-data.moveToLms');
        });
        Route::controller(UserManualController::class)->group(function () {
            Route::get('user-manual', 'index')->name('user-manual.index');
            Route::get('user-manual/add', 'add')->name('user-manual.add');
            Route::get('user-manual/edit/{id}', 'edit')->name('user-manual.edit');
            Route::post('user-manual/save', 'save')->name('user-manual.save');
            Route::get('user-manual/delete/{id}', 'delete')->name('user-manual.delete');
        });

        Route::controller(TicketController::class)->group(function () {
            Route::get('tickets', 'index')->name('tickets.index');
            Route::get('tickets/create', 'create')->name('tickets.create');
            Route::post('tickets/', 'store')->name('tickets.store');
            Route::get('tickets/{ticket}', 'show')->name('tickets.show');
            Route::get('tickets/{ticket}/edit', 'edit')->name('tickets.edit');
            Route::put('tickets/{ticket}', 'update')->name('tickets.update');
            Route::delete('tickets/{ticket}', 'destroy')->name('tickets.destroy');

            // Enhanced routes
            Route::post('tickets/{ticket}/comments', 'addComment')->name('tickets.comments.add');
            Route::post('tickets/{ticket}/watchers', 'addWatcher')->name('tickets.watchers.add');
            Route::post('tickets/{ticket}/attachments', 'uploadAttachment')->name('tickets.attachments.upload');
            Route::post('tickets/{ticket}/time-logs', 'logTime')->name('tickets.time-logs.add');
            Route::patch('tickets/{ticket}/status', 'updateStatus')->name('tickets.status.update');
            Route::patch('tickets/{ticket}/reopen', 'reopen')->name('tickets.reopen');
        });
    });
});

Route::get('stream-video/{file}', [FileController::class, 'streamVideo'])->name('stream.video');

Route::prefix('school-portal')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::controller(\App\Http\Controllers\schoolPortal\DashboardController::class)->group(function () {
            Route::get('dashboard', 'dashboard')->name('sp.dashboard');
            Route::get('active-access-Code/download', 'activeAccessCodeDownload')->name('active-access-Code.download');
            Route::get('download-app', 'downloadApp')->name('sp.download-app.page');
            Route::get('user-manuals', 'userManual')->name('sp.user.manual');
        });
        Route::controller(SchoolPortalProfileController::class)->group(function () {
            Route::post('upload-profile-image', 'uploadProfileImage')->name('sp.upload.profile.image');
            Route::post('change-password', 'changePassword')->name('sp.change.password');
            Route::post('update-profile-details', 'updateProfileDetails')->name('sp.update.profile.details');
            Route::post('update-profile-address', 'updateProfileAddress')->name('sp.update.profile.address');
        });
        Route::controller(DailyPlannerController::class)->group(function () {
            Route::get('planner', 'getPlanner')->name('daily.planner');
            Route::post('mark-holiday', 'markHoliday')->name('daily.planner.mark.holiday');
            Route::get('chapter/details/{id}', 'chapterDetails')->name('chapter.details');
            Route::get('folder/documents/{id}', 'folderDocument')->name('chapter.documents');

            Route::post('planner/visibilty', 'plannerVisibilty')->name('sp.planner.visibilty');
            Route::post('confirm/planner-complete/{id}', 'confirmPlannerComplete')->name('sp.confirm.planner.complete');
        });
        Route::controller(SchoolPortalUserController::class)->group(function () {
            Route::get('student/manager', 'studentManager')->name('sp.student.manager');
            Route::get('student/add', 'studentAdd')->name('sp.student.add');
            Route::get('student/edit/{id}', 'studentEdit')->name('sp.student.edit');
            Route::get('un-verfired/student', 'UnVerfiredStudent')->name('sp.un-verfired.student');
            Route::post('student/save', 'userSave')->name('sp.student.save');
            Route::post('un-verfired/student/save', 'UnVerfiredStudentSave')->name('sp.un-verfired.student.save');
            Route::post('user/toggle-status/{id}', 'toggleStatus')->name('user.toggle.status');
            Route::get('user/logs/{id}/', 'getUserLogs')->name('user.logs');

            Route::get('teacher/manager', 'teacherManager')->name('sp.teacher.manager');
            Route::get('teacher/add-edit', 'teacherAddEdit')->name('sp.teacher.add-edit');
            Route::get('teacher/edit/{id}', 'teacherEdit')->name('sp.teacher.edit');
            Route::post('teacher/save', 'userSave')->name('sp.teacher.save');
            Route::get('edit/teacher', 'editTeacher')->name('sp.edit.teacher');
            // Route::get('students/{id}', 'getStudentData');
            Route::get('cities/{state}', 'getCities')->name('sp.getCities');
            Route::get('export-students', 'exportStudents')->name('export.students');
            Route::get('teachers/export', 'exportTeachers')->name('teachers.export');
            Route::get('{userType}/login-aceess/export', 'exportLoginAceess')->name('login.access.details.export');

            Route::get('teacher/access', 'teacherLoginAceess')->name('sp.check.teacher.access');
            Route::get('student/access', 'studentLoginAceess')->name('sp.check.student.access');

            Route::get('branch-schools', 'branchSchools')->name('sp.branch.schools');
            Route::get('branch-schools/login/{id}', 'branchSchoolLogin')->name('sp.branch.schools.login');
            Route::get('branch-schools/back-to-parent', 'backToParent')->name('sp.back.to.parent');
        });

        Route::controller(OnlineClassController::class)->group(function () {
            Route::get('online/class', 'show')->name('online.class');
            Route::post('online/class', 'store')->name('online-classes.store');
            Route::get('online/class/details/{id}', 'onlineClassDetails')->name('online.class.details');
            Route::post('store/study/material', 'storeFile')->name('online.class.store.files');
        });

        Route::controller(MediaContentController::class)->group(function () {
            Route::get('content/upload', 'contentUpload')->name('content.upload');
            Route::post('create/folder', 'createFolder')->name('sp.create.folder');
            Route::get('content/folder/view/{id}', 'contentFolderView')->name('content.folder.view');
            Route::post('store/files', 'storeFile')->name('sp.content.store.files');
            Route::get('content/delete/{id}', 'contentDelete')->name('content.delete');
            Route::get('content-folder-file/delete/{id}', 'fileDelete')->name('content.folder.file.delete');
            Route::get('class/content/folder', 'classContentFolder')->name('class.content.folder');
            Route::get('search-class-courses', 'search')->name('class.course.search');
        });

        Route::controller(MyCoursesController::class)->group(function () {
            Route::get('my-courses', 'myCourses')->name('sp.my.courses');
            Route::get('class-subject/{id}', 'classSubject')->name('sp.class.subject');
            Route::get('my-courses/class-subjects/{id}', 'fetchClassSubjects')->name('class.subjects.ajax');

            Route::get('course-listing/access-codes/{id}', 'classAccessCodeView')->name('sp.access.codes');
            Route::get('course-listing/{id}/{class_id}', 'courseListing')->name('sp.course.listing');
            Route::post('course-listing/assign-access-codes', 'assignAccessCodes')->name('sp.assign.access.codes');
            Route::get('my-courses/courses-details/{id}/{classId}/{subjectId}', 'coursesDetails')->name('sp.courses.details');
        });
        Route::controller(LessonPlannerController::class)->group(function () {
            Route::get('lesson-planner', 'lessonPlanner')->name('sp.lesson.planner');
            Route::get('lesson-planner/class-subject/{id}', 'classSubject')->name('sp.lesson.planner.subjects');
            Route::get('lesson-planner/course-listing/{id}/{class_id}', 'courseListing')->name('sp.lesson.planner.course.listing');
            Route::get('lesson-planner/chapter/details/{id}/{classId}/{subjectId}', 'chapterDetails')->name('lesson.planner.chapter.details');
            Route::get('lesson/chapter-plannner/{id}/{course_id}/{subject_id}/{class_id}', 'chapterPlanner')->name('lesson.chapter-plannner');
            Route::get('chapter/supporting-documents/download/{id}', 'downloadSupportingDocuments')->name('chapter.supporting-documents.download');
        });
        Route::controller(SpTestPaperGenController::class)->group(function () {
            Route::get('test-papers', 'index')->name('sp.test-papers');
            Route::get('test-papers/add', 'testPapersAdd')->name('sp.test-papers.add');
            Route::get('test-papers/edit/{id}', 'testPapersEdit')->name('sp.test-papers.edit');
            Route::post('test-papers/save', 'testPapersSave')->name('sp.test-papers.save');
            Route::get('test-paper-view/{id}', 'testPaperView')->name('sp.test-paper-view');
            Route::get('test-paper/delete/{id}', 'testPaperDelete')->name('sp.test-paper.delete');
            Route::get('/tests/{paperId}/pdf/{user}', 'generatePDF')->name('tests.pdf');
            Route::get('tests/download/{paperId}/{user}/{format}', 'download')->name('tests.download');
            Route::get('tests/download-hindi/{paperId}/{user}/{format}', 'download')->name('tests.download-hindi');

            Route::get('test-paper/get-chapters', 'getChapters')->name('sp.test-paper.get-chapters');
            Route::get('test-paper/add-question/{id}', 'addQuestion')->name('sp.test-paper.add-question');
            Route::post('update-approval-status', 'updateApproval')->name('update.approval.status');
            Route::get('/get-students/{classId}', 'getStudents')->name('get.students');
            Route::post('/assign-test', 'assignTest')->name('assign.test');
            Route::post('/assign-test/questions', 'assignTestQuestions')->name('assign.test.questions');
            Route::get('/get-participants/{testId}/{classId}', 'getParticipants')->name('get.participants');

            Route::get('/get-classes-by-series', 'getClassesBySeries')->name('get.classes.by.series');
            Route::get('/get-subjects-by-class', 'getSubjectsByClass')->name('get.subjects.by.class');
            // Route::get('test-paper/question-bank', 'questionBank')->name('sp.question.bank');
        });
        Route::controller(SpQuestionBankController::class)->group(function () {
            Route::get('test-paper/question-bank/create', 'createQuestionBank')->name('sp.create.question.bank');
            Route::get('test-paper/question-bank', 'questionBank')->name('sp.question.bank');
            Route::get('test-paper/question-bank/edit/{id}', 'questionEdit')->name('sp.question-bank.edit');
            Route::post('test-paper/question-bank/delete/{id}', 'questionDelete')->name('sp.question-bank.delete');
        });
        Route::controller(SpTestReviewController::class)->group(function () {
            Route::get('test-paper-review', 'indexView')->name('sp.test-paper.view');
            Route::get('test-paper-review/{id}/users', 'tpAssignedUsersView')->name('sp.test-paper.assigned.users');
            Route::get('test-paper-review/remark/{id}/{user_id}/{test_id}', 'tpRemark')->name('sp.test-paper.remark');
            Route::get('test-paper-review/{id}/user-answers/{user_id}/{test_id}', 'tpReview')->name('sp.test-paper.review');
            Route::post('sp/question/subjective/score/submit', 'saveSubjectiveScore')->name('sp.question.subjective.score.submit');

            Route::post('sp/question/score/submit', 'savePassageScores')->name('sp.question.score.submit');
        });
        Route::controller(SpYourLicenseController::class)->group(function () {
            Route::get('your-license', 'yourLicense')->name('sp.your-license');
            Route::post('your-license-embibe/export', 'exportCodeEmbibe')->name('your-license.embibe.export');
            Route::get('your-license-embibe/print', 'printEmbibe')->name('your-license.embibe.print');

            Route::get('get-class-users', 'getClassUsers')->name('get.class.users');
            Route::post('send-access-code', 'sendAccessCodeMittlense')->name('send.access.code.mittlense');
            Route::post('/save-access-codes', 'saveAccessCodesTeachlite')->name('save.access.codes');

            // Route::get('your-license/{id}', 'yourLicense')->name('sp.your-license');
        });

        Route::controller(MediaGalleryController::class)->group(function () {
            Route::get('media-gallery/list', 'mediaGalleryList')->name('gallery.list');
            Route::post('media-gallery/create', 'createMediaGallery')->name('sp.media.gallery.create');
            Route::get('media/gallery/view/{id}', 'mediaGalleryView')->name('media.gallery.view');
            Route::get('media/gallery/delete/{id}', 'mediaGalleryDetele')->name('sp.media.gallery.delete');
            Route::post('media-gallery/store/files', 'storeFile')->name('sp.store,media-gallery.files');
            Route::get('file/delete/{id}', 'fileDelete')->name('sp.media.gallery.file.delete');
            Route::get('content-file/delete/{id}', 'fileDelete')->name('content.file.delete');
            Route::get('class/content/folder', 'classContentFolder')->name('class.content.folder');
            Route::get('search-class-courses', 'search')->name('class.course.search');
        });
        Route::controller(TeacherDevelopmentContentController::class)->group(function () {
            Route::get('/faculty-development', 'index')->name('sp.teacher.development.index');
            Route::get('/faculty-development/{id}/videos', 'viewVideos')->name('sp.teacher.development.videos');
        });
    });
});

Route::prefix('')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::controller(UserPortalController::class)->group(function () {
            Route::get('dashboard', 'dashboard')->name('up.dashboard');

            Route::get('classes/{date}', 'showDashClasses')->name('up.show.classes');
            Route::get('download/app', 'downloadApp')->name('up.download.app.page');

            Route::post('vallidate/access-code', 'vallidateAccessCode')->name('vallidate.access.code');
        });
        Route::controller(UserDigitalContentController::class)->group(function () {
            Route::get('digital-content', 'digitalContent')->name('up.digitalContent');
            Route::get('digital-content/files/{id}', 'digitalContentFiles')->name('up.digital-content-files');
        });
        Route::controller(UserMediaGalleryController::class)->group(function () {
            Route::get('media-gallery', 'mediaGallery')->name('up.media-gallery');
            Route::get('media-gallery-files/{id}', 'mediaGalleryFiles')->name('up.media-gallery.files');
        });
        Route::controller(UserSubscriptionController::class)->group(function () {
            Route::get('subscription', 'subscription')->name('up.subscription');
            Route::post('upgrade/subscription', 'subscriptionUpgrade')->name('up.upgrade.subscription');
            Route::post('upgarde-plan/otp', 'upgradePlanOtp')->name('up.upgarde-plan.otp');
            Route::post('resend/otp', 'resendOtp')->name('up.resend.otp');
            Route::post('upgrade-plan/otp-check', 'subscriptionOtpCheck')->name('up.upgrade.subscription.otp.check');
        });
        Route::controller(OnlineClassesController::class)->group(function () {
            Route::get('online-class', 'onlineClass')->name('up.online.class');
            Route::get('online-class/digital-content/{id}', 'onlineClassDigitalContent')->name('up.online.class.digital.content');
            Route::post('join-class', 'onlineClassJoinLogs')->name('join.class');
        });
        Route::controller(UserMyCoursesController::class)->group(function () {
            Route::get('my-courses', 'myCourses')->name('up.my.courses');
            Route::get('my-courses/{slug}', 'coursesListing')->name('up.course.listing');
            Route::get('my-course/digital-content/{id}', 'courseDigitalContent')->name('up.course.digital-content');
            Route::get('my-courses/{slug}/{id}', 'coursesChapterListing')->name('up.courses.chapter.listing');
            Route::get('non-acad/course-detail', 'nonAcadCourseDetail')->name('up.non-acad.course.detail');
            Route::get('get-chapter-details/{id}/{plannerId?}', 'getChapterDetails')->name('up.courses.chapter.details');
            Route::get('certificate/download/{course_id}', 'certificateDownload')->name('up.courses.certificate.download');
        });
        Route::controller(FileController::class)->group(function () {
            Route::post('/save-user-video-duration', 'saveUserVideoDuration')->name('save.duration');
            Route::post('/update-user-video-progress', 'saveUserVideoProgress')->name('update.user.video.progress');
        });
        Route::controller(UserTestPaperController::class)->group(function () {
            Route::get('test-papers', 'testPaperList')->name('up.test.paper.list');
            Route::get('test-paper-questions/{id}', 'testPaperQuestion')->name('up.test.paper.question');
        });
        Route::get('/start-test-session/{id}', function ($id) {
            session(['testStarted' => true]);
            return response()->json(['status' => 'started']);
        })->name('start.test.session');

        Route::controller(UserProfileController::class)->group(function () {
            Route::post('upload-profile-image', 'uploadProfileImage')->name('up.upload.profile.image');
            Route::post('change-password', 'changePassword')->name('up.change.password');
            Route::post('update-profile-details', 'updateProfileDetails')->name('up.update.profile.details');
            Route::post('update-profile-address', 'updateProfileAddress')->name('up.update.profile.address');
        });
        Route::controller(MyPlannerController::class)->group(function () {
            Route::get('planner', 'planner')->name('up.planner');
            Route::get('planner/filter', 'filterMonthlyPlanner');
            Route::get('planner/{slug}/{id}', 'plannerChapterListing')->name('up.planner.chapter.listing');
        });
    });
});
Route::prefix('mittbunny')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::controller(MittBunnyPortalController::class)->group(function () {
            Route::get('classes/{date}', 'showDashClasses')->name('mittbunny.show.classes');
            Route::get('dashboard', 'dashboard')->name('mittbunny.dashboard');
            Route::get('my-profile', 'profile')->name('mittbunny.profile');
            Route::post('upload-profile-image', 'uploadProfileImage')->name('mittbunny.upload.profile.image');
            Route::post('change-password', 'changePassword')->name('mittbunny.change.password');
            Route::post('update-profile-details', 'updateProfileDetails')->name('mittbunny.update.profile.details');
            Route::get('download', 'downloadApp')->name('mittbunny.download');
        });
        Route::controller(MittPlannerController::class)->group(function () {
            Route::get('planner', 'myPlanner')->name('mittbunny.planner');
            Route::get('planner-detail/{slug}/{id}', 'plannerCoursesChapterListing')->name('mittbunny.planner.detail');
        });
        Route::controller(MittCoursesController::class)->group(function () {
            Route::get('courses', 'mycourses')->name('mittbunny.courses');
            Route::get('my-courses/{slug}', 'coursesListing')->name('mittbunny.course.listing');
            Route::get('course/digital-content/{id}', 'courseDigitalContent')->name('mittbunny.course.digital-content');
            Route::get('my-courses/{slug}/{id}', 'coursesChapterListing')->name('mittbunny.courses.chapter.listing');
            Route::get('get-chapter-details/{id}/{plannerId?}', 'getChapterDetails')
                ->name('mittbunny.courses.chapter.details');
        });
        Route::controller(MittOnlineClassesController::class)->group(function () {
            Route::get('online-classes', 'onlineClasses')->name('mittbunny.online-classes');
            Route::get('online-class/digital-content/{id}', 'onlineClassDigitalContent')->name('mittbunny.online.class.digital.content');
            Route::post('join-class', 'onlineClassJoinLogs')->name('mittbunny.join.class');
        });
        Route::controller(MittDigitalContentController::class)->group(function () {
            Route::get('digital-content', 'digitalContent')->name('mittbunny.digital-content');
        });
        Route::controller(MittMediaGalleryController::class)->group(function () {
            Route::get('media-gallery-files', 'mediaGallery')->name('mittbunny.media-gallery');
        });
        Route::controller(MittSubscriptionController::class)->group(function () {
            Route::get('subscription', 'subscription')->name('mittbunny.subscription');
            Route::post('upgrade/subscription', 'subscriptionUpgrade')->name('mittbunny.upgrade.subscription');
            Route::post('upgarde-plan/otp', 'upgradePlanOtp')->name('mittbunny.upgarde-plan.otp');
            Route::post('resend/otp', 'resendOtp')->name('mittbunny.resend.otp');
            Route::post('upgrade-plan/otp-check', 'subscriptionOtpCheck')->name('mittbunny.upgrade.subscription.otp.check');
        });
    });
});

// Public Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login/submit', 'loginSubmit')->name('login.submit');
    Route::get('login/otp', 'loginOtp')->name('login.otp');
    Route::post('login/emailverify/check', 'loginEmailverifyCheck')->name('login.emailverify.check');
    Route::match(['get', 'post'], '/login/otp/fill', 'loginOtpFill')->name('login.otp.fill');
    Route::post('login/otp/check', 'loginOtpCheck')->name('login.otp.check');
    Route::post('resend-otp', 'resendOtp')->name('login.resend.otp');
    Route::get('logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('register', 'index')->name('register');
    Route::post('register/store', 'store')->name('register.store');
    Route::post('register/otp/check', 'registerOtpCheck')->name('register.otp.check');

    Route::get('signup', 'qrSignup')->name('qrSignup');
    Route::get('/get-classes', 'getClasses')->name('get.classes');
    Route::get('/get-series', 'fetchSeries')->name('get.series');
    Route::get('/get-subjects', 'fetchSubjects');
    Route::get('/get-subjects-by-class-series', 'fetchSubjectsByClassSeries');

    Route::post('qr/register/store', 'storeQrRegister')->name('store.qr.register');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('forgot_password', 'forgotPassword')->name('forgot_password');
    // Route::get('forgot_password/otp/fill', 'forgotPasswordOtpFill')->name('forgotPassword.otp.fill');
    Route::post('password/reset/otp/check', 'forgotPasswordOtpCheck')->name('forgot_password_otp_check');
    Route::match(['get', 'post'], 'password/reset/otp', 'resetOtpFill')->name('password_otp');
    // Route::post('password/reset/otp', 'reset_otp_fill')->name('password_otp');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('password/reset', 'resetPassword')->name('password.reset');
    Route::post('password/reset/submit', 'resetPasswordSubmit')->name('reset.password.submit');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        // Route::get('home', 'index')->name('front.index');
        // Route::get('home', 'index')->name('user.dashboard');
        Route::get('get-class-courses/{class_id}', 'getClassCourses')->name('get.class.courses');
        Route::post('purchase-subscription', 'purchaseSubscription')->name('purchase.subscription');
        Route::post('validate-access-code', 'validateAccessCode')->name('validate.access.code');
    });
});

Route::controller(RegisterController::class)->group(function () {
    // Demo access code register login and register bellow 2 routes
    Route::get('/olympiad-demo', 'd2cQrRegisterDemo')->name('demo.access.code');
    Route::post('register1', 'd2cQrRegisterDemoSubmit')->name('demo.access.code.submit');
    Route::get('/mom', 'olympiadRegister')->name('olympiad.register');
    Route::post('mom-submit', 'olympiadRegisterSubmit')->name('olympiad.register.submit');
    Route::get('/{categorySlug}/{medium}/{classSlug}', 'd2cMQrRegister')->name('d2c.qr.register');
    Route::get('/{categorySlug}/{classSlug}', 'd2cQrRegister')->name('d2cM.qr.register');
    Route::post('qr-store', 'd2cQrRegisterSubmit')->name('d2c.qr.register.store');
});
Route::middleware(['auth'])->group(function () {
    Route::controller(HomeController::class)->group(function () {
        // Route::get('home', 'index')->name('front.index');
        // Route::get('home', 'index')->name('user.dashboard');
        Route::get('get-class-courses/{class_id}', 'getClassCourses')->name('get.class.courses');
        Route::post('purchase-subscription', 'purchaseSubscription')->name('purchase.subscription');
        Route::post('validate-access-code', 'validateAccessCode')->name('validate.access.code');
    });
});

Route::get('optimize', function () {

    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('optimize:clear');

    return '<h1>Web Cache Cleared</h1>';
});

// Storage link for file access
Route::get('storage/uploads/tickets/attachments/{filename}', function ($filename) {
    $path = storage_path('app/public/uploads/tickets/attachments/' . $filename);
    if (! file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('ticket.attachment.download');

// SMS-ERP Routes
Route::prefix('erp-admin')->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::controller(ErpDemoController::class)->group(function () {
            Route::get('demo', 'demo')->name('demo');
        });
        Route::controller(ErpFeesHeaderController::class)->group(function () {
            Route::get('fee-header', 'createFeesHeader')->name('create.fee.header');
            Route::post('fee-header/save', 'feesHeaderSave')->name('save.fee.header');
            Route::get('fee-header/edit/{id}', 'editFeeHeader')->name('edit.fee.header');
            Route::get('fee-header/delete/{id}', 'deleteFeeHeader')->name('delete.fee.header');
        });
        Route::controller(ErpConsessionCategoryController::class)->group(function () {
            Route::get('consession-category/create', 'createConsessionCategory')->name('create.consession.category');
            Route::post('fee-header/save', 'feesHeaderSave')->name('save.fee.header');
            Route::get('fee-header/edit/{id}', 'editFeeHeader')->name('edit.fee.header');
            Route::get('fee-header/delete/{id}', 'deleteFeeHeader')->name('delete.fee.header');
        });
    });
});

Route::get('/report-error', [App\Http\Controllers\ErrorReportController::class, 'index'])
    ->name('report.error.show');
Route::post('/report-error', [App\Http\Controllers\ErrorReportController::class, 'store'])
    ->name('report.error');

// Local dev only: uploaded media isn't present on this machine (not in git/DB dump),
// so any image missing locally is served from the live production site instead.
if (app()->environment('local')) {
    Route::get('storage/{path}', function ($path) {
        return redirect('https://mittlearn.com/storage/' . $path);
    })->where('path', '.*');

    Route::get('uploads/{path}', function ($path) {
        return redirect('https://mittlearn.com/uploads/' . $path);
    })->where('path', '.*');
}
