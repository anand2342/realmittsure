@extends('frontend.layouts.master')

@section('content')
    <script>
        var base_url = "{{ url('/') . '/' }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    <div>
        <div class="courseBanner">
            <div class="container">
                <div class="bannerTxt position-relative">
                    <div class="scrollDownJson">
                        <lottie-player src="{{ asset('frontend/images/Scroll-Down.json') }}" background="transparent"
                            speed="1" style="width: 98px; height: 98px;" loop autoplay></lottie-player>
                    </div>
                    <h1>High-Demand Academic Courses</h1>
                    <p>Empower Your Learning with Top-Selling Programs</p>
                    <span>Explore all of our courses and pick your suitable ones<br> to enroll and start learning with
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
                            Top Selling Programs for Academic</h2>
                        <p>Explore courses from experienced, real-world experts.</p>

                    </div>
                    {{-- <div class=" d-flex align-items-center gap-3 viewCategory">
                        <label>View by Category</label>
                        <form method="GET" action="{{ request()->url() }}">
                            <select class="form-control form-select" name="sub_category_id" onchange="this.form.submit()">
                                <option value="">All Academic Courses</option>
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory['id'] }}"
                                        @if ($subCategory['id'] == $selectedSubCategory) selected @endif>
                                        {{ $subCategory['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div> --}}
                    <div class=" d-flex align-items-center gap-3 viewCategory">
                        <!-- Filter Form (aligned to right side) -->
                        <form method="GET" action="{{ request()->url() }}"
                            class="d-flex flex-wrap gap-2 align-items-center">
                            <div class="d-flex flex-column">
                                <label class="small mb-1">Filter By Class</label>
                                <select class="form-control form-select" name="class_id" onchange="this.form.submit()"
                                    style="min-width: 150px;">
                                    <option value="">All Classes</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-flex flex-column">
                                <label class="small mb-1">Filter By Subject</label>
                                <select class="form-control form-select" name="subject_id" onchange="this.form.submit()"
                                    style="min-width: 150px;">
                                    <option value="">All Subjects</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}"
                                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="loading-spinner" style="display: none; text-align: center;">
                    <img src="{{ asset('frontend/images/loader.gif') }}" alt="Loading...">
                </div>

                <div class="row px-md-1">
                    @foreach ($acadCoursesList as $course)
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
                                    @if (in_array($course->id, $recentCourseIds))
                                        <div class="labelTxt">Recently Added</div>
                                    @endif
                                    @if (in_array($course->id, $filteredTopAcademicCourseIds))
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
                                <a href="{{ route('about-acadcourse', $course->slug) }}">
                                    <h3 class="px-2">{{ limit_words($course->course_name ?? 'No Course Name', 7) }}</h3>
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
    <div class="bottomToggleBtn">
        <span>Switch to Talent Content</span>
        <div class="toggleBtn">
            <input type="checkbox" id="switchacademic" />
            <label for="switchacademic"></label>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const switchAcademic = document.getElementById('switchacademic');

            switchAcademic.addEventListener('change', function() {
                if (this.checked) {
                    // Optional: disable toggle to prevent double-clicks
                    this.disabled = true;

                    // Redirect to desired URL
                    window.location.href = "{{ route('courses.listing', 'talent-skills') }}";
                }
            });
        });
    </script>
@endsection
