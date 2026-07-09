<?php

use App\Http\Controllers\Api\AppVersionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CoursePurchaseController;
use App\Http\Controllers\Api\CrmApiController;
use App\Http\Controllers\Api\FrontController;
use App\Http\Controllers\Api\SchoolPortalApiController;
use App\Http\Controllers\Api\SpPlannerApiController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\user\QrCodeController;
use App\Http\Controllers\Api\user\TestPaperController;
use App\Http\Controllers\Api\user\TrackProgressController;
use App\Http\Controllers\Api\user\UserApiController;
use App\Http\Controllers\Api\user\UserDashboardController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\OpenAIVisionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

// Route for getting the authenticated user details
Route::middleware('auth:sanctum')->get('user', function (Request $request) {
    return $request->user();
});

// Routes without authentication
Route::controller(AppVersionController::class)->group(function () {
    Route::get('get-base-url', 'getBaseUrl');
    Route::post('store-app-version', 'storeOrUpdateAppVersion');
    Route::post('get-app-version', 'getAppVersion');
});
Route::controller(CrmApiController::class)->group(function () {
    Route::post('users/save', 'userSaveFromApi');
    Route::post('users/update', 'userUpdateFromApi');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('register-guest-user', 'registerGuestUser');
    Route::post('login', 'login');
    Route::post('login/otp', 'loginOtp');
    Route::post('login/verify-otp', 'verifyOtp');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
    Route::get('all-school-list', 'allSchoolList');
    Route::get('get-master-data', 'getMasterData');
});

// CoursePurchaseController is for in app purchase (iOS)
Route::controller(CoursePurchaseController::class)->group(function () {
    Route::post('courses-filter', 'courseFilter');

    Route::post('continue-as-guest', 'continueAsGuest');
    Route::get('courses', 'allCourses');

    Route::post('app-store/purchase', 'storeInAppPurchase');
});

// Routes require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('verify-email-mobile', 'verifyEmailMobile');
        Route::post('verify-email-mobile-otp', 'verifyEmailMobileOtp');
        Route::post('logout', 'logout');
        // Route::post('change-password', 'changePassword');
    });

    Route::controller(UserController::class)->group(function () {

        Route::get('get-states', 'getStates');
        Route::post('get-cities', 'getCities');
        Route::get('get-subjects', 'getSubjects');
        Route::post('get-classes', 'getClasses');

        Route::get('users', 'getUsers');
        Route::post('user/student/save', 'saveUser');
        Route::post('user/teacher/save', 'saveUser');

        // user profile image update
        Route::post('update-profile-image', 'profileImageUpdate');

        Route::get('delete-user-account', 'deleteUserAccount');
        Route::post('video-details', 'downloadVideoDetails');
    });
});

Route::controller(SubscriptionController::class)->group(function () {
    Route::post('subscription-plan-redirection', 'redirectToSubscriptionPage');
    Route::post('item-add-to-cart', 'addToCart');
    Route::post('item-remove-from-cart', 'removeFromCart');
});
Route::controller(WishlistController::class)->group(function () {
    Route::post('item-add-to-wishlist', 'addToWishlist');
    Route::post('item-remove-from-wishlist', 'removeFromWishlist');
});

Route::controller(FrontController::class)->group(function () {
    Route::get('get-courses', 'getCourses');
    Route::get('get-academic-courses', 'getAcademicCourses');
    Route::get('get-non-academic-courses', 'getNonAcademicCourses');
    Route::post('contact-enquiries-save', 'saveContactEnquiries');
});

