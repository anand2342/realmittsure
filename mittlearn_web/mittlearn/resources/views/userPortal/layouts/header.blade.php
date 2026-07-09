@php
    $userDashboardRoutes = ['up.dashboard'];
    $isShowUserDashboardMenu = isPermission($userDashboardRoutes);
    $isActiveUserDashboardMenu = isActiveRoute($userDashboardRoutes);

    $userPlannerRoutes = ['up.planner', 'up.planner.chapter.listing'];
    $isShowUserPlannerMenu = isPermission($userPlannerRoutes);
    $isActiveUserPlannerMenu = isActiveRoute($userPlannerRoutes);

    $userMyCoursesRoutes = [
        'up.my.courses',
        'up.course.listing',
        'up.course.digital-content',
        'up.courses.chapter.listing',
        'up.non-acad.course.detail',
    ];
    $isShowUserMyCoursesMenu = isPermission($userMyCoursesRoutes);
    $isActiveUserMyCoursesMenu = isActiveRoute($userMyCoursesRoutes);

    $userOnlineClassRoutes = ['up.online.class', 'up.online.class.digital.content'];
    $isShowUserOnlineClassMenu = isPermission($userOnlineClassRoutes);
    $isActiveUserOnlineClassMenu = isActiveRoute($userOnlineClassRoutes);

    $digitalContentRoutes = ['up.digitalContent', 'up.digital-content-files'];
    $isShowDigitalContentMenu = isPermission($digitalContentRoutes);
    $isActiveDigitalContentMenu = isActiveRoute($digitalContentRoutes);

    $mediaGalleryRoutes = ['up.media-gallery', 'up.media-gallery.files'];
    $isShowMediaGalleryMenu = isPermission($mediaGalleryRoutes);
    $isActiveMediaGalleryMenu = isActiveRoute($mediaGalleryRoutes);

    $testPaperRoutes = ['up.test.paper.list', 'up.test.paper.question'];
    $isShowTestPaperMenu = isPermission($testPaperRoutes);
    $isActiveTestPaperMenu = isActiveRoute($testPaperRoutes);

    $userSubscriptionRoutes = ['up.subscription'];
    $isShowUserSubscriptionMenu = isPermission($userSubscriptionRoutes);
    $isActiveUserSubscriptionMenu = isActiveRoute($userSubscriptionRoutes);

    $appDownloadRoutes = ['up.download.app.page'];
    $isShowUserAppDownloadMenu = isPermission($appDownloadRoutes);
    $isActiveUserAppDownloadMenu = isActiveRoute($appDownloadRoutes);
@endphp

<header class="studentHead d-flex flex-wrap justify-content-between">
    <div class="leftItem">
        <a href="javascript:void(0)"><img src="{{ asset('frontend/images/mittlearn-logo-white.png') }}" alt=""
                width="130"></a>
    </div>
    <div class="rightItem d-flex flex-wrap justify-content-between">
        <button type="button" class="toggleBtn2 d-md-none">
            <img src="{{ asset('frontend/images/toggletop-icon-white.svg') }}" alt="" width="16"
                class="me-md-3">
        </button>
        <div class="studentNav">
            <ul class="navList">
                @if ($isShowUserDashboardMenu)
                    <li><a href="{{ route('up.dashboard') }}" class="{{ $isActiveUserDashboardMenu }}">Dashboard</a>
                    </li>
                @endif
                @if ($isShowUserPlannerMenu)
                    <li><a href="{{ route('up.planner') }}" class="{{ $isActiveUserPlannerMenu }}">My Planner</a></li>
                @endif
                @if ($isShowUserMyCoursesMenu)
                    <li><a href="{{ route('up.my.courses') }}"
                            class="{{ $isActiveUserMyCoursesMenu }}">Subjects/Courses</a>
                    </li>
                @endif
                @if ($isShowUserOnlineClassMenu)
                    <li><a href="{{ route('up.online.class') }}" class="{{ $isActiveUserOnlineClassMenu }}">Online
                            Classes</a></li>
                @endif
                @if ($isShowDigitalContentMenu)
                    <li><a href="{{ route('up.digitalContent') }}" class="{{ $isActiveDigitalContentMenu }}">Digital
                            Content</a></li>
                @endif
                @if ($isShowMediaGalleryMenu)
                    <li><a href="{{ route('up.media-gallery') }}" class="{{ $isActiveMediaGalleryMenu }}">Media
                            Gallery</a></li>
                @endif
                {{-- @if ($isShowTestPaperMenu)
                <li><a href="{{ route('up.test.paper.list') }}" class="{{ $isActiveTestPaperMenu }}">Test Papers</a>
                </li>
                @endif --}}
                @if ($isShowUserSubscriptionMenu)
                    <li><a href="{{ route('up.subscription') }}"
                            class="{{ $isActiveUserSubscriptionMenu }}">Subscription</a></li>
                @endif
                {{--  @if ($isShowUserAppDownloadMenu)  --}}
                <li><a href="{{ route('up.download.app.page') }}" class="{{ $isActiveUserAppDownloadMenu }}">Download
                        App</a></li>
                {{--  @endif  --}}
            </ul>
        </div>


        <button class="dropdownPrf text-white" type="button" data-bs-target="#profile" data-bs-toggle="modal">
            <img src="{{ Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('frontend/images/default-image.jpg') }}"
                alt="">{{ $currentUser->name }}
        </button>
    </div>
