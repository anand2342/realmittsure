@php
$dashboardRoutes = ['sp.dashboard'];
$isShowDashboardMenu = isPermission($dashboardRoutes);
$isActiveDashboardMenu = isActiveMenu($dashboardRoutes);

$teacherManagerRoutes = ['sp.teacher.manager', 'sp.check.teacher.access'];
$isShowTeacherManagerMenu = isPermission($teacherManagerRoutes);
$isActiveTeacherManagerMenu = isActiveMenu($teacherManagerRoutes);

$branchSchoolsRoutes = ['sp.branch.schools'];
$isShowBranchSchoolsMenu = isPermission($branchSchoolsRoutes);
$isActiveBranchSchoolsMenu = isActiveMenu($branchSchoolsRoutes);

$studentManagerRoutes = ['sp.student.manager', 'sp.check.student.access'];
$isShowStudentManagerMenu = isPermission($studentManagerRoutes);
$isActiveStudentManagerMenu = isActiveMenu($studentManagerRoutes);

$myCoursesRoutes = [
'sp.my.courses',
'sp.class.subject',
'sp.access.codes',
'sp.course.listing',
'sp.courses.details',
];
$isShowCourseMenu = isPermission($myCoursesRoutes);
$isActiveCourseMenu = isActiveMenu($myCoursesRoutes);

$onlineClassRoutes = ['online.class', 'online.class.details'];
$isShowOnlineClassMenu = isPermission($onlineClassRoutes);
$isActiveOnlineClassMenu = isActiveMenu($onlineClassRoutes);

$dailyPlannerRoutes = ['daily.planner', 'chapter.details', 'chapter.documents'];
$isShowDailyPlannerMenu = isPermission($dailyPlannerRoutes);
$isActiveDailyPlannerMenu = isActiveMenu($dailyPlannerRoutes);

$LessonPlannerRoutes = [
'sp.lesson.planner',
'sp.lesson.planner.subjects',
'sp.lesson.planner.course.listing',
'lesson.planner.chapter.details',
'lesson.chapter-plannner',
];
$isShowLessonPlannerMenu = isPermission($LessonPlannerRoutes);
$isActiveLessonPlannerMenu = isActiveMenu($LessonPlannerRoutes);

$uploadedContentRoutes = ['content.upload', 'content.folder.view'];
$isShowUploadedContentMenu = isPermission($uploadedContentRoutes);
$isActiveUploadedContentMenu = isActiveMenu($uploadedContentRoutes);

$mediaGalleryRoutes = ['gallery.list', 'media.gallery.view'];
$isShowMediaGalleryMenu = isPermission($mediaGalleryRoutes);
$isActiveMediaGalleryMenu = isActiveMenu($mediaGalleryRoutes);

$tdcRoutes = ['sp.teacher.development.index', 'sp.teacher.development.videos'];
$isShowTdcMenu = isPermission($tdcRoutes);
$isActiveTdcMenu = isActiveMenu($tdcRoutes);

$yourLicense = ['sp.your-license'];
$isShowYourLicenseMenu = isPermission($yourLicense);
$isActiveYourLicenseMenu = isActiveMenu($yourLicense);

$testPaperGenRoutes = [
'sp.test-papers',
'sp.test-papers.add',
'sp.test-papers.edit',
'sp.test-paper-view',
'sp.test-paper.delete',
'sp.test-paper.get-chapters',
'sp.test-paper.add-question',
'get.students',
'assign.test',
'assign.test.questions',
];
$isShowTestPaperMenu = isPermission($testPaperGenRoutes);
$isActiveTestPaperGenMenu = isActiveRoute($testPaperGenRoutes);

$questionBankRoutes = [
'sp.create.question.bank',
'sp.question.bank',
'sp.question-bank.edit',
'sp.question-bank.delete',
];
$isShowQuestionBankMenu = isPermission($questionBankRoutes);
$isActiveQuestionBankMenu = isActiveRoute($questionBankRoutes);

