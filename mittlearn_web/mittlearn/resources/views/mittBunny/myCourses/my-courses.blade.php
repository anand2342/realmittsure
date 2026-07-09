@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">
        <div class="d-flex flex-wrap">
            <div class="leftpanel">
                <div class="helloSection">
                    <div class=" pe-md-5">
                        <h2><b>MY</b> Courses</h2>
                        <p>Access all your courses in one place.</p>
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
                                {{-- @dd($courses) --}}
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
                                    {{-- @dd($groupedCourses) --}}
                                    @foreach ($groupedCourses as $classLabel => $courseGroup)
                                        @php
                                            $classLabelName = \App\Models\Classes::where('id', $classLabel)->value(
                                                'name',
                                            );
                                            $userClassesSchStudent = App\Models\UserClass::where('user_id', Auth::id())->exists();

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
                                        <div class="col-md-4 px-2 mb-3">
                                            <div class="h-100 maincoursebx">
                                                {{-- <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                    class="cardcourse {{ $className }} mb-3 h-100"> --}}

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
                                                <div class="btnBottom">
                                                    <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                        class="btn btn-primary-gradient rounded-1 py-2 w-100">View
                                                        Course</a>
                                                    <a href="{{ route('mittbunny.course.digital-content', $course['id']) }}"
                                                        class="btn btn-success rounded-1 py-2 w-100 mt-2">View
                                                        Content</a>
                                                </div>
                                            </div>
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

                                            // Ensure the index does not exceed the array length
                                            $className = $classNames[$index % count($classNames)];
                                            $lottieImage = $lottieImages[$index % count($lottieImages)];
                                        @endphp
                                        <div class="col-md-4 px-2 mb-3">
                                            <div class="h-100 maincoursebx">
                                                {{-- <a href="{{ route('mittbunny.course.listing', ['slug' => $course['slug']]) }}"
                                                    class="cardcourse {{ $className }} mb-3 h-100"> --}}

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
                @include('mittBunny.layouts.continue-watching-sec')

            </div>

        </div>
    </div>
@endsection
