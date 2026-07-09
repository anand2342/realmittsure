@extends('frontend.layouts.master')
@section('meta')
    @php
        $courseTitle = $course->course_name ?? 'Course Title';
        $courseDescription =
            $course->metadataValues->where('field_name', 'description')->value('field_value') ?? 'Explore this course.';
        $courseUrl = request()->fullUrl();

        $thumbnailImage = $course->metadataValues->where('field_name', 'banner_image')->value('field_value');
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
                        <h3>About Talent & Skill Course</h3>
                        <p>A revolutionary digital platform in field of education, committed on social empowerment and
                            enhancing learning capabilities</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="aboutCourses">
            <div class="container">
                <div class="row reverseRow">
                    <div class="col-xl-9 col-lg-7">
                        <nav aria-label="breadcrumb ">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Talent-Skills</li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $course->getSubCategory->name ?? ' ' }}</li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $course->course_name ?? 'No Course Name' }}</li>
                            </ol>
                        </nav>

                        <ul class="nav nav-tabs ViewTabs">
                            <li class="nav-item " role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#curriculumTab"
                                    type="button">Course Content</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " data-bs-toggle="tab" data-bs-target="#overviewTab"
                                    type="button">Overview</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#instructorTab"
                                    type="button">Instructor</button>
                            </li>
                            {{--  <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#audienceTab"
                                    type="button">Audience</button>
                            </li>  --}}
                        </ul>


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
                                                                                        aria-label="Close">
                                                                                    </button>
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
                                                        @else
                                                            <p class="text-muted">Oops! No videos are available for this
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
                                            ->where('field_name', 'course_overview')
                                            ->value('field_value');
                                        $whatWillYouLearn = $course->metadataValues
                                            ->where('field_name', 'what_you_will_learn')
                                            ->value('field_value');
                                        $requirements = $course->metadataValues
                                            ->where('field_name', 'requirements')
                                            ->value('field_value');
                                        $introVideo = $course->metadataValues
                                            ->where('field_name', 'intro_video')
                                            ->value('field_value');
                                        $instructorName = $course->metadataValues
                                            ->where('field_name', 'instructor_name')
                                            ->value('field_value');
                                        $instructorImage = $course->metadataValues
                                            ->where('field_name', 'instructor_image')
                                            ->value('field_value');
                                        $instructorDescription = $course->metadataValues
                                            ->where('field_name', 'instructor')
                                            ->value('field_value');
                                        // dd($instructorImage);
                                    @endphp
                                    <p>{{ $course->course_name ?? 'No Course Name' }},{!! $description !!}</p>

                                    <div class="lottieCourse">
                                        <lottie-player src="{{ asset('frontend/images/wave-lines.json') }}"
                                            autoplay="" loop="" style="width: 180px; height: 70px;"
                                            background="transparent"></lottie-player>
                                    </div>
                                </div>
                                {{--  <div class="">
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
                                </div>  --}}
                                <div class="willLearn">
                                    <div class="container">
                                        <div class="learnInner row">
                                            <div class="col-xl-7 col-md-12">
                                                <div class="section-heading m-0 text-start pb-2">
                                                    <h2 class="text-black"><span class="greenBorder"></span>
                                                        What You Will Learn</h2>
                                                </div>
                                                <p>{!! $whatWillYouLearn !!} </p>
                                            </div>
                                            {{-- <div class="col-xl-5 col-md-12">
                                                <div class="lottieDance">
                                                    <lottie-player src="{{ asset('frontend/images/indian-dance.json') }}"
                                                        autoplay="" loop=""
                                                        style="max-width: 220px; height: 100%;margin: auto;"
                                                        background="transparent"></lottie-player>
                                                </div>
                                            </div> --}}
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
                                            <p>{!! $requirements !!}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="tab-pane fade" id="instructorTab">
                                <div class="section-heading m-0 text-start pb-2">
                                    <h2 class="text-black"><span class="greenBorder"></span>
                                        {{ $instructorName }}</h2>
                                </div>
                                <div class="row">
                                    @if ($instructorImage)
                                        <div class="col-md-3">
                                            <img src="{{ Storage::url($instructorImage) }}" alt=""
                                                style="max-height:300px;">
                                        </div>
                                    @endif
                                    <div class="@if ($instructorImage) col-md-9 @else col-md-11 @endif">
                                        <p>{!! $instructorDescription !!}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{--  <div class="tab-pane fade" id="audienceTab">
                                <div class="section-heading m-0 text-start pb-2">
                                    <h2 class="text-black"><span class="greenBorder"></span>
                                        Audience</h2>
                                </div>
                                <p>1. children of age 3 yrs. And above</p>
                                <p>2. The course is for beginners. No knowledge of experience is required</p>
                                <p>3. Boys, girls and adults who wish to learn Kathak or brush their Kathak skills </p>
                            </div>  --}}
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
                            <figure>
                                @if ($introVideo)
                                    <video width="100%" height="240" controls controlsList="nodownload"
                                        oncontextmenu="return false;">
                                        <source src="{{ Storage::url($introVideo) }}">
                                    </video>
                                @else
                                    <img src="{{ asset('frontend/images/default-image.jpg') }}" alt="Default Image">
                                @endif
                                {{--  <a href="" class="playBtn"><img
                                        src="{{ asset('frontend/images/play-icon.svg') }}" alt=""
                                        width="35"></a>  --}}
                            </figure>
                            <div class="cartContent">
                                {{-- <small class="ml-2">Preview this course</small> --}}
                                <span>
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

                                    <b class="lineThr mb-my-3 me-2">{{ number_format($course->price) }}</b>
                                    ₹{{ number_format($finalPrice) }}

                                </span>
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

                                    {{-- <ul class="socialCart m-3">
                                    {!! $shareButtons !!}
                                </ul> --}}
                                    <ul class="socialCart m-3">
                                        {!! str_replace('<a ', '<a target="_blank" rel="noopener noreferrer" ', $shareButtons) !!}
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
                    @foreach ($nonAcadCourses as $noncourse)
                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                            <div class="coursesBox">
                                <figure class="position-relative">
                                    @php
                                        $bannerImage = $noncourse->metadataValues->firstWhere(
                                            'field_name',
                                            'banner_image',
                                        );
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
                                    <a href="{{ route('about-nonacadcourse', $noncourse->slug) }}">
                                        @if ($bannerImage)
                                            <img src="{{ Storage::url($bannerImage->field_value) }}" alt="Banner Image">
                                        @else
                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                alt="Default Image">
                                        @endif
                                    </a>
                                    @if ($noncourse->in_wishlist == 0)
                                        <button type="button" class=" bg-transparent border-0 p-0 wishlistButton"
                                            data-course-id="{{ $noncourse->id }}" data-item-id="{{ $noncourse->id }}"
                                            data-item-type="academic_course">
                                            <img src="{{ asset('frontend/images/heart-icon.svg') }}"
                                                class="wishlist-icon-{{ $noncourse->id }}" alt="Wishlist Icon"
                                                width="18">
                                        </button>
                                    @else
                                        <button type="button" class=" bg-transparent border-0 p-0 wishlistButton"
                                            data-course-id="{{ $noncourse->id }}" data-item-id="{{ $noncourse->id }}"
                                            data-item-type="academic_course">
                                            <img src="{{ asset('frontend/images/red-heart-icon.svg') }}"
                                                class="wishlist-icon-{{ $noncourse->id }}" alt="Wishlist Icon"
                                                width="18">
                                        </button>
                                    @endif
                                </figure>

                                <div class="d-flex gap-2 justify-content-between px-2">
                                    <b>Mittlearn</b>
                                    {{-- <div class="d-flex gap-3">
                                        @if ($noncourse->in_cart == 0)
                                            <button type="button"
                                                class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn" data-item-id=""
                                                data-item-type="academic_course" data-course-id="{{ $noncourse->id }}"
                                                data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                <img src="{{ asset('frontend/images/cart-icon.svg') }}"alt="Cart Icon"
                                                    class="cart-icon-{{ $noncourse->id }}" width="20">
                                            </button>
                                        @else
                                            <button type="button"
                                                class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn cartAdded"
                                                data-item-id="" data-item-type="academic_course"
                                                data-course-id="{{ $noncourse->id }}"
                                                data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                <img src="{{ asset('frontend/images/cart-icon-saved.svg') }}"alt="Cart Icon"
                                                    class="cart-icon-{{ $noncourse->id }}" width="20">
                                            </button>
                                        @endif
                                        <input type="hidden" name="cart_id" id="savedCartId" value="">
                                        <input type="hidden" name="user_id" id="userAuthId"
                                            value="{{ auth()->check() ? auth()->id() : null }}">
                                        <input type="hidden" name="wishlist_id" id="savedWishlistId" value="">
                                    </div> --}}
                                </div>
                                <a href="{{ route('about-nonacadcourse', $noncourse->slug) }}">
                                    <h3 class="px-2">{{ limit_words($noncourse->course_name ?? 'No Course Name', 3) }}
                                    </h3>
                                </a>
                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                    <span><img src="{{ asset('frontend/images/lessons-icon.svg') }}"
                                            alt="mittlearn-image" width="14">
                                        {{ $noncourse->totalChapters->count() }}
                                        Lessons</span>
                                    <span><img src="{{ asset('frontend/images/student-icon.svg') }}"
                                            alt="mittlearn-image" width="14">
                                        {{ $noncourse->getSubCategory->name }}</span>
                                </div>
                                <hr>
                                <div class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                    <div class="pricetag">
                                        <span>₹ {{ number_format($noncourse->price) }}</span>
                                        @php
                                            if ($noncourse->discount_type == 'flat') {
                                                $finalPrice = $noncourse->price - $noncourse->discount_value;
                                            } elseif ($noncourse->discount_type == 'percent') {
                                                $finalPrice =
                                                    $noncourse->price -
                                                    ($noncourse->discount_value / 100) * $noncourse->price;
                                            } else {
                                                $finalPrice = $noncourse->price;
                                            }
                                        @endphp
                                        ₹ {{ number_format($finalPrice) }}
                                    </div>
                                    <a href="{{ route('about-nonacadcourse', $noncourse->slug) }}"
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
            const copyText = document.getElementById("shareUrl");

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            document.execCommand("copy");

            const message = document.getElementById("copyMessage");
            message.style.display = "inline";

            setTimeout(() => {
                message.style.display = "none";
            }, 2000);
        }
    </script>
    <!-- V Added For Copy URL End --------------->
@endsection