$onlineTestReviewRoutes = [
'sp.test-paper.view',
'sp.test-paper.assigned.users',
'sp.test-paper.remark',
'sp.test-paper.review',
];
$isShowOnlineTestReviewMenu = isPermission($onlineTestReviewRoutes);
$isActiveOnlineTestReviewMenu = isActiveRoute($onlineTestReviewRoutes);

$downloadApp = ['sp.download-app.page'];
$isShowDownloadAppMenu = isPermission($downloadApp);
$isActiveDownloadAppMenu = isActiveMenu($downloadApp);
@endphp




<div class="siderBar" id="siderBar">
    <div class="sideMenu">
        <div class="sideMenuscrl">
            <ul class="menuList">
                @if ($isShowDashboardMenu)
                <li>
                    <a href="{{ route('sp.dashboard') }}" class="{{ $isActiveDashboardMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/dashboard-icon.svg') }}" width="16" class="me-2">
                        Dashboard</a>
                </li>
                @endif
                {{-- @if ($isShowBranchSchoolsMenu) --}}
                @php
                $isParentSchool = App\Models\Schools::where('user_id', Auth::id())
                ->where('school_role', 'parent')
                ->get();
                @endphp
                @if (getUserRoles() == 'school_admin' && $isParentSchool->isNotEmpty())
                <li>
                    <a href="{{ route('sp.branch.schools') }}"
                        class="{{ $isActiveBranchSchoolsMenu ? 'active' : '' }}">
                        <img src="{{ asset('frontend/images/teacher-manager-icon.svg') }}" width="14"
                            class="me-2">
                        Branch Schools
                    </a>
                </li>
                @endif
                {{-- @endif --}}
                @if ($isShowTeacherManagerMenu)
                @if (getUserRoles() !== 'school_teacher')
                <li>
                    <a href="{{ route('sp.teacher.manager') }}"
                        class="{{ $isActiveTeacherManagerMenu ? 'active' : '' }}">
                        <img src="{{ asset('frontend/images/teacher-manager-icon.svg') }}" width="14"
                            class="me-2">
                        Teacher Manager
                    </a>
                </li>
                @endif
                @endif

                @if ($isShowStudentManagerMenu)
                <li>
                    <a href="{{ route('sp.student.manager') }}"
                        class="{{ $isActiveStudentManagerMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/students-manager-icon.svg') }}" width="16"
                            class="me-2">
                        Students Manager</a>
                </li>
                @endif
                @if ($isShowCourseMenu)
                <li>
                    <a href="{{ route('sp.my.courses') }}" class=" {{ $isActiveCourseMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/my-courses-icon.svg') }}" width="18" class="me-2">
                        Subjects/ Courses</a>
                </li>
                @endif
                {{-- @if ($isShowDailyPlannerMenu)
                    <li>
                        <a href="{{ route('daily.planner') }}"
                class="{{ $isActiveDailyPlannerMenu ? 'active' : '' }}"><img
                    src="{{ asset('frontend/images/planners-manager-icon.svg') }}" width="16"
                    class="me-2">
                Daily Planner</a>
                </li>
                @endif --}}
                @if ($isShowLessonPlannerMenu)
                <li>
                    <a href="{{ route('sp.lesson.planner') }}"
                        class="{{ $isActiveLessonPlannerMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/lesson-planners-icon.svg') }}" width="16"
                            class="me-2">
                        Lesson Plan</a>
                </li>
                @endif
                @if ($isShowOnlineClassMenu)
                <li>
                    <a href="{{ route('online.class') }}"
                        class="{{ $isActiveOnlineClassMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/online-classes-icon.svg') }}" width="16"
                            class="me-2">
                        Online Classes</a>
                </li>
                @endif
                @if ($isShowUploadedContentMenu)
                <li>
                    <a href="{{ route('content.upload') }}"
                        class="{{ $isActiveUploadedContentMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/content-upload-icon.svg') }}" width="16"
                            class="me-2">
                        Content Upload</a>
                </li>
                @endif
                @if ($isShowMediaGalleryMenu)
                <li>
                    <a href="{{ route('gallery.list') }}"
                        class="{{ $isActiveMediaGalleryMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/content-upload-icon.svg') }}" width="16"
                            class="me-2">
                        Events Media Gallery</a>
                </li>
                @endif
                @if(!empty($hasTdcContent))
                <li>
                    <a href="{{ route('sp.teacher.development.index') }}"
                        class="{{ $isActiveTdcMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/planners-manager-icon.svg') }}" width="16"
                            class="me-2">
                        Faculty Development Videos</a>
                </li>
                @endif

                @php
                $isAnyTPGMenuActive =
                $isActiveTestPaperGenMenu || $isActiveQuestionBankMenu || $isActiveOnlineTestReviewMenu;
                @endphp

                {{-- <li>
                    <a href="#tpg" class="submenuToggle" data-bs-toggle="collapse"
                        aria-expanded="{{ $isAnyTPGMenuActive ? 'true' : 'false' }}">
                <img src="{{ asset('frontend/images/test-paper-icon.svg') }}" width="16" class="me-2">
                Test Paper Gen. (TPG)
                </a>
                <div class="collapse {{ $isAnyTPGMenuActive ? 'show' : '' }}" id="tpg">
                    <ul class="submenuList">
                        <li>
                            <a href="{{ route('sp.test-papers') }}"
                                class="{{ $isActiveTestPaperGenMenu ? 'active' : '' }}">Test Paper</a>
                        </li>
                        <li>
                            <a class="{{ $isActiveQuestionBankMenu ? 'active' : '' }}"
                                href="{{ route('sp.question.bank') }}">Question Bank</a>
                        </li>
                        <li>
                            <a class="{{ $isActiveOnlineTestReviewMenu ? 'active' : '' }}"
                                href="{{ route('sp.test-paper.view') }}">Online Test Review</a>
                        </li>
                    </ul>
                </div>
                </li> --}}
                @if (getUserRoles() == 'school_admin')
                {{-- @if ($isShowYourLicenseMenu) --}}
                <li>
                    <a href="{{ route('sp.your-license') }}"
                        class="{{ $isActiveYourLicenseMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/access-code.svg') }}" width="16" class="me-2">
                        Licenses/ Access Codes</a>
                </li>
                {{-- @endif --}}
                @endif
                <li>
                    <a href="{{ route('sp.download-app.page') }}"
                        class="{{ $isActiveDownloadAppMenu ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/download-icn.svg') }}" width="16" class="me-2">
                        Download App </a>

                </li>

            </ul>
        @if ($notificationAlerts && $notificationAlerts->marketing_banner)
            <hr class="form_divider m-0">
            @php
            $file = $notificationAlerts->marketing_banner;
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $videoExtensions = [
            'mp4',
            'avi',
            'mov',
            'm4v',
            'm4p',
            'mpg',
            'mp2',
            'mpeg',
            'mpe',
            'mpv',
            'm2v',
            'wmv',
            'flv',
            'mkv',
            'webm',
            '3gp',
            '3gp',
            'm2ts',
            'ogv',
            'ts',
            'mxf',
            'ogg',
            ];
            @endphp


            @if (in_array(strtolower($extension), $videoExtensions))
            <a target="_blank" href="{{ $notificationAlerts->redirection_url }}"><video autoplay loop muted
                    playsinline class="img-thumbnail">
                    <source src="{{ Storage::url('uploads/marketing_banner/' . $file) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video></a>
            @else
            <a target="_blank" href="{{ $notificationAlerts->redirection_url }}"><img
                    src="{{ Storage::url('uploads/marketing_banner/' . $file) }}" alt="Marketing Banner"
                    width="300"></a>
            @endif

        @endif
        </div>
    </div>
</div>
