@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="row">
            <div class="col-lg-12 col-xl-8">

                <div class="cardBox mb-3">
                    <h2 class="fs-6 fw-semibold mb-3">My Courses</h2>
                    {{-- Academic Courses --}}
                    {{-- <div class="classesMain">
                        <span>ACADEMIC</span>
                        @if (!empty($courses) && $courses['academic_courses']->isNotEmpty())
                            <ul class="mySubjects mb-3">
                                @foreach ($courses['academic_courses'] as $course)
                                    @php
                                        $subject = $course['metadataValues']->where('field_name', 'subject')->first();
                                        $subjectImage = $subject->subjectInfo->image ?? null;
                                        $subjectName = $subject->subjectInfo->name ?? 'N/A';
                                        $bgClass = 'bg' . (($loop->iteration % 5) + 2); // Cycle between bg1, bg2, bg3, bg4, bg5

                                        $imageField1 = $course['metadataValues']
                                            ->where('field_name', 'book_cover_image')
                                            ->first();
                                        $imageField2 = $course['metadataValues']
                                            ->where('field_name', 'thumbnail_image')
                                            ->first();

                                        // Determine which image to use
                                        $bannerImage = $imageField1->field_value
                                            ? $imageField1->field_value
                                            : $imageField2->field_value ?? null;

                                    @endphp


                                    <li>
                                        <div class="ViewBox h-100 {{ $bgClass }}">
                                            @if ($bannerImage)
                                                <figure>
                                                    <img src="{{ $bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-subject.png') }}"
                                                        alt="{{ $course['course_name'] }}" width="100%" height="100">
                                                </figure>
                                            @else
                                                <figure>
                                                    <img src="{{ $subjectImage ? Storage::url('uploads/subject/' . $subjectImage) : asset('frontend/images/default-subject.png') }}"
                                                        alt="{{ $subjectName }}" width="45">
                                                </figure>
                                            @endif
                                            <span>
                                                {{ $subjectName }}
                                                <b class="courseNameView">{{ $course['course_name'] }}</b>
                                            </span>
                                            @if ($role != 'd2c_user')
                                                <a href="{{ route('up.course.listing', ['slug' => $course['slug']]) }}"
                                                    class="btnview mt-2">View Course</a>
                                            @endif
                                            <a href="{{ route('up.course.digital-content', $course['id']) }}"
                                                class="btnviewCO mt-2">View Content</a>

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="fw-medium">Your Class Academic Courses are not available right now. Check
                                back soon!</p>
                        @endif
                    </div> --}}

                    @php
                        if (!empty($courses) && $courses['academic_courses']->isNotEmpty()) {
                            $groupedCourses = $courses['academic_courses']->groupBy(function ($course) {
                                return optional($course['metadataValues']->where('field_name', 'class')->first())
                                    ->field_value ?? 'Unknown Class';
                            });
                        }

                        $role = getUserRoles();
                    @endphp

                    <div class="classesMain">
                        <span>ACADEMIC</span>
                        {{-- @dd($groupedCourses) --}}
                        @if (isset($groupedCourses) && $groupedCourses->isNotEmpty())
                            <ul class="mySubjects mb-3">
                                @foreach ($groupedCourses as $classId => $courseGroup)
                                    @php
                                        $classLabelName =
                                            \App\Models\Classes::where('id', $classId)->value('name') ??
                                            'Unknown Class';
                                        $userClassesSchStudent = \App\Models\UserClass::where(
                                            'user_id',
                                            Auth::id(),
                                        )->exists();

                                    @endphp

                                    {{-- Class Label Heading --}}
                                    @if (getUserRoles() == 'd2c_user' || (getUserRoles() == 'school_student' && $userClassesSchStudent))
                                        <li class="w-100">
                                            <h6 class="fw-bold text-primary mb-3 classNameLbl">{{ $classLabelName }}</h6>
                                        </li>
                                    @endif

                                    @foreach ($courseGroup as $course)
                                        @php
                                            $subject = $course['metadataValues']
                                                ->where('field_name', 'subject')
                                                ->first();
                                            $subjectImage = $subject->subjectInfo->image ?? null;
                                            $subjectName = $subject->subjectInfo->name ?? 'N/A';
                                            $bgClass = 'bg' . (($loop->iteration % 5) + 2);

                                            $imageField1 = $course['metadataValues']
                                                ->where('field_name', 'book_cover_image')
                                                ->first();
                                            $imageField2 = $course['metadataValues']
                                                ->where('field_name', 'thumbnail_image')
                                                ->first();
                                            $bannerImage =
                                                optional($imageField1)->field_value ??
                                                (optional($imageField2)->field_value ?? null);
                                        @endphp

                                        <li>
                                            <div class="ViewBox h-100 {{ $bgClass }}">
                                                @if ($bannerImage)
                                                    <figure>
                                                        <img src="{{ Storage::url($bannerImage) }}"
                                                            alt="{{ $course['course_name'] }}" width="100%"
                                                            height="100">
                                                    </figure>
                                                @else
                                                    <figure>
                                                        <img src="{{ $subjectImage ? Storage::url('uploads/subject/' . $subjectImage) : asset('frontend/images/default-subject.png') }}"
                                                            alt="{{ $subjectName }}" width="45">
                                                    </figure>
                                                @endif

                                                <span>
                                                    {{ $subjectName }}
                                                    <b class="courseNameView">{{ $course['course_name'] }}</b>
                                                </span>
                                                @if ($role != 'd2c_user')
                                                    <a href="{{ route('up.course.listing', ['slug' => $course['slug']]) }}"
                                                        class="btnview mt-2">View Course</a>
                                                @endif

                                                <a href="{{ route('up.course.digital-content', $course['id']) }}"
                                                    class="btnviewCO mt-2">View Content</a>
                                            </div>
                                        </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        @else
                            <p class="fw-medium">Your Class Academic Courses are not available right now. Check back soon!
                            </p>
                        @endif
                    </div>

                    @php
                        $role = getUserRoles();
                        $category = App\Models\UserClass::where('user_id', Auth::id())->value('category_id');
                    @endphp
                    @if (!($role == 'd2c_user' && $category == '35'))
                        {{-- Talent (Non-Academic) Courses --}}
                        <div class="classesMain">
                            <span>TALENT</span>
                            @if (!empty($courses) && $courses['nonacademic_courses']->isNotEmpty())
                                <ul class="mySubjects mb-3">
                                    @foreach ($courses['nonacademic_courses'] as $course)
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
                                                        alt="{{ $course['course_name'] }}" width="100%" height="100">
                                                </figure>
                                                <span class="userCourseName">
                                                    <b class="courseNameView"> {{ $course['course_name'] }}</b>
                                                </span>
                                                @if ($role != 'd2c_user')
                                                    <a href="{{ route('up.course.listing', ['slug' => $course['slug']]) }}"
                                                        class="btnview mt-2">View Course</a>
                                                @endif
                                                <a href="{{ route('up.course.digital-content', $course['id']) }}"
                                                    class="btnviewCO mt-2">View Content</a>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="fw-medium">Talent Courses are not available right now. Stay tuned – coming
                                    soon!</p>
                            @endif
                        </div>
                        {{-- @dd($courses) --}}
                        @if (!empty($courses) && $courses['academic_activity_courses']->isNotEmpty())
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
                    @endif
                </div>
            </div>
            @include('userPortal.layouts.continue-watching-sec')

        </div>
        <div class="cardBox mycoursesTbl">
            <div class="d-md-flex align-items-center gap-4 mb-3 ">
                <h2 class="fs-6 fw-semibold d-flex align-items-center gap-2 m-md-0">My Courses
                    {{-- <span>6</span> --}}
                </h2>
                <ul class="nav nav-tabs ViewTabs mb-0" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#academicTab"
                            type="button">Subjects</button>
                    </li>
                    @php
                        $role = getUserRoles();
                        $category = App\Models\UserClass::where('user_id', Auth::id())->value('category_id');
                    @endphp
                    @if (!($role == 'd2c_user' && $category == '35'))
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#talentTab" type="button">Talent
                                Box</button>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="academicTab">
                    <div class="table-responsive tbleDiv ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="15%">Subject</th>
                                    <th>Start Date</th>
                                    <th>Total Chapters</th>
                                    <th>Progress</th>
                                    <th>Lesson Completed</th>
                                    <th>Duration</th>
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
                                                $videoDuration > 0 ? ($watchedDuration / $videoDuration) * 100 : 0;
                                            $coursePerChap = App\Models\TrackUserVideoProgress::where(
                                                'user_id',
                                                Auth::id(),
                                            )
                                                ->where('course_id', $course->id)
                                                ->whereColumn('watched_duration', '=', 'video_duration')
                                                ->select('chapter_id')
                                                ->distinct()
                                                ->get();
                                            $count = 0;
                                            foreach ($coursePerChap as $one) {
                                                $count++;
                                            }
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
                                                        <div class="progress-bar" style="width: {{ $percentage }}%;">
                                                        </div>
                                                    </div>
                                                    <span>{{ round($percentage, 2) }}%</span>
                                                </div>
                                            </td>
                                            <td><span class="lessonCom"> {{ $count }}/<b>{{ $uniqueChapters }}</b>
                                                    ({{ round($percentage, 2) }}%)
                                                </span></td>
                                            <td>{{ \Carbon\Carbon::createFromTimestamp($videoDuration)->format('H\h i\m s\s') ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No Courses found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="talentTab">
                    <div class="table-responsive tbleDiv ">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="15%">Course</th>
                                    <th>Start Date</th>
                                    <th>Total Lessons</th>
                                    <th>Progress</th>
                                    <th>Lesson Completed</th>
                                    <th>Duration</th>
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
                                                'user_id',
                                                Auth::id(),
                                            )->where('course_id', $course->id);
                                            $videoDuration = $userProgress->sum('video_duration');
                                            $watchedDuration = $userProgress->sum('watched_duration');
                                            $startDate = $userProgress->min('created_at'); // this will give the first record
                                            $percentage =
                                                $videoDuration > 0 ? ($watchedDuration / $videoDuration) * 100 : 0;
                                            $coursePerChap = App\Models\TrackUserVideoProgress::where(
                                                'user_id',
                                                Auth::id(),
                                            )
                                                ->where('course_id', $course->id)
                                                ->whereColumn('watched_duration', '=', 'video_duration')
                                                ->select('chapter_id')
                                                ->distinct()
                                                ->get();
                                            $count = 0;
                                            foreach ($coursePerChap as $one) {
                                                $count++;
                                            }
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
                                                        <div class="progress-bar" style="width: {{ $percentage }}%;">
                                                        </div>
                                                    </div>
                                                    <span>{{ round($percentage, 2) }}%</span>
                                                </div>
                                            </td>
                                            <td><span class="lessonCom"> {{ $count }}/<b>{{ $uniqueChapters }}</b>
                                                    ({{ round($percentage, 2) }}%)
                                                </span></td>
                                            <td> {{ \Carbon\Carbon::createFromTimestamp($videoDuration)->format('H\h i\m s\s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No Courses found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            @foreach ($conWatching['courses'] as $index => $course)
                @php
                    $userProgress = App\Models\TrackUserVideoProgress::where('user_id', Auth::id())->where('course_id', $course->course_id)->get();
                    $videoDuration = $userProgress->sum('video_duration');
                    $watchedDuration = $userProgress->sum('watched_duration');
                    $finalPer = $videoDuration > 0 ? ($watchedDuration / $videoDuration) * 100 : 0;
                    $percentage = round($finalPer, 2);
                @endphp

                var percentage = {{ $percentage }};
                Highcharts.chart('watchingTrend{{ $index }}', {
                    chart: {
                        type: 'pie',
                        margin: [0, 0, 0, 0], // Set all margins to 0
                        spacing: [0, 0, 0, 0],
                    },
                    title: {
                        text: null,
                    },
                    credits: {
                        enabled: false
                    },
                    tooltip: {
                        enabled: false // Disable the tooltip on hover
                    },
                    plotOptions: {
                        pie: {
                            dataLabels: {
                                enabled: false // Set dataLabels to false to hide labels
                            },
                            plotBorderWidth: 0, // Remove the border
                            borderRadius: 10,
                            showInLegend: false
                        },
                    },

                    series: [{
                        states: {
                            hover: {
                                enabled: false,
                                opacity: 1
                            }
                        },
                        innerSize: '70%',
                        zMin: 0,
                        borderRadius: 0,
                        data: [{
                            name: 'Completed',
                            y: percentage, // Dynamically set the completed percentage
                            color: '#1780DD', // Ensure blue color is applied for completed part
                        }, {
                            name: 'Remaining',
                            y: 100 - percentage, // The remaining percentage
                            color: '#CDD5E1', // Light gray for the remaining part
                        }],
                    }]
                });
            @endforeach
        });
    </script>
@endsection
