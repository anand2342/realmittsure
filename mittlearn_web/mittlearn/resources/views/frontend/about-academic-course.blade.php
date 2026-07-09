@extends('frontend.layouts.master')
@section('meta')
    @php
        $courseTitle = $course->course_name ?? 'Course Title';
        $courseDescription =
            $course->metadataValues->where('field_name', 'description')->value('field_value') ?? 'Explore this course.';
        $courseUrl = request()->fullUrl();

        $thumbnailImage = $course->metadataValues->where('field_name', 'thumbnail_image')->value('field_value');
        $bookCoverImage = $course->metadataValues->where('field_name', 'book_cover_image')->value('field_value');
        $courseImage = $thumbnailImage
            ? Storage::url($thumbnailImage)
            : ($bookCoverImage
                ? Storage::url($bookCoverImage)
                : asset('frontend/images/mittlearn-logo.svg'));
    @endphp
    <meta name="description" content="{{ $courseDescription }}">
    <!-- Open Graph Meta -->
    <meta property="og:title" content="{{ $courseTitle }}">
    <meta property="og:description" content="{{ $courseDescription }}">
    <meta property="og:image" content="{{ asset($courseImage) }}">
    <meta property="og:url" content="{{ $courseUrl }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $courseTitle }}">
    <meta name="twitter:description" content="{{ $courseDescription }}">
    <meta name="twitter:image" content="{{ asset($courseImage) }}">
    <meta name="twitter:url" content="{{ $courseUrl }}">
