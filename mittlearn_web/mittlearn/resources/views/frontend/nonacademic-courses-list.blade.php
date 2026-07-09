@extends('frontend.layouts.master')

@section('content')
    <div>
        <div class="courseBanner courseBanner2">
            <div class="container">
                <div class="bannerTxt position-relative">
                    <div class="scrollDownJson">
                        <lottie-player src="{{ asset('frontend/images/Double-lines.json') }}" background="transparent"
                            speed="1" style="width: 198px; height: 198px;" loop autoplay></lottie-player>
                    </div>
                    <h1>High-Demand Talent-Skills Courses</h1>
                    <p>Empower Your Learning with Top-Selling Programs</p>
                    <span>Explore all of our courses and pick your suitable ones to <br> enroll and start earning with
                        us!</span>
                    {{-- <a href="" class="btn btn-primary-gradient rounded-1 fs-7 px-4">Explore</a> --}}
                </div>

            </div>
        </div>

        <div class="courseSection viewCourse">
            <div class="container">
                <div class="d-md-flex justify-content-between gap-3">
                    <div class="section-heading text-start mx-0">
                        <h2 class="text-black"><span class="greenBorder"></span>
                            Top Selling Programs for Talent & Skill</h2>
                        <p>Explore courses from experienced, real-world experts.</p>

                    </div>
                    {{-- @dd($subCategories) --}}
                    <div class=" d-flex align-items-center gap-3 viewCategory">
                        <label>View by Category</label>
                        <form method="GET" action="{{ request()->url() }}">
                            <select class="form-control form-select" name="sub_category_id" onchange="this.form.submit()">
                                <option value="" selected>All Talent Courses</option>
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory['id'] }}"
                                        @if ($subCategory['id'] == $selectedSubCategory) selected @endif>
                                        {{ $subCategory['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <div id="loading-spinner" style="display: none; text-align: center;">
                    <img src="{{ asset('frontend/images/loader.gif') }}" alt="Loading...">
                </div>

                <div class="row px-md-1">
                    @foreach ($nonAcadCoursesList as $course)
                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                            <div class="coursesBox nonacadBx">
                                <figure class="position-relative">
                                    @php
                                        $bookCoverImage = $course->metadataValues->firstWhere(
                                            'field_name',
                                            'banner_image',
                                        );
                                        $originalPrice = $course->price;
                                        if ($course->discount_type == 'percent') {
                                            $discountedPrice =
                                                $originalPrice - $originalPrice * ($course->discount_value / 100);
                                        } elseif ($course->discount_type == 'flat') {
                                            $discountedPrice = $originalPrice - $course->discount_value;
                                        } else {
                                            $discountedPrice = $originalPrice;
                                        }
                                    @endphp
                                    <a href="{{ route('about-nonacadcourse', $course->slug) }}">
                                        @if ($bookCoverImage)
                                            <img src="{{ Storage::url($bookCoverImage->field_value) }}"
                                                alt="Book Cover Image">
                                        @else
                                            <img src="{{ asset('frontend/images/default-image.jpg') }}" alt="Default Image">
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

                                    @if (in_array($course->id, $recentCourseIds))
                                        <div class="labelTxt">Recently Added</div>
                                    @endif
                                    @if (in_array($course->id, $filteredTopNonAcademicCourseIds))
                                        <div class="labelTxt">Popular</div>
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
                                <a href="{{ route('about-nonacadcourse', $course->slug) }}">
                                    <h3 class="px-2">{{ limit_words($course->course_name ?? 'No Course Name', 7) }}</h3>
                                </a>
                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                    <span><img src="{{ asset('frontend/images/lessons-icon.svg') }}" alt="mittlearn-image"
                                            width="14">
                                        {{ $course->totalChapters->count() }}
                                        Lessons</span>
                                    <span><img src="{{ asset('frontend/images/student-icon.svg') }}" alt="mittlearn-image"
                                            width="14">
                                        {{ $course->getSubCategory->name }}</span>
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
                                    <a href="{{ route('about-nonacadcourse', $course->slug) }}"
                                        class="btn btn-primary-gradient rounded-1">
                                        Know more
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="bottomToggleBtn">
        <span>Switch to Academic Content</span>
        <div class="toggleBtn">
            <input type="checkbox" id="switchacademic" />
            <label for="switchacademic"></label>
        </div>
    </div> -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const switchAcademic = document.getElementById('switchacademic');

            switchAcademic.addEventListener('change', function() {
                if (this.checked) {
                    // Optional: disable toggle to prevent double-clicks
                    this.disabled = true;

                    // Redirect to desired URL
                    window.location.href = "{{ route('courses.listing', 'academic') }}";
                }
            });
        });
    </script>


    <!-- V Added For Loading Spinner Start ---------------------->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('categoryFilterForm');
            const spinner = document.getElementById('loading-spinner');
            const coursesList = document.querySelector('.row.px-md-1');

            // Event listener on the select element
            document.getElementById('subcategorySelect').addEventListener('change', function() {
                spinner.style.display = 'block'; // Show spinner
                coursesList.style.display = 'none'; // Hide course list

                // Prepare AJAX request with cache-busting parameter
                const formData = new FormData(form);
                const queryString = new URLSearchParams(formData);
                queryString.append('_', Date.now());

                fetch(form.action + '?' + queryString, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        spinner.style.display = 'none';
                        coursesList.style.display = 'block';
                        coursesList.innerHTML = data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        spinner.style.display = 'none';
                        coursesList.style.display = 'block';
                    });
            });
        });
    </script>
    <!-- V Added For V Added For Loading Spinner End --------------->
@endsection
