@extends('mittBunny.layouts.master')
@section('content')
    {{-- @include('dn.layouts.flash-messages') --}}

    <div class="dashboardMain">
        <div class="d-flex flex-wrap">
            <div class="leftpanel">
                {{-- <div class="helloSection">
                    <div class=" pe-md-5">
                        <h2><b>Hi,</b> {{ Auth::user()->name }}</h2>
                        <p>Smart Insights for Young Minds, A Personalized Space for child’s Educational Journey</p>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        @php
                            $student = session('student_class');
                        @endphp
                        @if ($student)
                            <span class="badge">{{ $student['class'] }}</span>
                        @endif
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div> --}}
                <div class="helloSection d-flex justify-content-between align-items-center flex-wrap">
                    <div class="pe-md-5 d-flex flex-column flex-md-row align-items-start align-items-md-center gap-5">
                        <div>
                            <h2 class="m-0"><b>Hi,</b> {{ Auth::user()->name }}</h2>
                            <p class="mb-2 mb-md-0">Smart Insights for Young Minds, A Personalized Space for your child’s
                                Educational Journey</p>
                        </div>
                        @php
                            $role = getUserRoles();
                            $category = App\Models\UserClass::where('user_id', Auth::id())->value('category_id');
                        @endphp
                        @if (($role == 'd2c_user' && $category == '35') || $role == 'b2c_student')
                            <button class="btn fw-semibold text-dark shadow-sm rounded-pill px-3 py-1"
                                style="background-color: #FFAB01;" data-bs-toggle="modal"
                                title="You can add another access code for a different subject"
                                data-bs-target="#addAccessCodeModal">
                                ➕ Add Access Code
                            </button>
                        @endif
                    </div>

                    <div class="d-flex align-items-center gap-4 mt-3 mt-md-0">
                        @php $student = session('student_class'); @endphp
                        @if ($student)
                            <span class="badge">{{ $student['class'] }}</span>
                        @endif
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div>
                <div class="alertsSec cardBox mb-3 @if (Session::has('admin_id')) alertWhenAdmin @endif">
                    @if ($notificationAlerts)
                        <div class="alertList">
                            <a href="javascript:void(0);">{{ $notificationAlerts->message }}</a>
                            <a href="javascript:void(0);">{{ $notificationAlerts->message }}</a>
                        </div>
                    @endif
                </div>
                <div class="overviewGroup">
                    <h3>Overview</h3>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="overviewBox overviewBox1">
                                <span>Learning Time</span>
                                <strong>{{ $totalHours }}h{{ $totalMinutes }}m</strong>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="overviewBox overviewBox2">
                                <span>Subscribed Course</span>
                                @if (!($role == 'd2c_user' && $category == '35'))
                                    <strong>{{ $totalSubscribedCourses ?? '0' }}</strong>
                                @else
                                    <strong>{{ $olympiadSubscribedCourses }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="overviewBox overviewBox3">
                                <span>Completed Course</span>
                                <strong>{{ $completedAcadCourses + $completedNonAcadCourses }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="pe-md-2 mb-3 @if (getUserRoles() == 'd2c_user' || getUserRoles() == 'b2c_student') col-md-12 @else col-md-6 @endif">
                        <div class="cardBox h-100">
                            <h2 class="fs-6 fw-normal mb-3"><b class="fw-semibold">My</b> Progress</h2>
                            <div id="myProgress" style="width: 200px;height: 200px;margin: auto;"></div>
                            <div id="myProgressData" data-non-acad-completion="{{ $nonAcadCompletionPercentage ?? 5 }}"
                                data-acad-completion="{{ $acadCompletionPercentage ?? 5 }}">
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="progressCount">
                                    @if (!($role == 'd2c_user' && $category == '35'))
                                        <p class="m-0"> {{ $completedAcadCourses }}<b>/{{ $totalAcadCourses }}</b></p>
                                    @else
                                        <p class="m-0">
                                            {{ $completedAcadCourses }}<b>/{{ $olympiadSubscribedCourses }}</b></p>
                                    @endif
                                    <span>Academic Courses</span>
                                </div>
                                @if (!($role == 'd2c_user' && $category == '35'))
                                    <div class="progressCount borderOrg">
                                        <p class="m-0 text-blue">
                                            {{ $completedNonAcadCourses }}<b>/{{ $totalNonAcadCourses }}</b></p>
                                        <span>Talent Courses</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (getUserRoles() != 'd2c_user' && getUserRoles() != 'b2c_student')
                        <div class="col-md-6 ps-md-2 mb-3 ">
                            <div class="cardBox h-100">
                                <div class="headingBx pb-2">
                                    <h2 class="fs-6 fw-normal mb-0"><b class="fw-semibold">Associated</b> Teachers</h2>
                                    <a href="#teacherList" data-bs-toggle="modal" class="viewAll text-primary">View
                                        all</a>
                                </div>
                                <div class="table-responsive tbleDiv associatedTbl">
                                    <table class="table m-0">
                                        <thead>
                                            <tr class="position-sticky top-0">
                                                <th>Teacher Name</th>
                                                <th>Subject</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dashData['matchingTeachers'] as $data)
                                                <tr>
                                                    <td>
                                                        <div class="teacherTxt">
                                                            <strong><span
                                                                    class="circle">{{ substr($data['teacher']->user->name, 0, 1) }}</span></strong>
                                                            <span>{{ $data['teacher']->user->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td> <span>{{ $data['subjects'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="cardBox
                        mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h2 class="fs-6 fw-normal mb-0"><b class="fw-semibold">Time</b> Spendings</h2>
                        <div class="d-flex align-items-center gap-2">
                            <span class="hours">{{ $totalHours }}<b>h</b> {{ $totalMinutes }}<b>m</b></span>
                            <lottie-player src="{{ asset('mittbunny/images/cat-faded.json') }}"
                                style="width: 50px;height: 50px;margin: auto;" loop autoplay></lottie-player>
                        </div>
                    </div>
                    <div id="courseStatistics" style="height: 280px;"></div>
                </div>
                <div class="cardBox">
                    <div class="d-flex align-items-center gap-3 mb-4 ">
                        <h2 class="fs-6 fw-normal mb-0 d-flex align-items-center gap-2"><b class="fw-semibold">My</b>
                            Courses
                            {{-- <span class="badgeNumber">6</span> --}}
                        </h2>
                        <ul class="nav nav-tabs ViewTabs mb-0">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#academicTab"
                                    type="button">Academic</button>
                            </li>
                            @if (!($studentProle == 'd2c_user' && $studentPcategory == '35'))
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#talentTab"
                                        type="button">Talent</button>
                                </li>
                                @if (!empty($courses) && $courses['academic_act_courses']->isNotEmpty())
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activityTab"
                                            type="button">Academic Activity</button>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="academicTab" role="tabpanel">
                            <div class="row">
                                @php
                                    if (!empty($courses) && $courses['academic_courses']->isNotEmpty()) {
                                        $groupedCourses = $courses['academic_courses']->groupBy(function ($course) {
                                            return optional(
                                                $course['metadataValues']->where('field_name', 'class')->first(),
                                            )->field_value ?? 'Unknown Class';
                                        });
                                    }

                                    $classNames = [
                                        'colorSky',
                                        'colorPeach',
                                        'colorLightPink',
                                        'colorSkyDark',
                                        'colorRabbit',
                                    ];
                                    $lottieImages = [
                                        '../mittbunny/images/elephant-courses.json',
                                        '../mittbunny/images/lion-courses.json',
                                        '../mittbunny/images/fox-courses.json',
                                        '../mittbunny/images/zebra-woods-courses.json',
                                        '../mittbunny/images/rabbit-courses.json',
                                    ];
                                @endphp

                                @if (isset($groupedCourses) && $groupedCourses->isNotEmpty())
                                    @foreach ($groupedCourses as $classLabel => $courseGroup)
                                        @php
                                            $classLabelName = \App\Models\Classes::where('id', $classLabel)->value(
                                                'name',
                                            );
                                            $userClassesSchStudent = \App\Models\UserClass::where(
                                                'user_id',
                                                Auth::id(),
                                            )->exists();

                                        @endphp
                                        @if (getUserRoles() == 'd2c_user' || (getUserRoles() == 'school_student' && $userClassesSchStudent))
                                            <h6 class="fw-bold text-primary mb-3 classNameLbl">{{ $classLabelName }}</h6>
                                        @endif
                                        <div class="row mb-4">
                                            @foreach ($courseGroup as $index => $course)
                                                @php
                                                    $subject = $course['metadataValues']
                                                        ->where('field_name', 'subject')
                                                        ->first();
                                                    $subjectImage = $subject->subjectInfo->image ?? null;
                                                    $subjectName = $subject->subjectInfo->name ?? 'N/A';

                                                    $className = $classNames[$index % count($classNames)];
                                                    $lottieImage = $lottieImages[$index % count($lottieImages)];
                                                @endphp
                                                <div class="col-md-4 px-2">
                                                    <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                        class="cardcourse {{ $className }} mb-3">
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <span><b>{{ $subjectName }}</b></span>
                                                            <button type="button" class="btn p-0">
                                                                <img src="{{ asset('mittbunny/images/view-icon.svg') }}"
                                                                    alt="" width="24">
                                                            </button>
                                                        </div>
                                                        <lottie-player src="{{ asset($lottieImage) }}"
                                                            style="width: 180px;height: 180px;margin: auto;" loop
                                                            autoplay></lottie-player>
                                                    </a>
                                                    <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                        class="btn btn-primary-gradient rounded-1 py-2 w-100">View
                                                        Course</a>
                                                    <a href="{{ route('mittbunny.course.digital-content', $course['id']) }}"
                                                        class="btn btn-success rounded-1 py-2 w-100 mt-2">View
                                                        Content</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-medium">Your Class Academic Courses are not available right now. Check
                                        back
                                        soon!</p>
                                @endif
                            </div>
                        </div>
                        {{-- @dd($courses) --}}
                        <div class="tab-pane fade" id="talentTab" role="tabpanel">
                            <div class="row">
                                @if (!empty($courses) && $courses['nonacademic_courses']->isNotEmpty())
                                    @foreach ($courses['nonacademic_courses'] as $index => $course)
                                        @php
                                            $imageField = $course['metadataValues']
                                                ->where('field_name', 'banner_image')
                                                ->first();
                                            $bannerImage = $imageField->field_value ?? null;
                                            $classNames = [
                                                'colorPeach',
                                                'colorLightPink',
                                                'colorSky',
                                                'colorSkyDark',
                                                'colorSkyDark',
                                            ];
                                            $lottieImages = [
                                                '../mittbunny/images/lion-courses.json',
                                                '../mittbunny/images/fox-courses.json',
                                                '../mittbunny/images/elephant-courses.json',
                                                '../mittbunny/images/rabbit-courses.json',
                                                '../mittbunny/images/zebra-woods-courses.json',
                                            ];

                                            // Ensure the index does not exceed the array length
                                            $className = $classNames[$index % count($classNames)];
                                            $lottieImage = $lottieImages[$index % count($lottieImages)];
                                        @endphp
                                        <div class="col-md-4 px-2">
                                            <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                class="cardcourse {{ $className }} mb-3">
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <span><b>
                                                            {{ Str::limit($course['course_name'], 16, ' ...') }}</b></span>
                                                    <button type="button" class="btn p-0">
                                                        <img src="{{ asset('mittbunny/images/view-icon.svg') }}"
                                                            alt="" width="24">
                                                    </button>
                                                </div>
                                                <lottie-player src="{{ asset($lottieImage) }}"
                                                    style="width: 180px;height: 180px;margin: auto;" loop
                                                    autoplay></lottie-player>

                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-medium">Talent Courses are not available right now. Stay tuned – coming
                                        soon!</p>
                                @endif
                            </div>
                        </div>

                        <div class="tab-pane fade" id="activityTab" role="tabpanel">
                            <div class="row">
                                @if (!empty($courses) && $courses['academic_act_courses']->isNotEmpty())
                                    @foreach ($courses['academic_act_courses'] as $index => $course)
                                        @php
                                            $imageField = $course['metadataValues']
                                                ->where('field_name', 'banner_image')
                                                ->first();
                                            $bannerImage = $imageField->field_value ?? null;
                                            $classNames = [
                                                'colorPeach',
                                                'colorLightPink',
                                                'colorSky',
                                                'colorSkyDark',
                                                'colorSkyDark',
                                            ];
                                            $lottieImages = [
                                                '../mittbunny/images/lion-courses.json',
                                                '../mittbunny/images/fox-courses.json',
                                                '../mittbunny/images/elephant-courses.json',
                                                '../mittbunny/images/rabbit-courses.json',
                                                '../mittbunny/images/zebra-woods-courses.json',
                                            ];

                                            $className = $classNames[$index % count($classNames)];
                                            $lottieImage = $lottieImages[$index % count($lottieImages)];
                                        @endphp
                                        <div class="col-md-4 px-2 mb-3">
                                            <div class="h-100 maincoursebx">

                                                <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                    class="cardcourse {{ $className }} mb-3 h-100">



                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <span><b>
                                                                {{ $course['course_name'] }}</b></span>
                                                        <button type="button" class="btn p-0 btnview">
                                                            <img src="{{ asset('mittbunny/images/view-icon.svg') }}"
                                                                alt="" width="24">
                                                        </button>
                                                    </div>
                                                    <lottie-player src="{{ asset($lottieImage) }}"
                                                        style="width: 180px;height: 180px;margin: auto;" loop
                                                        autoplay></lottie-player>

                                                </a>
                                                <div class="">
                                                    <a href="{{ route('mittbunny.course.digital-content', $course['id']) }}"
                                                        class="btn btn-success rounded-1 py-2 w-100 ">View
                                                        Content</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-medium">Academic Activities are not available right now. Stay tuned –
                                        coming
                                        soon!</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="rightpanel">
                @include('mittBunny.layouts.profile-header')
                @if (getUserRoles() == 'd2c_user' || getUserRoles() == 'b2c_student')
                    @include('mittBunny.layouts.continue-watching-sec')
                @else
                    <div class="">
                        <h2 class="fs-6 fw-normal mb-3"><b class="fw-semibold">Scheduled </b> Online Class</h2>
                        <div class="weekMain">
                            <ul class="dateUl">
                                @foreach ($dashData['datesInWeek'] as $dates)
                                    <li>
                                        <span
                                            class="{{ $dates['date']->format('d') == \Carbon\Carbon::today()->format('d') ? 'active' : '' }}"
                                            data-date="{{ $dates['date'] }}" style="cursor: pointer;">
                                            {{ $dates['date']->format('d') }} <b>{{ $dates['day'] }}</b>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <ul class="subjectsUl mt-3">
                        </ul>
                        <div class="text-center bottomImg">
                            <img src="{{ asset('mittbunny/images/right-panel.svg') }}" alt="" width="140">
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div class="modal fade" id="teacherList">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header align-items-start border-0">
                    <h1 class="modal-title fs-6">Teacher List</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="studentLearner">
                        <h3>Total <b>{{ count($dashData['matchingTeachers']) }}</b></h3>
                        <div class="searchBox d-block mb-2">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name">
                        </div>
                        <div class="table-responsive tbleDiv associatedTbl">
                            <table class="table m-0" id="teachersTable">
                                <thead>
                                    <tr class="position-sticky top-0">
                                        <th>Teacher Name</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dashData['matchingTeachers'] as $data)
                                        <tr class="teacherRow">
                                            <td>
                                                <div class="teacherTxt">
                                                    <strong
                                                        data-name="{{ $data['teacher']->user->name }}">{{ substr($data['teacher']->user->name, 0, 1) }}</strong>
                                                    <span>{{ $data['teacher']->user->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span id="teacherSubject">{{ $data['subjects'] }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <button type="button" class="btn backbtn fw-regular my-2">Back</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Access Code Modal -->
    <div class="modal fade" id="addAccessCodeModal" tabindex="-1" aria-labelledby="addAccessCodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('vallidate.access.code') }}">
                @csrf
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header" style="background-color: #FFAB01;">
                        <h5 class="modal-title text-dark fw-bold" id="addAccessCodeModalLabel">
                            🔐 Enter Your Access Code
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body bg-white">
                        <p class="text-muted mb-3">
                            Please enter the access code for another book to access the contents of all your books.
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
                            style="background-color: #735EDF;">
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


    @if ($errors->accessCodeErrors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('addAccessCodeModal'));
                myModal.show();
            });
        </script>
    @endif


    <script>
        var timeSpendingsData = {!! $timeSpendingsData !!};
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#teachersTable tbody tr');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    tableRows.forEach(row => {
                        const teacherName = row.querySelector('strong').getAttribute('data-name')
                            .toLowerCase(); // Get the name from the <strong> tag

                        // Check if the name includes the search query
                        if (teacherName.includes(query)) {
                            row.style.display = ''; // Show row
                        } else {
                            row.style.display = 'none'; // Hide row
                        }
                    });
                });
            }
        });


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
                        background: [{ // Track for Conversion
                            outerRadius: '112%',
                            innerRadius: '98%',
                            backgroundColor: 'rgba(195, 195, 195, .2)',
                            borderWidth: 0
                        }, { // Track for Engagement
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
                            color: '#F2C200',
                            radius: '112%',
                            innerRadius: '98%',
                            y: acadCompletionPercentage
                        }],
                        custom: {
                            icon: 'filter',
                            iconColor: '#303030'
                        }
                    }, {
                        name: 'Engagement',
                        data: [{
                            color: '#785FF4',
                            radius: '87%',
                            innerRadius: '73%',
                            y: nonAcadCompletionPercentage
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

    <script>
        $(document).ready(function() {

            function loadClassesForDate(date) {
                $.ajax({
                    url: 'classes/' + date,
                    type: 'GET',
                    data: {
                        date: date
                    },

                    success: function(response) {
                        if (response.dateComparison && response.dateComparison.length > 0) {
                            var classesHtml = '';

                            response.dateComparison.forEach(function(classItem, index) {
                                var startTime = classItem.start_time.split(':').slice(0, 2)
                                    .join(':');
                                var endTime = classItem.end_time.split(':').slice(0, 2).join(
                                    ':');

                                var additionalClass = '';
                                if (index % 3 === 0) {
                                    additionalClass =
                                        'yellowBg';
                                } else if (index % 3 === 1) {
                                    additionalClass =
                                        'purpleBg';
                                } else {
                                    additionalClass =
                                        '';
                                }

                                classesHtml += '<li>';
                                classesHtml += '<strong class="mb-1">' + startTime +
                                    '</strong>';
                                classesHtml += '<div class="subjectBox ' + additionalClass +
                                    '">'; // Add the dynamic class here
                                classesHtml += '<span>' + classItem.subject.name +
                                    ' <b>Time- ' + startTime + ' - ' + endTime + '</b></span>';
                                classesHtml += '<div class="d-flex justify-content-between">';
                                classesHtml += '<figure class="m-0">';
                                classesHtml +=
                                    '<img src="{{ asset('frontend/images/john-smith-img.jpg') }}" alt="">' +
                                    classItem.instructor.name;
                                classesHtml += '</figure>';
                                classesHtml += '</div>';
                                classesHtml += '</div>';
                                classesHtml += '</li>';

                            });
                            $('.subjectsUl').html(classesHtml);
                        } else {
                            $('.subjectsUl').html('<p>No classes available for this date.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log('Error fetching classes:', error);
                    }
                });
            }

            $('.weekMain').on('click', 'ul.dateUl li span', function() {
                var date = $(this).data('date');
                loadClassesForDate(date);
                $('li span').removeClass('active');
                $(this).addClass('active');
            });

            var today = new Date();
            var todayFormatted = today.toISOString().split('T')[0];
            loadClassesForDate(todayFormatted);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.circle').forEach(circle => {
                let randomColor =
                    `rgba(${[...Array(3)].map(() => Math.floor(Math.random() * 106) + 150).join(',')}, 0.9)`;
                circle.style.backgroundColor = randomColor;
            });
        });
    </script>
@endsection