@endsection
@section('content')
    <div>
        <div class="aboutMain">
            <div class="sliderAbout">
                <div class="item">
                    <img src="{{ asset('frontend/images/sliderOne.png') }}" alt="">
                </div>
                <div class="item">
                    <img src="{{ asset('frontend/images/sliderTwo.png') }}" alt="">
                </div>

            </div>
            <div class="container">
                <div class="bannerTxt">
                    <div class="sliderTxt">
                        <h4>About Academic Course</h4>
                        <p>A revolutionary digital platform in field of education, committed on social empowerment and
                            enhancing learning capabilities</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="aboutCourses">
            <div class="container">
                @php
                    $classMeta = $course->metadataValues->firstWhere('field_name', 'class');
                    $subjectMeta = $course->metadataValues->firstWhere('field_name', 'subject');
                @endphp
                <div class="row reverseRow">
                    <div class="col-xl-9 col-lg-7">
                        <nav aria-label="breadcrumb ">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Academic</li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $classMeta && $classMeta->classInfo ? $classMeta->classInfo->name : '' }}
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $subjectMeta && $subjectMeta->subjectInfo ? $subjectMeta->subjectInfo->name : '' }}
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $course->course_name ?? 'N/A' }}</li>
                            </ol>
                        </nav>
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs ViewTabs mb-0">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#curriculumTab"
                                        type="button">Course Content</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#overviewTab"
                                        type="button">Overview</button>
                                </li>
                            </ul>

                            <!-- Filter Form -->
                            <div class="viewCategory">
                                <form method="GET" action="{{ request()->url() }}"
                                    class="d-flex flex-wrap gap-2 align-items-center mb-0">
                                    <div class="d-flex flex-column">
                                        <label class="small mb-1">Content Language</label>
                                        <select class="form-control form-select" name="language"
                                            onchange="this.form.submit()" style="min-width: 150px;">
                                            @foreach (config('constants.CONTENT_LANGUAGE') as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ request('language') == $key ? 'selected' : 'bilingual' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade  show active" id="curriculumTab">
                                <div class="urriculumMain">
                                    <div class="headingsections">
                                        <span>{{ $courseChapters->count() }} lessons</span>
                                        {{-- <a href="#" class="expandall">Expand all sections</a> --}}
                                    </div>

                                    <div class="accordion curriculumAcrdn" id="accordionclm">
                                        @foreach ($courseChapters as $index => $chapter)
                                            @php
                                                $uniqueId = 'clmTwo_' . $index; // Unique ID for accordion
                                                $video = $chapter->filtered_video; // Get the first video from the chapter
                                                $otherDoc = $chapter->otherDoc; // Get the first video from the chapter
                                                $activityLink = \App\Models\MediaFiles::where(
                                                    'type',
                                                    'activity_worksheet_link',
                                                )
                                                    ->where('tbl_id', $chapter->id)
                                                    ->get();
                                            @endphp

                                            <div class="accordion-item">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#{{ $uniqueId }}"
                                                    aria-expanded="false" aria-controls="{{ $uniqueId }}">
                                                    Lesson {{ $chapter->sort_order }}
                                                    <span></span>
                                                </button>

                                                <div id="{{ $uniqueId }}" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionclm">
                                                    <div class="accordion-body">
                                                        @if ($video)
                                                            @php
                                                                $canViewAllVideos =
                                                                    auth()->check() && auth()->user()->is_admin == 1;
                                                            @endphp

                                                            <div class="accordianInner mx-3">
                                                                <button type="button"
                                                                    class="border-0 bg-transparent p-0 text-start w-100 d-flex align-items-center gap-3"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="{{ $canViewAllVideos || $index < 3 ? '#coursePreview-' . $video->id : '#coursePurchage' }}">

                                                                    <span><strong>{{ $chapter->chapter_name }}</strong></span>

                                                                    <span class="play-lock-btn">
                                                                        @if ($canViewAllVideos || $index < 3)
                                                                            <i class="bi bi-play-fill fs-3"></i>
                                                                        @else
                                                                            <i class="bi bi-lock-fill fs-3"></i>
                                                                        @endif
                                                                    </span>
                                                                </button>

                                                                @if ($canViewAllVideos || $index < 3)
                                                                    <div class="modal fade previewVdo"
                                                                        id="coursePreview-{{ $video->id }}"
                                                                        tabindex="-1"
                                                                        aria-labelledby="coursePreviewLabel-{{ $video->id }}"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content rounded-0 border-0"
                                                                                style="background: rgba(0, 0, 0, .5); color: #fff;">

                                                                                <div class="modal-header border-0">
                                                                                    <h1 class="modal-title fs-5 fw-normal"
                                                                                        id="coursePreviewLabel-{{ $video->id }}">
                                                                                        Course Preview
                                                                                    </h1>
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>

                                                                                <div class="modal-body p-0">
                                                                                    <p class="py-2 px-3 fs-8 mb-0">
                                                                                        {{ $chapter->chapter_name }}
                                                                                    </p>
                                                                                    <video width="100%" height="240"
                                                                                        controls controlsList="nodownload"
                                                                                        oncontextmenu="return false">
                                                                                        <source
                                                                                            src="{{ Storage::url('uploads/course_chapter_files/' . $video->attachment_file) }}"
                                                                                            type="video/mp4">
                                                                                    </video>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @elseif($otherDoc)
                                                            <div class="accordianInner mx-3">
                                                                <a target="_blank"
                                                                    href="{{ Storage::url('uploads/course_chapter_files/' . $otherDoc->attachment_file) }}"
                                                                    class="d-flex align-items-center justify-content-between w-100 text-decoration-none text-dark py-2">
                                                                    <span><strong>{{ $chapter->chapter_name }}</strong></span>
                                                                    <span class="play-lock-btn">
                                                                        @if ($index < 3)
                                                                            <i
                                                                                class="bi bi-file-earmark-text-fill fs-3"></i>
                                                                        @else
                                                                            <i class="bi bi-lock-fill fs-3"></i>
                                                                        @endif
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        @elseif(!empty($activityLink))
                                                            <div class="game-links">
                                                                @foreach ($activityLink as $item)
                                                                    <a href="{{ $item->link_url }}" target="_blank"
                                                                        class="game-card d-block mb-3 text-decoration-none">
                                                                        <div
                                                                            class="game-content rounded-2 position-relative overflow-hidden">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="game-icon me-3"
                                                                                    style="background-color: rgba(0, 190, 85, 0.1);">
                                                                                    <i class="bi bi-joystick"
                                                                                        style="color: #00BE55;"></i>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <h5 class="game-title mb-0"
                                                                                        style="color: #000; font-size: 12px">
                                                                                        {{ $item->file_name ?? $item->original_name }}
                                                                                    </h5>
                                                                                </div>
                                                                                <div
                                                                                    class="game-cta d-flex align-items-center">

                                                                                    <div class="game-arrow"
                                                                                        style="color: #00438C;">
                                                                                        <i
                                                                                            class="bi bi-arrow-right-short"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                @endforeach
                                                            </div>

                                                            <style>
                                                                /* Main game card styling */
                                                                .game-card {
                                                                    transition: all 0.3s ease;
                                                                }

                                                                .game-content {
                                                                    background-color: white;
                                                                    border: 1px solid #e0e0e0;
                                                                    transition: all 0.3s ease;
                                                                }

                                                                .game-card:hover .game-content {
                                                                    transform: translateY(-3px);
                                                                    box-shadow: 0 5px 15px rgba(0, 67, 140, 0.1);
                                                                    border-color: #00438C;
                                                                }

                                                                /* Game icon styling */
                                                                .game-icon {
                                                                    width: 48px;
                                                                    height: 48px;
                                                                    border-radius: 8px;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    font-size: 1.5rem;
                                                                }

                                                                /* Title styling */
                                                                .game-title {
                                                                    font-weight: 600;
                                                                    transition: color 0.3s ease;
                                                                }

                                                                /* CTA animation */
                                                                .cta-text {
                                                                    opacity: 0;
                                                                    transform: translateX(-10px);
                                                                    transition: all 0.3s ease;
                                                                    font-weight: 500;
                                                                }

                                                                .game-arrow {
                                                                    font-size: 1.5rem;
                                                                    transition: all 0.3s ease;
                                                                }

                                                                .game-card:hover .cta-text {
                                                                    opacity: 1;
                                                                    transform: translateX(0);
                                                                    color: #00BE55;
                                                                }

                                                                .game-card:hover .game-arrow {
                                                                    transform: translateX(5px);
                                                                    color: #00BE55;
                                                                }

                                                                /* Hover effect */
                                                                .game-hover-effect {
                                                                    position: absolute;
                                                                    bottom: 0;
                                                                    left: 0;
                                                                    width: 0;
                                                                    height: 3px;
                                                                    background-color: #00BE55;
                                                                    transition: width 0.3s ease;
                                                                }

                                                                .game-card:hover .game-hover-effect {
                                                                    width: 100%;
                                                                }
                                                            </style>
                                                        @else
                                                            <p class="text-muted">Oops! No videos are available for
                                                                this
                                                                lesson. Try exploring another lesson for more exciting
                                                                content!</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="overviewTab">
                                <div class="aboutLeft position-relative">
                                    <div class="section-heading m-0 text-start pb-2">
                                        <h2 class="text-black"><span class="greenBorder"></span>
                                            About Course</h2>
                                    </div>
                                    @php
                                        $description = $course->metadataValues
                                            ->where('field_name', 'description')
                                            ->value('field_value');
                                    @endphp
                                    <p>{{ $course->course_name ?? 'N/A' }}, {!! $description !!}</p>

                                    <div class="lottieCourse">
                                        <lottie-player src="{{ asset('frontend/images/wave-lines.json') }}"
                                            autoplay="" loop="" style="width: 180px; height: 70px;"
                                            background="transparent"></lottie-player>
                                    </div>
                                </div>

                                {{--  <div class  <div class="">
                                    <div class="aboutBg">
                                        <div class="section-heading m-0 text-start pb-2">
                                            <h2 class="text-black"><span class="greenBorder"></span>
                                                About Course</h2>
                                        </div>
                                        <p>Mittsure’s Kathak for Beginners course helps you learn the basics of this skill
                                            through 30 short
                                            sessions. You can access these sessions anytime, as the course is presented in
                                            the form of videos.
                                            The
                                            run time of each video is less than 18 minutes to ensure learning is fun and
                                            less time consuming.
                                            The
                                            length of these videos does not depict the information quality. By watching each
                                            video you will be
                                            able
                                            to follow a step-by-step approach towards your Kathak journey. This course will
                                            not make you a
                                            professional but will definitely set you on the journey by teaching you the
                                            basics.You can also
                                            register
                                            for our online classes or opt for one-on-one sessions to clarify your doubts
                                            directly with the
                                            trainer.
                                        </p>
                                        <p>Wondering what you will learn in the Mittsure’s Kathak for Beginner’s course?
                                            Here is a sneak peek:
                                        </p>
                                        <p>1. You will learn the basic Kathak postures, how to perform namaskaar and the
                                            procedure for tying
                                            ankle
                                            bells (ghungroos)</p>
                                        <p>2. Learn different facial expressions, mudras, laya, abhinaya, and padsanchalan
                                        </p>
                                        <p>3. You will learn how to walk in different ways, thaat and chakkar using 5
                                            footsteps and heel.</p>
                                        <p>4. You will also learn the wrist positions along with balancing the hand position
                                            with the neck
                                            position
                                            in Madhya and Vilambhit laya </p>
                                        <p>Want to learn the techniques associated with the Kathak dance form and start your
                                            journey to become
                                            Kathak dancer? Join our Kathak for Beginners course today. Happy dancing!</p>
                                    </div>
                                </div>="willLearn">
                                    <div class="container">
                                        <div class="learnInner row">
                                            <div class="col-xl-7 col-md-12">
                                                <div class="section-heading m-0 text-start pb-2">
                                                    <h2 class="text-black"><span class="greenBorder"></span>
                                                        What You Will Learn</h2>
                                                </div>
                                                <p>1. The basics of Kathak postures </p>
                                                <p>2. How to tie and carry Ghongroos</p>
                                                <p>3. Learn different layas and mudras</p>
                                                <p>4. The art of storytelling through abhinays and facial expressions</p>
                                                <p>5. Balance between the neck and hand positions </p>
                                                <p>6. Count in Teentals (16 rhythms) and other different tempos </p>
                                                <p>7. Move with grace and confidence while performing Kathak</p>
                                            </div>
                                            <div class="col-xl-5 col-md-12">
                                                <div class="lottieDance">
                                                    <lottie-player src="" autoplay="" loop=""
                                                        style="max-width: 220px; height: 100%;margin: auto;"
                                                        background="transparent"></lottie-player>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="requirement">
                                    <div class="container">
                                        <div class="requirementInner ">
                                            <div class="section-heading m-0 text-start pb-2">
                                                <h2 class="text-black"><span class="greenBorder"></span>
                                                    Requirement</h2>
                                            </div>
                                            <p>1. High-speed internet connection for watching the videos</p>
                                            <p>2. Open space to practice dance </p>
                                            <p>3. Ghongroos or anklebells </p>
                                            <p>4. Traditional Kathak clothing is optional</p>
                                        </div>
                                    </div>
                                </div>  --}}

                            </div>


                        </div>

                        <div class="needOnly">
                            <div class="container">
                                <div class="section-heading m-0 text-start pb-2">
                                    <h2 class="text-black"><span class="greenBorder"></span>
                                        You only need</h2>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="needBox h-100">
                                            <figure>
                                                <img src="{{ asset('frontend/images/desktop-icon.svg') }}" alt=""
                                                    width="48">
                                            </figure>
                                            <span>Desktop/Laptop/Mobile access for an hour</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="needBox h-100">
                                            <figure>
                                                <img src="{{ asset('frontend/images/broadband-icon.svg') }}"
                                                    alt="" width="60">
                                            </figure>
                                            <span>Broadband internet connection</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="needBox h-100">
                                            <figure>
                                                <img src="{{ asset('frontend/images/headset-icon.svg') }}" alt=""
                                                    width="55">
                                            </figure>
                                            <span>Headset(Not Mandatory)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-5">
                        <div class="cartsImag">
                            @php
                                if ($course->discount_type == 'flat') {
                                    $finalPrice = $course->price - $course->discount_value;
                                } elseif ($course->discount_type == 'percentage') {
                                    $finalPrice = $course->price - ($course->discount_value / 100) * $course->price;
                                } else {
                                    $finalPrice = $course->price;
                                }
                                $bookCoverImage = $course->metadataValues
                                    ->Where('field_name', 'book_cover_image')
                                    ->value('field_value');
                                $thumbnailImage = $course->metadataValues
                                    ->Where('field_name', 'thumbnail_image')
                                    ->value('field_value');
                            @endphp
                            {{--  <figure class="imageMain">  --}}
                            @if ($thumbnailImage || $bookCoverImage)
                                <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage) }}"
                                    alt="course image">
                            @else
                                <img src="{{ asset('frontend/images/default-image.jpg') }}" alt="Default Image">
                            @endif
                            {{--  <span class="prevCrs">Preview this course</span>  --}}
                            {{--  </figure>  --}}
                            <div class="cartContent">
                                <span>


                                    <b class="lineThr mb-my-3 me-2">{{ number_format($course->price) }}</b>
                                    ₹{{ number_format($finalPrice) }}

                                </span>
                                {{--  <img src="{{ asset('frontend/images/alarm-icon.svg') }}" width="15" alt="">  --}}
                                {{-- @dump($course) --}}

                                @if ($course->in_cart == 0)
                                    <a href="{{ route('course.add-to-cart', $course->id) }}"
                                        class="btn btn-primary w-100 rounded-0 fw-semibold my-3">Add to cart</a>
                                @else
                                    <a href="{{ route('course.go-to-cart') }}"
                                        class="btn btn-primary w-100 rounded-0 fw-semibold my-3">Go to cart</a>
                                @endif
                                <div class="shareCourse">
                                    <span class="fw-semibold d-block mb-2">Share this course</span>

                                    <div class="form-group position-relative">
                                        <input type="text" class="form-control" id="shareUrl"
                                            value="{{ url()->current() }}" readonly>
                                        <button onclick="copyToClipboard()" class="btn btnCopy">Copy</button>
                                    </div>
                                    <span id="copyMessage" style="display: none; color: green;">URL copied!</span>

                                    <ul class="socialCart m-3">
                                        {!! $shareButtons !!}
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="container">
                <div class="section-heading m-0 text-start pb-2">
                    <h2 class="text-black"><span class="greenBorder"></span>
                        Relevant Academic Courses</h2>
                </div>
                <div class="row px-md-1">
                    @foreach ($acadCourses as $course)
                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                            <div class="coursesBox">
                                <figure class="position-relative">
                                    @php
                                        $bookCoverImage = $course->metadataValues
                                            ->Where('field_name', 'book_cover_image')
                                            ->value('field_value');
                                        $thumbnailImage = $course->metadataValues
                                            ->Where('field_name', 'thumbnail_image')
                                            ->value('field_value');
                                        $originalPrice = $course->price;
                                        // Discount calculation
                                        if ($course->discount_type == 'percent') {
                                            // Calculate the price after discount for percent type
                                            $discountedPrice =
                                                $originalPrice - $originalPrice * ($course->discount_value / 100);
                                        } elseif ($course->discount_type == 'flat') {
                                            // Calculate the price after discount for flat type
                                            $discountedPrice = $originalPrice - $course->discount_value;
                                        } else {
                                            // If no discount type, keep the original price
                                            $discountedPrice = $originalPrice;
                                        }
                                    @endphp
                                    <a href="{{ route('about-acadcourse', $course->slug) }}">
                                        @if ($thumbnailImage || $bookCoverImage)
                                            <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage) }}"
                                                alt="course image">
                                        @else
                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                alt="Default Image">
                                        @endif
                                    </a>
                                    @if ($course->in_wishlist == 0)
                                        <button type="button" class=" bg-transparent border-0 p-0 wishlistButton"
                                            data-course-id="{{ $course->id }}" data-item-id="{{ $course->id }}"
                                            data-item-type="academic_course">
                                            <img src="{{ asset('frontend/images/heart-icon.svg') }}"
                                                class="wishlist-icon-{{ $course->id }}" alt="Wishlist Icon"
                                                width="18">
                                        </button>
                                    @else
                                        <button type="button" class=" bg-transparent border-0 p-0 wishlistButton"
                                            data-course-id="{{ $course->id }}" data-item-id="{{ $course->id }}"
                                            data-item-type="academic_course">
                                            <img src="{{ asset('frontend/images/red-heart-icon.svg') }}"
                                                class="wishlist-icon-{{ $course->id }}" alt="Wishlist Icon"
                                                width="18">
                                        </button>
                                    @endif
                                </figure>

                                <div class="d-flex gap-2 justify-content-between px-2">
                                    <div class="blogProfile">
                                        <strong>Mittlearn</strong>
                                    </div>
                                    {{-- <div class="d-flex gap-3">
                                        @if ($course->in_cart == 0)
                                            <button type="button"
                                                class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn" data-item-id=""
                                                data-item-type="academic_course" data-course-id="{{ $course->id }}"
                                                data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                <img src="{{ asset('frontend/images/cart-icon.svg') }}"alt="Cart Icon"
                                                    class="cart-icon-{{ $course->id }}" width="20">
                                            </button>
                                        @else
                                            <button type="button"
                                                class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn cartAdded"
                                                data-item-id="" data-item-type="academic_course"
                                                data-course-id="{{ $course->id }}"
                                                data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                <img src="{{ asset('frontend/images/cart-icon-saved.svg') }}"alt="Cart Icon"
                                                    class="cart-icon-{{ $course->id }}" width="20">
                                            </button>
                                        @endif
                                        <input type="hidden" name="cart_id" id="savedCartId" value="">
                                        <input type="hidden" name="user_id" id="userAuthId"
                                            value="{{ auth()->check() ? auth()->id() : null }}">
                                        <input type="hidden" name="wishlist_id" id="savedWishlistId" value="">
                                    </div> --}}
                                </div>

                                <a href="{{ route('about-acadcourse', $course->slug) }}">
                                    <h3 class="px-2">
                                        {{ limit_words($course->course_name ?? 'No Course Name', 3) }}
                                    </h3>
                                </a>
                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                    @foreach ($course->metadataValues ?? [] as $metadataValue)
                                        @if ($metadataValue->field_name == 'subject' && $metadataValue->subjectInfo)
                                            <span>
                                                <img src="{{ asset('frontend/images/student-icon.svg') }}"
                                                    alt="mittlearn-image" width="14">
                                                Sub:
                                                {{ $metadataValue->subjectInfo->name ?? '' }}
                                            </span>
                                        @elseif ($metadataValue->field_name == 'class' && $metadataValue->classInfo)
                                            <span>
                                                <img src="{{ asset('frontend/images/student-icon.svg') }}"
                                                    alt="mittlearn-image" width="14">
                                                {{ $metadataValue->classInfo->name ?? '' }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                                <hr>
                                <div class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                    <div class="pricetag">
                                        <span>₹ {{ number_format($course->price) }}</span>
                                        @php
                                            if ($course->discount_type == 'flat') {
                                                $finalPrice = $course->price - $course->discount_value;
                                            } elseif ($course->discount_type == 'percent') {
                                                $finalPrice =
                                                    $course->price - ($course->discount_value / 100) * $course->price;
                                            } else {
                                                $finalPrice = $course->price;
                                            }
                                        @endphp
                                        ₹ {{ number_format($finalPrice) }}
                                    </div>
                                    <a href="{{ route('about-acadcourse', $course->slug) }}"
                                        class="btn btn-primary-gradient rounded-1">Know more</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>





    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $('.sliderAbout').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            autoplay: true,
            arrows: false,
            slidesToScroll: 1,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: false,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]
        });
        $('.studentSaySlider').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            infinite: true,
            arrows: true,
            prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-arrow-left"></i></button>',
            nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-arrow-right"></i></button>',
            responsive: [{
                    breakpoint: 991,
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        $('.activitieSlider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            infinite: true,
            speed: 300,
            centerMode: true,
            arrows: true,
            centerPadding: '550px',
            prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-arrow-left"></i></button>',
            nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-arrow-right"></i></button>',
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        centerPadding: '450px',
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        centerPadding: '250px',
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        centerPadding: '0',
                    }
                },

                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
        const horizontalAccordions = $(".accordion.programAccordion");

        horizontalAccordions.each((index, element) => {
            const accordion = $(element);
            const collapse = accordion.find(".collapse");
            const bodies = collapse.find("> *");
            accordion.height(accordion.height());
            bodies.width(bodies.eq(0).width());
            collapse.not(".show").each((index, element) => {
                $(element).parent().find("[data-bs-toggle='collapse']").addClass("collddapsed");
            });
        });
    </script>

    <!-- V Added For Copy URL Start --------------->
    <script type="text/javascript">
        function copyToClipboard() {
            var copyText = document.getElementById("shareUrl");
            copyText.select();
            document.execCommand("copy");

            var copyMessage = document.getElementById("copyMessage");
            copyMessage.style.display = "inline";

            setTimeout(() => {
                copyMessage.style.display = "none";
            }, 2000);
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".socialCart a").forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    var url = this.href;
                    window.open(url, '_blank', 'width=800,height=600,top=100,left=200');
                });
            });
        });
    </script>
    <!-- V Added For Copy URL End --------------->
@endsection
