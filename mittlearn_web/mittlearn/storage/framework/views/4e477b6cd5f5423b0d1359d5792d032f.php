<?php
    $dashboardRoutes = ['dashboard', 'login.users.view'];
    $isShowDashboardMenu = isPermission($dashboardRoutes);
    $isActiveDashboardMenu = isActiveMenu($dashboardRoutes);

    $userRoutes = [
        'user.index',
        'user.create',
        'user.view',
        'user.edit',
        'roles.index',
        'roles.create',
        'roles.edit',
        'school.assign.digital.content',
    ];
    $isShowUserMenu = isPermission($userRoutes);
    $isActiveUserMenu = isActiveMenu($userRoutes);

    $masterRoutes = [
        'level.index',
        'level.edit',
        'level.create',
        'grade.index',
        'grade.edit',
        'grade.create',
        'class.index',
        'class.create',
        'class.edit',
        'medium.index',
        'medium.create',
        'medium.edit',
        'book.series.index',
        'book.series.create',
        'book.series.edit',
        'subject.index',
        'subject.create',
        'subject.edit',
        'board.index',
        'board.create',
        'board.edit',
        'language.index',
        'language.create',
        'language.edit',
        'lesson.index',
        'lesson.create',
        'lesson.edit',
        'school.list',
        'school.edit',
        'school.access.code',
        'bookset.index',
        'bookset.edit',
        'bookset.create',
        'academic.session.index',
        'academic.session.create',
        'academic.session.edit',
        'prefix.list',
        'prefix.edit',
        'section.index',
        'section.create',
        'section.edit',
        'category.index',
        'sub-category.field-add',
        'sub-category.edit',
        'sub-category.add',
        'sub-category.edit',
        'd2c-category.index',
        'd2c-content.assginment',
        'd2c-category.index',
        'state.district.index',
        'state.district.create',
        'state.district.edit',
        'district.create',
        'district.index',
    ];
    $isShowMasterMenu = isPermission($masterRoutes);
    $isActiveMasterMenu = isActiveMenu($masterRoutes);

    $subsPlanRoutes = [
        'plans.index',
        'plans.add',
        'plans.edit',
        'course-bucket.add',
        'course-bucket.index',
        'purchase.report',
    ];
    $isShowSubsPlanMenu = isPermission($subsPlanRoutes);
    $isActiveSubsPlanMenu = isActiveMenu($subsPlanRoutes);

    $couponRoutes = ['coupon.index', 'coupon.create', 'coupon.edit'];
    $isShowCouponMenu = isPermission($couponRoutes);
    $isActiveCouponMenu = isActiveMenu($couponRoutes);

    $plannerManagementRoutes = ['planner.index', 'planner.create', 'planner.view'];
    $isPlannerManagementMenu = isPermission($plannerManagementRoutes);
    $isActivePlannerManagementMenu = isActiveMenu($plannerManagementRoutes);

    $testPaperGenrationRoutes = [
        'test-paper.index',
        'test-paper.create',
        'test-paper.edit',
        'question-bank.create',
        'question-bank.index',
        'question.add',
        'question-bank.edit',
    ];
    $isTestPaperGenrationMenu = isPermission($testPaperGenrationRoutes);
    $isActiveTestPaperGenrationMenu = isActiveMenu($testPaperGenrationRoutes);

    $courseRoutes = [
        'course.index',
        'course.create',
        'course.edit',
        'course.add.chapter',
        'course.chapter.edit',
        'courses.bulk-upload',
        'courses.chapter.bulk-upload',
        'course.complimentary.index',
        'course.academic-activities.index',
        'merge.course',
    ];
    $isShowCourseMenu = isPermission($courseRoutes);
    $isActiveCourseMenu = isActiveMenu($courseRoutes);

    $tdcRoutes = ['teacher.development.index', 'teacher.development.add', 'teacher.development.edit'];
    $isShowTdcMenu = isPermission($tdcRoutes);
    $isActiveTdcMenu = isActiveMenu($tdcRoutes);

    $groupRoutes = ['category.index', 'sub-category.add', 'sub-category.edit'];
    $isShowGroupMenu = isPermission($groupRoutes);
    $isActiveGroupMenu = isActiveMenu($groupRoutes);

    $enquiriesRoutes = ['enquiries', 'enquiry.view'];
    $isShowEnquiriesMenu = isPermission($enquiriesRoutes);
    $isActiveEnquiriesMenu = isActiveMenu($enquiriesRoutes);

    $mediaGalleryRoutes = ['folder.list', 'folder.edit', 'media.gallery.folder.view'];
    $isShowMediaGalleryMenu = isPermission($mediaGalleryRoutes);
    $isActiveMediaGalleryMenu = isActiveMenu($mediaGalleryRoutes);

    $schoolActivityLogsRoutes = ['online.class.logs', 'online.class.log.details', 'folder.index', 'files.index'];
    $isShowSchoolActivityLogsMenu = isPermission($schoolActivityLogsRoutes);
    $isActiveSchoolActivityLogsMenu = isActiveMenu($schoolActivityLogsRoutes);

    $rBARoutes = ['permissions.assign'];
    $isShowRBAMenu = isPermission($rBARoutes);
    $isActiveRBAMenu = isActiveMenu($rBARoutes);

    $blogRoutes = [
        'blog.category.index',
        'blog.index',
        'blog.category.edit',
        'blog.sub_category.create',
        'blog.create',
        'blog.edit',
    ];
    $isShowBlogMenu = isPermission($blogRoutes);
    $isActiveBlogMenu = isActiveMenu($blogRoutes);

    $emailTempRoutes = [
        'sms-template.add',
        'sms-template.index',
        'email-template.index',
        'email-template.add',
        'email-template.edit',
        'sms-template.edit',
        'email-template.edit',
    ];
    $isShowEmailTempMenu = isPermission($emailTempRoutes);
    $isActiveEmailTempMenu = isActiveMenu($emailTempRoutes);

    $ticketMangeRoutes = ['tickets.index', 'tickets.create', 'tickets.show', 'tickets.edit'];
    $isShowTicketMangeMenu = isPermission($ticketMangeRoutes);
    $isActiveTicketMangeMenu = isActiveMenu($ticketMangeRoutes);

    $cmsPageRoutes = [
        'home.page-content',
        'cms-about.index',
        'cms.index',
        'cms.edit',
        'testimonial.index',
        'testimonial.page-content.add',
        'testimonial.page-content.edit',
        'cms-faq.index',
        'cms-faq.add',
        'cms-faq.edit',
        'our.offerings.add',
    ];
    $isShowCmsPageMenu = isPermission($cmsPageRoutes);
    $isActiveCmsPageMenu = isActiveMenu($cmsPageRoutes);

    $cmsTestimonialRoutes = ['testimonial.index', 'testimonial.page-content.add', 'testimonial.page-content.edit'];
    $isShowCmsTestimonialMenu = isPermission($cmsTestimonialRoutes);
    $isActiveCmsTestimonialMenu = isActiveMenu($cmsTestimonialRoutes);

    $settingsRoutes = ['setting.add'];
    $isShowSettingsMenu = isPermission($settingsRoutes);
    $isActiveSettingsMenu = isActiveMenu($settingsRoutes);

    $userManualsRoutes = ['user-manual.index', 'user-manual.add', 'user-manual.edit'];
    $isShowUserManualsMenu = isPermission($userManualsRoutes);
    $isActiveUserManualsMenu = isActiveMenu($userManualsRoutes);

    $erpDataSyncRoutes = [
        'erp-data.schools.index',
        'erp-data.teachers.index',
        'erp-data.students.index',
        'erp-data.add.schools',
    ];
    $isShowErprDataSyncMenu = isPermission($erpDataSyncRoutes);
    $isActiveErprDataSyncMenu = isActiveMenu($erpDataSyncRoutes);

    $notificationAlertsRoutes = [
        'flash.notification.alerts',
        'flash.notification.alerts.add',
        'flash.notification.alerts.edit',
    ];
    $isShowNotificationAlertsMenu = isPermission($notificationAlertsRoutes);
    $isActiveNotificationAlertsMenu = isActiveMenu($notificationAlertsRoutes);

    $holidayRoutes = ['add.holiday', 'index.holiday', 'edit.holiday'];
    $isShowholidayMenu = isPermission($holidayRoutes);
    $isActiveholidayMenu = isActiveMenu($holidayRoutes);

    $accessCodeRoutes = [
        'access.code.create',
        'access.code.index',
        'access.code.edit',
        'access.code.view',
        'access.code.olympiad.index',
        'access.code.olympiad.edit',
        'access.code.olympiad.view',
        'school.access.code',
        'print.setting',
        'olympiad.print',
    ];
    $isShowAccessCodeMenu = isPermission($accessCodeRoutes);
    $isActiveAccessCodeMenu = isActiveMenu($accessCodeRoutes);
