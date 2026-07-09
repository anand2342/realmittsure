@php
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
@endphp

<ul class="sidebar-nav" id="sidebar-nav">
    @if ($isShowDashboardMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveDashboardMenu ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
    @endif
    @if ($isShowUserMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveUserMenu ? '' : 'collapsed' }}" data-bs-target="#user-nav"
                data-bs-toggle="collapse" href="#" aria-expanded="{{ $isActiveUserMenu ? 'true' : 'false' }}">
                <i class="bi bi-person-circle"></i><span>User Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-nav" class="nav-content collapse {{ $isActiveUserMenu ? 'show' : '' }}"
                data-bs-parent="#user-nav">
                <!-- Link for creating a user -->
                <li>
                    <a href="{{ route('user.create') }}"
                        class="{{ request()->routeIs('user.create') ? 'active' : '' }}">
                        <i class="bi bi-record-fill"></i><span>Add User</span>
                    </a>
                </li>

                <!-- All Users Section -->
                <li class="nav-item">
                    @php
                        $schoolUsersActive = false;
                        $currentRole = request()->query('role');
                        foreach ($adminSidebarUserRoles as $role) {
                            if (request()->routeIs('user.index') && $currentRole == $role->role_slug) {
                                $schoolUsersActive = true;
                                break;
                            }
                        }
                    @endphp
                    <a class="nav-link {{ $schoolUsersActive ? '' : 'collapsed' }}" data-bs-target="#school-users-nav"
                        data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ $schoolUsersActive ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>All Users</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="school-users-nav" class="nav-content collapse {{ $schoolUsersActive ? 'show' : '' }} ps-3"
                        data-bs-parent="#user-nav">
                        @foreach ($adminSidebarUserRoles as $role)
                            @if ($role->role_name)
                                <li>
                                    <a href="{{ route('user.index', ['role' => $role->role_slug]) }}"
                                        class="{{ request()->routeIs('user.index') && request()->query('role') == $role->role_slug ? 'active' : '' }}">
                                        <i class="bi bi-circle"></i>
                                        <span>{{ $role->role_name }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                <!-- System Users Section -->
                <li class="nav-item">
                    @php
                        $systemUsersActive = false;
                        $currentRole = request()->query('role');
                        foreach ($adminSidebarsytemUserRoles as $role) {
                            if (request()->routeIs('user.index') && $currentRole == $role->role_slug) {
                                $systemUsersActive = true;
                                break;
                            }
                        }
                    @endphp
                    <a class="nav-link {{ $systemUsersActive ? '' : 'collapsed' }}" data-bs-target="#system-users-nav"
                        data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ $systemUsersActive ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>System Users</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="system-users-nav" class="nav-content collapse {{ $systemUsersActive ? 'show' : '' }} ps-3"
                        data-bs-parent="#user-nav">
                        @foreach ($adminSidebarsytemUserRoles as $role)
                            @if ($role->role_name)
                                <li>
                                    <a href="{{ route('user.index', ['role' => $role->role_slug]) }}"
                                        class="{{ request()->routeIs('user.index') && request()->query('role') == $role->role_slug ? 'active' : '' }}">
                                        <i class="bi bi-circle"></i>
                                        <span>{{ $role->role_name }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>

                <!-- Link for roles management -->
                <li>
                    <a href="{{ route('roles.index') }}"
                        class="{{ request()->routeIs(['roles.index', 'roles.create', 'roles.edit']) ? 'active' : '' }}">
                        <i class="bi bi-record-fill"></i><span>Roles Management</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowRBAMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveRBAMenu ? 'active' : '' }}" href="{{ route('permissions.assign') }}">
                <i class="bi bi-shield-lock"></i>
                <span>Role Based Access Control</span>
            </a>
        </li>
    @endif
    {{--  Access code skip for 2025-2026   --}}
    @if ($isShowAccessCodeMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveAccessCodeMenu ? '' : 'collapsed' }}" data-bs-target="#access-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-code"></i><span>Access Code Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="access-nav" class="nav-content collapse {{ $isActiveAccessCodeMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('access.code.create') }}" class="{{ isActiveRoute(['access.code.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Access Code</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('access.code.index') }}"
                        class="{{ isActiveRoute(['access.code.index', 'access.code.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Embibe Access Code</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('print.setting') }}" class="{{ isActiveRoute(['print.setting']) }}">
                        <i class="bi bi-record-fill"></i><span>Print Settings</span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('access.code.olympiad.index') }}"
                        class="{{ isActiveRoute(['access.code.olympiad.index', 'access.code.olympiad.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Olympiad Access Code</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('olympiad.print') }}" class="{{ isActiveRoute(['olympiad.print']) }}">
                        <i class="bi bi-record-fill"></i><span>Olympiad Print Settings</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowSubsPlanMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveSubsPlanMenu ? '' : 'collapsed' }}" data-bs-target="#plans-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveSubsPlanMenu ? 'true' : 'false' }}">
                <i class="bi bi-collection"></i><span>Subscription Plans</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="plans-nav" class="nav-content collapse {{ $isActiveSubsPlanMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('plans.add') }}" class="{{ isActiveRoute(['plans.add']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Plan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('plans.index') }}" class="{{ isActiveRoute(['plans.index', 'plans.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>All Plans</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('course-bucket.index') }}"
                        class="{{ isActiveRoute(['course-bucket.index', 'course-bucket.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Courses Bucket</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('course-bucket.add') }}" class="{{ isActiveRoute(['course-bucket.add']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Courses Bucket</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchase.report') }}" class="{{ isActiveRoute(['purchase.report']) }}">
                        <i class="bi bi-record-fill"></i><span>Purchases Report</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowMasterMenu)
        <li class="nav-item">
            <!-- Main Master Management Menu -->
            <a class="nav-link {{ $isActiveMasterMenu ? '' : 'collapsed' }}" data-bs-target="#MasterManagement-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveMasterMenu ? 'true' : 'false' }}">
                <i class="bi bi-gem"></i><span>Master Management</span>
                <i class="bi bi-chevron-down ms-auto"></i> <!-- Down arrow icon -->
            </a>
            <ul id="MasterManagement-nav" class="nav-content collapse ps-3 {{ $isActiveMasterMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">

                {{-- <------School Management component----> --}}
                <li>
                    <a href="{{ route('school.list') }}"
                        class="{{ isActiveRoute(['school.list', 'school.edit', 'school.access.code']) }}">
                        <i class="bi bi-record-fill"></i></i>
                        <span>School Management</span>
                    </a>
                </li>
                {{-- <------Board component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['board.index', 'board.create', 'board.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Board-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['board.index', 'board.create', 'board.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Board</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Board-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['board.index', 'board.create', 'board.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('board.create') }}" class="{{ isActiveRoute(['board.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Board</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('board.index') }}"
                                class="{{ isActiveRoute(['board.index', 'board.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Boards</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Medium component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['medium.index', 'medium.create', 'medium.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Medium-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['medium.index', 'medium.create', 'medium.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Medium</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Medium-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['medium.index', 'medium.create', 'medium.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('medium.create') }}" class="{{ isActiveRoute(['medium.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Medium</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('medium.index') }}"
                                class="{{ isActiveRoute(['medium.index', 'medium.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Mediums</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------BookSeries component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['book.series.index', 'book.series.create', 'book.series.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#BookSeries-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['book.series.index', 'book.series.create', 'book.series.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Book Series</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="BookSeries-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['book.series.index', 'book.series.create', 'book.series.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('book.series.create') }}"
                                class="{{ isActiveRoute(['book.series.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Book Series</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('book.series.index') }}"
                                class="{{ isActiveRoute(['book.series.index', 'book.series.edit']) }}">
                                <i class="bi bi-circle"></i><span>All BookSeries</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Class component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['class.index', 'class.create', 'class.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Class-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['class.index', 'class.create', 'class.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Class</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Class-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['class.index', 'class.create', 'class.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('class.create') }}" class="{{ isActiveRoute(['class.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Class</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('class.index') }}"
                                class="{{ isActiveRoute(['class.index', 'class.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Classes</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Subject component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['subject.index', 'subject.create', 'subject.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Subject-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['subject.index', 'subject.create', 'subject.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Subject</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Subject-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['subject.index', 'subject.create', 'subject.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('subject.create') }}" class="{{ isActiveRoute(['subject.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Subject</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('subject.index') }}"
                                class="{{ isActiveRoute(['subject.index', 'subject.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Subjects</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Section component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['section.index', 'section.create', 'section.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Section-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['section.index', 'section.create', 'section.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Classes Section</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Section-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['section.index', 'section.create', 'section.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('section.create') }}" class="{{ isActiveRoute(['section.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Section</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('section.index') }}"
                                class="{{ isActiveRoute(['section.index', 'section.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Sections</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- --------Academic Session ----- --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['academic.session.index', 'academic.session.create', 'academic.session.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#academicSession-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['academic.session.index', 'academic.session.create', 'academic.session.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Academic Session</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="academicSession-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['academic.session.index', 'academic.session.create', 'academic.session.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('academic.session.create') }}"
                                class="{{ isActiveRoute(['academic.session.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Session</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('academic.session.index') }}"
                                class="{{ isActiveRoute(['academic.session.index', 'academic.session.index']) }}">
                                <i class="bi bi-circle"></i><span>All Sessions</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('category.index') }}"
                        class="{{ isActiveRoute(['category.index', 'sub-category.edit', 'sub-category.field-add', 'sub-category.edit']) }}">
                        <i class="bi bi-layer-backward"></i></i>
                        <span>Content Group List</span>
                    </a>
                </li>
                {{-- <------Grade component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['grade.index', 'grade.create', 'grade.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#grade-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['grade.index', 'grade.create', 'grade.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Grades</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="grade-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['grade.index', 'grade.create', 'grade.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('grade.create') }}" class="{{ isActiveRoute(['grade.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Grade</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('grade.index') }}"
                                class="{{ isActiveRoute(['grade.index', 'grade.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Grades</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Course Level component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['level.index', 'level.create', 'level.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Management-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['level.index', 'level.create', 'level.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Course Levels</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Management-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['level.index', 'level.create', 'level.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('level.create') }}" class="{{ isActiveRoute(['level.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Course Level</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('level.index') }}"
                                class="{{ isActiveRoute(['level.index', 'level.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Course Levels</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Language component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['language.index', 'language.create', 'language.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Language-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['language.index', 'language.create', 'language.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Language</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Language-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['language.index', 'language.create', 'language.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('language.create') }}"
                                class="{{ isActiveRoute(['language.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Language</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('language.index') }}"
                                class="{{ isActiveRoute(['language.index', 'language.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Languages</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Lesson Number component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['lesson.index', 'lesson.create', 'lesson.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#Number-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['lesson.index', 'lesson.create', 'lesson.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Lesson Number</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Number-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['lesson.index', 'lesson.create', 'lesson.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('lesson.create') }}" class="{{ isActiveRoute(['lesson.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Lesson Number</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lesson.index') }}"
                                class="{{ isActiveRoute(['lesson.index', 'lesson.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Lesson Numbers</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- <------Book Set component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['bookset.index', 'bookset.create', 'bookset.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#bookset-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['bookset.index', 'bookset.create', 'bookset.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Book Sets</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="bookset-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['bookset.index', 'bookset.create', 'bookset.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('bookset.create') }}"
                                class="{{ isActiveRoute(['bookset.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Book Set</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bookset.index') }}"
                                class="{{ isActiveRoute(['bookset.index', 'bookset.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Book Set</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('prefix.list') }}"
                        class="{{ isActiveRoute(['prefix.list', 'prefix.edit']) }}">
                        <i class="bi bi-record-fill"></i></i>
                        <span>Access Code Prefixes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('d2c-category.index') }}"
                        class="{{ isActiveRoute(['d2c-category.index', 'd2c-content.assginment']) }}">
                        <i class="bi bi-record-fill"></i></i>
                        <span>D2C Digital Content</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('state.district.index') }}"
                        class="{{ isActiveRoute(['state.district.index', 'state.district.create', 'state.district.edit', 'district.create', 'district.index']) }}">
                        <i class="bi bi-record-fill"></i></i>
                        <span>State & Districts</span>
                    </a>
                </li>
                {{-- <------Question type component----> --}}
                <li>
                    <a class="nav-link {{ isActiveMenu(['question-type.index', 'question-type.create', 'question-type.edit']) ? '' : 'collapsed' }}"
                        data-bs-target="#questionType-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ isActiveMenu(['question-type.index', 'question-type.create', 'question-type.edit']) ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>TPG Questions Type</span>
                        <i class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="questionType-nav"
                        class="nav-content collapse ps-4 {{ isActiveMenu(['question-type.index', 'question-type.create', 'question-type.edit']) ? 'show' : '' }}"
                        data-bs-parent="#MasterManagement-nav">
                        <li>
                            <a href="{{ route('question-type.create') }}"
                                class="{{ isActiveRoute(['question-type.create']) }}">
                                <i class="bi bi-circle"></i><span>Add Type</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('question-type.index') }}"
                                class="{{ isActiveRoute(['question-type.index', 'question-type.edit']) }}">
                                <i class="bi bi-circle"></i><span>All Question Type</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowCourseMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveCourseMenu ? '' : 'collapsed' }}" data-bs-target="#courses-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveCourseMenu ? 'true' : 'false' }}">
                <i class="bi bi-book"></i><span>Digital Content Mgmt.</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="courses-nav" class="nav-content collapse {{ $isActiveCourseMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('course.create') }}"
                        class="{{ isActiveRoute(['course.create', 'courses.bulk-upload']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Book/Course</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('course.index', ['group' => 'academic-digital-content']) }}"
                        class="{{ isActiveRoute(['course.index', 'course.edit', 'course.add.chapter', 'course.chapter.edit', 'courses.chapter.bulk-upload']) }}">
                        <i class="bi bi-record-fill"></i><span>Books/Courses List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('course.complimentary.index') }}"
                        class="{{ isActiveRoute(['course.complimentary.index']) }}">
                        <i class="bi bi-record-fill"></i><span>Talent Box List (Compli.)</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('course.academic-activities.index') }}"
                        class="{{ isActiveRoute(['course.academic-activities.index']) }}">
                        <i class="bi bi-record-fill"></i><span>Academic Activities List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('merge.course') }}" class="{{ isActiveRoute(['merge.course']) }}">
                        <i class="bi bi-record-fill"></i><span>Merge Courses</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $isActiveTdcMenu ? 'active' : '' }}" href="{{ route('teacher.development.index') }}">
                <i class="bi bi-camera-video"></i><span>Teacher Dev. Videos</span>
            </a>
        </li> 
    @endif
    {{-- @if ($isShowCouponMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveCouponMenu ? '' : 'collapsed' }}" data-bs-target="#coupons-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveCouponMenu ? 'true' : 'false' }}">
                <i class="bi bi-bag-check-fill"></i><span>Coupon Management </span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="coupons-nav" class="nav-content collapse {{ $isActiveCouponMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('coupon.create') }}" class="{{ isActiveRoute(['coupon.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Coupon</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('coupon.index') }}"
                        class="{{ isActiveRoute(['coupon.index', 'coupon.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Coupons List</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif --}}
    @if ($isPlannerManagementMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActivePlannerManagementMenu ? '' : 'collapsed' }}"
                data-bs-target="#planner-nav" data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActivePlannerManagementMenu ? 'true' : 'false' }}">
                <i class="bi bi-calendar-event"></i><span>Planner Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="planner-nav" class="nav-content collapse {{ $isActivePlannerManagementMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('planner.create') }}" class="{{ isActiveRoute(['planner.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Planner</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('planner.index') }}"
                        class="{{ isActiveRoute(['planner.index', 'planner.view']) }}">
                        <i class="bi bi-record-fill"></i><span>Planner List</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    {{-- @if ($isTestPaperGenrationMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveTestPaperGenrationMenu ? '' : 'collapsed' }}"
                data-bs-target="#testPaper-nav" data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveTestPaperGenrationMenu ? 'true' : 'false' }}">
                <i class="bi bi-file-earmark-break"></i></i><span>Test Paper Gen.(TPG)</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="testPaper-nav" class="nav-content collapse {{ $isActiveTestPaperGenrationMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('test-paper.create') }}" class="{{ isActiveRoute(['test-paper.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Test Paper</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('test-paper.index') }}"
                        class="{{ isActiveRoute(['test-paper.index', 'test-paper.view', 'question.add', 'test-paper.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Test Papers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('question-bank.create') }}"
                        class="{{ isActiveRoute(['question-bank.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Question Bank</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('question-bank.index') }}"
                        class="{{ isActiveRoute(['question-bank.index', 'question-bank.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Question Banks</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif --}}
    @if ($isShowholidayMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveholidayMenu ? '' : 'collapsed' }}" data-bs-target="#holiday-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveholidayMenu ? 'true' : 'false' }}">
                <i class="ri-community-line"></i><span>Holiday Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="holiday-nav" class="nav-content collapse {{ $isActiveholidayMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('add.holiday') }}" class="{{ isActiveRoute(['add.holiday']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Holiday</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('index.holiday') }}"
                        class="{{ isActiveRoute(['index.holiday', 'edit.holiday']) }}">
                        <i class="bi bi-record-fill"></i><span>Holiday List</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowMediaGalleryMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveMediaGalleryMenu ? 'active' : '' }}" href="{{ route('folder.list') }}">
                <i class="bi bi-card-image"></i><span>Content Deck</span>
            </a>
        </li>
    @endif

    {{-- @if ($isShowSchoolActivityLogsMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveSchoolActivityLogsMenu ? '' : 'collapsed' }}"
                data-bs-target="#school-activity-logs-nav" data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveSchoolActivityLogsMenu ? 'true' : 'false' }}">
                <i class="ri-database-line"></i><span>School Activity Logs</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="school-activity-logs-nav"
                class="nav-content collapse {{ $isActiveSchoolActivityLogsMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('online.class.logs') }}"
                        class="{{ isActiveRoute(['online.class.logs', 'online.class.log.details']) }}">
                        <i class="bi bi-record-fill"></i><span>Online Class Logs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('folder.index') }}"
                        class="{{ isActiveRoute(['folder.index', 'files.index']) }}">
                        <i class="bi bi-record-fill"></i><span>Uploaded Content Logs</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif --}}

    @if ($isShowEnquiriesMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveEnquiriesMenu ? 'active' : '' }}" href="{{ route('enquiries') }}">
                <i class="bi bi-gear"></i><span>Contact Page Enquiries</span>
            </a>
        </li>
    @endif


    @if ($isShowBlogMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveBlogMenu ? '' : 'collapsed' }}" data-bs-target="#Blog-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveBlogMenu ? 'true' : 'false' }}">
                <i class="bi bi-chat-square-text"></i><span>Blog Management</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="Blog-nav" class="nav-content collapse {{ $isActiveBlogMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('blog.category.index') }}"
                        class="{{ isActiveRoute(['blog.category.index', 'blog.category.edit', 'blog.sub_category.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Blog Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('blog.index') }}"
                        class="{{ isActiveRoute(['blog.index', 'blog.create', 'blog.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>All Blogs</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowEmailTempMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveEmailTempMenu ? '' : 'collapsed' }}" data-bs-target="#EmailTemplate-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveEmailTempMenu ? 'true' : 'false' }}">
                <i class="bi bi-layout-text-window-reverse"></i><span>Email Templates</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="EmailTemplate-nav" class="nav-content collapse {{ $isActiveEmailTempMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('email-template.add') }}"
                        class="{{ isActiveRoute(['email-template.add']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Email Template </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('email-template.index') }}"
                        class="{{ isActiveRoute(['email-template.index', 'email-template.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Email Templates</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('sms-template.index') }}"
                        class="{{ isActiveRoute(['sms-template.index']) }}">
                        <i class="bi bi-record-fill"></i><span> SMS Templates </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sms-template.add') }}" class="{{ isActiveRoute(['sms-template.add']) }}">
                        <i class="bi bi-record-fill"></i><span> Add SMS Template </span>
                    </a>
                </li> --}}
            </ul>
        </li>
    @endif

    @if ($isShowCmsPageMenu)
        <li class="nav-item">
            <a class="nav-link {{ $isActiveCmsPageMenu ? '' : 'collapsed' }}" data-bs-target="#Cms-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveCmsPageMenu ? 'true' : 'false' }}">
                <i class="bi bi-wrench"></i><span>Page Managment</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="Cms-nav" class="nav-content collapse {{ $isActiveCmsPageMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('home.page-content') }}" class="{{ isActiveRoute(['home.page-content']) }}">
                        <i class="bi bi-circle"></i><span>Home Page Content</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cms-about.index') }}" class="{{ isActiveRoute(['cms-about.index']) }}">
                        <i class="bi bi-record-fill"></i><span>About-us Page</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cms.index') }}" class="{{ isActiveRoute(['cms.index', 'cms.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Other CMS Pages</span>
                    </a>
                </li>
                <!-- Testimonial Content -->
                <li>
                    <a class="nav-link {{ $isActiveCmsTestimonialMenu ? '' : 'collapsed' }}"
                        data-bs-target="#Testimonial-nav" data-bs-toggle="collapse" href="#"
                        aria-expanded="{{ $isActiveCmsTestimonialMenu ? 'true' : 'false' }}">
                        <i class="bi bi-record-fill"></i><span>Testimonial Content</span><i
                            class="bi bi-chevron-down ms-auto fs-6"></i>
                    </a>
                    <ul id="Testimonial-nav"
                        class="nav-content collapse ps-4 {{ $isActiveCmsTestimonialMenu ? 'show' : '' }}"
                        data-bs-parent="#WebsitePage-nav">
                        <li>
                            <a href="{{ route('testimonial.page-content.add') }}"
                                class="{{ isActiveRoute(['testimonial.page-content.add']) }}">
                                <i class="bi bi-circle"></i><span>Add Content </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('testimonial.index') }}"
                                class="{{ isActiveRoute(['testimonial.index', 'testimonial.page-content.edit']) }}">
                                <i class="bi bi-circle"></i><span> Content</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('cms-faq.index') }}"
                        class="{{ isActiveRoute(['cms-faq.index', 'cms-faq.add', 'cms-faq.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>FAQs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('our.offerings.add') }}" class="{{ isActiveRoute(['our.offerings.add']) }}">
                        <i class="bi bi-circle"></i><span>Our Offering</span>
                    </a>
                </li>
            </ul>
        </li>
    @endif
    @if ($isShowSettingsMenu)
        <li class="nav-item">
            <a href="{{ route('setting.add') }}" class="nav-link {{ $isActiveSettingsMenu ? 'active' : '' }}">
                <i class="bi bi-gear"></i><span>Master Settings</span>
            </a>
        </li>
    @endif
    @if ($isShowSettingsMenu)
        <li class="nav-item">
            <a href="{{ route('flash.notification.alerts') }}"
                class="nav-link {{ $isActiveNotificationAlertsMenu ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle"></i><span>Marketing & Flash Alerts</span>
            </a>
        </li>
    @endif
    {{--  @if ($isShowUserManualsMenu)  --}}
    <li class="nav-item">
        <a href="{{ route('user-manual.index') }}"
            class="nav-link {{ $isActiveUserManualsMenu ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i><span>User Manuals</span>
        </a>
    </li>
    @if (in_array(getUserRoles(), ['super_admin', 'admin', 'qd_developer']))
        {{-- @if ($isShowTicketMangeMenu) --}}
        <li class="nav-item">
            <a class="nav-link {{ $isActiveTicketMangeMenu ? '' : 'collapsed' }}" data-bs-target="#TicketManage-nav"
                data-bs-toggle="collapse" href="#"
                aria-expanded="{{ $isActiveTicketMangeMenu ? 'true' : 'false' }}">
                <i class="bi bi-chat-left-quote"></i><span>Ticket Managment System</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="TicketManage-nav" class="nav-content collapse {{ $isActiveTicketMangeMenu ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('tickets.create') }}" class="{{ isActiveRoute(['tickets.create']) }}">
                        <i class="bi bi-record-fill"></i><span>Add Ticket </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tickets.index') }}"
                        class="{{ isActiveRoute(['tickets.index', 'tickets.edit']) }}">
                        <i class="bi bi-record-fill"></i><span>Tickets</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- @endif --}}
    @endif
    {{-- @if ($isShowErprDataSyncMenu) --}}
    <li class="nav-item">
        <a href="{{ route('erp-data.schools.index') }}"
            class="nav-link {{ $isActiveErprDataSyncMenu ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i><span>ERP Data Sync</span>
        </a>
    </li>
    {{-- @endif --}}
    {{-- <li class="nav-item">
        <a href="{{ route('merge.course') }}"
            class="nav-link {{ $isActiveUserManualsMenu ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark"></i><span>Merge Courses</span>
        </a>
    </li> --}}
    {{--  @endif  --}}

    {{--  <hr class="form-divider">
    <p>ERP-SMS System</p>
    <li class="nav-item">
        <a class="nav-link {{ $isActiveCourseMenu ? '' : 'collapsed' }}" data-bs-target="#courses-nav"
            data-bs-toggle="collapse" href="#" aria-expanded="{{ $isActiveCourseMenu ? 'true' : 'false' }}">
            <i class="bi bi-book"></i><span>Fees Management</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="courses-nav" class="nav-content collapse {{ $isActiveCourseMenu ? 'show' : '' }}"
            data-bs-parent="#sidebar-nav">
            <li>
                <a href="{{ route('create.fee.header') }}">
                    <i class="bi bi-record-fill"></i><span>Fees Header</span>
                </a>
            </li>
            <li>
                <a href="{{ route('create.consession.category') }}">
                    <i class="bi bi-record-fill"></i><span>Consession Category</span>
                </a>
            </li>
        </ul>
    </li>
</ul>  --}}
