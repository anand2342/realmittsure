<?php
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
?>




<div class="siderBar" id="siderBar">
    <div class="sideMenu">
        <div class="sideMenuscrl">
            <ul class="menuList">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowDashboardMenu): ?>
                <li>
                    <a href="<?php echo e(route('sp.dashboard')); ?>" class="<?php echo e($isActiveDashboardMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/dashboard-icon.svg')); ?>" width="16" class="me-2">
                        Dashboard</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php
                $isParentSchool = App\Models\Schools::where('user_id', Auth::id())
                ->where('school_role', 'parent')
                ->get();
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() == 'school_admin' && $isParentSchool->isNotEmpty()): ?>
                <li>
                    <a href="<?php echo e(route('sp.branch.schools')); ?>"
                        class="<?php echo e($isActiveBranchSchoolsMenu ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('frontend/images/teacher-manager-icon.svg')); ?>" width="14"
                            class="me-2">
                        Branch Schools
                    </a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowTeacherManagerMenu): ?>
                <?php if(getUserRoles() !== 'school_teacher'): ?>
                <li>
                    <a href="<?php echo e(route('sp.teacher.manager')); ?>"
                        class="<?php echo e($isActiveTeacherManagerMenu ? 'active' : ''); ?>">
                        <img src="<?php echo e(asset('frontend/images/teacher-manager-icon.svg')); ?>" width="14"
                            class="me-2">
                        Teacher Manager
                    </a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowStudentManagerMenu): ?>
                <li>
                    <a href="<?php echo e(route('sp.student.manager')); ?>"
                        class="<?php echo e($isActiveStudentManagerMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/students-manager-icon.svg')); ?>" width="16"
                            class="me-2">
                        Students Manager</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowCourseMenu): ?>
                <li>
                    <a href="<?php echo e(route('sp.my.courses')); ?>" class=" <?php echo e($isActiveCourseMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/my-courses-icon.svg')); ?>" width="18" class="me-2">
                        Subjects/ Courses</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowLessonPlannerMenu): ?>
                <li>
                    <a href="<?php echo e(route('sp.lesson.planner')); ?>"
                        class="<?php echo e($isActiveLessonPlannerMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/lesson-planners-icon.svg')); ?>" width="16"
                            class="me-2">
                        Lesson Plan</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowOnlineClassMenu): ?>
                <li>
                    <a href="<?php echo e(route('online.class')); ?>"
                        class="<?php echo e($isActiveOnlineClassMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/online-classes-icon.svg')); ?>" width="16"
                            class="me-2">
                        Online Classes</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowUploadedContentMenu): ?>
                <li>
                    <a href="<?php echo e(route('content.upload')); ?>"
                        class="<?php echo e($isActiveUploadedContentMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/content-upload-icon.svg')); ?>" width="16"
                            class="me-2">
                        Content Upload</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowMediaGalleryMenu): ?>
                <li>
                    <a href="<?php echo e(route('gallery.list')); ?>"
                        class="<?php echo e($isActiveMediaGalleryMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/content-upload-icon.svg')); ?>" width="16"
                            class="me-2">
                        Events Media Gallery</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($hasTdcContent)): ?>
                <li>
                    <a href="<?php echo e(route('sp.teacher.development.index')); ?>"
                        class="<?php echo e($isActiveTdcMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/planners-manager-icon.svg')); ?>" width="16"
                            class="me-2">
                        Faculty Development Videos</a>
                </li>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php
                $isAnyTPGMenuActive =
                $isActiveTestPaperGenMenu || $isActiveQuestionBankMenu || $isActiveOnlineTestReviewMenu;
                ?>

                
                <?php if(getUserRoles() == 'school_admin'): ?>
                
                <li>
                    <a href="<?php echo e(route('sp.your-license')); ?>"
                        class="<?php echo e($isActiveYourLicenseMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/access-code.svg')); ?>" width="16" class="me-2">
                        Licenses/ Access Codes</a>
                </li>
                
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <li>
                    <a href="<?php echo e(route('sp.download-app.page')); ?>"
                        class="<?php echo e($isActiveDownloadAppMenu ? 'active' : ''); ?>"><img
                            src="<?php echo e(asset('frontend/images/download-icn.svg')); ?>" width="16" class="me-2">
                        Download App </a>

                </li>

            </ul>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notificationAlerts && $notificationAlerts->marketing_banner): ?>
            <hr class="form_divider m-0">
            <?php
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
            ?>


            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(strtolower($extension), $videoExtensions)): ?>
            <a target="_blank" href="<?php echo e($notificationAlerts->redirection_url); ?>"><video autoplay loop muted
                    playsinline class="img-thumbnail">
                    <source src="<?php echo e(Storage::url('uploads/marketing_banner/' . $file)); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video></a>
            <?php else: ?>
            <a target="_blank" href="<?php echo e($notificationAlerts->redirection_url); ?>"><img
                    src="<?php echo e(Storage::url('uploads/marketing_banner/' . $file)); ?>" alt="Marketing Banner"
                    width="300"></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/layouts/sidebar.blade.php ENDPATH**/ ?>