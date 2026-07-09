@extends('frontend.layouts.master')

@section('content')
    <div>
        <section class="frontend-main-section">
            <div class="mainBanner">
                <div class="container">
                    <div class="bannerTxt">
                        <span>{{ $homePageContent->title }}</span>
                        <h1>{{ $headingWithoutLastWord }} <b>{{ $lastWord }}</b></h1>

                        <p>{{ $homePageContent->description }}</p>
                        <div class="bnrSearch">
                            <div class="searchInput">
                                <div class="gradientBorder"><input type="text" class="form-control"
                                        placeholder="Search Course">
                                </div>
                                <button class="btn btn-primary-gradient">Search Now</button>
                            </div>

                            <div class="recentSearch mt-5">
                                <span><a href="">Sr. UI Designer</a></span>
                                <span><a href="">Backend Dev</a></span>
                                <span><a href="">Frontend Dev</a></span>
                                <span><a href="">Sr. Digital Man</a></span>
                                <span><a href="">Sr. Digital Man</a></span>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="bgIcons1"><img src="{{ asset('frontend/images/bgIcon1.svg') }}" width="30"></span>
                <span class="bgIcons2"><img src="{{ asset('frontend/images/bgIcon2.png') }}" width="50"></span>
                <span class="bgIcons3"><img src="{{ asset('frontend/images/bgIcon3.png') }}" width="50"></span>
                <span class="bgIcons4"><img src="{{ asset('frontend/images/bgIcon4.png') }}" width="50"></span>
                <span class="bgIcons5"><img src="{{ asset('frontend/images/bgIcon5.png') }}" width="60"></span>
                <span class="bgIcons6"><img src="{{ asset('frontend/images/bgIcon6.png') }}" width="40"></span>
                <span class="bgIcons7"><img src="{{ asset('frontend/images/bgIcon7.png') }}" width="40"></span>
                <span class="bgIcons8"><img src="{{ asset('frontend/images/bgIcon8.png') }}" width="55"></span>
                <span class="bgIcons9"><img src="{{ asset('frontend/images/bgIcon9.png') }}" width="60"></span>
                <span class="bgIcons10"><img src="{{ asset('frontend/images/bgIcon10.png') }}" width="55"></span>
                <span class="bgIcons11"><img src="{{ asset('frontend/images/bgIcon11.png') }}" width="50"></span>
                <span class="bgIcons12"><img src="{{ asset('frontend/images/bgIcon12.png') }}" width="50"></span>
                <span class="bgIcons13"><img src="{{ asset('frontend/images/bgIcon13.png') }}" width="60"></span>
            </div>

            <div class="">
                <div class="container">
                    <div class="contentNeeds">
                        <div class="maincon">
                            <div class="layoutText">
                                <figure>
                                    <img src="{{ Storage::url('uploads/academic/' . $homePageContent->academic_image) }}"
                                        alt="Academic Image">
                                </figure>
                                <h3>{{ $homePageContent->academic_title }}</h3>
                                <p>{{ $homePageContent->academic_description }}</p>
                            </div>
                            <div class="toggleGroup">
                                <div class="togglediv">
                                    <input type="checkbox" id="switch" onclick="toggleContent()" /><label
                                        for="switch">Toggle</label>
                                </div>
                                <p>Switch the content as needed</p>
                            </div>
                            <div class="layoutText">
                                <figure>
                                    <img src="{{ Storage::url('uploads/non-academic/' . $homePageContent->non_academic_image) }}"
                                        alt="Profile Image">
                                </figure>
                                <h3>{{ $homePageContent->non_academic_title }}</h3>
                                <p>{{ $homePageContent->non_academic_description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="academic-page">
                    <div class="learnSection py-5">
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
                                                    alt="">
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
                                                    alt="">
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
                                                    alt="">
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
                                                    alt="">
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
                                        <figure><img src="{{ asset('frontend/images/sliderImg1.jpg') }}" alt="">
                                        </figure>
                                    </div>
                                    <div>
                                        <figure><img src="{{ asset('frontend/images/sliderImg2.jpg') }}" alt="">
                                        </figure>
                                    </div>
                                    <div>
                                        <figure><img src="{{ asset('frontend/images/sliderImg3.jpg') }}" alt="">
                                        </figure>
                                    </div>
                                    <!-- <div>
                                                                                                                                                                                    <figure><img src="{{ asset('frontend/images/sliderImg4.jpg') }}" alt=""></figure>
                                                                                                                                                                                </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="skillsSection">
                        <div class="skillsDots">
                            <img src="{{ asset('frontend/images/dotsImg.svg') }}" alt="" width="260">
                        </div>
                        <div class="container">
                            <div class="section-heading">
                                <h2><span class="greenBorder"></span>
                                    Start learning new skills</h2>
                                <p>Below are some of the quick categories to <br>get started</p>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox1">
                                        <figure>
                                            <lottie-player
                                                src="{{ asset('frontend/images/girl-building-sand-castle.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 130px; height: 130px;margin: auto;" loop
                                                autoplay></lottie-player>
                                        </figure>
                                        <h3>Pre School</h3>
                                        <p>Nursery, K1 - K2</p>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox2">
                                        <figure>
                                            <lottie-player src="{{ asset('frontend/images/girl-solving-a-puzzle.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 130px; height: 130px;margin: auto;" loop
                                                autoplay></lottie-player>
                                        </figure>
                                        <h3>Primary School</h3>
                                        <p>Grade 01 - 05</p>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox3">
                                        <figure>
                                            <lottie-player src="{{ asset('frontend/images/boy-reading-a-book.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 130px; height: 130px;margin: auto;" loop
                                                autoplay></lottie-player>
                                        </figure>
                                        <h3>Middle School</h3>
                                        <p>Grade 06 - 08</p>
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox4">
                                        <figure>
                                            <lottie-player src="{{ asset('frontend/images/girl-doing-homework.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 130px; height: 130px;margin: auto;" loop
                                                autoplay></lottie-player>
                                        </figure>
                                        <h3>Senior School</h3>
                                        <p>Grade 09 - 12</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="featuresSection">
                        <div class="container">
                            <div class="section-heading">
                                <h2 class="text-white"><span class="greenBorder"></span>
                                    Our Core Features</h2>
                                <p class="text-white">We are redifing early childhood education</p>
                            </div>
                            <div class="CircleLottie">
                                <lottie-player src="{{ asset('frontend/images/Loader-animation.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 250px; height: 250px;margin: auto;opacity: .2;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="shapeLottie">
                                <lottie-player src="{{ asset('frontend/images/data.json') }}" background="transparent"
                                    speed="1" style="width: 550px; height: 550px;margin: auto;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="featureGroup">
                                <div class="row">
                                    <div class="col-md-7 pe-md-0 mb-4 mb-md-0">
                                        <div class="featureContent slick-carousel">
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Books
                                                    </h3>
                                                    <p>A set of text books for each class Nursery, LKG & UKG as per the
                                                        curriculum.
                                                    </p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon2.svg') }}"
                                                            alt="">
                                                        Digital Lessons
                                                    </h3>
                                                    <p>Play-way teaching through interactive animated videos to give real
                                                        classroom
                                                        experience.
                                                    </p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon3.svg') }}"
                                                            alt="">
                                                        Digital Activities & Games
                                                    </h3>
                                                    <p>Class- wise and subject -wise activities & games to revise and make
                                                        learning
                                                        more
                                                        fun.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon4.svg') }}"
                                                            alt="">
                                                        Day to Day Planner
                                                    </h3>
                                                    <p>Simple to follow 105 day planner for each class, keeping in mind the
                                                        pace of
                                                        learning
                                                        and
                                                        least screen time.</p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon5.svg') }}"
                                                            alt="">
                                                        Extra-Curricular Videos
                                                    </h3>
                                                    <p>A gallery of videos teaching moral values and basic life skills to
                                                        have a
                                                        holistic
                                                        development.</p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon6.svg') }}"
                                                            alt="">
                                                        Learning Kit
                                                    </h3>
                                                    <p>An educational kit meeting all the learning needs including
                                                        stationery as
                                                        well as
                                                        art
                                                        and
                                                        craft material.</p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon7.svg') }}"
                                                            alt="">
                                                        Worksheets
                                                    </h3>
                                                    <p>Practice sheets to revise and make learning easy for the child.</p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon8.svg') }}"
                                                            alt="">
                                                        Certificate
                                                    </h3>
                                                    <p>A certificate given at the time of completion of the program as per
                                                        day to
                                                        day
                                                        planner.
                                                    </p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon9.svg') }}"
                                                            alt="">
                                                        Digital Activities & Games
                                                    </h3>
                                                    <p>Class- wise and subject -wise activities & games to revise and make
                                                        learning
                                                        more
                                                        fun.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon7.svg') }}"
                                                            alt="">
                                                        Worksheets
                                                    </h3>
                                                    <p>Practice sheets to revise and make learning easy for the child.</p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon8.svg') }}"
                                                            alt="">
                                                        Certificate
                                                    </h3>
                                                    <p>A certificate given at the time of completion of the program as per
                                                        day to
                                                        day
                                                        planner.
                                                    </p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon9.svg') }}"
                                                            alt="">
                                                        Digital Activities & Games
                                                    </h3>
                                                    <p>Class- wise and subject -wise activities & games to revise and make
                                                        learning
                                                        more
                                                        fun.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="featureImg">
                                            <div class="row px-1">
                                                <div class="col-4 px-2">
                                                    <figure class="portraitImg mb-0">
                                                        <img src="{{ asset('frontend/images/feature-img1.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                                <div class="col-4 px-2">
                                                    <figure class="landscapeImg">
                                                        <img src="{{ asset('frontend/images/feature-img2.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                    <figure class="landscapeImg mb-0">
                                                        <img src="{{ asset('frontend/images/feature-img3.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                                <div class="col-4 px-2">
                                                    <figure class="portraitImg mb-0">
                                                        <img src="{{ asset('frontend/images/feature-img4.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="courseSection">
                        <div class="container">
                            <div class="section-heading">
                                <h2><span class="greenBorder"></span>
                                    High-Demand Courses: Empower Your Learning with Top-Selling Programs</h2>
                                <p>Explore all of our courses and pick your suitable ones to enroll and start earning with
                                    us!</p>

                                <a href="{{ route('courses.listing', ['category_slug' => $academicCategory->slug]) }}"
                                    class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a>
                            </div>

                            <ul class="nav nav-tabs coursesTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#preSchool" data-bs-toggle="tab">Pre - school</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#primarySchool" data-bs-toggle="tab">Primary School</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#middleSchool" data-bs-toggle="tab">Middle School</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#seniorSchool" data-bs-toggle="tab">Senior School</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="preSchool">
                                    <div class="row px-md-1">

                                        @foreach ($acadCourses as $course)
                                            <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                                <div class="coursesBox">
                                                    <figure>
                                                        @php
                                                            $bookCoverImage = $course->metadataValues->firstWhere(
                                                                'field_name',
                                                                'book_cover_image',
                                                            );
                                                            
                                                        @endphp
                                                        @if ($bookCoverImage != null)
                                                            <img src="{{ asset('frontend/images/' . $bookCoverImage->field_value) }}"
                                                                alt="Book Cover Image">
                                                        @else
                                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    </figure>
                                                    <div class="d-flex gap-2 justify-content-between px-2">
                                                        <b>Mittlearn</b>
                                                        <div class="d-flex gap-3">
                                                            <button type="button"
                                                                class="bg-transparent border-0 p-0"><img
                                                                    src="{{ asset('frontend/images/heart-icon.svg') }}"
                                                                    alt="" width="18"></button>
                                                            <button type="button"
                                                                class="bg-transparent border-0 p-0"><img
                                                                    src="{{ asset('frontend/images/cart-icon.svg') }}"
                                                                    alt="" width="18"></button>
                                                        </div>
                                                    </div>
                                                    <h3 class="px-2">
                                                        {{ limit_words($course->course_name ?? 'No Course Name', 3) }}</h3>
                                                    <div class="d-flex flex-wrap gap-3 courseInfo px-2">

                                                        @foreach ($course->metadataValues as $metadataValue)
                                                            @if ($metadataValue->field_name == 'subject')
                                                                <!-- Print the subject's name using the subjectInfo relationship -->
                                                                @if ($metadataValue->subjectInfo)
                                                                    <span><img
                                                                            src="{{ asset('frontend/images/student-icon.svg') }}"
                                                                            alt="" width="14">
                                                                        Sub: {{ $metadataValue->subjectInfo->name }}
                                                                    </span>
                                                                @endif
                                                            @endif

                                                            @if ($metadataValue->field_name == 'class' && $metadataValue->classInfo)
                                                                <span><img
                                                                        src="{{ asset('frontend/images/student-icon.svg') }}"
                                                                        alt="" width="14">
                                                                    Class: {{ $metadataValue->classInfo->name }}
                                                                </span>
                                                            @endif
                                                        @endforeach

                                                        {{-- <span><img src="{{ asset('frontend/images/lessons-icon.svg') }}" alt="" width="14"> 08 Lessons</span> --}}
                                                    </div>
                                                    <hr>
                                                    <div
                                                        class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                                        <div class="pricetag">
                                                            <span>₹ {{ number_format($course->price) }}</span>
                                                            @php
                                                                if ($course->discount_type == 'flat') {
                                                                    $finalPrice =
                                                                        $course->price - $course->discount_value;
                                                                } elseif ($course->discount_type == 'percentage') {
                                                                    $finalPrice =
                                                                        $course->price -
                                                                        ($course->discount_value / 100) *
                                                                            $course->price;
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


                                <div class="tab-pane fade" id="primarySchool">...</div>
                                <div class="tab-pane fade" id="middleSchool">...</div>
                                <div class="tab-pane fade" id="seniorSchool">...</div>
                            </div>

                        </div>
                    </div>
                    <div class="launchedSection py-5">
                        <div class="exclusiveTag">
                            <lottie-player src="{{ asset('frontend/images/exclusive-tag-red.json') }}"
                                background="transparent" speed="1" style="width: 130px; height: 130px;margin: auto;"
                                loop autoplay></lottie-player>
                        </div>
                        <div class="container">
                            <div class="section-heading pb-0">
                                <h2 class=""><span class="greenBorder"></span>
                                    Newly Launched</h2>
                            </div>
                            <ul class="nav nav-tabs coursesTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#preSchool1" data-bs-toggle="tab">Pre - school</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#primarySchool2" data-bs-toggle="tab">Primary School</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#middleSchool3" data-bs-toggle="tab">Middle School</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#seniorSchool4" data-bs-toggle="tab">Senior School</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="preSchool1">
                                    <div class="exploreMain">
                                        <div class="slider slider-explore">
                                            <div class="sliderContent">
                                                <div class="sliderImgtxt">
                                                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry
                                                    </h3>
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry.
                                                        Lorem
                                                        Ipsum has been the industry's standard dummy text ever since the
                                                        1500s, when
                                                        an
                                                        unknown printer took a galley of type and scrambled it to make a
                                                        type
                                                        specimen
                                                        book. It has survived not only five centuries,</p>
                                                    <a href="#"
                                                        class="btn btn-primary btn-primary-gradient rounded-5 px-4">Explore
                                                        <i class="bi bi-arrow-right"></i></a>
                                                </div>
                                                <div class="sliderImg">
                                                    <figure><img src="{{ asset('frontend/images/exploreImg.png') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                            </div>
                                            <div class="sliderContent">
                                                <div class="sliderImgtxt">
                                                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry
                                                    </h3>
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry.
                                                        Lorem
                                                        Ipsum has been the industry's standard dummy text ever since the
                                                        1500s, when
                                                        an
                                                        unknown printer took a galley of type and scrambled it to make a
                                                        type
                                                        specimen
                                                        book. It has survived not only five centuries,</p>
                                                    <a href="#"
                                                        class="btn btn-primary btn-primary-gradient rounded-5 px-4">Explore
                                                        <i class="bi bi-arrow-right"></i></a>
                                                </div>
                                                <div class="sliderImg">
                                                    <figure><img src="{{ asset('frontend/images/middle-schoolImg.png') }}"
                                                            alt=""></figure>
                                                </div>
                                            </div>
                                            <div class="sliderContent">
                                                <div class="sliderImgtxt">
                                                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry
                                                    </h3>
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry.
                                                        Lorem
                                                        Ipsum has been the industry's standard dummy text ever since the
                                                        1500s, when
                                                        an
                                                        unknown printer took a galley of type and scrambled it to make a
                                                        type
                                                        specimen
                                                        book. It has survived not only five centuries,</p>
                                                    <a href="#"
                                                        class="btn btn-primary btn-primary-gradient rounded-5 px-4">Explore
                                                        <i class="bi bi-arrow-right"></i></a>
                                                </div>
                                                <div class="sliderImg">
                                                    <figure><img src="{{ asset('frontend/images/senior-schoolImg.png') }}"
                                                            alt=""></figure>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slider slider-explore-thumb">
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure><img
                                                            src="{{ asset('frontend/images/primary-schoolImg.png') }}"
                                                            alt=""></figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>Primary School <b>Grade: 01 - 05</b></span>
                                                        <figure>
                                                            <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                alt="" width="15">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure><img src="{{ asset('frontend/images/middle-schoolImg.png') }}"
                                                            alt=""></figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>Middle School <b>Grade: 06 - 08</b></span>
                                                        <figure>
                                                            <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                alt="" width="15">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure><img src="{{ asset('frontend/images/senior-schoolImg.png') }}"
                                                            alt=""></figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>Senior School <b>Grade: 09 - 12</b></span>
                                                        <figure>
                                                            <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                alt="" width="15">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="primarySchool2">...</div>
                                <div class="tab-pane fade" id="middleSchool3">...</div>
                                <div class="tab-pane fade" id="seniorSchool4">...</div>
                            </div>
                        </div>
                    </div>
                    @include('frontend.plans')
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
                                    {{ $homePageContent->instructor_title }}</h2>
                                <p> Meet our associated trainer and what <br> drives them to keep moving </p>
                            </div>

                            <div class="meetMain">

                                <div class="slider meetSlider">
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-md-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Sahil Vijay </span>
                                                        <b>Beatboxing</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-md-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Sahil Vijay, a certified musician and a professional sound engineer.
                                                    I have
                                                    years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/sahil-vijay.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Neha Sharma </span>
                                                        <b>Art & Craft Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Neha Sharma, a certified musician and a professional sound engineer.
                                                    I have
                                                    years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/neha-sharma.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Rohan Singh </span>
                                                        <b>Guitar Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Rohan Singh, a certified musician and a professional sound engineer.
                                                    I have
                                                    years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/rohan-singh.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Rahul Yadav </span>
                                                        <b>Art & Craft Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Rahul Yadav, a certified musician and a professional sound engineer.
                                                    I have
                                                    years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/rahul-yadav.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Priyanka Singh</span>
                                                        <b>Guitar Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Priyanka Singh, a certified musician and a professional sound
                                                    engineer. I
                                                    have
                                                    years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/priyanka-singh.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider meetSliderThumb">
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/sahil-vijay.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Sahil Vijay</span>
                                            <b>Beatboxing</b>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/neha-sharma.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Neha Sharma </span>
                                            <b>Art & Craft Teacher</b>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/rohan-singh.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Rohan Singh </span>
                                            <b>Guitar Teacher</b>

                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/rahul-yadav.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Rahul Yadav </span>
                                            <b>Art & Craft Teacher</b>

                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/priyanka-singh.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Priyanka Singh </span>
                                            <b>Guitar Teacher</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="studentSayAbout">
                        <div class="container">
                            <div class="section-heading">
                                <h2 class="text-white"><span class="greenBorder"></span>
                                    {{ $testimonialBanner->heading_1 }}</h2>
                                <p class="text-white">{{ $testimonialBanner->sub_heading_1 }}</p>
                            </div>
                            <div class="aboutSliderSec position-relative">
                                <div class="topImg">
                                    <lottie-player src="{{ asset('frontend/images/customer-response.json') }}"
                                        background="transparent" speed="1" style="width: 250px; height: 250px;" loop
                                        autoplay></lottie-player>
                                </div>
                                <div class="sayAboutSlider">
                                    @foreach ($testimonial as $data)
                                        <div class="item">
                                            <div class="sayAbout">
                                                <p>{{ $data->comment }}</p>
                                                <div class="sayProfile">
                                                    <figure>
                                                        <img src="{{ Storage::url('uploads/testimonial-profile/' . $data->image) }}"
                                                            alt="Profile Image">
                                                    </figure>
                                                    <strong><b>{{ $data->name }}</b>
                                                        {{ $data->designation }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="advantagesSection">
                        <div class="container">
                            <div class="section-heading">
                                <h2><span class="greenBorder"></span>
                                    Our Association brings Advantages to Schools, Students, Parents, and Individuals</h2>
                            </div>

                            <div class="row flex-row-reverse">
                                <div class="col-md-6 position-relative mb-4 mb-md-0">
                                    <ul class="nav nav-tabs benefitsTab">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#schoolTab" type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/schools-icon.svg') }}"
                                                        alt="" width="25" height="25">
                                                </figure>
                                                Schools
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#studentTab"
                                                type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/student-icon1.svg') }}"
                                                        alt="" width="25" height="25">
                                                </figure>Students
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parentTab"
                                                type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/parents-icon.svg') }}"
                                                        alt="" width="40" height="25">
                                                </figure>Parents
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#individualsTab" type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/individuals-icon.svg') }}"
                                                        alt="" width="25" height="25">
                                                </figure>Individuals
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="arrowImg">
                                        <lottie-player src="{{ asset('frontend/images/arrow.json') }}"
                                            background="transparent" speed="1" style="width: 90px; height: 90px;"
                                            loop autoplay></lottie-player>
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
                                                        <p>Schools can diversify their offerings by incorporating
                                                            supplementary
                                                            courses,
                                                            enriching the educational experience and catering to a broader
                                                            range of
                                                            student interests</p>
                                                    </li>
                                                    <li>
                                                        <strong>Enhanced Reputation:</strong>
                                                        <p>Providing students with opportunities to learn additional skills
                                                            and earn
                                                            certifications can enhance the school's reputation and attract a
                                                            more
                                                            diverse student body.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Cost-Efficient Training:</strong>
                                                        <p>Schools can leverage existing talents within their faculty or tap
                                                            into
                                                            external expertise to provide specialized training without
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
                                                        <p>Students can explore and learn new talents from the comfort of
                                                            their
                                                            homes,
                                                            adapting their study schedule to their preferences.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Skill Enhancement:</strong>
                                                        <p>Existing skills can be taken to the next level, allowing students
                                                            to
                                                            continually improve and excel in their chosen areas of interest.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <strong>Certification and Recognition:</strong>
                                                        <p>Completion of courses with add-on quizzes and interactive
                                                            worksheets
                                                            leads to
                                                            valuable certifications, showcasing their expertise to potential
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
                                                        <p>Parents can save on commuting, material, and possibly tuition
                                                            fees by
                                                            opting
                                                            for online courses, making quality education more affordable.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <strong>Supervised Learning:</strong>
                                                        <p>Parents can monitor their child's progress and engagement in the
                                                            courses,
                                                            ensuring a productive learning experience and providing support
                                                            as
                                                            needed.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <strong>Diverse Learning Opportunities:</strong>
                                                        <p>Online courses offer a wider range of subjects and skills,
                                                            enabling
                                                            parents
                                                            to help their children explore various interests and aptitudes
                                                            beyond
                                                            the
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
                                                        <p>Individuals can broaden their skill set by accessing courses that
                                                            are not
                                                            part of their formal education, enabling well-rounded personal
                                                            and
                                                            professional growth.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Tailored Learning:</strong>
                                                        <p>Online courses allow individuals to focus on specific areas of
                                                            interest
                                                            or
                                                            skills they want to acquire, tailoring their learning journey to
                                                            match
                                                            their
                                                            unique aspirations.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Career Advancement:</strong>
                                                        <p>Earning certifications from supplementary courses can enhance an
                                                            individual's
                                                            resume and open doors to new career opportunities for
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
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="blogContent">
                                                <figure class="blogImg">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/blog-img1.jpg') }}"
                                                            alt=""></a>
                                                </figure>
                                                <span>Technology</span>
                                                <h4><a href="">Unleashing the Power of Artificial Intelligence: A
                                                        Journey
                                                        into
                                                        the....</a></h4>
                                                <p>simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                                    has been
                                                    the
                                                    industry's standard dummy text ever since the.</p>
                                                <div class="blogProfile">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                    <strong><b>Mittlearn</b> Jan 29, 2024</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="blogContent">
                                                <figure class="blogImg">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/blog-img2.jpg') }}"
                                                            alt=""></a>
                                                </figure>
                                                <span>Design</span>
                                                <h4><a href="">Unleashing the Power of Artificial Intelligence: A
                                                        Journey
                                                        into
                                                        the....</a></h4>
                                                <p>simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                                    has been
                                                    the
                                                    industry's standard dummy text ever since the.</p>
                                                <div class="blogProfile">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                    <strong><b>Mittlearn</b> Jan 29, 2024</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 border-start ps-md-4">
                                    <ul class="recentBlogList">
                                        <li>
                                            <strong>Mastering the Melodies: Guitar Mastery Course for At-Home
                                                Learning.</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                        <li>
                                            <strong>Unveiling the Enchanting Melodies of the Guitar: A Musical
                                                Journey</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                        <li>
                                            <strong>Crafting Rhythmic Magic: The Art of Love Beatbox</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                        <li>
                                            <strong>Unraveling the Myths: Dispelling Misconceptions About Data
                                                Science</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                    </ul>
                                    <div class="text-end">
                                        <a href="" class="btn btn-success rounded-1 fs-7">
                                            View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nonacademic-page">
                    <div class="learnSection py-5">
                        <div class="container">
                            <div class="section-heading">
                                <h2><span class="greenBorder"></span>
                                    Different ways to learn on the platform</h2>
                                <p>Learn as per your own suitability for online recorded lectures, trainer led online
                                    sessions, group
                                    sessions and one-on-one sessions</p>
                            </div>
                            <div class="sliderMain">
                                <div class="slider slider-content">
                                    <div class="sliderContent">
                                        <div class="sliderImg">
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg1.png') }}"
                                                    alt="">
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
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg2.png') }}"
                                                    alt="">
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
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg3.png') }}"
                                                    alt="">
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
                                            <figure><img src="{{ asset('frontend/images/slider-differentImg4.png') }}"
                                                    alt="">
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
                                                alt="">
                                        </figure>
                                    </div>
                                    <div>
                                        <figure><img src="{{ asset('frontend/images/slider-differentImg2.png') }}"
                                                alt="">
                                        </figure>
                                    </div>
                                    <div>
                                        <figure><img src="{{ asset('frontend/images/slider-differentImg3.png') }}"
                                                alt="">
                                        </figure>
                                    </div>
                                    <!-- <div>
                                                                                                                                                                            <figure><img src="{{ asset('frontend/images/slider-differentImg4.png') }}" alt=""></figure>
                                                                                                                                                                        </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="skillsSection">
                        <div class="skillsDots">
                            <img src="{{ asset('frontend/images/dotsImg.svg') }}" alt="" width="260">
                        </div>
                        <div class="container">
                            <div class="section-heading">
                                <h2><span class="greenBorder"></span>
                                    Start learning new skills</h2>
                                <p>Below are some of the quick categories <br> to get started</p>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox1">
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
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox2">
                                        <figure>
                                            <lottie-player src="{{ asset('frontend/images/music.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 130px; height: 130px;margin: auto;" loop
                                                autoplay></lottie-player>
                                        </figure>
                                        <h3>Music</h3>
                                        <!-- <p>Grade 01 - 05</p> -->
                                    </a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <a href="" class="skillsBox skillsBox3">
                                        <figure>
                                            <lottie-player src="{{ asset('frontend/images/dance.json') }}"
                                                background="transparent" speed="1"
                                                style="width: 130px; height: 130px;margin: auto;" loop
                                                autoplay></lottie-player>
                                        </figure>
                                        <h3>Dance</h3>
                                        <!-- <p>Grade 06 - 08</p> -->
                                    </a>
                                </div>
                                <!-- <div class="col-6 col-md-3">
                                                                                                                                                                        <a href="" class="skillsBox skillsBox4">
                                                                                                                                                                            <figure>
                                                                                                                                                                                <lottie-player src="{{ asset('frontend/images/girl-doing-homework.json') }}" background="transparent" speed="1"
                                                                                                                                                                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay></lottie-player>
                                                                                                                                                                            </figure>
                                                                                                                                                                            <h3>Senior School</h3>
                                                                                                                                                                            <p>Grade 09 - 12</p>
                                                                                                                                                                        </a>
                                                                                                                                                                    </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="featuresSection">
                        <div class="container">
                            <div class="section-heading">
                                <h2 class="text-white"><span class="greenBorder"></span>
                                    Our Core Features</h2>
                                <p class="text-white">We are redifing early childhood education</p>
                            </div>
                            <div class="CircleLottie">
                                <lottie-player src="{{ asset('frontend/images/Loader-animation.json') }}"
                                    background="transparent" speed="1"
                                    style="width: 250px; height: 250px;margin: auto;opacity: .2;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="shapeLottie">
                                <lottie-player src="{{ asset('frontend/images/data.json') }}" background="transparent"
                                    speed="1" style="width: 550px; height: 550px;margin: auto;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="featureGroup">
                                <div class="row">
                                    <div class="col-md-7 pe-md-0 mb-4 mb-md-0">
                                        <div class="featureContent featuresecContent slick-carousel">
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Live Programs
                                                    </h3>
                                                    <p>We just do not provide only videos but the courses are curated with
                                                        assisted live
                                                        programs as per the requirements</p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Quality of program
                                                    </h3>
                                                    <p>Taught by our specialist : Backed with specialists the courses are of
                                                        premium
                                                        quality content
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        User defined courses
                                                    </h3>
                                                    <p>User defined courses - Suitable for Kids and Adults, Easy to learn
                                                        courses for
                                                        quick
                                                        learning and better understanding
                                                    </p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Worksheets / Quizes
                                                    </h3>
                                                    <p>User defined courses - Suitable for Kids and Adults, Easy to learn
                                                        courses for
                                                        quick
                                                        learning and better understanding
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Learn & Buy
                                                    </h3>
                                                    <p>Learn and purchase assistance provided for a quick relevance and
                                                        better learning
                                                    </p>
                                                </div>
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Get certified
                                                    </h3>
                                                    <p>Get certified with participation certifications</p>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <div class="featureTxtbox">
                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                        <img src="{{ asset('frontend/images/feature-icon1.svg') }}"
                                                            alt="">
                                                        Learn @ your own pace
                                                    </h3>
                                                    <p>No pressure on learning as the courses are self driven courses at
                                                        your own pace
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-5">
                                        <div class="featureImg">
                                            <div class="row px-1">
                                                <div class="col-4 px-2">
                                                    <figure class="portraitImg mb-0">
                                                        <img src="{{ asset('frontend/images/slider-differentImg3.png') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                                <div class="col-4 px-2">
                                                    <figure class="landscapeImg">
                                                        <img src="{{ asset('frontend/images/courseSecondimg.png') }}"
                                                            alt="">
                                                    </figure>
                                                    <figure class="landscapeImg mb-0">
                                                        <img src="{{ asset('frontend/images/courseThreeimg.png') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                                <div class="col-4 px-2">
                                                    <figure class="portraitImg mb-0">
                                                        <img src="{{ asset('frontend/images/slider-differentImg4.png') }}"
                                                            alt="">
                                                    </figure>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="courseSection">
                        <div class="container">
                            <div class="section-heading">
                                <h2><span class="greenBorder"></span>
                                    High-Demand Courses: Empower Your Learning with Top-Selling Programs</h2>
                                <p>Explore all of our courses and pick your suitable ones to enroll and start earning with
                                    us!</p>

                                <a href="{{ route('courses.listing', ['category_slug' => $nonAcademicCategory->slug]) }}"
                                    class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a>

                                <!-- <a href="{{ route('courses.listing', ['category_slug' => $academicCategory->slug]) }}" class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a> -->


                            </div>

                            <ul class="nav nav-tabs coursesTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#dance" data-bs-toggle="tab">Dance</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#music" data-bs-toggle="tab">Music</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#creativity" data-bs-toggle="tab">Creativity</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="dance">
                                    <div class="row px-md-1">

                                        @foreach ($nonAcadCourses as $noncourse)
                                            <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                                <div class="coursesBox">
                                                    <figure>
                                                        @php
                                                            $bannerImage = $noncourse->metadataValues->firstWhere(
                                                                'field_name',
                                                                'banner_image',
                                                            );
                                                        @endphp
                                                        @if ($bannerImage)
                                                            <img src="{{ asset('frontend/images/' . $bannerImage->field_value) }}"
                                                                alt="Banner Image">
                                                        @else
                                                            <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                alt="Default Image">
                                                        @endif
                                                    </figure>

                                                    <div class="d-flex gap-2 justify-content-between px-2">
                                                        <b>Mittlearn</b>
                                                        <div class="d-flex gap-3">
                                                            <button type="button"
                                                                class="bg-transparent border-0 p-0"><img
                                                                    src="{{ asset('frontend/images/heart-icon.svg') }}"
                                                                    alt="" width="18"></button>
                                                            <button type="button"
                                                                class="bg-transparent border-0 p-0"><img
                                                                    src="{{ asset('frontend/images/cart-icon.svg') }}"
                                                                    alt="" width="18"></button>
                                                        </div>
                                                    </div>
                                                    <h3 class="px-2">
                                                        {{ limit_words($noncourse->course_name ?? 'No Course Name', 3) }}
                                                    </h3>
                                                    <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                                        <span><img src="{{ asset('frontend/images/lessons-icon.svg') }}"
                                                                alt="" width="14"> 08
                                                            Lessons</span>
                                                        <span><img src="{{ asset('frontend/images/student-icon.svg') }}"
                                                                alt="" width="14"> 71
                                                            Students</span>
                                                        <span><img src="{{ asset('frontend/images/timer-icon.svg') }}"
                                                                alt="" width="14"> 43
                                                            minutes</span>
                                                    </div>
                                                    <hr>
                                                    <div
                                                        class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                                        <div class="pricetag">
                                                            <span>₹ {{ number_format($noncourse->price) }}</span>
                                                            @php
                                                                if ($noncourse->discount_type == 'flat') {
                                                                    $finalPrice =
                                                                        $noncourse->price - $noncourse->discount_value;
                                                                } elseif ($noncourse->discount_type == 'percent') {
                                                                    $finalPrice =
                                                                        $noncourse->price -
                                                                        ($noncourse->discount_value / 100) *
                                                                            $noncourse->price;
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
                                <div class="tab-pane fade" id="music">...</div>
                                <div class="tab-pane fade" id="creativity">...</div>

                            </div>
                        </div>
                    </div>
                    <div class="launchedSection py-5">
                        <div class="exclusiveTag">
                            <lottie-player src="{{ asset('frontend/images/exclusive-tag-red.json') }}"
                                background="transparent" speed="1" style="width: 130px; height: 130px;margin: auto;"
                                loop autoplay></lottie-player>
                        </div>
                        <div class="container">
                            <div class="section-heading pb-0">
                                <h2 class=""><span class="greenBorder"></span>
                                    Newly Launched</h2>
                            </div>
                            <ul class="nav nav-tabs coursesTabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#dance1" data-bs-toggle="tab">Dance </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#music2" data-bs-toggle="tab">Music</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#creativity3" data-bs-toggle="tab">Creativity</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="dance1">
                                    <div class="exploreMain">
                                        <div class="slider slider-explore">
                                            <div class="sliderContent">
                                                <div class="sliderImgtxt">
                                                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry</h3>
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry. Lorem
                                                        Ipsum has been the industry's standard dummy text ever since the
                                                        1500s, when an
                                                        unknown printer took a galley of type and scrambled it to make a
                                                        type specimen
                                                        book. It has survived not only five centuries,</p>
                                                    <a href="#"
                                                        class="btn btn-primary btn-primary-gradient rounded-5 px-4">Explore
                                                        <i class="bi bi-arrow-right"></i></a>
                                                </div>
                                                <div class="sliderImg">
                                                    <figure><img src="{{ asset('frontend/images/launchedImg2.png') }}"
                                                            alt=""></figure>
                                                </div>
                                            </div>
                                            <div class="sliderContent">
                                                <div class="sliderImgtxt">
                                                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry</h3>
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry. Lorem
                                                        Ipsum has been the industry's standard dummy text ever since the
                                                        1500s, when an
                                                        unknown printer took a galley of type and scrambled it to make a
                                                        type specimen
                                                        book. It has survived not only five centuries,</p>
                                                    <a href="#"
                                                        class="btn btn-primary btn-primary-gradient rounded-5 px-4">Explore
                                                        <i class="bi bi-arrow-right"></i></a>
                                                </div>
                                                <div class="sliderImg">
                                                    <figure><img src="{{ asset('frontend/images/launchedImg3.png') }}"
                                                            alt=""></figure>
                                                </div>
                                            </div>
                                            <div class="sliderContent">
                                                <div class="sliderImgtxt">
                                                    <h3>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry</h3>
                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting
                                                        industry. Lorem
                                                        Ipsum has been the industry's standard dummy text ever since the
                                                        1500s, when an
                                                        unknown printer took a galley of type and scrambled it to make a
                                                        type specimen
                                                        book. It has survived not only five centuries,</p>
                                                    <a href="#"
                                                        class="btn btn-primary btn-primary-gradient rounded-5 px-4">Explore
                                                        <i class="bi bi-arrow-right"></i></a>
                                                </div>
                                                <div class="sliderImg">
                                                    <figure><img src="{{ asset('frontend/images/launchedImg4.png') }}"
                                                            alt=""></figure>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="slider slider-explore-thumb">
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure><img src="{{ asset('frontend/images/launchedImg2.png') }}"
                                                            alt=""></figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>Kathak <b>Mittlearn</b></span>
                                                        <figure>
                                                            <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                alt="" width="15">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure><img src="{{ asset('frontend/images/launchedImg3.png') }}"
                                                            alt=""></figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>Folk Dance <b>Mittlearn</b></span>
                                                        <figure>
                                                            <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                alt="" width="15">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="exploreconTent">
                                                    <figure><img src="{{ asset('frontend/images/launchedImg4.png') }}"
                                                            alt=""></figure>
                                                    <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                        <span>Contemporary <b>Mittlearn</b></span>
                                                        <figure>
                                                            <img src="{{ asset('frontend/images/greenArrow.png') }}"
                                                                alt="" width="15">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="music2">...</div>
                                <div class="tab-pane fade" id="creativity3">...</div>
                            </div>
                        </div>
                    </div>
                    @include('frontend.plans')
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
                                    Meet our Skill Instructors</h2>
                                <p>Meet our associated trainer and what <br> drives them to keep moving</p>
                            </div>

                            <div class="meetMain">

                                <div class="slider meetSlider">
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-md-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Sahil Vijay </span>
                                                        <b>Beatboxing</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-md-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Sahil Vijay, a certified musician and a professional sound engineer.
                                                    I have years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/sahil-vijay.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Neha Sharma </span>
                                                        <b>Art & Craft Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Neha Sharma, a certified musician and a professional sound engineer.
                                                    I have years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/neha-sharma.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Rohan Singh </span>
                                                        <b>Guitar Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Rohan Singh, a certified musician and a professional sound engineer.
                                                    I have years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/rohan-singh.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Rahul Yadav </span>
                                                        <b>Art & Craft Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Rahul Yadav, a certified musician and a professional sound engineer.
                                                    I have years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/rahul-yadav.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetSliderContent">
                                            <div class="meetslidertxt">
                                                <div class="d-flex flex-wrap gap-3">
                                                    <div>
                                                        <span>Priyanka Singh</span>
                                                        <b>Guitar Teacher</b>
                                                    </div>
                                                    {{-- <div class="ms-md-5 mt-3">
                                                        <img src="{{ asset('frontend/images/star.svg') }}"
                                                            height="20">
                                                    </div> --}}
                                                </div>
                                                <p>I'm Priyanka Singh, a certified musician and a professional sound
                                                    engineer. I have
                                                    years
                                                    of
                                                    experience playing various musical instruments like Guitar, drums, and
                                                    keyboards.
                                                    Along
                                                    with this, he can teach advanced techniques of music and musical
                                                    instruments to
                                                    novice
                                                    students.</p>

                                                <div class="d-flex align-items-center gap-3">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/facebook-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/linkedin-blue.svg') }}"
                                                            alt=""></a>
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/twitter-blue.svg') }}"
                                                            alt=""></a>
                                                </div>
                                            </div>
                                            <div class="meetprofileImg">
                                                <figure><img src="{{ asset('frontend/images/priyanka-singh.jpg') }}"
                                                        alt="">
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider meetSliderThumb">
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/sahil-vijay.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Sahil Vijay</span>
                                            <b>Beatboxing</b>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/neha-sharma.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Neha Sharma </span>
                                            <b>Art & Craft Teacher</b>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/rohan-singh.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Rohan Singh </span>
                                            <b>Guitar Teacher</b>

                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/rahul-yadav.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Rahul Yadav </span>
                                            <b>Art & Craft Teacher</b>

                                        </div>
                                    </div>
                                    <div>
                                        <div class="meetContent">
                                            <figure><img src="{{ asset('frontend/images/priyanka-singh.jpg') }}"
                                                    alt="">
                                            </figure>
                                            <span>Priyanka Singh </span>
                                            <b>Guitar Teacher</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="studentSayAbout">
                        <div class="container">
                            <div class="section-heading">
                                <h2 class="text-white"><span class="greenBorder"></span>
                                    What Our Student Say About Mittlearn</h2>
                                <p class="text-white">simply dummy text of the printing and typesetting industry.</p>
                            </div>
                            <div class="aboutSliderSec position-relative">
                                <div class="topImg">
                                    <lottie-player src="{{ asset('frontend/images/customer-response.json') }}"
                                        background="transparent" speed="1" style="width: 250px; height: 250px;"
                                        loop autoplay></lottie-player>
                                </div>
                                <div class="sayAboutSlider">

                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="sayAbout">
                                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum
                                                has
                                                been the industry's standard dummy text ever since the 1500s, when an
                                                unknown printer
                                                took a
                                                galley of type and scrambled it to make a type specimen book.</p>
                                            <div class="sayProfile">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/profile-img.jpg') }}"
                                                        alt="">
                                                </figure>
                                                <strong><b>Nisha Singh</b> Sr. UI/UX Designer</strong>
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
                                    Our Association brings Advantages to Schools, Students, Parents, and Individuals</h2>
                            </div>

                            <div class="row flex-row-reverse">
                                <div class="col-md-6 position-relative mb-4 mb-md-0">
                                    <ul class="nav nav-tabs benefitsTab">
                                        <li class="nav-item">
                                            <button class="nav-link active" data-bs-toggle="tab"
                                                data-bs-target="#schoolTab" type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/schools-icon.svg') }}"
                                                        alt="" width="25" height="25">
                                                </figure>
                                                Schools
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#studentTab"
                                                type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/student-icon1.svg') }}"
                                                        alt="" width="25" height="25">
                                                </figure>Students
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parentTab"
                                                type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/parents-icon.svg') }}"
                                                        alt="" width="40" height="25">
                                                </figure>Parents
                                            </button>
                                        </li>
                                        <li class="nav-item">
                                            <button class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#individualsTab" type="button">
                                                <figure>
                                                    <img src="{{ asset('frontend/images/individuals-icon.svg') }}"
                                                        alt="" width="25" height="25">
                                                </figure>Individuals
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="arrowImg">
                                        <lottie-player src="{{ asset('frontend/images/arrow.json') }}"
                                            background="transparent" speed="1" style="width: 90px; height: 90px;"
                                            loop autoplay></lottie-player>
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
                                                        <p>Schools can diversify their offerings by incorporating
                                                            supplementary courses,
                                                            enriching the educational experience and catering to a broader
                                                            range of
                                                            student interests</p>
                                                    </li>
                                                    <li>
                                                        <strong>Enhanced Reputation:</strong>
                                                        <p>Providing students with opportunities to learn additional skills
                                                            and earn
                                                            certifications can enhance the school's reputation and attract a
                                                            more
                                                            diverse student body.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Cost-Efficient Training:</strong>
                                                        <p>Schools can leverage existing talents within their faculty or tap
                                                            into
                                                            external expertise to provide specialized training without
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
                                                        <p>Students can explore and learn new talents from the comfort of
                                                            their homes,
                                                            adapting their study schedule to their preferences.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Skill Enhancement:</strong>
                                                        <p>Existing skills can be taken to the next level, allowing students
                                                            to
                                                            continually improve and excel in their chosen areas of interest.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <strong>Certification and Recognition:</strong>
                                                        <p>Completion of courses with add-on quizzes and interactive
                                                            worksheets leads to
                                                            valuable certifications, showcasing their expertise to potential
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
                                                        <p>Parents can save on commuting, material, and possibly tuition
                                                            fees by opting
                                                            for online courses, making quality education more affordable.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <strong>Supervised Learning:</strong>
                                                        <p>Parents can monitor their child's progress and engagement in the
                                                            courses,
                                                            ensuring a productive learning experience and providing support
                                                            as needed.
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <strong>Diverse Learning Opportunities:</strong>
                                                        <p>Online courses offer a wider range of subjects and skills,
                                                            enabling parents
                                                            to help their children explore various interests and aptitudes
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
                                                        <p>Individuals can broaden their skill set by accessing courses that
                                                            are not
                                                            part of their formal education, enabling well-rounded personal
                                                            and
                                                            professional growth.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Tailored Learning:</strong>
                                                        <p>Online courses allow individuals to focus on specific areas of
                                                            interest or
                                                            skills they want to acquire, tailoring their learning journey to
                                                            match their
                                                            unique aspirations.</p>
                                                    </li>
                                                    <li>
                                                        <strong>Career Advancement:</strong>
                                                        <p>Earning certifications from supplementary courses can enhance an
                                                            individual's
                                                            resume and open doors to new career opportunities for
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
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="blogContent">
                                                <figure class="blogImg">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/blog-img1.jpg') }}"
                                                            alt=""></a>
                                                </figure>
                                                <span>Technology</span>
                                                <h4><a href="">Unleashing the Power of Artificial Intelligence: A
                                                        Journey into
                                                        the....</a></h4>
                                                <p>simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                                    has been the
                                                    industry's standard dummy text ever since the.</p>
                                                <div class="blogProfile">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                    <strong><b>Mittlearn</b> Jan 29, 2024</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="blogContent">
                                                <figure class="blogImg">
                                                    <a href=""><img
                                                            src="{{ asset('frontend/images/blog-img2.jpg') }}"
                                                            alt=""></a>
                                                </figure>
                                                <span>Design</span>
                                                <h4><a href="">Unleashing the Power of Artificial Intelligence: A
                                                        Journey into
                                                        the....</a></h4>
                                                <p>simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                                    has been the
                                                    industry's standard dummy text ever since the.</p>
                                                <div class="blogProfile">
                                                    <figure>
                                                        <img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                            alt="">
                                                    </figure>
                                                    <strong><b>Mittlearn</b> Jan 29, 2024</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 border-start ps-md-4">
                                    <ul class="recentBlogList">
                                        <li>
                                            <strong>Mastering the Melodies: Guitar Mastery Course for At-Home
                                                Learning.</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                        <li>
                                            <strong>Unveiling the Enchanting Melodies of the Guitar: A Musical
                                                Journey</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                        <li>
                                            <strong>Crafting Rhythmic Magic: The Art of Love Beatbox</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                        <li>
                                            <strong>Unraveling the Myths: Dispelling Misconceptions About Data
                                                Science</strong>
                                            <a href="">Learn More</a>
                                        </li>
                                    </ul>
                                    <div class="text-end">
                                        <a href="" class="btn btn-success rounded-1 fs-7">
                                            View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        </section>
    </div>
@endsection