?>

<ul class="sidebar-nav" id="sidebar-nav">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowDashboardMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveDashboardMenu ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowUserMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveUserMenu ? '' : 'collapsed'); ?>" data-bs-target="#user-nav"
                data-bs-toggle="collapse" href="#" aria-expanded="<?php echo e($isActiveUserMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-person-circle"></i><span>User Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-nav" class="nav-content collapse <?php echo e($isActiveUserMenu ? 'show' : ''); ?>"
                data-bs-parent="#user-nav">
                <!-- Link for creating a user -->
                <li>
                    <a href="<?php echo e(route('user.create')); ?>"
                        class="<?php echo e(request()->routeIs('user.create') ? 'active' : ''); ?>">
                        <i class="bi bi-record-fill"></i><span>Add User</span>
                    </a>
                </li>

                <!-- All Users Section -->
                <li class="nav-item">
                    <?php
                        $schoolUsersActive = false;
                        $currentRole = request()->query('role');
                        foreach ($adminSidebarUserRoles as $role) {
                            if (request()->routeIs('user.index') && $currentRole == $role->role_slug) {
                                $schoolUsersActive = true;
                                break;
                            }
                        }
                    ?>
                    <a class="nav-link <?php echo e($schoolUsersActive ? '' : 'collapsed'); ?>" data-bs-target="#school-users-nav"
                        data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e($schoolUsersActive ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>All Users</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="school-users-nav" class="nav-content collapse <?php echo e($schoolUsersActive ? 'show' : ''); ?> ps-3"
                        data-bs-parent="#user-nav">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $adminSidebarUserRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role->role_name): ?>
                                <li>
                                    <a href="<?php echo e(route('user.index', ['role' => $role->role_slug])); ?>"
                                        class="<?php echo e(request()->routeIs('user.index') && request()->query('role') == $role->role_slug ? 'active' : ''); ?>">
                                        <i class="bi bi-circle"></i>
                                        <span><?php echo e($role->role_name); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </li>

                <!-- System Users Section -->
                <li class="nav-item">
                    <?php
                        $systemUsersActive = false;
                        $currentRole = request()->query('role');
                        foreach ($adminSidebarsytemUserRoles as $role) {
                            if (request()->routeIs('user.index') && $currentRole == $role->role_slug) {
                                $systemUsersActive = true;
                                break;
                            }
                        }
                    ?>
                    <a class="nav-link <?php echo e($systemUsersActive ? '' : 'collapsed'); ?>" data-bs-target="#system-users-nav"
                        data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e($systemUsersActive ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>System Users</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="system-users-nav" class="nav-content collapse <?php echo e($systemUsersActive ? 'show' : ''); ?> ps-3"
                        data-bs-parent="#user-nav">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $adminSidebarsytemUserRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role->role_name): ?>
                                <li>
                                    <a href="<?php echo e(route('user.index', ['role' => $role->role_slug])); ?>"
                                        class="<?php echo e(request()->routeIs('user.index') && request()->query('role') == $role->role_slug ? 'active' : ''); ?>">
                                        <i class="bi bi-circle"></i>
                                        <span><?php echo e($role->role_name); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </li>

                <!-- Link for roles management -->
                <li>
                    <a href="<?php echo e(route('roles.index')); ?>"
                        class="<?php echo e(request()->routeIs(['roles.index', 'roles.create', 'roles.edit']) ? 'active' : ''); ?>">
                        <i class="bi bi-record-fill"></i><span>Roles Management</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowRBAMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveRBAMenu ? 'active' : ''); ?>" href="<?php echo e(route('permissions.assign')); ?>">
                <i class="bi bi-shield-lock"></i>
                <span>Role Based Access Control</span>
            </a>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowAccessCodeMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveAccessCodeMenu ? '' : 'collapsed'); ?>" data-bs-target="#access-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-code"></i><span>Access Code Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="access-nav" class="nav-content collapse <?php echo e($isActiveAccessCodeMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('access.code.create')); ?>" class="<?php echo e(isActiveRoute(['access.code.create'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Access Code</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('access.code.index')); ?>"
                        class="<?php echo e(isActiveRoute(['access.code.index', 'access.code.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Embibe Access Code</span>
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo e(route('access.code.olympiad.index')); ?>"
                        class="<?php echo e(isActiveRoute(['access.code.olympiad.index', 'access.code.olympiad.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Olympiad Access Code</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('olympiad.print')); ?>" class="<?php echo e(isActiveRoute(['olympiad.print'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Olympiad Print Settings</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowSubsPlanMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveSubsPlanMenu ? '' : 'collapsed'); ?>" data-bs-target="#plans-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveSubsPlanMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-collection"></i><span>Subscription Plans</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="plans-nav" class="nav-content collapse <?php echo e($isActiveSubsPlanMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('plans.add')); ?>" class="<?php echo e(isActiveRoute(['plans.add'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Plan</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('plans.index')); ?>" class="<?php echo e(isActiveRoute(['plans.index', 'plans.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>All Plans</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('course-bucket.index')); ?>"
                        class="<?php echo e(isActiveRoute(['course-bucket.index', 'course-bucket.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Courses Bucket</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('course-bucket.add')); ?>" class="<?php echo e(isActiveRoute(['course-bucket.add'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Courses Bucket</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('purchase.report')); ?>" class="<?php echo e(isActiveRoute(['purchase.report'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Purchases Report</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowMasterMenu): ?>
        <li class="nav-item">
            <!-- Main Master Management Menu -->
            <a class="nav-link <?php echo e($isActiveMasterMenu ? '' : 'collapsed'); ?>" data-bs-target="#MasterManagement-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveMasterMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-gem"></i><span>Master Management</span>
                <i class="bi bi-chevron-down ms-auto"></i> <!-- Down arrow icon -->
            </a>
            <ul id="MasterManagement-nav" class="nav-content collapse ps-3 <?php echo e($isActiveMasterMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">

                
                <li>
                    <a href="<?php echo e(route('school.list')); ?>"
                        class="<?php echo e(isActiveRoute(['school.list', 'school.edit', 'school.access.code'])); ?>">
                        <i class="bi bi-record-fill"></i></i>
                        <span>School Management</span>
                    </a>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['board.index', 'board.create', 'board.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Board-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['board.index', 'board.create', 'board.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Board</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Board-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['board.index', 'board.create', 'board.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('board.create')); ?>" class="<?php echo e(isActiveRoute(['board.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Board</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('board.index')); ?>"
                                class="<?php echo e(isActiveRoute(['board.index', 'board.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Boards</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['medium.index', 'medium.create', 'medium.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Medium-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['medium.index', 'medium.create', 'medium.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Medium</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Medium-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['medium.index', 'medium.create', 'medium.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('medium.create')); ?>" class="<?php echo e(isActiveRoute(['medium.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Medium</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('medium.index')); ?>"
                                class="<?php echo e(isActiveRoute(['medium.index', 'medium.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Mediums</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['book.series.index', 'book.series.create', 'book.series.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#BookSeries-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['book.series.index', 'book.series.create', 'book.series.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Book Series</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="BookSeries-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['book.series.index', 'book.series.create', 'book.series.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('book.series.create')); ?>"
                                class="<?php echo e(isActiveRoute(['book.series.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Book Series</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('book.series.index')); ?>"
                                class="<?php echo e(isActiveRoute(['book.series.index', 'book.series.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All BookSeries</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['class.index', 'class.create', 'class.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Class-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['class.index', 'class.create', 'class.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Class</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Class-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['class.index', 'class.create', 'class.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('class.create')); ?>" class="<?php echo e(isActiveRoute(['class.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Class</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('class.index')); ?>"
                                class="<?php echo e(isActiveRoute(['class.index', 'class.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Classes</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['subject.index', 'subject.create', 'subject.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Subject-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['subject.index', 'subject.create', 'subject.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Subject</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Subject-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['subject.index', 'subject.create', 'subject.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('subject.create')); ?>" class="<?php echo e(isActiveRoute(['subject.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Subject</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('subject.index')); ?>"
                                class="<?php echo e(isActiveRoute(['subject.index', 'subject.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Subjects</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['section.index', 'section.create', 'section.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Section-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['section.index', 'section.create', 'section.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Classes Section</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Section-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['section.index', 'section.create', 'section.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('section.create')); ?>" class="<?php echo e(isActiveRoute(['section.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Section</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('section.index')); ?>"
                                class="<?php echo e(isActiveRoute(['section.index', 'section.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Sections</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['academic.session.index', 'academic.session.create', 'academic.session.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#academicSession-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['academic.session.index', 'academic.session.create', 'academic.session.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Academic Session</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="academicSession-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['academic.session.index', 'academic.session.create', 'academic.session.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('academic.session.create')); ?>"
                                class="<?php echo e(isActiveRoute(['academic.session.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Session</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('academic.session.index')); ?>"
                                class="<?php echo e(isActiveRoute(['academic.session.index', 'academic.session.index'])); ?>">
                                <i class="bi bi-circle"></i><span>All Sessions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo e(route('category.index')); ?>"
                        class="<?php echo e(isActiveRoute(['category.index', 'sub-category.edit', 'sub-category.field-add', 'sub-category.edit'])); ?>">
                        <i class="bi bi-layer-backward"></i></i>
                        <span>Content Group List</span>
                    </a>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['grade.index', 'grade.create', 'grade.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#grade-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['grade.index', 'grade.create', 'grade.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Grades</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="grade-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['grade.index', 'grade.create', 'grade.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('grade.create')); ?>" class="<?php echo e(isActiveRoute(['grade.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Grade</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('grade.index')); ?>"
                                class="<?php echo e(isActiveRoute(['grade.index', 'grade.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Grades</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['level.index', 'level.create', 'level.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Management-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['level.index', 'level.create', 'level.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Course Levels</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Management-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['level.index', 'level.create', 'level.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('level.create')); ?>" class="<?php echo e(isActiveRoute(['level.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Course Level</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('level.index')); ?>"
                                class="<?php echo e(isActiveRoute(['level.index', 'level.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Course Levels</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['language.index', 'language.create', 'language.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Language-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['language.index', 'language.create', 'language.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Language</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Language-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['language.index', 'language.create', 'language.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('language.create')); ?>"
                                class="<?php echo e(isActiveRoute(['language.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Language</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('language.index')); ?>"
                                class="<?php echo e(isActiveRoute(['language.index', 'language.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Languages</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['lesson.index', 'lesson.create', 'lesson.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#Number-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['lesson.index', 'lesson.create', 'lesson.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Lesson Number</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Number-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['lesson.index', 'lesson.create', 'lesson.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('lesson.create')); ?>" class="<?php echo e(isActiveRoute(['lesson.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Lesson Number</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('lesson.index')); ?>"
                                class="<?php echo e(isActiveRoute(['lesson.index', 'lesson.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Lesson Numbers</span>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['bookset.index', 'bookset.create', 'bookset.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#bookset-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['bookset.index', 'bookset.create', 'bookset.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Book Sets</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="bookset-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['bookset.index', 'bookset.create', 'bookset.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('bookset.create')); ?>"
                                class="<?php echo e(isActiveRoute(['bookset.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Book Set</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('bookset.index')); ?>"
                                class="<?php echo e(isActiveRoute(['bookset.index', 'bookset.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Book Set</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo e(route('prefix.list')); ?>"
                        class="<?php echo e(isActiveRoute(['prefix.list', 'prefix.edit'])); ?>">
                        <i class="bi bi-record-fill"></i></i>
                        <span>Access Code Prefixes</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('d2c-category.index')); ?>"
                        class="<?php echo e(isActiveRoute(['d2c-category.index', 'd2c-content.assginment'])); ?>">
                        <i class="bi bi-record-fill"></i></i>
                        <span>D2C Digital Content</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('state.district.index')); ?>"
                        class="<?php echo e(isActiveRoute(['state.district.index', 'state.district.create', 'state.district.edit', 'district.create', 'district.index'])); ?>">
                        <i class="bi bi-record-fill"></i></i>
                        <span>State & Districts</span>
                    </a>
                </li>
                
                <li>
                    <a class="nav-link <?php echo e(isActiveMenu(['question-type.index', 'question-type.create', 'question-type.edit']) ? '' : 'collapsed'); ?>"
                        data-bs-target="#questionType-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e(isActiveMenu(['question-type.index', 'question-type.create', 'question-type.edit']) ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>TPG Questions Type</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="questionType-nav"
                        class="nav-content collapse ps-4 <?php echo e(isActiveMenu(['question-type.index', 'question-type.create', 'question-type.edit']) ? 'show' : ''); ?>"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="<?php echo e(route('question-type.create')); ?>"
                                class="<?php echo e(isActiveRoute(['question-type.create'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Type</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('question-type.index')); ?>"
                                class="<?php echo e(isActiveRoute(['question-type.index', 'question-type.edit'])); ?>">
                                <i class="bi bi-circle"></i><span>All Question Type</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowCourseMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveCourseMenu ? '' : 'collapsed'); ?>" data-bs-target="#courses-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveCourseMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-book"></i><span>Digital Content Mgmt.</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="courses-nav" class="nav-content collapse <?php echo e($isActiveCourseMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('course.create')); ?>"
                        class="<?php echo e(isActiveRoute(['course.create', 'courses.bulk-upload'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Book/Course</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('course.index', ['group' => 'academic-digital-content'])); ?>"
                        class="<?php echo e(isActiveRoute(['course.index', 'course.edit', 'course.add.chapter', 'course.chapter.edit', 'courses.chapter.bulk-upload'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Books/Courses List</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('course.complimentary.index')); ?>"
                        class="<?php echo e(isActiveRoute(['course.complimentary.index'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Talent Box List (Compli.)</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('course.academic-activities.index')); ?>"
                        class="<?php echo e(isActiveRoute(['course.academic-activities.index'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Academic Activities List</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('merge.course')); ?>" class="<?php echo e(isActiveRoute(['merge.course'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Merge Courses</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveTdcMenu ? 'active' : ''); ?>" href="<?php echo e(route('teacher.development.index')); ?>">
                <i class="bi bi-camera-video"></i><span>Teacher Dev. Videos</span>
            </a>
        </li> 
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPlannerManagementMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActivePlannerManagementMenu ? '' : 'collapsed'); ?>"
                data-bs-target="#planner-nav" data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActivePlannerManagementMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-calendar-event"></i><span>Planner Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="planner-nav" class="nav-content collapse <?php echo e($isActivePlannerManagementMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('planner.create')); ?>" class="<?php echo e(isActiveRoute(['planner.create'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Planner</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('planner.index')); ?>"
                        class="<?php echo e(isActiveRoute(['planner.index', 'planner.view'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Planner List</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowholidayMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveholidayMenu ? '' : 'collapsed'); ?>" data-bs-target="#holiday-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveholidayMenu ? 'true' : 'false'); ?>">
                <i class="ri-community-line"></i><span>Holiday Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="holiday-nav" class="nav-content collapse <?php echo e($isActiveholidayMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('add.holiday')); ?>" class="<?php echo e(isActiveRoute(['add.holiday'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Holiday</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('index.holiday')); ?>"
                        class="<?php echo e(isActiveRoute(['index.holiday', 'edit.holiday'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Holiday List</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowMediaGalleryMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveMediaGalleryMenu ? 'active' : ''); ?>" href="<?php echo e(route('folder.list')); ?>">
                <i class="bi bi-card-image"></i><span>Content Deck</span>
            </a>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowEnquiriesMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveEnquiriesMenu ? 'active' : ''); ?>" href="<?php echo e(route('enquiries')); ?>">
                <i class="bi bi-gear"></i><span>Contact Page Enquiries</span>
            </a>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowBlogMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveBlogMenu ? '' : 'collapsed'); ?>" data-bs-target="#Blog-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveBlogMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-chat-square-text"></i><span>Blog Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="Blog-nav" class="nav-content collapse <?php echo e($isActiveBlogMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('blog.category.index')); ?>"
                        class="<?php echo e(isActiveRoute(['blog.category.index', 'blog.category.edit', 'blog.sub_category.create'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Blog Categories</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('blog.index')); ?>"
                        class="<?php echo e(isActiveRoute(['blog.index', 'blog.create', 'blog.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>All Blogs</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowEmailTempMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveEmailTempMenu ? '' : 'collapsed'); ?>" data-bs-target="#EmailTemplate-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveEmailTempMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-layout-text-window-reverse"></i><span>Email Templates</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="EmailTemplate-nav" class="nav-content collapse <?php echo e($isActiveEmailTempMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('email-template.add')); ?>"
                        class="<?php echo e(isActiveRoute(['email-template.add'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Email Template </span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('email-template.index')); ?>"
                        class="<?php echo e(isActiveRoute(['email-template.index', 'email-template.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Email Templates</span>
                    </a>
                </li>
                
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowCmsPageMenu): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveCmsPageMenu ? '' : 'collapsed'); ?>" data-bs-target="#Cms-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveCmsPageMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-wrench"></i><span>Page Managment</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="Cms-nav" class="nav-content collapse <?php echo e($isActiveCmsPageMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('home.page-content')); ?>" class="<?php echo e(isActiveRoute(['home.page-content'])); ?>">
                        <i class="bi bi-circle"></i><span>Home Page Content</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('cms-about.index')); ?>" class="<?php echo e(isActiveRoute(['cms-about.index'])); ?>">
                        <i class="bi bi-record-fill"></i><span>About-us Page</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('cms.index')); ?>" class="<?php echo e(isActiveRoute(['cms.index', 'cms.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Other CMS Pages</span>
                    </a>
                </li>
                <!-- Testimonial Content -->
                <li>
                    <a class="nav-link <?php echo e($isActiveCmsTestimonialMenu ? '' : 'collapsed'); ?>"
                        data-bs-target="#Testimonial-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="<?php echo e($isActiveCmsTestimonialMenu ? 'true' : 'false'); ?>">
                        <i class="bi bi-record-fill"></i><span>Testimonial Content</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Testimonial-nav"
                        class="nav-content collapse ps-4 <?php echo e($isActiveCmsTestimonialMenu ? 'show' : ''); ?>"
                        data-bs-parent="#WebsitePage-nav">
                        <li>
                            <a href="<?php echo e(route('testimonial.page-content.add')); ?>"
                                class="<?php echo e(isActiveRoute(['testimonial.page-content.add'])); ?>">
                                <i class="bi bi-circle"></i><span>Add Content </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('testimonial.index')); ?>"
                                class="<?php echo e(isActiveRoute(['testimonial.index', 'testimonial.page-content.edit'])); ?>">
                                <i class="bi bi-circle"></i><span> Content</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo e(route('cms-faq.index')); ?>"
                        class="<?php echo e(isActiveRoute(['cms-faq.index', 'cms-faq.add', 'cms-faq.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>FAQs</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('our.offerings.add')); ?>" class="<?php echo e(isActiveRoute(['our.offerings.add'])); ?>">
                        <i class="bi bi-circle"></i><span>Our Offering</span>
                    </a>
                </li>
            </ul>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowSettingsMenu): ?>
        <li class="nav-item">
            <a href="<?php echo e(route('setting.add')); ?>" class="nav-link <?php echo e($isActiveSettingsMenu ? 'active' : ''); ?>">
                <i class="bi bi-gear"></i><span>Master Settings</span>
            </a>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isShowSettingsMenu): ?>
        <li class="nav-item">
            <a href="<?php echo e(route('flash.notification.alerts')); ?>"
                class="nav-link <?php echo e($isActiveNotificationAlertsMenu ? 'active' : ''); ?>">
                <i class="bi bi-exclamation-triangle"></i><span>Marketing & Flash Alerts</span>
            </a>
        </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <li class="nav-item">
        <a href="<?php echo e(route('user-manual.index')); ?>"
            class="nav-link <?php echo e($isActiveUserManualsMenu ? 'active' : ''); ?>">
            <i class="bi bi-journal-bookmark"></i><span>User Manuals</span>
        </a>
    </li>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(getUserRoles(), ['super_admin', 'admin', 'qd_developer'])): ?>
        
        <li class="nav-item">
            <a class="nav-link <?php echo e($isActiveTicketMangeMenu ? '' : 'collapsed'); ?>" data-bs-target="#TicketManage-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="<?php echo e($isActiveTicketMangeMenu ? 'true' : 'false'); ?>">
                <i class="bi bi-chat-left-quote"></i><span>Ticket Managment System</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="TicketManage-nav" class="nav-content collapse <?php echo e($isActiveTicketMangeMenu ? 'show' : ''); ?>"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="<?php echo e(route('tickets.create')); ?>" class="<?php echo e(isActiveRoute(['tickets.create'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Add Ticket </span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('tickets.index')); ?>"
                        class="<?php echo e(isActiveRoute(['tickets.index', 'tickets.edit'])); ?>">
                        <i class="bi bi-record-fill"></i><span>Tickets</span>
                    </a>
                </li>
            </ul>
        </li>
        
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <li class="nav-item">
        <a href="<?php echo e(route('erp-data.schools.index')); ?>"
            class="nav-link <?php echo e($isActiveErprDataSyncMenu ? 'active' : ''); ?>">
            <i class="bi bi-journal-bookmark"></i><span>ERP Data Sync</span>
        </a>
    </li>
    
    
    

    
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/layouts/sidebar.blade.php ENDPATH**/ ?>