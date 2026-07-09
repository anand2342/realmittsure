@extends('frontend.layouts.master')

@section('content')
    <div>
        <input type="hidden" id="session_id" name="session_id" value="">
        <section class="frontend-main-section">
            <div class="mainBanner homeBanner" id="homeBannerAcademic" style="display: none;">
                @if (session('success'))
                    <div class="alert alert-success small" style="margin-left: 105px;">{{ session('success') }}</div>
                @endif
                <div class="container">
                    <div class="bannerSlide bannerSlide1">
                        @if (isset($firstBannerAdditionalAcad))
                            @foreach ($firstBannerAdditionalAcad as $data)
                                <div>
                                    <div class="d-flex flex-wrap">
                                        <div class="bannerTxt">
                                            <h1>{{ $firstBanner->heading ?? '' }}
                                                <b> {{ $data->title ?? '' }}
                                                </b>
                                            </h1>
                                        </div>
                                        @if (isset($data->image))
                                            <div class="bannerImages">
                                                @if (isset($data->description))
                                                    <a href="{{ $data->description }}" target="_blank"> <img
                                                            src="{{ Storage::url('uploads/website-pages/academic/' . $data->image) }}"
                                                            alt="mittlearn-image"></a>
                                                @else
                                                    <img src="{{ Storage::url('uploads/website-pages/academic/' . $data->image) }}"
                                                        alt="mittlearn-image">
                                                @endif
                                            </div>
                                        @else
                                            <img src="{{ asset('frontend/images/default-image.jpg') }}" alt="Default Image">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="mainBanner homeBanner" id="homeBannerNonAcademic" style="display: none;">
                @if (session('success'))
                    <div class="alert alert-success small" style="margin-left: 105px;">{{ session('success') }}</div>
                @endif
                <div class="container">
                    <div class="bannerSlide bannerSlideNonAcadmic">
                        @if (isset($firstBannerAdditionalNonAcad))
                            @foreach ($firstBannerAdditionalNonAcad as $data)
                                <div>
                                    <div class="d-flex flex-wrap">
                                        <div class="bannerTxt">
                                            <h1>{{ $firstBanner->heading ?? '' }}
                                                <b> {{ $data->title ?? '' }}
                                                </b>
                                            </h1>
                                        </div>
                                        @if (isset($data->image))
                                            <div class="bannerImages">
                                                @if (isset($data->description))
                                                    <a href="{{ $data->description }}" target="_blank"> <img
                                                            src="{{ Storage::url('uploads/website-pages/academic/' . $data->image) }}"
                                                            alt="mittlearn-image"></a>
                                                @else
                                                    <img src="{{ Storage::url('uploads/website-pages/academic/' . $data->image) }}"
                                                        alt="mittlearn-image">
                                                @endif
                                            </div>
                                        @else
                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                alt="Default Image">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="pageTab nav nav-tabs" id="myTab" role="tablist">
                <button type="button"
                    class="tabLink nav-link  rounded-0 d-flex align-items-center justify-content-center flex-wrap"
                    id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic-tab-pane" role="tab"
                    aria-controls="academic-tab-pane" data-tab="academic" aria-selected="true">Academic Digital Content
                    <span class="explore-btn btn btn-sm mt-md-0 mt-1">Explore<lottie-player
                            src="{{ asset('frontend/images/right-blue.json') }}" loop="" autoplay=""
                            style="width: 20px;height: 20px;display:inline-block;margin-left:3px;vertical-align: middle"
                            background="transparent"></lottie-player></span>
                </button>

                <button type="button"
                    class="tabLink nav-link active rounded-0  d-flex align-items-center justify-content-center flex-wrap"
                    id="nonacademic-tab" data-bs-toggle="tab" data-bs-target="#nonacademic-tab-pane" role="tab"
                    aria-controls="nonacademic-tab-pane"data-tab="nonacademic" aria-selected="false">Talent / Skill <span
                        class="explore-btn btn btn-sm mt-md-0 mt-1">Explore<lottie-player
                            src="{{ asset('frontend/images/right-blue.json') }}" loop="" autoplay=""
                            style="width: 20px;height: 20px; display:inline-block;vertical-align: middle;margin-left:3px"
                            background="transparent"></lottie-player></span>
                </button>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade academic-page" id="academic-tab-pane" role="tabpanel"
                    aria-labelledby="academic-tab">
                    <div class="academic-page">
                        <div class="courseSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Comprehensive Academic Courses: Strengthen Your Child’s Foundation with Expert Video
                                        Lessons</h2>
                                    <p>Top-Rated Curriculum-Aligned Lessons for Every Grade<br>
                                        Explore our structured academic platform offering engaging video lectures from
                                        Pre-Primary to Class 10—covering every subject, designed to simplify concepts and
                                        boost performance.</p>

                                    <a href="{{ route('courses.listing', ['category_slug' => $academicCategory->slug]) }}"
                                        class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a>
                                </div>
                                <div class="mainCourseTab">
                                    <ul class="nav nav-tabs coursesTabs p-0 flex-wrap gap-1 gap-md-0">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#preSchool" data-bs-toggle="tab">
                                                <i> <img src="{{ asset('frontend/images/pre-school.svg') }}"> <img
                                                        class="hoverImg"
                                                        src="{{ asset('frontend/images/pre-school-white.svg') }}">
                                                </i> Pre - school</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#primarySchool" data-bs-toggle="tab"><i>
                                                    <img src="{{ asset('frontend/images/primary-school.svg') }}">
                                                    <img class="hoverImg"
                                                        src="{{ asset('frontend/images/primary-school-white.svg') }}">
                                                </i> Primary School</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#middleSchool" data-bs-toggle="tab"><i>
                                                    <img src="{{ asset('frontend/images/middle-school.svg') }}">
                                                    <img class="hoverImg"
                                                        src="{{ asset('frontend/images/middle-school-white.svg') }}">
                                                </i> Middle School</a>
                                        </li>
                                        {{-- <li class="nav-item">
                                            <a class="nav-link" href="#seniorSchool" data-bs-toggle="tab"><i>
                                                    <img src="{{ asset('frontend/images/senior-school.svg') }}">
                                                    <img class="hoverImg"
                                                        src="{{ asset('frontend/images/senior-school-white.svg') }}">
                                                </i> Senior School</a>
                                        </li> --}}
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    @php
                                        $categories = [
                                            'preSchool' => $preSchool,
                                            'primarySchool' => $primarySchool,
                                            'middleSchool' => $middleSchool,
                                            'seniorSchool' => $seniorSchool,
                                        ];
                                    @endphp

                                    @foreach ($categories as $tabId => $classGroup)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="{{ $tabId }}">
                                            <div class="row px-md-1">
                                                @php
                                                    $noCoursesAvailable = true;
                                                @endphp
                                                @if (isset($acadCourses) && $acadCourses->isNotEmpty())
                                                    @foreach ($acadCourses as $course)
                                                        @php
                                                            $hasClass = false;
                                                            if (isset($course->metadataValues)) {
                                                                foreach ($course->metadataValues as $metadataValue) {
                                                                    if (
                                                                        $metadataValue->field_name == 'class' &&
                                                                        isset($metadataValue->classInfo)
                                                                    ) {
                                                                        if (
                                                                            in_array(
                                                                                $metadataValue->classInfo->id,
                                                                                $classGroup,
                                                                            )
                                                                        ) {
                                                                            $hasClass = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        @if ($hasClass)
                                                            @php
                                                                $noCoursesAvailable = false;
                                                            @endphp
                                                            <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                                                <div class="coursesBox">
                                                                    <figure class="position-relative">
                                                                        @php
                                                                            $bookCoverImage = $course->metadataValues
                                                                                ->Where(
                                                                                    'field_name',
                                                                                    'book_cover_image',
                                                                                )
                                                                                ->value('field_value');
                                                                            $thumbnailImage = $course->metadataValues
                                                                                ->Where('field_name', 'thumbnail_image')
                                                                                ->value('field_value');
                                                                            $originalPrice = $course->price;

                                                                            // Discount calculation
                                                                            if ($course->discount_type == 'percent') {
                                                                                // Calculate the price after discount for percent type
                                                                                $discountedPrice =
                                                                                    $originalPrice -
                                                                                    $originalPrice *
                                                                                        ($course->discount_value / 100);
                                                                            } elseif (
                                                                                $course->discount_type == 'flat'
                                                                            ) {
                                                                                // Calculate the price after discount for flat type
                                                                                $discountedPrice =
                                                                                    $originalPrice -
                                                                                    $course->discount_value;
                                                                            } else {
                                                                                // If no discount type, keep the original price
                                                                                $discountedPrice = $originalPrice;
                                                                            }
                                                                        @endphp
                                                                        <a
                                                                            href="{{ route('about-acadcourse', $course->slug) }}">
                                                                            @if ($thumbnailImage || $bookCoverImage)
                                                                                <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage) }}"
                                                                                    alt="course image">
                                                                            @else
                                                                                <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                                    alt="Default Image">
                                                                            @endif
                                                                        </a>
                                                                        @if ($course->in_wishlist == 0)
                                                                            <button type="button"
                                                                                class=" bg-transparent border-0 p-0 wishlistButton"
                                                                                data-course-id="{{ $course->id }}"
                                                                                data-item-id="{{ $course->id }}"
                                                                                data-item-type="academic_course">
                                                                                <img src="{{ asset('frontend/images/heart-icon.svg') }}"
                                                                                    class="wishlist-icon-{{ $course->id }}"
                                                                                    alt="Wishlist Icon" width="18">
                                                                            </button>
                                                                        @else
                                                                            <button type="button"
                                                                                class=" bg-transparent border-0 p-0 wishlistButton"
                                                                                data-course-id="{{ $course->id }}"
                                                                                data-item-id="{{ $course->id }}"
                                                                                data-item-type="academic_course">
                                                                                <img src="{{ asset('frontend/images/red-heart-icon.svg') }}"
                                                                                    class="wishlist-icon-{{ $course->id }}"
                                                                                    alt="Wishlist Icon" width="18">
                                                                            </button>
                                                                        @endif
                                                                    </figure>

                                                                    <div class="d-flex gap-2 justify-content-between px-2">
                                                                        <b>Mittlearn</b>
                                                                        {{-- <div class="d-flex gap-3">

                                                                            @if ($course->in_cart == 0)
                                                                                <button type="button"
                                                                                    class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn"
                                                                                    data-item-id=""
                                                                                    data-item-type="academic_course"
                                                                                    data-course-id="{{ $course->id }}"
                                                                                    data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                                                    <img src="{{ asset('frontend/images/cart-icon.svg') }}"alt="Cart Icon"
                                                                                        class="cart-icon-{{ $course->id }}"
                                                                                        width="20">
                                                                                </button>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn cartAdded"
                                                                                    data-item-id=""
                                                                                    data-item-type="academic_course"
                                                                                    data-course-id="{{ $course->id }}"
                                                                                    data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                                                    <img src="{{ asset('frontend/images/cart-icon-saved.svg') }}"alt="Cart Icon"
                                                                                        class="cart-icon-{{ $course->id }}"
                                                                                        width="20">
                                                                                </button>
                                                                            @endif
                                                                            <input type="hidden" name="cart_id"
                                                                                id="savedCartId" value="">
                                                                            <input type="hidden" name="wishlist_id"
                                                                                id="savedWishlistId" value="">
                                                                            <input type="hidden" name="user_id"
                                                                                id="userAuthId"
                                                                                value="{{ auth()->check() ? auth()->id() : null }}">
                                                                        </div> --}}
                                                                    </div>

                                                                    <a
                                                                        href="{{ route('about-acadcourse', $course->slug) }}">
                                                                        <h3 class="px-2">
                                                                            {{ limit_words($course->course_name ?? 'No Course Name', 6) }}
                                                                        </h3>
                                                                    </a>

                                                                    <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                                                        @foreach ($course->metadataValues ?? [] as $metadataValue)
                                                                            @if ($metadataValue->field_name == 'subject' && $metadataValue->subjectInfo)
                                                                                <span>
                                                                                    <img src="{{ asset('frontend/images/student-icon.svg') }}"
                                                                                        alt="mittlearn-image"
                                                                                        width="14">
                                                                                    Sub:
                                                                                    {{ $metadataValue->subjectInfo->name ?? '' }}
                                                                                </span>
                                                                            @elseif ($metadataValue->field_name == 'class' && $metadataValue->classInfo)
                                                                                <span>
                                                                                    <img src="{{ asset('frontend/images/student-icon.svg') }}"
                                                                                        alt="mittlearn-image"
                                                                                        width="14">
                                                                                    {{ $metadataValue->classInfo->name ?? '' }}
                                                                                </span>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>

                                                                    <hr>
                                                                    <div
                                                                        class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                                                        <div class="pricetag">
                                                                            <span>₹
                                                                                {{ number_format($course->price) ?? '' }}</span>
                                                                            @php
                                                                                $finalPrice = $course->price;
                                                                                if ($course->discount_type == 'flat') {
                                                                                    $finalPrice -=
                                                                                        $course->discount_value;
                                                                                } elseif (
                                                                                    $course->discount_type ==
                                                                                    'percentage'
                                                                                ) {
                                                                                    $finalPrice -=
                                                                                        ($course->discount_value /
                                                                                            100) *
                                                                                        $course->price;
                                                                                }
                                                                            @endphp
                                                                            ₹ {{ number_format($finalPrice) ?? '' }}
                                                                        </div>
                                                                        <a href="{{ route('about-acadcourse', $course->slug) }}"
                                                                            class="btn btn-primary-gradient rounded-1">
                                                                            Know more
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if ($noCoursesAvailable)
                                                    <div class="col-12">
                                                        <p class="text-center text-muted">Oops! There are no courses
                                                            available for the selected category yet. But don’t
                                                            worry—exciting courses are coming your way soon! </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        {{-- <div class="launchedSection py-5">
                            <div class="exclusiveTag">
                                <lottie-player src="{{ asset('frontend/images/exclusive-tag-red.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay></lottie-player>
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class=""><span class="greenBorder"></span>
                                        Newly Launched</h2>
                                    <p>Discover What's New at Mittlearn!</p>
                                    <p>Learn from expert instructors who make learning exciting with creativity and hands-on teaching.</p>
                                </div>
                                <div class="exploreMain">
                                    <div class="slider slider-explore">
                                        @if (isset($acadCoursesLatest) && $acadCoursesLatest->isNotEmpty())
                                            @foreach ($acadCoursesLatest as $course)
                                                @php
                                                    $description = $course->metadataValues
                                                        ->where('field_name', 'description')
                                                        ->value('field_value');
                                                    $thumbnailImage = $course->metadataValues
                                                        ->where('field_name', 'thumbnail_image')
                                                        ->value('field_value');
                                                    $bookCoverImage = $course->metadataValues
                                                        ->where('field_name', 'book_cover_image')
                                                        ->value('field_value');
                                                    $class =
                                                        optional(
                                                            $course->metadataValues
                                                                ->where('field_name', 'class')
                                                                ->first(),
                                                        )->classInfo->name ?? null;
                                                @endphp
                                                <div class="sliderContent">
                                                    <div class="sliderImgtxt">
                                                        <a href="{{ route('about-acadcourse', $course->slug) }}">
                                                            <h3>{{ $course->course_name }} ( {{ $class }})</h3>
                                                        </a>
                                                        <p>{{ $description ?? 'No description available' }}</p>
                                                    </div>
                                                    <div class="sliderImg">
                                                        <figure>
                                                            @if ($thumbnailImage || $bookCoverImage)
                                                                <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage) }}"
                                                                    alt="course image">
                                                            @else
                                                                <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                    alt="Default Image">
                                                            @endif
                                                        </figure>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No courses available for this category.</p>
                                        @endif
                                    </div>
                                    <div class="slider slider-explore-thumb" style="height: 211.38;">
                                        @foreach ($acadCoursesLatest as $course)
                                            @php
                                                $class =
                                                    optional(
                                                        $course->metadataValues->where('field_name', 'class')->first(),
                                                    )->classInfo->name ?? null;

                                                $thumbnailImage = $course->metadataValues
                                                    ->where('field_name', 'thumbnail_image')
                                                    ->value('field_value');
                                                $bookCoverImage = $course->metadataValues
                                                    ->where('field_name', 'book_cover_image')
                                                    ->value('field_value');
                                            @endphp
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure>
                                                        @if ($thumbnailImage || $bookCoverImage)
                                                            <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage) }}"
                                                                alt="course image">
                                                        @else
                                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    </figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>{{ $course->course_name }} <b>Grade:
                                                                {{ $class }}</b></span>
                                                        <figure>
                                                            <a href="{{ route('about-acadcourse', $course->slug) }}"><img
                                                                    src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                    alt="mittlearn-image" width="15"></a>
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- @include('frontend.plans') --}}

                        <div class="studentSayAbout mb-0">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        {{ $testimonialBanner->heading_1 ?? '' }}</h2>
                                    <p class="text-white">{{ $testimonialBanner->sub_heading_1 ?? '' }}</p>
                                </div>
                                <div class="aboutSliderSec position-relative">
                                    <div class="topImg">
                                        <lottie-player src="{{ asset('frontend/images/customer-response.json') }}"
                                            background="transparent" speed="1" style="width: 250px; height: 250px;"
                                            loop autoplay></lottie-player>
                                    </div>
                                    <div class="sayAboutSlider">
                                        @if (isset($testimonial))
                                            @foreach ($testimonial as $data)
                                                <div class="item">
                                                    <div class="sayAbout">
                                                        <p>{{ $data->comment }}</p>
                                                        <div class="sayProfile">
                                                            <figure>
                                                                @if (isset($data->image))
                                                                    <img src="{{ Storage::url('uploads/testimonial-profile/' . $data->image) }}"
                                                                        alt="Profile Image">
                                                                @else
                                                                    <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                        alt="Default Image">
                                                                @endif
                                                            </figure>
                                                            <strong><b>{{ $data->name ?? '' }}</b>
                                                                {{ $data->designation ?? '' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="advantagesSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Our Association brings Advantages to Schools, Students, Parents, and
                                        Individuals
                                    </h2>
                                </div>

                                <div class="row flex-row-reverse">
                                    <div class="col-md-6 position-relative mb-4 mb-md-0">
                                        <ul class="nav nav-tabs benefitsTab">
                                            <li class="nav-item">
                                                <button class="nav-link active" data-bs-toggle="tab"
                                                    data-bs-target="#schoolTab1" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/schools-icon.svg') }}"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>
                                                    Schools
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#studentTab1" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/student-icon1.svg') }}"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Students
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#parentTab1" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/parents-icon.svg') }}"
                                                            alt="mittlearn-image" width="40" height="25">
                                                    </figure>Parents
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#individualsTab1" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/individuals-icon.svg') }}"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Individuals
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="arrowImg">
                                            <lottie-player src="{{ asset('frontend/images/arrow.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 90px; height: 90px;" loop autoplay></lottie-player>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="schoolTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Schools</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Expanded Curriculum :</strong>
                                                            <p>Schools can diversify their offerings by
                                                                incorporating
                                                                supplementary
                                                                courses,
                                                                enriching the educational experience and catering to
                                                                a
                                                                broader
                                                                range of
                                                                student interests</p>
                                                        </li>
                                                        <li>
                                                            <strong>Enhanced Reputation:</strong>
                                                            <p>Providing students with opportunities to learn
                                                                additional
                                                                skills
                                                                and earn
                                                                certifications can enhance the school's reputation
                                                                and
                                                                attract a
                                                                more
                                                                diverse student body.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Cost-Efficient Training:</strong>
                                                            <p>Schools can leverage existing talents within their
                                                                faculty or
                                                                tap
                                                                into
                                                                external expertise to provide specialized training
                                                                without
                                                                significant
                                                                additional costs.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="studentTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Students</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Flexible Learning Environment:</strong>
                                                            <p>Students can explore and learn new talents from the
                                                                comfort
                                                                of
                                                                their
                                                                homes,
                                                                adapting their study schedule to their preferences.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Skill Enhancement:</strong>
                                                            <p>Existing skills can be taken to the next level,
                                                                allowing
                                                                students
                                                                to
                                                                continually improve and excel in their chosen areas
                                                                of
                                                                interest.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Certification and Recognition:</strong>
                                                            <p>Completion of courses with add-on quizzes and
                                                                interactive
                                                                worksheets
                                                                leads to
                                                                valuable certifications, showcasing their expertise
                                                                to
                                                                potential
                                                                employers
                                                                or educational institutions.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="parentTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Parents</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Cost-Effective:</strong>
                                                            <p>Parents can save on commuting, material, and possibly
                                                                tuition
                                                                fees by
                                                                opting
                                                                for online courses, making quality education more
                                                                affordable.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Supervised Learning:</strong>
                                                            <p>Parents can monitor their child's progress and
                                                                engagement
                                                                in
                                                                the
                                                                courses,
                                                                ensuring a productive learning experience and
                                                                providing
                                                                support
                                                                as
                                                                needed.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Diverse Learning Opportunities:</strong>
                                                            <p>Online courses offer a wider range of subjects and
                                                                skills,
                                                                enabling
                                                                parents
                                                                to help their children explore various interests and
                                                                aptitudes
                                                                beyond
                                                                the
                                                                conventional school curriculum.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="individualsTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Individuals</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Holistic Skill Development:</strong>
                                                            <p>Individuals can broaden their skill set by accessing
                                                                courses
                                                                that
                                                                are not
                                                                part of their formal education, enabling
                                                                well-rounded
                                                                personal
                                                                and
                                                                professional growth.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Tailored Learning:</strong>
                                                            <p>Online courses allow individuals to focus on specific
                                                                areas
                                                                of
                                                                interest
                                                                or
                                                                skills they want to acquire, tailoring their
                                                                learning
                                                                journey to
                                                                match
                                                                their
                                                                unique aspirations.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Career Advancement:</strong>
                                                            <p>Earning certifications from supplementary courses can
                                                                enhance
                                                                an
                                                                individual's
                                                                resume and open doors to new career opportunities
                                                                for
                                                                advancement within
                                                                their current field.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Commented in Academic section active only on talent skills  --}}
                        {{-- <div class="meetSection py-5">
                            <div class="meetLottie">
                                <lottie-player src="{{ asset('frontend/images/double-lines.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 250px; height: 250px;margin: auto;opacity: .7;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        {{ $instructorBanner->instructor_title ?? '' }}</h2>
                                    <p> {{ $instructorBanner->instructor_description ?? '' }}</p>
                                </div>

                                <div class="meetMain">
                                    <div class="slider meetSlider">
                                        @foreach ($instructorList as $data)
                                            <div>
                                                <div class="meetSliderContent">
                                                    <div class="meetslidertxt">
                                                        <div class="d-md-flex flex-wrap gap-3">
                                                            <div>
                                                                <span>{{ $data->user->name ?? '' }}</span>
                                                                <b>{{ $data->user->userAdditionalDetail->designation ?? '' }}</b>
                                                            </div>
                                                        </div>
                                                        <p>{{ $data->user->userAdditionalDetail->about ?? '' }}</p>
                                                    </div>
                                                    <div class="meetprofileImg">
                                                        <figure>
                                                            @if (!empty($data->user->image))
                                                                <img src="{{ Storage::url('uploads/user/profile_image/' . $data->user->image) }}"
                                                                    alt="Instructor Image">
                                                            @else
                                                                <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                    alt="Default Image">
                                                            @endif
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="slider meetSliderThumb">
                                        @foreach ($instructorList as $data)
                                            <div>
                                                <div class="meetContent">
                                                    <figure>
                                                        <img src="{{ !empty($data->user->image) ? Storage::url('uploads/user/profile_image/' . $data->user->image) : asset('frontend/images/default-image.jpg') }}"
                                                            alt="Instructor Image">
                                                    </figure>
                                                    <span>{{ $data->user->name ?? '' }}</span>
                                                    <b>{{ $data->user->designation ?? '' }}</b>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div> --}}
                        {{-- Commented As Document Provided  --}}
                        {{-- <div class="learnSection py-5 mt-0">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Different ways to learn on the platform</h2>
                                    <p>Learn as per your own suitability for online recorded lectures, trainer led online
                                        sessions,
                                        group
                                        sessions and one-on-one sessions</p>
                                </div>
                                <div class="sliderMain">
                                    <div class="slider slider-content">
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img src="{{ asset('frontend/images/sliderImg1.jpg') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Standalone Lecture
                                                </h3>
                                                <p>Learn at your own pace with self explanatory video courses</p>
                                            </div>
                                        </div>
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img src="{{ asset('frontend/images/sliderImg2.jpg') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Recorded Lectures with Mentoring
                                                </h3>
                                                <p>Learn with self explanatory videos and assisted mentorship</p>
                                            </div>
                                        </div>
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img src="{{ asset('frontend/images/sliderImg3.jpg') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Group session
                                                </h3>
                                                <p>Group based sessions to assist learning</p>
                                            </div>
                                        </div>
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img src="{{ asset('frontend/images/sliderImg4.jpg') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    One on one live session
                                                </h3>
                                                <p>one-one based session for a personalized training experience</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider slider-thumb">
                                        <div>
                                            <figure><img src="{{ asset('frontend/images/sliderImg1.jpg') }}"
                                                    alt="mittlearn-image">
                                            </figure>
                                        </div>
                                        <div>
                                            <figure><img src="{{ asset('frontend/images/sliderImg2.jpg') }}"
                                                    alt="mittlearn-image">
                                            </figure>
                                        </div>
                                        <div>
                                            <figure><img src="{{ asset('frontend/images/sliderImg3.jpg') }}"
                                                    alt="mittlearn-image">
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{--  <div class="skillsSection">
                            <div class="skillsDots">
                                <img src="{{ asset('frontend/images/dotsImg.svg') }}" alt="mittlearn-image" width="260">
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Start learning new skills</h2>
                                    <p>Below are some of the quick categories to <br>get started</p>
                                </div>
                                <div class="row">
                                    <div class="col-6 col-md-4">
                                        <div class="skillsBox skillsBox1">
                                            <figure>
                                                <lottie-player
                                                    src="{{ asset('frontend/images/girl-building-sand-castle.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay>
                                                </lottie-player>
                                            </figure>
                                            <h3>Pre School</h3>
                                            <p>Nursery, K1 - K2</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="skillsBox skillsBox2">
                                            <figure>
                                                <lottie-player
                                                    src="{{ asset('frontend/images/girl-solving-a-puzzle.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay>
                                                </lottie-player>
                                            </figure>
                                            <h3>Primary School</h3>
                                            <p>Grade 01 - 05</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-4">
                                        <div class="skillsBox skillsBox3">
                                            <figure>
                                                <lottie-player src="{{ asset('frontend/images/boy-reading-a-book.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay>
                                                </lottie-player>
                                            </figure>
                                            <h3>Middle School</h3>
                                            <p>Grade 06 - 08</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="skillsBox skillsBox4">
                                            <figure>
                                                <lottie-player src="{{ asset('frontend/images/girl-doing-homework.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay>
                                                </lottie-player>
                                            </figure>
                                            <h3>Senior School</h3>
                                            <p>Grade 09 - 12</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>  --}}
                        <style>
                            .featureContent .featureTxtbox {
                                width: 100% !important;
                            }
                        </style>
                        <div class="featuresSection mt-2">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        {{ $coreFeatureBanner->core_title }}</h2>
                                    <p class="text-white">{{ $coreFeatureBanner->core_heading }}</p>
                                </div>
                                <div class="CircleLottie">
                                    <lottie-player src="{{ asset('frontend/images/Loader-animation.json') }}"
                                        background="transparent" speed="1"
                                        style="width: 250px; height: 250px;margin: auto;opacity: .2;" loop
                                        autoplay></lottie-player>
                                </div>
                                <div class="shapeLottie">
                                    <lottie-player src="{{ asset('frontend/images/data.json') }}"
                                        background="transparent" speed="1"
                                        style="width: 550px; height: 550px;margin: auto;" loop autoplay></lottie-player>
                                </div>
                                <div class="featureGroup">
                                    <div class="row">
                                        <div class="col-md-7 pe-md-0 mb-4 mb-md-0">
                                            <div class="featureContent featuresecContent slick-carousel">
                                                @if (isset($coreFeatureBannerAdditional))
                                                    @foreach ($coreFeatureBannerAdditional->chunk(3) as $chunk)
                                                        <div class="d-flex gap-2 mb-2">
                                                            @foreach ($chunk as $data)
                                                                <div class="featureTxtbox">
                                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                                        @if (isset($data->image))
                                                                            <img src="{{ Storage::url('uploads/website-pages/core_icon_image/' . $data->image) }}"
                                                                                alt="Icon Image">
                                                                        @else
                                                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                                alt="Default Image">
                                                                        @endif
                                                                        {{ $data->title ?? '' }}
                                                                    </h3>
                                                                    <p>{{ $data->description ?? '' }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="featureImg">
                                                <div class="row px-1">
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="{{ asset('frontend/images/feature-img1.jpg') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="landscapeImg">
                                                            <img src="{{ asset('frontend/images/feature-img2.jpg') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                        <figure class="landscapeImg mb-0">
                                                            <img src="{{ asset('frontend/images/feature-img3.jpg') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="{{ asset('frontend/images/feature-img4.jpg') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="exclusiveBlog">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Exclusive Blog</h2>
                                    <p>Get to know what's trending</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 pe-md-4">
                                        <div class="row">
                                            @if (isset($exclusiveBlogs))
                                                @foreach ($exclusiveBlogs as $index => $data)
                                                    @php
                                                        $mainCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            null,
                                                        );
                                                        $subCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            '!=',
                                                            null,
                                                        );
                                                    @endphp

                                                    <div class="col-md-6 mb-3 mb-md-0">
                                                        <div class="blogContent h-100">
                                                            <figure class="blogImg">
                                                                <a
                                                                    href="{{ route('blog.details', ['slug' => $data->slug]) }}">
                                                                    @if (isset($data->blogsMedia->attachment_file))
                                                                        <img src="{{ Storage::url('uploads/blog/' . $data->blogsMedia->attachment_file) }}"
                                                                            alt="mittlearn-image">
                                                                    @else
                                                                        <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                            alt="Default Image">
                                                                    @endif
                                                                </a>
                                                            </figure>

                                                            <span>
                                                                {{ $mainCategory?->name ?? 'Uncategorized' }}
                                                                @if ($subCategory)
                                                                    &rarr; {{ $subCategory->name }}
                                                                @endif
                                                            </span>

                                                            <h4>
                                                                <a
                                                                    href="{{ route('blog.details', ['slug' => $data->slug]) }}">
                                                                    {{ $data->title }}
                                                                </a>
                                                            </h4>

                                                            <p>{!! $data->meta_description ?? '' !!}</p>

                                                            <div class="blogProfile">
                                                                <figure>
                                                                    <img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                                        alt="mittlearn-image">
                                                                </figure>
                                                                <strong>
                                                                    <b>Mittlearn</b> {{ $data->formatted_date ?? '' }}
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-5 border-start ps-md-4">
                                        <ul class="recentBlogList">
                                            @if (isset($exclusiveBlogList))
                                                @foreach ($exclusiveBlogList as $data)
                                                    <li>
                                                        <strong>{{ $data->title ?? '' }}</strong>
                                                        <a href="{{ route('blog.details', ['slug' => $data->slug]) }}">Learn
                                                            More</a>
                                                    </li>
                                                @endforeach
                                            @endif
                                            <div class="text-end">
                                                <a href="{{ route('blogs') }}" class="btn btn-success rounded-1 fs-7">
                                                    View All
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bottomToggleBtn" style="display: none;">
                        <span>Switch to Talent Content</span>
                        <div class="toggleBtn">
                            <input type="checkbox" id="switchnonacademic" />
                            <label for="switchnonacademic">Toggle</label>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade show active  nonacademic-page" id="nonacademic-tab-pane" role="tabpanel"
                    aria-labelledby="nonacademic-tab">
                    <div class="nonacademic-page">
                        {{-- Commented As Document Provided  --}}
                        <div class="courseSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Skill-Based Talent Courses: Unleash Creativity and Explore Hidden Potential
                                    </h2>
                                    <p>Top-Rated Programs to Inspire and Engage Young Minds<br>Discover our engaging
                                        talent-based courses—from dance and music to coding and
                                        storytelling—crafted to build confidence and creativity in every child.</p>

                                    <a href="{{ route('courses.listing', ['category_slug' => $nonAcademicCategory->slug]) }}"
                                        class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a>

                                    <!-- <a href="{{ route('courses.listing', ['category_slug' => $academicCategory->slug]) }}" class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a> -->


                                </div>


                                <div class="mainCourseTab ">
                                    <ul
                                        class="nav nav-tabs coursesTabs pb-0 flex-wrap gap-md-2 gap-1 justify-content-md-center justify-content-start">
                                        @foreach ($nonAcadSubCategory as $index => $category)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                                    href="#{{ Str::slug($category->name) }}-{{ $category->id }}"
                                                    data-bs-toggle="tab">
                                                    <i>
                                                        <img src="{{ $category->icon ? Storage::url('uploads/categories/icon/' . $category->icon) : asset('frontend/images/dance-white.svg') }}"
                                                            alt="icon">
                                                        <img class="hoverImg"
                                                            src="{{ $category->icon ? Storage::url('uploads/categories/icon/' . $category->icon) : asset('frontend/images/dance-white.svg') }}"
                                                            alt="icon">
                                                    </i>
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    @foreach ($nonAcadSubCategory as $category)
                                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                            id="{{ Str::slug($category->name) }}-{{ $category->id }}">
                                            <div class="row px-md-1">
                                                @php
                                                    $hasCourses = false; // Flag to check if there are courses for this category
                                                @endphp

                                                @foreach ($nonAcadCourses as $course)
                                                    @if ($category->name === optional($course->getSubCategory)->name)
                                                        @php $hasCourses = true; @endphp
                                                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                                            <div class="coursesBox nonacadBx">
                                                                <figure class="position-relative">
                                                                    @php
                                                                        $bannerImage = $course->metadataValues->firstWhere(
                                                                            'field_name',
                                                                            'banner_image',
                                                                        );
                                                                        $originalPrice = $course->price;
                                                                        // Discount calculation
                                                                        if ($course->discount_type == 'percent') {
                                                                            // Calculate the price after discount for percent type
                                                                            $discountedPrice =
                                                                                $originalPrice -
                                                                                $originalPrice *
                                                                                    ($course->discount_value / 100);
                                                                        } elseif ($course->discount_type == 'flat') {
                                                                            // Calculate the price after discount for flat type
                                                                            $discountedPrice =
                                                                                $originalPrice -
                                                                                $course->discount_value;
                                                                        } else {
                                                                            // If no discount type, keep the original price
                                                                            $discountedPrice = $originalPrice;
                                                                        }
                                                                    @endphp
                                                                    <a
                                                                        href="{{ route('about-nonacadcourse', $course->slug) }}">
                                                                        @if ($bannerImage)
                                                                            <img src="{{ Storage::url($bannerImage->field_value) }}"
                                                                                alt="Banner Image">
                                                                        @else
                                                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                                alt="Default Image">
                                                                        @endif
                                                                    </a>
                                                                    @if ($course->in_wishlist == 0)
                                                                        <button type="button"
                                                                            class=" bg-transparent border-0 p-0 wishlistButton"
                                                                            data-course-id="{{ $course->id }}"
                                                                            data-item-id="{{ $course->id }}"
                                                                            data-item-type="academic_course">
                                                                            <img src="{{ asset('frontend/images/heart-icon.svg') }}"
                                                                                class="wishlist-icon-{{ $course->id }}"
                                                                                alt="Wishlist Icon" width="18">
                                                                        </button>
                                                                    @else
                                                                        <button type="button"
                                                                            class=" bg-transparent border-0 p-0 wishlistButton"
                                                                            data-course-id="{{ $course->id }}"
                                                                            data-item-id="{{ $course->id }}"
                                                                            data-item-type="academic_course">
                                                                            <img src="{{ asset('frontend/images/red-heart-icon.svg') }}"
                                                                                class="wishlist-icon-{{ $course->id }}"
                                                                                alt="Wishlist Icon" width="18">
                                                                        </button>
                                                                    @endif
                                                                </figure>

                                                                <div class="d-flex gap-2 justify-content-between px-2">
                                                                    <b>Mittlearn</b>
                                                                    {{-- <div class="d-flex gap-3">
                                                                        @if ($course->in_cart == 0)
                                                                            <button type="button"
                                                                                class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn"
                                                                                data-item-id=""
                                                                                data-item-type="academic_course"
                                                                                data-course-id="{{ $course->id }}"
                                                                                data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                                                <img src="{{ asset('frontend/images/cart-icon.svg') }}"alt="Cart Icon"
                                                                                    class="cart-icon-{{ $course->id }}"
                                                                                    width="20">
                                                                            </button>
                                                                        @else
                                                                            <button type="button"
                                                                                class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn cartAdded"
                                                                                data-item-id=""
                                                                                data-item-type="academic_course"
                                                                                data-course-id="{{ $course->id }}"
                                                                                data-course-price="{{ number_format($discountedPrice, 2) }}">
                                                                                <img src="{{ asset('frontend/images/cart-icon-saved.svg') }}"alt="Cart Icon"
                                                                                    class="cart-icon-{{ $course->id }}"
                                                                                    width="20">
                                                                            </button>
                                                                        @endif
                                                                    </div> --}}
                                                                </div>
                                                                <a
                                                                    href="{{ route('about-nonacadcourse', $course->slug) }}">
                                                                    <h3 class="px-2">
                                                                        {{ limit_words($course->course_name ?? 'No Course Name', 6) }}
                                                                    </h3>
                                                                </a>
                                                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                                                    <span><img
                                                                            src="{{ asset('frontend/images/lessons-icon.svg') }}"
                                                                            alt="mittlearn-image" width="14">
                                                                        {{ $course->totalChapters->count() }}
                                                                        Lessons</span>
                                                                    <span><img
                                                                            src="{{ asset('frontend/images/student-icon.svg') }}"
                                                                            alt="mittlearn-image" width="14">
                                                                        {{ $course->getSubCategory->name }}</span>
                                                                </div>
                                                                <hr>
                                                                <div
                                                                    class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                                                    <div class="pricetag">
                                                                        <span>₹
                                                                            {{ number_format($course->price) ?? '' }}</span>
                                                                        @php
                                                                            if ($course->discount_type == 'flat') {
                                                                                $finalPrice =
                                                                                    $course->price -
                                                                                    $course->discount_value;
                                                                            } elseif (
                                                                                $course->discount_type == 'percent'
                                                                            ) {
                                                                                $finalPrice =
                                                                                    $course->price -
                                                                                    ($course->discount_value / 100) *
                                                                                        $course->price;
                                                                            } else {
                                                                                $finalPrice = $course->price;
                                                                            }
                                                                        @endphp
                                                                        ₹ {{ number_format($finalPrice) ?? '' }}
                                                                    </div>
                                                                    <a href="{{ route('about-nonacadcourse', $course->slug) }}"
                                                                        class="btn btn-primary-gradient rounded-1">Know
                                                                        more</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                @if (!$hasCourses)
                                                    <div class="col-12">
                                                        <p class="text-center text-muted">
                                                            Oops! There are no courses available for the selected category
                                                            yet.
                                                            But don’t worry—exciting courses are coming your way soon! </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="launchedSection py-5">
                            <div class="exclusiveTag">
                                <lottie-player src="{{ asset('frontend/images/exclusive-tag-red.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay></lottie-player>
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class=""><span class="greenBorder"></span>
                                        Newly Launched</h2>
                                    <p>Discover What's New at Mittlearn!</p>
                                    <p>Learn from expert instructors who make learning exciting with creativity and hands-on
                                        teaching.</p>
                                </div>
                                <div class="exploreMain">
                                    <div class="slider slider-explore">
                                        @if (isset($nonAcadCoursesLatest) && $nonAcadCoursesLatest->isNotEmpty())
                                            @foreach ($nonAcadCoursesLatest as $course)
                                                @php
                                                    $description = $course->metadataValues
                                                        ->where('field_name', 'course_overview')
                                                        ->value('field_value');
                                                    $bannerImage = $course->metadataValues
                                                        ->where('field_name', 'banner_image')
                                                        ->value('field_value');

                                                @endphp
                                                <a href="{{ route('about-nonacadcourse', $course->slug) }}"
                                                    class="text-black">
                                                    <div class="sliderContent">
                                                        <div class="sliderImgtxt">
                                                            <h3>{{ $course->course_name }}</h3>

                                                            <p>{{ Str::limit(strip_tags($description), 500, '...') }}</p>
                                                        </div>
                                                        <div class="sliderImg">
                                                            <figure>
                                                                <img src="{{ $bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-image.jpg') }}"
                                                                    alt="course image">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        @else
                                            <p>No courses available for this category.</p>
                                        @endif
                                    </div>
                                    <div class="slider slider-explore-thumb" style="height: 211.38;">
                                        @foreach ($nonAcadCoursesLatest as $course)
                                            @php

                                                $bannerImage = $course->metadataValues
                                                    ->where('field_name', 'banner_image')
                                                    ->value('field_value');
                                            @endphp
                                            <div>
                                                <a href="{{ route('about-nonacadcourse', $course->slug) }}">
                                                    <div class="exploreconTent">
                                                        <figure>
                                                            <img src="{{ $bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-image.jpg') }}"
                                                                alt="course image">
                                                        </figure>
                                                        <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                            <span>{{ $course->course_name }}</span>

                                                            <figure>
                                                                <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                    alt="mittlearn-image" width="15">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- @include('frontend.plans') --}}

                        <div class="studentSayAbout mb-0">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        {{ $testimonialBanner->heading_1 ?? '' }}</h2>
                                    <p class="text-white">{{ $testimonialBanner->sub_heading_1 ?? '' }}
                                    </p>
                                </div>
                                <div class="aboutSliderSec position-relative">
                                    <div class="topImg">
                                        <lottie-player src="{{ asset('frontend/images/customer-response.json') }}"
                                            background="transparent" speed="1" style="width: 250px; height: 250px;"
                                            loop autoplay></lottie-player>
                                    </div>
                                    <div class="sayAboutSlider">
                                        @if (isset($testimonial))
                                            @foreach ($testimonial as $data)
                                                <div class="item">
                                                    <div class="sayAbout">
                                                        <p>{{ $data->comment ?? '' }}</p>
                                                        <div class="sayProfile">
                                                            <figure>
                                                                @if (isset($data->image))
                                                                    <img src="{{ Storage::url('uploads/testimonial-profile/' . $data->image) }}"
                                                                        alt="Profile Image">
                                                                @else
                                                                    <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                        alt="Default Image">
                                                                @endif
                                                            </figure>
                                                            <strong><b>{{ $data->name ?? '' }}</b>
                                                                {{ $data->designation ?? '' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="meetSection py-5">
                            <div class="meetLottie">
                                <lottie-player src="{{ asset('frontend/images/double-lines.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 250px; height: 250px;margin: auto;opacity: .7;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        {{ $instructorBanner->instructor_title ?? '' }}</h2>
                                    <p> {{ $instructorBanner->instructor_description ?? '' }}</p>
                                </div>
                                <div class="meetMain">
                                    <div class="slider meetSlider">
                                        @foreach ($instructorList as $data)
                                            <div>
                                                <div class="meetSliderContent">
                                                    <div class="meetslidertxt">
                                                        <div class="d-md-flex flex-wrap gap-3">
                                                            <div>
                                                                <span>{{ $data->user->name ?? '' }}</span>
                                                                <b>{{ $data->user->userAdditionalDetail->designation ?? '' }}</b>
                                                            </div>
                                                        </div>
                                                        <p>{{ $data->user->userAdditionalDetail->about ?? '' }}
                                                        </p>

                                                        {{-- <div class="d-flex align-items-center gap-3">
                                                            <a
                                                                href="{{ $data->user->userAdditionalDetail->facebook ?? '#' }}"><img
                                                                    src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                                    alt="Facebook"></a>
                                                            <a
                                                                href="{{ $data->user->userAdditionalDetail->linkedin ?? '#' }}"><img
                                                                    src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                                    alt="LinkedIn"></a>
                                                            <a
                                                                href="{{ $data->user->userAdditionalDetail->twitter ?? '#' }}"><img
                                                                    src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                                    alt="Twitter"></a>
                                                        </div> --}}
                                                    </div>
                                                    <div class="meetprofileImg">
                                                        <figure>
                                                            @if (!empty($data->user->image))
                                                                <img src="{{ Storage::url('uploads/user/profile_image/' . $data->user->image) }}"
                                                                    alt="Instructor Image">
                                                            @else
                                                                <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                    alt="Default Image">
                                                            @endif
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="slider meetSliderThumb">
                                        @foreach ($instructorList as $data)
                                            <div>
                                                <div class="meetContent">
                                                    <figure>
                                                        <img src="{{ !empty($data->user->image) ? Storage::url('uploads/user/profile_image/' . $data->user->image) : asset('frontend/images/default-image.jpg') }}"
                                                            alt="Instructor Image">
                                                    </figure>
                                                    <span>{{ $data->user->name ?? '' }}</span>
                                                    <b>{{ $data->user->userAdditionalDetail->designation ?? '' }}</b>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>


                            </div>
                        </div>
                        {{-- <div class="learnSection py-5 mt-0">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Different ways to learn on the platform</h2>
                                    <p>Learn as per your own suitability for online recorded lectures, trainer led
                                        online
                                        sessions, group
                                        sessions and one-on-one sessions</p>
                                </div>
                                <div class="sliderMain">
                                    <div class="slider slider-content">
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img
                                                        src="{{ asset('frontend/images/slider-differentImg1.png') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Standalone Lecture
                                                </h3>
                                                <p>Learn at your own pace with self explanatory video courses</p>
                                            </div>
                                        </div>
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img
                                                        src="{{ asset('frontend/images/slider-differentImg2.png') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Standalone Lecture
                                                </h3>
                                                <p>Learn at your own pace with self explanatory video courses</p>
                                            </div>
                                        </div>
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img
                                                        src="{{ asset('frontend/images/slider-differentImg3.png') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Standalone Lecture
                                                </h3>
                                                <p>Learn at your own pace with self explanatory video courses</p>
                                            </div>
                                        </div>
                                        <div class="sliderContent">
                                            <div class="sliderImg">
                                                <figure><img
                                                        src="{{ asset('frontend/images/slider-differentImg4.png') }}"
                                                        alt="mittlearn-image">
                                                </figure>
                                            </div>
                                            <div class="sliderImgtxt">
                                                <h3>
                                                    <span class="greenBorder"></span>
                                                    Standalone Lecture
                                                </h3>
                                                <p>Learn at your own pace with self explanatory video courses</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider slider-thumb">
                                        <div>
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg1.png') }}"
                                                    alt="mittlearn-image">
                                            </figure>
                                        </div>
                                        <div>
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg2.png') }}"
                                                    alt="mittlearn-image">
                                            </figure>
                                        </div>
                                        <div>
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg3.png') }}"
                                                    alt="mittlearn-image">
                                            </figure>
                                        </div>
                                       
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        {{--  <div class="skillsSection">
                            <div class="skillsDots">
                                <img src="{{ asset('frontend/images/dotsImg.svg') }}" alt="mittlearn-image"
                                    width="260">
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Start learning new skills</h2>
                                    <p>Below are some of the quick categories <br> to get started</p>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-6 col-md-3 mb-3">
                                        <div class="skillsBox skillsBox1">
                                            <figure>
                                                <lottie-player src="{{ asset('frontend/images/creativity.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                            </figure>
                                            <h3>Creativity</h3>
                                            <!-- <p>Nursery, K1 - K2</p> -->
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-3">
                                        <div class="skillsBox skillsBox2">
                                            <figure>
                                                <lottie-player src="{{ asset('frontend/images/music.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                            </figure>
                                            <h3>Music</h3>
                                            <!-- <p>Grade 01 - 05</p> -->
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-3">
                                        <div class="skillsBox skillsBox3">

                                            <figure>
                                                <lottie-player src="{{ asset('frontend/images/dance.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                            </figure>
                                            <h3>Dance</h3>
                                            <!-- <p>Grade 06 - 08</p> -->
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3 mb-3">
                                        <div class="skillsBox skillsBox4">
                                            <figure>
                                                <lottie-player src="{{ asset('frontend/images/language.json') }}"
                                                    background="transparent" speed="1"
                                                    style="width: 130px; height: 130px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                            </figure>
                                            <h3>Language</h3>
                                        </div>
                                    </div>
                                    <!-- <div class="col-6 col-md-3">                                                                                                                                                                                                                                                                                                                                                                                           </div> -->
                                </div>
                            </div>
                        </div>  --}}
                        {{-- <div class="languageEducation" id="languageEducationSection">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 mb-5 mb-md-0">
                                        <div class="section-heading text-start">
                                            <h2><span class="greenBorder"></span>
                                                Language Education For</h2>
                                        </div>

                                        <div class="educationTxt">
                                            <h3>Learn <b id="dynamicText">German</b> from Professionals</h3>
                                            <p>School student's learning foreign language as an optional subject</p>
                                            <a href="" class="btn-success rounded-2">Show More</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="circleImg text-center position-relative">
                                            <img src="{{ asset('frontend/images/circleImg.svg') }}" width="370"
                                                class="d-inline-block" id="circleImage">

                                            <div class="locationImge">
                                                <figure><img id="locationImage"
                                                        src="{{ asset('frontend/images/location-img1.jpg') }}">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="featuresSection mt-2">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        {{ $coreFeatureBanner->core_title ?? '' }}</h2>
                                    <p class="text-white">{{ $coreFeatureBanner->core_heading ?? '' }}</p>
                                </div>
                                <div class="CircleLottie">
                                    <lottie-player src="{{ asset('frontend/images/Loader-animation.json') }}"
                                        background="transparent" speed="1"
                                        style="width: 250px; height: 250px;margin: auto;opacity: .2;" loop
                                        autoplay></lottie-player>
                                </div>
                                <div class="shapeLottie">
                                    <lottie-player src="{{ asset('frontend/images/data.json') }}"
                                        background="transparent" speed="1"
                                        style="width: 550px; height: 550px;margin: auto;" loop autoplay></lottie-player>
                                </div>
                                <div class="featureGroup">
                                    <div class="row">
                                        <div class="col-md-7 pe-md-0 mb-4 mb-md-0">
                                            <div class="featureContent featuresecContent slick-carousel">
                                                @if (isset($coreNonFeatureBannerAdditional))
                                                    @foreach ($coreNonFeatureBannerAdditional->chunk(2) as $chunk)
                                                        <div class="d-flex gap-2 mb-2">
                                                            @foreach ($chunk as $data)
                                                                <div class="featureTxtbox">
                                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                                        @if (isset($data->image))
                                                                            <img src="{{ Storage::url('uploads/website-pages/non_academic_core_icon_image/' . $data->image) }}"
                                                                                alt="Icon Image">
                                                                        @else
                                                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                                alt="Default Image">
                                                                        @endif
                                                                        {{ $data->title ?? '' }}
                                                                    </h3>
                                                                    <p>{{ $data->description ?? '' }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="featureImg">
                                                <div class="row px-1">
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="{{ asset('frontend/images/slider-differentImg3.png') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="landscapeImg">
                                                            <img src="{{ asset('frontend/images/courseSecondimg.png') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                        <figure class="landscapeImg mb-0">
                                                            <img src="{{ asset('frontend/images/courseThreeimg.png') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="{{ asset('frontend/images/slider-differentImg4.png') }}"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="advantagesSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Our Association brings Advantages to Schools, Students, Parents, and
                                        Individuals
                                    </h2>
                                </div>

                                <div class="row flex-row-reverse">
                                    <div class="col-md-6 position-relative mb-4 mb-md-0">
                                        <ul class="nav nav-tabs benefitsTab">
                                            <li class="nav-item">
                                                <button class="nav-link active" data-bs-toggle="tab"
                                                    data-bs-target="#schoolTab" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/schools-icon.svg') }}"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>
                                                    Schools
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#studentTab" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/student-icon1.svg') }}"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Students
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parentTab"
                                                    type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/parents-icon.svg') }}"
                                                            alt="mittlearn-image" width="40" height="25">
                                                    </figure>Parents
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#individualsTab" type="button">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/individuals-icon.svg') }}"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Individuals
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="arrowImg">
                                            <lottie-player src="{{ asset('frontend/images/arrow.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 90px; height: 90px;" loop autoplay></lottie-player>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="schoolTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Schools</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Expanded Curriculum :</strong>
                                                            <p>Schools can diversify their offerings by
                                                                incorporating
                                                                supplementary courses,
                                                                enriching the educational experience and
                                                                catering to
                                                                a
                                                                broader
                                                                range of
                                                                student interests</p>
                                                        </li>
                                                        <li>
                                                            <strong>Enhanced Reputation:</strong>
                                                            <p>Providing students with opportunities to
                                                                learn
                                                                additional
                                                                skills
                                                                and earn
                                                                certifications can enhance the school's
                                                                reputation
                                                                and
                                                                attract a
                                                                more
                                                                diverse student body.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Cost-Efficient Training:</strong>
                                                            <p>Schools can leverage existing talents within
                                                                their
                                                                faculty or
                                                                tap
                                                                into
                                                                external expertise to provide specialized
                                                                training
                                                                without
                                                                significant
                                                                additional costs.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="studentTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Students</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Flexible Learning Environment:</strong>
                                                            <p>Students can explore and learn new talents
                                                                from
                                                                the
                                                                comfort
                                                                of
                                                                their homes,
                                                                adapting their study schedule to their
                                                                preferences.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Skill Enhancement:</strong>
                                                            <p>Existing skills can be taken to the next
                                                                level,
                                                                allowing
                                                                students
                                                                to
                                                                continually improve and excel in their
                                                                chosen
                                                                areas
                                                                of
                                                                interest.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Certification and Recognition:</strong>
                                                            <p>Completion of courses with add-on quizzes and
                                                                interactive
                                                                worksheets leads to
                                                                valuable certifications, showcasing their
                                                                expertise
                                                                to
                                                                potential
                                                                employers
                                                                or educational institutions.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="parentTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Parents</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Cost-Effective:</strong>
                                                            <p>Parents can save on commuting, material, and
                                                                possibly
                                                                tuition
                                                                fees by opting
                                                                for online courses, making quality education
                                                                more
                                                                affordable.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Supervised Learning:</strong>
                                                            <p>Parents can monitor their child's progress
                                                                and
                                                                engagement
                                                                in
                                                                the
                                                                courses,
                                                                ensuring a productive learning experience
                                                                and
                                                                providing
                                                                support
                                                                as needed.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Diverse Learning Opportunities:</strong>
                                                            <p>Online courses offer a wider range of
                                                                subjects
                                                                and
                                                                skills,
                                                                enabling parents
                                                                to help their children explore various
                                                                interests
                                                                and
                                                                aptitudes
                                                                beyond the
                                                                conventional school curriculum.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="individualsTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Individuals</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Holistic Skill Development:</strong>
                                                            <p>Individuals can broaden their skill set by
                                                                accessing
                                                                courses
                                                                that
                                                                are not
                                                                part of their formal education, enabling
                                                                well-rounded
                                                                personal
                                                                and
                                                                professional growth.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Tailored Learning:</strong>
                                                            <p>Online courses allow individuals to focus on
                                                                specific
                                                                areas
                                                                of
                                                                interest or
                                                                skills they want to acquire, tailoring their
                                                                learning
                                                                journey to
                                                                match their
                                                                unique aspirations.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Career Advancement:</strong>
                                                            <p>Earning certifications from supplementary
                                                                courses
                                                                can
                                                                enhance
                                                                an
                                                                individual's
                                                                resume and open doors to new career
                                                                opportunities
                                                                for
                                                                advancement within
                                                                their current field.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="exclusiveBlog">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Exclusive Blog</h2>
                                    <p>Get to know what's trending</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 pe-md-4">
                                        <div class="row">
                                            @if (isset($exclusiveBlogs))
                                                @foreach ($exclusiveBlogs as $index => $data)
                                                    @php
                                                        $mainCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            null,
                                                        );
                                                        $subCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            '!=',
                                                            null,
                                                        );
                                                    @endphp

                                                    <div class="col-md-6 mb-3 mb-md-0">
                                                        <div class="blogContent h-100">
                                                            <figure class="blogImg">
                                                                <a
                                                                    href="{{ route('blog.details', ['slug' => $data->slug]) }}">
                                                                    @if (isset($data->blogsMedia->attachment_file))
                                                                        <img src="{{ Storage::url('uploads/blog/' . $data->blogsMedia->attachment_file) }}"
                                                                            alt="mittlearn-image">
                                                                    @else
                                                                        <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                            alt="Default Image">
                                                                    @endif
                                                                </a>
                                                            </figure>

                                                            <span>
                                                                {{ $mainCategory?->name ?? 'Uncategorized' }}
                                                                @if ($subCategory)
                                                                    &rarr; {{ $subCategory->name }}
                                                                @endif
                                                            </span>

                                                            <h4>
                                                                <a
                                                                    href="{{ route('blog.details', ['slug' => $data->slug]) }}">
                                                                    {{ $data->title }}
                                                                </a>
                                                            </h4>

                                                            <p>{!! $data->meta_description ?? '' !!}</p>

                                                            <div class="blogProfile">
                                                                <figure>
                                                                    <img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                                        alt="mittlearn-image">
                                                                </figure>
                                                                <strong>
                                                                    <b>Mittlearn</b> {{ $data->formatted_date ?? '' }}
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-md-5 border-start ps-md-4">
                                        <ul class="recentBlogList">
                                            @if (isset($exclusiveBlogList))
                                                @foreach ($exclusiveBlogList as $data)
                                                    <li>
                                                        <strong>{{ $data->title ?? '' }}</strong>
                                                        <a href="{{ route('blog.details', ['slug' => $data->slug]) }}">Learn
                                                            More</a>
                                                    </li>
                                                @endforeach
                                            @endif

                                            <div class="text-end">
                                                <a href="{{ route('blogs') }}" class="btn btn-success rounded-1 fs-7">
                                                    View All
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bottomToggleBtn" style="display: none;">
                        <span>Switch to Academic Content</span>
                        <div class="toggleBtn">
                            <input type="checkbox" id="switchacademic" />
                            <label for="switchacademic"></label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        // Show bottom toggle on 20% scroll
        $(window).on("scroll", function() {
            let scrollTop = $(window).scrollTop();
            let docHeight = $(document).height();
            let windowHeight = $(window).height();
            let scrollPercent = (scrollTop / (docHeight - windowHeight)) * 100;

            if (scrollPercent > 10) {
                $(".bottomToggleBtn").fadeIn(); // Show toggle
            } else {
                $(".bottomToggleBtn").fadeOut(); // Hide toggle
            }
        });
    </script>
@endsection
