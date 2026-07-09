@extends('userPortal.layouts.master')
@section('content')
    @include('admin.layouts.flash-messages')

    <div class="dashboardMain p-4">
        <div class="row px-lg-1">
            <div class="col-lg-8 px-lg-2 mb-3">

            </div>
            <div class="col-lg-4 px-lg-2 mb-3">

            </div>
        </div>
        <div class="row px-lg-1">
            <div class="col-lg-8 px-lg-2 mb-3">
                <div class="cardBox  mb-3">
                    <div class="headingBx d-block d-md-flex justify-content-between overallSelect">
                        <div>
                            <h4>Time Spendings</h4>
                            {{ $totalHours }}<b>h</b> {{ $totalMinutes }}<b>m</b>
                        </div>
                    </div>
                    <div id="courseStatistics" style="height: 280px;"></div>

                </div>
                <div class="cardBox mycoursesTb" style="min-height: 715px">
                    <div class="d-md-flex align-items-center gap-4 mb-3 ">
                        <h2 class="fs-6 fw-semibold d-flex align-items-center gap-2 m-md-0">My Courses
                            {{-- <span>6</span> --}}
                        </h2>
                        <ul class="nav nav-tabs ViewTabs mb-0" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#boardacademicDash"
                                    type="button">Academic</button>
                            </li>

                            @if (!($studentProle == 'd2c_user' && $studentPcategory == '35'))
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#talentDash"
                                        type="button">Talent</button>
                                </li>
                                @if (!empty($courses) && $courses['academic_activity_courses']->isNotEmpty())
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activityDash"
                                            type="button">Academic Activity</button>
                                    </li>
                                @endif
                            @endif
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="boardacademicDash">
                            <div class="table-responsive tbleDiv">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="15%">Subject</th>
                                            <th>Start Date</th>
                                            <th>Total Chapter </th>
                                            <th>Progress</th>
                                            <th>Duration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($courses) && $courses['academic_courses']->isNotEmpty())
                                            @foreach ($courses['academic_courses'] as $course)
                                                @php
                                                    $subject = $course['metadataValues']
                                                        ->where('field_name', 'subject')
                                                        ->first();
                                                    $uniqueChapters = App\Models\CourseChapter::where(
                                                        'course_id',
                                                        $course->id,
                                                    )->count();
                                                    $subjectName = $subject->subjectInfo->name ?? 'N/A';
                                                    $userProgress = App\Models\TrackUserVideoProgress::where(
                                                        'course_id',
                                                        $course->id,
                                                    );
                                                    $videoDuration = $userProgress->sum('video_duration');
                                                    $watchedDuration = $userProgress->sum('watched_duration');
                                                    $startDate = $userProgress->min('created_at'); // this will give the first record
                                                    $percentage =
                                                        $videoDuration > 0
                                                            ? ($watchedDuration / $videoDuration) * 100
                                                            : 0;
                                                @endphp

                                                <tr>
                                                    <td> {{ $subjectName }}</td>
                                                    <td>{{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d M, Y') : 'Not Started' }}
                                                    </td>
                                                    <td> {{ $uniqueChapters ?? 'N/A' }} </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress progressTbl me-2"
                                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                                <div class="progress-bar"
                                                                    style="width: {{ $percentage }}%;">
                                                                </div>
                                                            </div>
                                                            <span>{{ round($percentage, 2) }}%</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::createFromTimestamp($videoDuration)->format('H\h i\m s\s') ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('up.course.digital-content', $course->id) }}">
                                                            <img src="{{ asset('frontend/images/view-icon.svg') }}"
                                                                alt="" width="25"></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">No Course Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- @if (!empty($courses) && $courses['academic_courses']->isNotEmpty())
                                <div class="customPagination mt-4">
                                    <ul class="pagination">
                                        <li
                                            class="page-item {{ $courses['academic_courses']->onFirstPage() ? 'disabled' : '' }} previous-item">
                                            <a class="page-link"
                                                href="{{ $courses['academic_courses']->previousPageUrl() }}">
                                                <span><img src="{{ asset('frontend/images/arrowprw.svg') }}"
                                                        width="6"></span>
                                            </a>
                                        </li>

                                        @foreach ($courses['academic_courses']->getUrlRange(1, $courses['academic_courses']->lastPage()) as $page => $url)
                                            <li
                                                class="page-item {{ $page == $courses['academic_courses']->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        <li
                                            class="page-item {{ $courses['academic_courses']->hasMorePages() ? '' : 'disabled' }} next-item">
                                            <a class="page-link" href="{{ $courses['academic_courses']->nextPageUrl() }}">
                                                <span><img src="{{ asset('frontend/images/arrownxt.svg') }}"
                                                        width="6"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif --}}
                        </div>
                        <div class="tab-pane fade" id="talentDash">
                            <div class="table-responsive tbleDiv ">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="15%">Course</th>
                                            <th>Start Date</th>
                                            <th>Total Lesson </th>
                                            <th>Progress</th>
                                            <th>Duration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($courses) && $courses['nonacademic_courses']->isNotEmpty())
                                            @foreach ($courses['nonacademic_courses'] as $course)
                                                @php
                                                    $uniqueChapters = App\Models\CourseChapter::where(
                                                        'course_id',
                                                        $course->id,
                                                    )->count();
                                                    $userProgress = App\Models\TrackUserVideoProgress::where(
                                                        'course_id',
                                                        $course->id,
                                                    );
                                                    $videoDuration = $userProgress->sum('video_duration');
                                                    $watchedDuration = $userProgress->sum('watched_duration');
                                                    $startDate = $userProgress->min('created_at'); // this will give the first record
                                                    $percentage =
                                                        $videoDuration > 0
                                                            ? ($watchedDuration / $videoDuration) * 100
                                                            : 0;
                                                @endphp
                                                <tr>
                                                    <td> {{ $course->course_name }}</td>
                                                    <td>{{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d M, Y') : 'Not Started' }}
                                                    </td>
                                                    <td> {{ $uniqueChapters ?? 'N/A' }} </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="progress progressTbl me-2"
                                                                aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                                <div class="progress-bar"
                                                                    style="width: {{ $percentage }}%;">
                                                                </div>
                                                            </div>
                                                            <span>{{ round($percentage, 2) }}%</span>
                                                        </div>
                                                    </td>
                                                    <td> {{ \Carbon\Carbon::createFromTimestamp($videoDuration)->format('H\h i\m s\s') }}
                                                    </td>
                                                    <td><a
                                                            href="{{ route('up.courses.chapter.listing', ['slug' => $course->slug, 'id' => $course->id]) }}">
                                                            <img src="{{ asset('frontend/images/view-icon.svg') }}"
                                                                alt="" width="25"></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">No Course Found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            {{-- @if (!empty($courses) && $courses['nonacademic_courses']->isNotEmpty())
                                <div class="customPagination mt-4">
                                    <ul class="pagination">
                                        <li
                                            class="page-item {{ $courses['nonacademic_courses']->onFirstPage() ? 'disabled' : '' }} previous-item">
                                            <a class="page-link"
                                                href="{{ $courses['nonacademic_courses']->previousPageUrl() }}">
                                                <span><img src="{{ asset('frontend/images/arrowprw.svg') }}"
                                                        width="6"></span>
                                            </a>
                                        </li>

                                        @foreach ($courses['nonacademic_courses']->getUrlRange(1, $courses['nonacademic_courses']->lastPage()) as $page => $url)
                                            <li
                                                class="page-item {{ $page == $courses['nonacademic_courses']->currentPage() ? 'active' : '' }}">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endforeach

                                        <li
                                            class="page-item {{ $courses['nonacademic_courses']->hasMorePages() ? '' : 'disabled' }} next-item">
                                            <a class="page-link"
                                                href="{{ $courses['nonacademic_courses']->nextPageUrl() }}">
                                                <span><img src="{{ asset('frontend/images/arrownxt.svg') }}"
                                                        width="6"></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif --}}

                        </div>
                        <div class="tab-pane fade" id="activityDash">

                            @if (isset($courses['academic_activity_courses']) && $courses['academic_activity_courses']->isNotEmpty())
                                <div class="classesMain">
                                    <span>ACADEMIC ACTIVITY</span>
                                    @if (!empty($courses) && $courses['academic_activity_courses']->isNotEmpty())
                                        <ul class="mySubjects mb-3">
                                            @foreach ($courses['academic_activity_courses'] as $course)
                                                @php
                                                    $imageField = $course['metadataValues']
                                                        ->where('field_name', 'banner_image')
                                                        ->first();
                                                    $bannerImage = $imageField->field_value ?? null;
                                                    // Get the class for alternating backgrounds
                                                    $bgClass = 'bg' . (($loop->iteration % 5) + 3); // Cycle between bg1, bg2, bg3, bg4, bg5
                                                @endphp
                                                <li>
                                                    <div class="ViewBox h-100 {{ $bgClass }}">
                                                        <figure>
                                                            <img src="{{ $bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-telent.webp') }}"
                                                                alt="{{ $course['course_name'] }}" width="100%"
                                                                height="100">
                                                        </figure>
                                                        <span class="userCourseName">
                                                            <b class="courseNameView"> {{ $course['course_name'] }}</b>
                                                        </span>
                                                        <a href="{{ route('up.course.digital-content', $course['id']) }}"
                                                            class="btnviewCO mt-2">View Content</a>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="fw-medium">Academic Activities are not available right now. Stay tuned –
                                            coming
                                            soon!</p>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 px-lg-2 mb-3">
                @if (getUserRoles() == 'd2c_user' || getUserRoles() == 'b2c_student')
                    <div class="cardBox mb-3">
                        <h2 class="fs-6 fw-semibold mb-3">Continue Watching</h2>
                        <div class="WatchMain">
                            <div class="row px-md-1">
                                <div class="col-lg-12 col-xl px-md-2 mb-1">
                                    @if ($conWatching['courses']->isNotEmpty())
                                        @foreach ($conWatching['courses'] as $index => $course)
                                            @if (!empty($courses) && $course->course)
                                                @php
                                                    $Image1 = $course->course->metadataValues
                                                        ->where('field_name', 'thumbnail_image')
                                                        ->first();
                                                    $Image2 = $course->course->metadataValues
                                                        ->where('field_name', 'banner_image')
                                                        ->first();
                                                    $Image3 = $course->course->metadataValues
                                                        ->where('field_name', 'book_cover_image')
                                                        ->first();
                                                    $userProgress = App\Models\TrackUserVideoProgress::where(
                                                        'user_id',
                                                        Auth::id(),
                                                    )
                                                        ->where('course_id', $course->course_id)
                                                        ->get();
                                                    $videoDuration = $userProgress->sum('video_duration');
                                                    $watchedDuration = $userProgress->sum('watched_duration');
                                                    $percentage =
                                                        $videoDuration > 0
                                                            ? ($watchedDuration / $videoDuration) * 100
                                                            : 0;
                                                    $uniqueChapters = App\Models\CourseChapter::where(
                                                        'course_id',
                                                        $course->course_id,
                                                    )->count();
                                                    $coursePerChap = App\Models\TrackUserVideoProgress::where(
                                                        'user_id',
                                                        Auth::id(),
                                                    )
                                                        ->where('course_id', $course->course_id)
                                                        ->whereColumn('watched_duration', '=', 'video_duration')
                                                        ->select('chapter_id')
                                                        ->distinct()
                                                        ->get();
                                                    $count = 0;
                                                    foreach ($coursePerChap as $one) {
                                                        $count++;
                                                    }
                                                @endphp
                                                <div class="watchingBox mb-2">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex align-items-center">
                                                            <figure class="m-0">
                                                                <img src="{{ $Image1 ? Storage::url($Image1->field_value) : ($Image2 ? Storage::url($Image2->field_value) : ($Image3 ? Storage::url($Image3->field_value) : asset('frontend/images/default-image.jpg'))) }}"
                                                                    alt="">
                                                            </figure>
                                                            <div class="coursesName">
                                                                <h3>{{ $course['course']['course_name'] }}</h3>
                                                                <p>{{ $count }}/{{ $uniqueChapters }} Lessons</p>
                                                            </div>
                                                        </div>
                                                        <div class="position-relative">
                                                            <div id="watchingTrend{{ $index }}"
                                                                style="width: 80px; height: 80px;"></div>
                                                            <strong
                                                                class="watchingTxt">{{ round($percentage, 2) }}%</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <tr>
                                                    <td class="text-center">Nothing to continue. Explore more!</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">Nothing to continue. Explore more!</td>
                                        </tr>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="cardBox mb-3">
                        <div class="headingBx pb-2">
                            <h4>Associated Teachers</h4>
                            <a href="#teacherList" data-bs-toggle="modal" class="viewAll text-primary">View all</a>
                        </div>
                        <div class="table-responsive tbleDiv associatedTbl">
                            <table class="table">
                                <thead>
                                    <tr class="position-sticky top-0">
                                        <th>Teacher Name</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($dashData['matchingTeachers']))
                                        @foreach ($dashData['matchingTeachers'] as $data)
                                            @if (!empty($data['teacher']) && !empty($data['teacher']->user))
                                                <tr>
                                                    <td>
                                                        <div class="teacherTxt">
                                                            <strong>{{ substr($data['teacher']->user->name, 0, 1) }}</strong>
                                                            <span>{{ $data['teacher']->user->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td> <span>{{ $data['subjects'] }}</span></td>
                                                </tr>
                                            @else
                                                <td> <span>N/A</span> </td>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">No teacher has been assigned.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="cardBox mb-3">
                        <div class="headingBx pb-2">
                            <h4>Scheduled Online Class</h4>
                            <a href="{{ route('up.online.class') }}" class="viewAll text-primary">View all</a>
                        </div>
                        <div class="monthCal">
                            <div class="headingBx">
                                <h4>{{ $dashData['currentMonth'] }}</h4>
                            </div>
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
                    </div>
                @endif

                @if ($notificationAlerts && $notificationAlerts->marketing_banner)
                    <hr class="form_divider m-0 mt-3">
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
                        <a target="_blank" href="{{ $notificationAlerts->redirection_url }}"><video class="w-100"
                                height="350" autoplay loop muted playsinline class="img-thumbnail">
                                <source src="{{ Storage::url('uploads/marketing_banner/' . $file) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video></a>
                    @else
                        <a target="_blank" href="{{ $notificationAlerts->redirection_url }}"><img
                                src="{{ Storage::url('uploads/marketing_banner/' . $file) }}" alt="Marketing Banner"
                                class="w-100" height="350"></a>
                    @endif
                @endif
            </div>

        </div>
    </div>
    <div class="modal fade" id="teacherList">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header align-items-start border-0">
                    <h1 class="modal-title fs-6" id="exampleModalToggleLabel">Teacher List</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="studentLearner">
                        <h3>Total <b>{{ count($dashData['matchingTeachers']) }}</b></h3>
                        <div class="searchBox dropdown-menu d-block mb-2">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by name">
                        </div>
                        <div class="table-responsive learnerList">
                            <table class="table" id="teachersTable">
                                <thead>
                                    <tr class="position-sticky top-0">
                                        <th width="60%">Name</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($dashData['matchingTeachers']))
                                        @foreach ($dashData['matchingTeachers'] as $data)
                                            <tr class="teacherRow">
                                                <td>
                                                    <div class="teacherTxt">
                                                        @if (!empty($data['teacher']) && !empty($data['teacher']->user))
                                                            <strong data-name="{{ $data['teacher']->user->name }}">
                                                                {{ substr($data['teacher']->user->name, 0, 1) }}
                                                            </strong>
                                                            <span>{{ $data['teacher']->user->name }}</span>
                                                        @else
                                                            <strong data-name="N/A">N</strong>
                                                            <span>N/A</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span id="teacherSubject">{{ $data['subjects'] ?? 'N/A' }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">No teacher has been assigned.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end flex-column">
                        <button type="button" class="btn backbtn fw-regular my-2">Back</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var timeSpendingsData = {!! $timeSpendingsData !!};
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#teachersTable tbody tr'); // Get all rows in the table

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase(); // Get the search query and convert to lowercase

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

                            response.dateComparison.forEach(function(classItem) {
                                var startTime = classItem.start_time.split(':').slice(0, 2)
                                    .join(':');
                                var endTime = classItem.end_time.split(':').slice(0, 2).join(
                                    ':');


                                classesHtml += '<li>';
                                classesHtml += '<strong class="mb-1">' + startTime +
                                    '</strong>';
                                classesHtml += '<div class="subjectBox">';
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

            $('.monthCal').on('click', 'ul.dateUl li span', function() {
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
@endsection