</header>
@php
    $student = session('student_overview');
@endphp

@if ($student)
    <div class="studentBanner">
        @if ($notificationAlerts)
            <div class="alertsSec alertWhenAdmin ">
                <div class="alertList">
                    <a href="javascript:void(0);">{{ $notificationAlerts->message }}</a>
                    <a href="javascript:void(0);">{{ $notificationAlerts->message }}</a>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-8 mb-3">
                <h3 class="text-white fs-6 mb-4">Overview</h3>
                <div class="studentOverView">
                    <div class="row">
                        <div class="col-md-5 col-lg-12 col-xl-5 pe-lg-5">
                            <div class="studentImage">
                                <figure>
                                    <img src="{{ $student['image'] }}">
                                </figure>
                                {{-- @dd($student['completedNonAcadCourses']) --}}
                                <h5 class="d-flex align-items-center">{{ $student['name'] }}
                                    <lottie-player src="{{ asset('frontend/images/hand.json') }}" loop autoplay
                                        style="width: 35px;height: 30px;"></lottie-player>
                                </h5>
                                <span>Student</span>
                            </div>
                            <div class="accountStart">
                                <figure><img src="{{ asset('frontend/images/accountIcon.svg') }}" alt="">
                                </figure>
                                <p>
                                    @if (!empty($student['subscribedCourses']))
                                        Plan Start Date:
                                        {{ \Carbon\Carbon::parse($student['subscribedCourses']->start_date)->format('m/d/Y') }}
                                        <span>Expiry Date:
                                            {{ \Carbon\Carbon::parse($student['subscribedCourses']->end_date)->format('m/d/Y') }}</span>
                                    @else
                                        Plan :
                                        @if (!($studentProle == 'd2c_user' && $studentPcategory == '35'))
                                            <span>Plan Not Subscribed</span>
                                        @else
                                            <span>Olympiad</span>
                                        @endif
                                    @endif
                                </p>
                            </div>

                        </div>

                        <div class="col-md-7 col-lg-12 col-xl-7">
                            <ul class="studentinfoList">
                                @if (getUserRoles() != 'b2c_student')
                                    <li>
                                        <figure><img src="{{ asset('frontend/images/parent-name.svg') }}"
                                                alt="">
                                        </figure>
                                        <p>{{ $student['parent_name'] }} <span>Parent Name</span></p>
                                    </li>
                                @endif
                                <li>
                                    <figure><img src="{{ asset('frontend/images/student-class.svg') }}" alt="">
                                    </figure>
                                    <p>{{ $student['class'] }} <span>Student Class</span></p>
                                </li>
                                <li>
                                    <figure><img src="{{ asset('frontend/images/purchased-courses.svg') }}"
                                            alt=""></figure>
                                    @if ($studentProle == 'd2c_user' && $studentPcategory == '35')
                                        <p>{{ $olympiadSubscribedCourses }} <span>Subscribed Courses</span></p>
                                    @else
                                        <p>{{ $student['totalSubscribedCourses'] }} <span>Subscribed Courses</span></p>
                                    @endif
                                </li>
                                <li>
                                    <figure><img src="{{ asset('frontend/images/completed-task.svg') }}"
                                            alt=""></figure>
                                    <p>{{ $student['completedAcadCourses'] + $student['completedNonAcadCourses'] }}<span>Completed</span>
                                    </p>
                                </li>
                            </ul>


                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-4 ps-md-4">
                    <h3 class="text-white fs-6 m-0">My Progress</h3>
                    @php
                        $studentProle = getUserRoles();
                        $categorysp = App\Models\UserClass::where('user_id', Auth::id())->value('category_id');
                    @endphp
                    @if (($studentProle == 'd2c_user' && $categorysp == '35') || $studentProle == 'b2c_student')
                        <button class="btn btn-primary fw-semibold shadow-sm px-3 py-1 rounded-pill"
                            data-bs-toggle="modal" data-bs-target="#addAccessCodeModal"
                            title="You can add another access code for a different subject"
                            style="background-color: #ffffff !important; color: black !important; border: none;">
                            ➕ Add Access Code
                        </button>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div id="myProgress" style="height: 200px;width: 200px;">
                    </div>
                    <div id="myProgressData"
                        data-non-acad-completion="{{ $student['nonAcadCompletionPercentage'] ?? 5 }}"
                        data-acad-completion="{{ $student['acadCompletionPercentage'] ?? 5 }}">
                    </div>
                    <div>
                        <div class="progressCount">

                            <p class="m-0">
                                @if (!($studentProle == 'd2c_user' && $studentPcategory == '35'))
                                    {{ $student['completedAcadCourses'] }} <b>/{{ $student['totalAcadCourses'] }}</b>
                                @else
                                    {{ $student['completedAcadCourses'] }} <b>/{{ $olympiadSubscribedCourses }}</b>
                                @endif
                            </p>
                            <span>Academic Courses</span>
                        </div>
                        @if (!($studentProle == 'd2c_user' && $studentPcategory == '35'))
                            <div class="progressCount borderOrg">
                                <p class="m-0 text-white">
                                    {{ $student['completedNonAcadCourses'] }}
                                    <b>/{{ $student['totalNonAcadCourses'] }}</b>
                                </p>
                                <span>Talent Courses</span>
                            </div>
                        @endif
                    </div>
                </div>
                @php
                    $daysLeft = $courses['days_left'] ?? 0;
                    $expiresAt = \Carbon\Carbon::parse(Auth::user()->created_at)->addDays(15);
                    $now = \Carbon\Carbon::now();
                    $diffInSeconds = $expiresAt->diffInSeconds($now, false);
                @endphp
                @if (getUserRoles() == 'b2c_student' && (request()->routeIs('up.dashboard') || request()->routeIs('up.my.courses')))
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-12">
                            <div class="border rounded p-2 shadow-sm text-center">
                                @if ($daysLeft && $diffInSeconds)
                                    <h5 class="mb-3 compMsg">🎁 Complimentary Access Remaining</h5>
                                    <div class="d-flex justify-content-center gap-4 fs-5 fw-semibold counterDiv">
                                        <div class="count-box compMsgInter "><span id="cd-days">--</span> Days
                                        </div>
                                        <div class="count-box compMsgInter"><span id="cd-hours">--</span> Hours
                                        </div>
                                        <div class="count-box compMsgInter"><span id="cd-minutes">--</span>
                                            Minutes
                                        </div>
                                        <div class="count-box compMsgInter"><span id="cd-seconds">--</span>
                                            Seconds
                                        </div>
                                    </div>
                                @else
                                    <h5 class="mb-1 compMsg">⏳ Complimentary Access Ended</h5>
                                    <p class="compMsgInter">Your <strong>15-day free access</strong> period has ended
                                        - but your journey doesn't have to! <strong>Subscribe
                                            now</strong> to unlock full access and keep learning with expert content.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Add Access Code Modal -->
            <div class="modal fade" id="addAccessCodeModal" tabindex="-1" aria-labelledby="addAccessCodeModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('vallidate.access.code') }}">
                        @csrf
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header" style="background-color: #00438C;">
                                <h5 class="modal-title text-white fw-bold" id="addAccessCodeModalLabel">
                                    🔐 Enter Your Access Code
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body bg-white">
                                <p class="text-muted mb-3">
                                    Please enter the access code for another book to access the contents of all your
                                    books.
                                </p>
                                <div class="mb-3">
                                    <label for="access_code" class="form-label fw-semibold">Access Code</label>
                                    <input type="text"
                                        class="form-control @error('access_code', 'accessCodeErrors') is-invalid @enderror"
                                        id="access_code" name="access_code" placeholder="e.g., ABC123XYZ" required>
                                    @error('access_code', 'accessCodeErrors')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer bg-white border-0">
                                <button type="submit" class="btn fw-semibold shadow-sm text-white"
                                    style="background-color: #00438C;">
                                    ✅ Submit Code
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <style>
                .btn-primary:hover {
                    background-color: #4e88f3 !important;
                    color: #fff;
                }
            </style>
        </div>
    </div>

    @if ($daysLeft && $diffInSeconds)
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let countdownEnd = new Date("{{ $expiresAt->format('Y-m-d H:i:s') }}").getTime();

                function updateCountdown() {
                    let now = new Date().getTime();
                    let distance = countdownEnd - now;

                    if (distance < 0) {
                        document.getElementById("countdown-timer").innerHTML =
                            "<span class='text-danger fw-bold'>Access expired. Please refresh the page.</span>";
                        return;
                    }

                    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById("cd-days").innerText = days;
                    document.getElementById("cd-hours").innerText = hours.toString().padStart(2, '0');
                    document.getElementById("cd-minutes").innerText = minutes.toString().padStart(2, '0');
                    document.getElementById("cd-seconds").innerText = seconds.toString().padStart(2, '0');
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            });
        </script>
    @endif


    @if ($errors->accessCodeErrors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('addAccessCodeModal'));
                myModal.show();
            });
        </script>
    @endif
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let countdownEnd = new Date("{{ $expiresAt->format('Y-m-d H:i:s') }}").getTime();

            function updateCountdown() {
                let now = new Date().getTime();
                let distance = countdownEnd - now;

                if (distance < 0) {
                    document.getElementById("countdown-timer").innerHTML =
                        "<span class='text-danger fw-bold'>Expired</span>";
                    return;
                }

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("cd-days").innerText = days;
                document.getElementById("cd-hours").innerText = hours.toString().padStart(2, '0');
                document.getElementById("cd-minutes").innerText = minutes.toString().padStart(2, '0');
                document.getElementById("cd-seconds").innerText = seconds.toString().padStart(2, '0');
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
    <script>
        if ($('#myProgress').length) {
            $(function() {
                var nonAcadCompletionPercentage = $('#myProgressData').data('non-acad-completion');
                var acadCompletionPercentage = $('#myProgressData').data('acad-completion');
                Highcharts.chart('myProgress', {

                    chart: {
                        type: 'solidgauge',
                        backgroundColor: null,
                    },

                    title: {
                        text: null
                    },

                    tooltip: {
                        enabled: false
                    },

                    pane: {
                        startAngle: 0,
                        endAngle: 360,
                        background: [{
                            outerRadius: '112%',
                            innerRadius: '98%',
                            backgroundColor: 'rgba(195, 195, 195, .2)',
                            borderWidth: 0
                        }, {
                            outerRadius: '87%',
                            innerRadius: '73%',
                            backgroundColor: 'rgba(195, 195, 195, .2)',
                            borderWidth: 0
                        }]
                    },

                    yAxis: {
                        min: 0,
                        max: 100,
                        lineWidth: 0,
                        tickPositions: []
                    },

                    plotOptions: {
                        solidgauge: {
                            dataLabels: {
                                enabled: false
                            },
                            linecap: 'round',
                            stickyTracking: false,
                            rounded: true
                        }
                    },

                    series: [{
                        name: 'Conversion',
                        data: [{
                            color: '#FE8949',
                            radius: '112%',
                            innerRadius: '98%',
                            y: nonAcadCompletionPercentage
                        }],
                        custom: {
                            icon: 'filter',
                            iconColor: '#303030'
                        }
                    }, {
                        name: 'Engagement',
                        data: [{
                            color: '#00BE55',
                            radius: '87%',
                            innerRadius: '73%',
                            y: acadCompletionPercentage
                        }],
                        custom: {
                            icon: 'comments-o',
                            iconColor: '#ffffff'
                        }
                    }]
                });

                $('.highcharts-credits').hide();
            });
        }
    </script>
@endif