Route::middleware('auth:sanctum')->group(function () {
    // Bellow Route prefix also is school butnot added because this is provided to app developer so they using without prefix
    Route::prefix('')->group(function () {

        Route::controller(SchoolPortalApiController::class)->group(callback: function () {
            Route::post('change-password', 'changePassword');

            Route::post('get-permission', 'getUserPermission');
            Route::get('get-constants', 'getConstants');
            Route::post('dashboard', 'dashboard');
            Route::post('teacher/manager', 'teacherManager');
            Route::post('student/manager', 'studentManager');
            Route::get('un-verfired/student', 'UnVerfiredStudent');
            Route::post('student/add-edit', 'studentAddEdit');
            Route::post('teacher/add-edit', 'teacherAddEdit');
            Route::post('user/active-inactive', 'userActiveInactive');
            Route::post('user/active-inactive-log', 'userActiveInactiveLog');
            Route::post('school/details', 'schoolDetails');
            Route::post('school/details/update', 'schoolDetailsUpdate');
            Route::post('get-access-codes', 'getAccessCodes');

            Route::post('get-teacher-assigned-classes', 'teacherAssignedClasses');
            Route::post('get-teacher-assigned-subjects', 'teacherAssignedSubjects');
            Route::get('teacher/details', 'teacherDetails');
            Route::post('teacher/details/update', 'teacherDetailsUpdate');
            Route::get('teacher-dashboard', 'teacherDashboard');

            Route::get('get-alert-marketing-banner', 'getAlertAndMarketingBanner');

            Route::post('teacher/search', 'teacherSearch'); // New search route
            Route::post('student/search', 'studentSearch'); // New search route

        });
        Route::controller(SpPlannerApiController::class)->group(callback: function () {
            Route::post('school/get-daily-planner', 'getPlanner');
            Route::post('school/daily-planner/mark-holiday', 'markHoliday');
            Route::post('school/daily-planner/chapter-details', 'chapterDetails');
        });
    });
    Route::prefix('school')->group(function () {
        Route::controller(SchoolPortalApiController::class)->group(callback: function () {
            Route::get('my-courses', 'myCourses');
            Route::post('my-courses/class-subjects', 'classSubject');
            Route::post('my-courses/subject/course-list', 'courseListing');
            Route::post('my-courses/subject/course-chapter-list', 'chapterListing');
            Route::post('assigin-access-code', 'assiginAccessCode');
            Route::get('school/content/folder/listing', 'schoolContentFolderListing');
            Route::post('school/folder/content/view', 'schoolFolderContentView');
            Route::post('create/folder', 'createFolder');
            Route::post('store/file', 'storeFile');
            Route::post('content/folder/delete', 'contentFolderDelete');
            Route::post('content/file/delete', 'fileDelete');
            Route::get('show/online/classes', 'showOnlineClasses');
            Route::post('store/online/class', 'storeOnlineClass');
            Route::post('online/class/details', 'onlineClassDetails');
            Route::post('store/online-class/study-material', 'storeOnlineClassStudyMaterial');

            Route::get('lesson-planner', 'lessonPlanner');
            Route::post('lesson-planner/class-subject', 'lessonPlannerClassSubject');
            Route::post('lesson-planner/course-listing', 'lessonPlannerCourseListing');
            Route::post('lesson-planner/chapter/details', 'lessonPlannerChapterDetails');

            Route::post('media-gallery/create', 'createMediaGallery');
            Route::get('media-gallery/list', 'mediaGalleryList');
            Route::post('media/gallery/view', 'mediaGalleryView');
            Route::post('file/delete', 'mediaGalleryFileDelete');
            Route::post('media-gallery/store/files', 'mediaGalleryStoreFile');
        });
    });

    Route::prefix('user')->group(function () {
        Route::controller(UserDashboardController::class)->group(callback: function () {
            Route::post('validate-access-code', 'validateAccessCode');
        });

        Route::controller(UserApiController::class)->group(callback: function () {
            Route::get('dashboard', 'dashboard');
            Route::post('vallidate/access-code', 'vallidateAccessCode');
            Route::get('online-classes', 'onlineClasses');
            Route::get('digital-content', 'digitalContent');
            Route::post('digital-content-file', 'digitalContentFiles');
            Route::post('online-class-content', 'getUserOnlineClassContent');
            Route::get('my-courses', 'myCourses');
            Route::post('my-course-detail', 'myCoursesListing');

            Route::post('user-planner', 'userPlanner');
            Route::post('planner/chapter-details', 'plannerChapterDetails');

            Route::get('user-profile-details', 'userProfileDetails');
            Route::post('update-user-profile-details', 'updateUserProfileDetails');

            Route::get('subscription', 'subscription');
            Route::post('generate-otp', 'upgradePlanOtp');
            Route::post('subscription-otp-check', 'subscriptionOtpCheck');
            Route::post('subscription/resend-otp', 'subscriptionResendOtp');

            Route::get('media-gallery', 'mediaGallery');
            Route::post('media-gallery-files', 'mediaGalleryFiles');
        });
        Route::controller(TrackProgressController::class)->group(callback: function () {
            Route::post('save-video-duration', 'saveVideoDuration');
            Route::post('update-video-progress', 'updateVideoProgress');
        });
        Route::controller(TestPaperController::class)->group(callback: function () {
            Route::get('test-papers', 'testPapers');
            Route::post('test-paper-questions', 'testPaperQuestion');
            Route::post('test-paper/submit', 'testPaperSubmit');
            Route::post('test-paper/submit-answer', 'testPaperSubmitAnswer');
        });
        Route::controller(TestPaperController::class)->group(callback: function () {
            Route::get('test-papers', 'testPapers');
            Route::post('test-paper-questions', 'testPaperQuestion');
            Route::post('test-paper/submit', 'testPaperSubmit');
            Route::post('test-paper/submit-answer', 'testPaperSubmitAnswer');
        });
        Route::controller(QrCodeController::class)->group(callback: function () {
            Route::post('assign-content-from-qr', 'assignContentFromQrUrl');
        });
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

Route::post('/openai-scan', [OpenAIVisionController::class, 'scan']);
