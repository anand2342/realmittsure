@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('up.my.courses') }}">Subjects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Course Listing</li>
            </ol>
        </nav>

        <div class="row damceCourse">
            <div class="col-md-4 mb-3">
                <div class="cardBox chapterSide h-100">
                    <h3 class="fs-6 fw-semibold"> <span class="text-secondary fw-normal">Course Name </span>(Dance)</h3>
                    <div class="tableSearch my-3">
                        <input type="text" class="form-control w-100" placeholder="Search Chapter">
                    </div>
                    <h5>CHAPTERS(12)</h5>
                    <ul class="chapterList">
                        <li>
                            <button type="button" class="chapterBtn selected">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-first-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day First Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-second-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Second Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-third-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Third Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-four-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Fourth Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-fifth-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Fifth Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-six-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Sixth Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn ">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-first-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day First Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-second-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Second Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-third-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Third Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-four-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Fourth Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-fifth-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Fifth Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="chapterBtn">
                                <figure>
                                    <img src="{{ asset('frontend/images/day-six-img.jpg') }}" alt="">
                                </figure>
                                <div class="w-100">
                                    <p>Day Sixth Title Here</p>
                                    <div class="">
                                        <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                width="12"> 34:45</span>
                                    </div>
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8 ps-md-1 mb-3">
                <div class="cardBox p-0 chapterDetail h-100">
                    <!-- code -->
                    <video style="width: 100%;height:300px;object-fit: cover;" controls controlsList="nodownload"
                        oncontextmenu="return false;">
                        <source src="{{ asset('frontend/images/dance.mp4') }}" type="video/mp4">
                    </video>
                    <div class="p-3">
                        <div class="d-md-flex flex-wrap justify-content-between mb-3">
                            <div class="chapterNme">
                                <h4>Lorem Ipsum Simply dummy text here</h4>
                                <span>Instructor : Neha Yadav | Duration : 35:40 Min</span>
                            </div>
                            <div class="chapterSlide d-flex align-items-center gap-2 justify-content-end">
                                <span class="me-2">CHAPTERS 1 / 12</span>
                                <button type="button" class="prevBtn"><img
                                        src="{{ asset('frontend/images/previcon.svg') }}" width="30"></button>
                                <button type="button" class="nextBtn"><img
                                        src="{{ asset('frontend/images/nexticon.svg') }}" width="30"></button>
                            </div>
                        </div>

                        <span class="blueTxt">CLASS DETAILS</span>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has
                            been the industry's standard dummy text ever since the 1500s, when an unknown printer took a
                            galley of type and scrambled it to make a type specimen book. It has survived not only five
                            centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum
                            passages, and more recently with desktop publishing software like Aldus PageMaker including
                            versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of the printing and typesetting
                            industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when
                            an unknown printer took a galley of type and scrambled it to make a type specimen book. It
                            has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the release of
                            Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
                            software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                    </div>

                </div>
            </div>
        </div>

        <div class="cardBox danceChep">
            <h3 class="fs-6 fw-semibold mb-4">Digital Content <span class="text-secondary fw-normal"> (10)</span> </h3>
            <ul class="chapterList">
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance1.jpg') }}" alt="">
                            <button type="button" class="plybtn" data-bs-toggle="modal"
                                data-bs-target="#coursePreview"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 1</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance2.jpg') }}" alt="">
                            <button type="button" class="plybtn" data-bs-toggle="modal"
                                data-bs-target="#coursePreview"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 2</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance3.jpg') }}" alt="">
                            <button type="button" class="plybtn" data-bs-toggle="modal"
                                data-bs-target="#coursePreview"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 3</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance4.jpg') }}" alt="">
                            <button type="button" class="plybtn"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 4</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance5.jpg') }}" alt="">
                            <button type="button" class="plybtn"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 5</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance1.jpg') }}" alt="">
                            <button type="button" class="plybtn" data-bs-toggle="modal"
                                data-bs-target="#coursePreview"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 6</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance2.jpg') }}" alt="">
                            <button type="button" class="plybtn" data-bs-toggle="modal"
                                data-bs-target="#coursePreview"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 7</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance3.jpg') }}" alt="">
                            <button type="button" class="plybtn" data-bs-toggle="modal"
                                data-bs-target="#coursePreview"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 8</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance4.jpg') }}" alt="">
                            <button type="button" class="plybtn"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 9</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="chapterBtn">
                        <figure class="position-relative">
                            <img src="{{ asset('frontend/images/dance5.jpg') }}" alt="">
                            <button type="button" class="plybtn"><img src="{{ asset('frontend/images/play-btn.svg') }}"
                                    alt="" width="30"></button>
                        </figure>
                        <div class="w-100 p-2">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badgeDay"> Day 10</span>
                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt="" width="12">
                                    34:45</span>
                            </div>
                            <p>Lorem Ipsum Dummy Text</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection
