<!-- Include CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<?php $__env->startSection('content'); ?>
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('crm.automation.dashboard')); ?>"
                class="btn btn-success text-center">

                <i class="bi bi-gear-wide-connected fs-5"></i>
                <span class="fw-medium">Automation Dashboard</span>
            </a>
        </div>
    </div>

    <section class="section dashboard">
        <?php
            $loginRoles = [
                'school_admin' => [
                    'label' => 'School Admin',
                    'color' => 'success',
                    'icon' => 'bi-building',
                ],
                'school_teacher' => [
                    'label' => 'School Teachers',
                    'color' => 'warning',
                    'icon' => 'bi-person-badge',
                ],
                'school_student' => [
                    'label' => 'School Students',
                    'color' => 'info',
                    'icon' => 'bi-mortarboard',
                ],
                'b2c_student' => [
                    'label' => 'B2C Students',
                    'color' => 'secondary',
                    'icon' => 'bi-person',
                ],
                'd2c_user' => [
                    'label' => 'D2C Students',
                    'color' => 'primary',
                    'icon' => 'bi-shield-lock',
                ],
            ];
        ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() == 'super_admin' || getUserRoles() == 'qd_developer'): ?>
            <div class="row mt-3">
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">📊 Today's Logged-In Users Count</h5>
                            <div class="row mt-3 px-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $loginRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $urlToday = route('login.users.view', ['role' => $role, 'type' => 'logged-in']);
                                    ?>

                                    <div class="col px-2">
                                        <a href="<?php echo e($urlToday); ?>" class="text-decoration-none">
                                            <div
                                                class="p-2 rounded border border-<?php echo e($data['color']); ?> h-100 shadow-sm hover-shadow text-center">
                                                <div class="text-<?php echo e($data['color']); ?>">
                                                    
                                                </div>
                                                <div style="font-size: 11px !important"
                                                    class="fs-7 fw-semibold text-<?php echo e($data['color']); ?>">
                                                    <?php echo e($data['label']); ?>

                                                </div>
                                                <div style="font-size: 11px !important" class="fs-7 h5 mt-1 mb-0 text-dark">
                                                    <?php echo e($loginCounts[$role] ?? 0); ?></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">🟢 Live Sessions (Currently Active Users) Count</h5>
                            <div class="row mt-3 px-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $loginRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $urlLive = route('login.users.view', ['role' => $role, 'type' => 'live']);
                                    ?>
                                    <div class="col px-2">
                                        <a href="<?php echo e($urlLive); ?>" class="text-decoration-none">
                                            <div
                                                class="p-2 rounded border border-<?php echo e($data['color']); ?> h-100 shadow-sm hover-shadow text-center">
                                                <div class="text-<?php echo e($data['color']); ?>">
                                                    
                                                </div>
                                                <div style="font-size: 11px !important"
                                                    class="fs-7 fw-semibold text-<?php echo e($data['color']); ?>">
                                                    <?php echo e($data['label']); ?>

                                                </div>
                                                <div style="font-size: 11px !important" class="fs-7 h5 mt-1 mb-0 text-dark">
                                                    <?php echo e($liveSessionCounts[$role] ?? 0); ?>

                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


        <div class="row mt-4">
            <div class="col-md-5">
                <div class="d-flex align-items-center gap-3">
                    <input type="text" id="dateRange" class="form-control" placeholder="Filter by date range"
                        value="<?php echo e(request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : ''); ?>">
                    <button type="button" id="filterButton" class="btn btn-primary">Search</button>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">Clear</a>
                </div>
            </div>
            <div class="col-md-7 text-end">
                <button id="download-report" class="btn btn-primary text-center">Download
                    Report</button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="row">
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total Schools</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($totalSchools ?? 0); ?></h6>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">
                                        Group :
                                        <?php echo e($groupSchoolsCount ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">
                                        Individual :
                                        <?php echo e($individualSchoolsCount ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">
                                        Demo :
                                        <?php echo e($demoSchoolsCount ?? 0); ?> </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total School Students</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-standing"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($userCounts->total_school_students ?? 0); ?></h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">
                                        Active :
                                        <?php echo e($userCounts->total_active_school_students ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-danger inactiveBadge">
                                        Inactive
                                        :
                                        <?php echo e($userCounts->total_inactive_school_students ?? 0); ?> </p>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Digital Content Uploaded</h5>

                                <div class="d-flex gap-5 mt-3">
                                    <!-- Academic Videos -->
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center academicIcon">
                                            <i class="bi bi-journal-bookmark-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="mb-0 small text-muted">Academic </p>
                                            <div class="ps-2">
                                                <h6 class="mb-0"><?php echo e($academicVideoCount ?? 0); ?></h6>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Talent Videos -->
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center talentIcon">
                                            <i class="bi bi-award-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="mb-0 small text-muted">Talent </p>
                                            <div class="ps-2">
                                                <h6 class="mb-0"><?php echo e($talentVideoCount ?? 0); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                </div>

                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Series: <?php echo e($bookSeries ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Book Titles: <?php echo e($bookTitles ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Chapters: <?php echo e($digitalContentCount ?? 0); ?>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total Teachers</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-workspace"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($userCounts->total_school_teachers ?? 0); ?></h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">
                                        Active
                                        :
                                        <?php echo e($userCounts->total_active_school_teachers ?? 0); ?></p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-danger inactiveBadge">
                                        Inactive :
                                        <?php echo e($userCounts->total_inactive_school_teachers ?? 0); ?></p>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total Students/ Users</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-standing"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($userCounts->total_students ?? 0); ?></h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">Active
                                        :
                                        <?php echo e($userCounts->total_active_students ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-danger inactiveBadge">
                                        Inactive :
                                        <?php echo e($userCounts->total_inactive_students ?? 0); ?> </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Licences/Access Code</h5>
                                <div class="d-flex gap-5 mt-3">
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center academicIcon">
                                            <i class="bi bi-card-checklist"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="mb-0 small text-muted">Total</p>
                                            <div class="ps-1">
                                                <h6 class="mb-0"><?php echo e($accessCodesCount ?? 0); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Teachlite: <?php echo e($accessCodesTeachliteCount ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        MittsureLens: <?php echo e($accessCodesMittLensCount ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Schools: <?php echo e($schoolAccessCodesCount ?? 0); ?>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total B2C Users</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center peopleIcon">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($userCounts->total_b2c_students ?? 0); ?></h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">Active
                                        :
                                        <?php echo e($userCounts->total_active_b2c_students ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-danger inactiveBadge">
                                        Inactive :
                                        <?php echo e($userCounts->total_inactive_b2c_students ?? 0); ?> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">D2C Users</h5>

                                <div class="d-flex gap-5 mt-3">
                                    <!-- Academic Videos -->
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center academicIcon">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="mb-0 small text-muted">Total </p>
                                            <div class="ps-1">
                                                <h6 class="mb-0"><?php echo e($userCounts->total_d2c_users ?? 0); ?>

                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        JP Kit: <?php echo e($userCounts->total_d2c_users_JPKit ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Worksheet: <?php echo e($userCounts->total_d2c_users_worksheets ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Olympiad: <?php echo e($userCounts->total_d2c_users_olympiad ?? 0); ?>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">D2C Content Uploaded</h5>

                                <div class="d-flex gap-5 mt-3">
                                    <!-- Academic Videos -->
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center academicIcon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <div class="ps-3">
                                            <p class="mb-0 small text-muted">Total </p>
                                            <div class="ps-1">
                                                <h6 class="mb-0"><?php echo e($totalD2cContent ?? 0); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        JP Kit: <?php echo e($JPKitContentCount ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Worksheet: <?php echo e($ActivityWorksheetsContentCount ?? 0); ?>

                                    </p>
                                    <p class="fw-medium mb-0 text-success activeBadge px-2" style="font-size: 12px">
                                        Olympiad: <?php echo e($OlympiadContentCount ?? 0); ?>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total Users under Free Trail </h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-all"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($freeTrailUserCounts->total_subscription_students ?? 0); ?></h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">Active
                                        :
                                        <?php echo e($freeTrailUserCounts->total_active_students ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-danger inactiveBadge">
                                        Expired :
                                        <?php echo e($freeTrailUserCounts->total_inactive_students ?? 0); ?> </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total Subscription Users</h5>
                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center inactiveIcon">
                                        <i class="bi bi-person-fill-slash" class="inactiveIcon"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e($totalSubscriptionUserCounts->total_subscription_students ?? 0); ?>

                                        </h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-2 align-items-center mt-3 botmTxt">
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-success activeBadge">Active
                                        :
                                        <?php echo e($totalSubscriptionUserCounts->total_active_students ?? 0); ?> </p>
                                    <p style="font-size: 12px" class="fw-medium mb-0 text-danger inactiveBadge">
                                        Expired :
                                        <?php echo e($totalSubscriptionUserCounts->total_inactive_students ?? 0); ?> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="card-body dashCard position-relative">
                                <h5 class="card-title">Total Revenue</h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center goldIcon">
                                        <i class="bi bi-currency-rupee"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?php echo e(number_format($totalRevenue, 2) ?? 0); ?></h6>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Current Year Monthly Reports</h5>
                                <div id="reportsChart"></div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        const verifiedSchoolsData = <?php echo json_encode($chartData['verifiedSchools'], 15, 512) ?>;
                                        const b2cStudentsData = <?php echo json_encode($chartData['b2cStudents'], 15, 512) ?>;
                                        const schoolStudentsData = <?php echo json_encode($chartData['schoolStudents'], 15, 512) ?>;

                                        const currentYear = new Date().getFullYear();
                                        const months = ["January", "February", "March", "April", "May", "June", "July",
                                            "August", "September", "October", "November", "December"
                                        ];

                                        function generateSeriesData(data) {
                                            const series = [];
                                            for (let i = 1; i <= 12; i++) {
                                                series.push(data[currentYear] && data[currentYear][i] ? data[currentYear][i] : 0);
                                            }
                                            return series;
                                        }

                                        new ApexCharts(document.querySelector("#reportsChart"), {
                                            series: [{
                                                    name: 'Schools',
                                                    data: generateSeriesData(verifiedSchoolsData)
                                                },
                                                {
                                                    name: 'School Students',
                                                    data: generateSeriesData(schoolStudentsData)

                                                },
                                                {
                                                    name: 'B2C Students',
                                                    data: generateSeriesData(b2cStudentsData)
                                                }
                                            ],
                                            chart: {
                                                height: 350,
                                                type: 'area',
                                                toolbar: {
                                                    show: false
                                                },
                                            },
                                            markers: {
                                                size: 4
                                            },
                                            colors: ['#00438C', '#198754', '#ff771d'],
                                            fill: {
                                                type: "gradient",
                                                gradient: {
                                                    shadeIntensity: 1,
                                                    opacityFrom: 0.3,
                                                    opacityTo: 0.4,
                                                    stops: [0, 90, 100]
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            stroke: {
                                                curve: 'smooth',
                                                width: 2
                                            },
                                            xaxis: {
                                                categories: months,
                                            },
                                            tooltip: {
                                                x: {
                                                    format: 'dd/MM/yy'
                                                },
                                            }
                                        }).render();
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column: Top Sold Courses -->
                        <div class="col-md-6">
                            <div class="card border-0">
                                <div class="card-header text-white"
                                    style="background-color: #198754 !important; border-radius: 5px;">
                                    <h4 class="text-center m-0 fs-6">🔥 Top Purchased Course </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table tableMain">
                                        <thead>
                                            <tr>
                                                <th>Course Name</th>
                                                <th>Category</th>
                                                <th>Sold Items</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $topPurchasedCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        <?php echo e($course->course_name); ?></td>
                                                    <td><span
                                                            class="badge bg-success text-white p-2"><?php echo e($course->category_name); ?></span>
                                                    </td>
                                                    <td><span><?php echo e($course->sold_items); ?></span></td>
                                                    <td>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.add.chapter')): ?>
                                                            <a href="<?php echo e(route('course.add.chapter', $course->course_id)); ?>"
                                                                class="btn btn-outline-success btn-sm">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Latest Added Courses -->
                        <div class="col-md-6">
                            <div class="card border-0">
                                <div class="card-header text-white"
                                    style="background-color: #00438C !important; border-radius: 5px;">
                                    <h4 class="text-center m-0 fs-6">🆕 Newly Added Courses </h4>
                                </div>
                                <div class="card-body">
                                    <table class="table tableMain">
                                        <thead>
                                            <tr>
                                                <th>Course Name</th>
                                                <th>Category</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $newlyAddedCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="font-weight-bold">
                                                        <?php echo e($course->course_name); ?></td>
                                                    <td><span
                                                            class="badge bg-warning text-dark p-2"><?php echo e($course->getSubCategory->name ?? ''); ?></span>
                                                    </td>
                                                    <td><?php echo e($course->created_at->format('M d, Y')); ?></td>
                                                    <td>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.add.chapter')): ?>
                                                            <a href="<?php echo e(route('course.add.chapter', $course->id)); ?>"
                                                                class="btn btn-outline-primary btn-sm">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="newUserData">

                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">New User Registered <small>(10)</small>
                                        </h5>

                                        <form method="GET" action="<?php echo e(route('dashboard')); ?>"
                                            class="d-flex align-items-center" style="min-width: 250px;">
                                            <label for="roles" class="me-2 fw-semibold">Filter:</label>
                                            <select class="form-select" name="roles" id="roles"
                                                onchange="this.form.submit()">
                                                <option value="" disabled <?php echo e(request('roles') ? '' : 'selected'); ?>>
                                                    --Select Role--</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e(request('roles') == $id ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </form>
                                    </div>
                                    <table class="table tableMain">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="border-bottom">SN</th>
                                                <th scope="col"class="border-bottom">Name</th>
                                                <th scope="col"class="border-bottom">Mobile No./ Email</th>
                                                <th class="border-bottom">Role</th>
                                                <th class="border-bottom">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $newlyAddedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <th><?php echo e($loop->iteration); ?></a></th>
                                                    <td><?php echo e($user->name); ?></td>
                                                    <td><?php echo e($user->mobile_no); ?><br><?php echo e($user->email); ?></td>
                                                    <td><?php echo e($user->userRole->role->role_name ?? 'N/A'); ?></td>
                                                    <td><?php echo e($user->created_at->format('d M Y')); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </section>

<?php $__env->startSection('javascript'); ?>
    <!-- Include JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(asset('admin/vendor/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/vendor/chart.js')); ?>/chart.umd.js') }}"></script>
    <script src="<?php echo e(asset('admin/vendor/echarts/echarts.min.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const roleParam = urlParams.get('roles');

            if (roleParam) {
                const target = document.getElementById('newUserData');
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#dateRange').daterangepicker({
                autoUpdateInput: false,
                showDropdowns: true,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Week': [moment().startOf('week'), moment().endOf('week')],
                    'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week')
                        .endOf('week')
                    ],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 12 Months': [moment().subtract(11, 'months').startOf('month'), moment().endOf(
                        'month')],
                    'Year to Date': [moment().startOf('year'), moment()]
                },
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear',
                    applyLabel: 'Apply',
                    customRangeLabel: "Custom Range"
                }
            });

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('#filterButton').click(function() {
                var dateRange = $('#dateRange').val();
                var startDate = '';
                var endDate = '';

                if (dateRange) {
                    var dates = dateRange.split(' to ');
                    startDate = dates[0];
                    endDate = dates[1];
                }

                window.location.href = "<?php echo e(route('dashboard')); ?>" + "?start_date=" + startDate +
                    "&end_date=" + endDate;
            });

            $('#download-report').click(function() {
                var dateRange = $('#dateRange').val();
                var startDate = '';
                var endDate = '';

                if (dateRange) {
                    var dates = dateRange.split(' to ');
                    startDate = dates[0];
                    endDate = dates[1];
                }

                window.location.href = "<?php echo e(route('dashboard.download.report')); ?>" + "?start_date=" +
                    startDate + "&end_date=" + endDate;
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>