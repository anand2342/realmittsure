@extends('frontend.layouts.master')
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
                        <h3>About Olympiad Course</h3>
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
                    {{-- <div class="col-xl-9 col-lg-7"> --}}
                    <div>
                        <nav aria-label="breadcrumb ">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Academic - Olympiad</li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $classMeta && $classMeta->classInfo ? $classMeta->classInfo->name : '' }}
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $subjectMeta && $subjectMeta->subjectInfo ? $subjectMeta->subjectInfo->name : '' }}
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $course->course_name ?? 'No Course Name' }}</li>
                            </ol>
                        </nav>
                        <ul class="nav nav-tabs ViewTabs">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#curriculumTab"
                                    type="button">Curriculum</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link " data-bs-toggle="tab" data-bs-target="#overviewTab"
                                    type="button">Overview</button>
                            </li>
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
                                                                    data-bs-target="{{ $canViewAllVideos || $index < 3 ? '#coursePreview-' . $video->id : '#coursePurchageOlympiad' }}">

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
                                                                                    <button type="button" class="btn-close"
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
                                    <p>{{ $course->course_name ?? 'N/A' }}, {{ $description }}</p>
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
                    {{-- <div class="col-xl-3 col-lg-5">
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
                            @if ($thumbnailImage || $bookCoverImage)
                                <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage) }}"
                                    alt="course image">
                            @else
                                <img src="{{ asset('frontend/images/default-image.jpg') }}" alt="Default Image">
                            @endif
                            <div class="cartContent">
                                <span>
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
                                    <ul class="socialCart m-3">
                                        {!! $shareButtons !!}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade previewVdo" id="coursePurchageOlympiad" tabindex="-1"
        aria-labelledby="ccoursePurchageOlympiadLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0 border-0">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-normal " id="ccoursePurchageOlympiadLabel">
                        Course Preview</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="purchase-message">
                    <div class="message-content"
                        style="padding: 20px; color: #333; border-radius: 8px; font-size: 1.2em;">
                        🚀 <strong>Whoa there, Champ!</strong> You've reached the preview limit.
                        <br><br>
                        Want to unlock more brain-boosting videos like this?
                        Grab your <strong>Olympiad Book Pack</strong> now and power up your knowledge!
                        <br><br>
                        Smarter. Sharper. Supercharged.
                        <br>
                        <em>Because legends don't just watch... they learn!</em>
                    </div>
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
