@extends('frontend.layouts.master')

@section('content')
    <script>
        var base_url = "{{ url('/') . '/' }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
    @php
        $activeTab = session()->get('user_activeTab');
        $childCategories = getCategoriesWithChild();
        $allChildCategories = categoriesToArray($childCategories);
    @endphp
    <div class="subscriptionBanner">
        <div class="container">
            <div class="bannerTxt">
                <h1>Subscription {{ $plan->name }}</h1>
                <p class="d-flex gap-2 justify-content-center align-items-center">Get started now <lottie-player
                        src="{{ asset('frontend/images/semicircle-arrow.json') }}" background="transparent" speed="1"
                        style="width: 40px; height: 40px;" loop autoplay></lottie-player></p>
                <div class="subscriptionTab">
                    <ul>
                        {{-- <li><button type="button" class="tbButton @if ($activeTab == 'academic') active @endif"
                                data-category-id="1"><i class="bi bi-check-circle"></i> Academic</button></li> --}}
                        <li><button type="button" class=" active tbButton @if ($activeTab == 'nonacademic') active @endif"
                                data-category-id="2"><i class="bi bi-check-circle"></i>
                                Talent & Skills</button></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bottomLottie">
            <lottie-player src="{{ asset('frontend/images/shapes-loader.json') }}" background="transparent" speed="1"
                style="width: 186px; height: 136px;" loop autoplay></lottie-player>
        </div>
    </div>

    <div class="container">
        <div id="planPackMsg" class="discountBx w-50 mx-auto mt-3" style="display: none">
        </div>
        <div class="dropdownFilter" id="academicGroupFilter">
            <div class="row">
                <div class="col-6 col-md-4 mb-3">
                    <div class="form-group" id="board-group">
                        {!! Form::label('board_id', 'Select Board', ['class' => 'form-label required']) !!}
                        {!! Form::select('board_id', $boards, null, [
                            'class' => 'form-select',
                            'id' => 'board-select',
                            'placeholder' => '--Select--',
                        ]) !!}
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-3">
                    <div class="form-group" id="medium-group">
                        {!! Form::label('medium_id', 'Select Medium', ['class' => 'form-label required']) !!}
                        {!! Form::select('medium_id', $mediums, null, [
                            'class' => 'form-select',
                            'id' => 'medium-select',
                            'placeholder' => '--Select--',
                        ]) !!}
                    </div>
                </div>
                <div class="col-6 col-md-4 mb-3">
                    <div class="form-group" id="class-group">
                        {!! Form::label('class_id', 'Select Class', ['class' => 'form-label required']) !!}
                        {!! Form::select('class_id', $classes, null, [
                            'class' => 'form-select',
                            'id' => 'class-select',
                            'placeholder' => '--Select--',
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdownFilter" id="non-academicGroupFilter">
            <div class="row">
                <div class="col-6 col-md-4 mb-3" id="subcategory-group">
                    {!! Form::label('subcategory_id', 'Subgroup', ['class' => 'form-label required']) !!}
                    {{ Form::text('subcategory_id', null, ['class' => 'form-select', 'id' => 'subcategory-select', 'autocomplete' => 'off', 'placeholder' => '--Select--']) }}
                    {!! Form::hidden('subcategory_id', '', ['id' => 'subCategoryInput']) !!}
                </div>
            </div>
        </div>

        <div class="courseSection viewCourse mt-0">
            <div class="row px-md-1" id="courses-list">

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let userActivetab = @json($activeTab);
            let defaultCategoryId = userActivetab === 'nonacademic' ? 2 : 1; // Set default based on active tab

            // Initialize UI based on active tab
            if (userActivetab == 'nonacademic') {
                $('#non-academicGroupFilter').show();
                $('#academicGroupFilter').hide();
                // Clear courses list
                $('#courses-list').html(`<div class="freetext">
                    <lottie-player src="{{ asset('frontend/images/arrow-1.json') }}" background="transparent" speed="1"
                        style="width: 100px; height: 100px;margin: auto;" loop autoplay></lottie-player>
                    <h4>Please Select options</h4>
                    <p>No options are select, Choose option to view courses into your plan</p>
                </div>`);
            } else {
                $('#non-academicGroupFilter').hide();
                $('#academicGroupFilter').show();
                // Clear courses list
                $('#courses-list').html(`<div class="freetext">
                    <lottie-player src="{{ asset('frontend/images/arrow-1.json') }}" background="transparent" speed="1"
                        style="width: 100px; height: 100px;margin: auto;" loop autoplay></lottie-player>
                    <h4>Please Select options</h4>
                    <p>No options are select, Choose option to view courses into your plan</p>
                </div>`);
            }

            //get BookSeries dropdown:
            const boardSelect = document.getElementById('board-select');
            const mediumSelect = document.getElementById('medium-select');
            const bookSeriesSelect = document.getElementById('book-series-select');

            function fetchBookSeries() {
                const boardId = boardSelect.value;
                const mediumId = mediumSelect.value;

                // Clear existing options
                bookSeriesSelect.innerHTML = '<option value="">--Select--</option>';

                if (boardId && mediumId) {
                    fetch(`/get-book-series?board_id=${boardId}&medium_id=${mediumId}`)
                        .then(response => response.json())
                        .then(data => {
                            for (const [id, name] of Object.entries(data)) {
                                const option = document.createElement('option');
                                option.value = id;
                                option.textContent = name;
                                bookSeriesSelect.appendChild(option);
                            }
                        })
                        .catch(error => console.error('Error fetching book series:', error));
                }
            }

            boardSelect.addEventListener('change', fetchBookSeries);
            mediumSelect.addEventListener('change', fetchBookSeries);

            //get subcategory dropdown:
            let type = '{{ $type ?? null }}';
            let itemId = '{{ $plan_id }}';
            let sessionId = globalVar.sessionId;
            let comboTreeForCourses;

            const jsonData = {!! json_encode($allChildCategories) !!};
            const selectedSubCategory =
                {{ isset($selectedSubCategory) ? json_encode($selectedSubCategory) : 'null' }};
            comboTreeForCourses = $("#subcategory-select").comboTree({
                source: jsonData,
                isMultiple: false,
                selected: selectedSubCategory ? [selectedSubCategory] : [],
            });

            if (comboTreeForCourses?.onChange) {
                comboTreeForCourses.onChange(() => {
                    selectedItems = comboTreeForCourses.getSelectedIds()[0];
                    $('#subCategoryInput').val(selectedItems);
                    // Trigger filter immediately when subcategory changes for non-academic
                    if (defaultCategoryId === 2) {
                        loadCourses(2);
                    }
                });
            }

            // Initialize the first button
            let firstButton = $('.tbButton').first();
            if (firstButton.length > 0) {
                firstButton.data('category-id', firstButton.data('category-id') || defaultCategoryId);
                // Set the active tab based on defaultCategoryId
                $('.tbButton').removeClass('active');
                $(`.tbButton[data-category-id="${defaultCategoryId}"]`).addClass('active');

                // Load courses if filters are already set
                if (defaultCategoryId === 1) {
                    // Check if academic filters are set
                    var boardId = $('#board-select').val();
                    var mediumId = $('#medium-select').val();
                    var classId = $('#class-select').val();

                    if (boardId && mediumId && classId) {
                        loadCourses(1);
                    }
                } else if (defaultCategoryId === 2) {
                    // Check if non-academic filter is set
                    const subcategoryId = $('#subCategoryInput').val();
                    if (subcategoryId) {
                        loadCourses(2);
                    }
                }
            }

            // Handle tab button clicks
            $('.tbButton').on('click', function() {
                let categoryId = $(this).data('category-id') || defaultCategoryId;
                defaultCategoryId = categoryId; // Update the default category ID

                // Toggle active class
                $('.tbButton').removeClass('active');
                $(this).addClass('active');

                // Show/hide filter groups
                if (categoryId == 1) {
                    $('#academicGroupFilter').show();
                    $('#non-academicGroupFilter').hide();
                } else if (categoryId == 2) {
                    $('#academicGroupFilter').hide();
                    $('#non-academicGroupFilter').show();
                }

                // Clear courses list
                $('#courses-list').html(`<div class="freetext">
                    <lottie-player src="{{ asset('frontend/images/arrow-1.json') }}" background="transparent" speed="1"
                        style="width: 100px; height: 100px;margin: auto;" loop autoplay></lottie-player>
                    <h4>Please Select options</h4>
                    <p>No options are select, Choose option to view courses into your plan</p>
                </div>`);
            });

            // Academic filter change handler
            $('#board-select, #medium-select, #class-select').change(function() {

                if (defaultCategoryId === 1) {
                    loadCourses(1);
                }
            });

            // Non-academic filter change handler
            $('#subcategory-select').on('change', function() {
                if (defaultCategoryId === 2) {
                    loadCourses(2);
                }
            });

            // Function to load courses based on category
            function loadCourses(categoryId) {
                const coursesList = $('#courses-list');

                if (categoryId == 1) {
                    var boardId = $('#board-select').val();
                    var mediumId = $('#medium-select').val();
                    var classId = $('#class-select').val();

                    if (boardId && mediumId && classId) {
                        $.ajax({
                            url: `{{ route('get-courses', '') }}/${categoryId}`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                category_id: categoryId,
                                board_id: boardId,
                                medium_id: mediumId,
                                class_id: classId,
                                item_id: itemId,
                                session_id: sessionId,
                                type: type
                            },
                            success: function(courses) {
                                renderCourses(courses, categoryId);
                            },
                            error: function() {
                                coursesList.html('<p>Error loading courses.</p>');
                            }
                        });
                    }
                } else if (categoryId == 2) {
                    const subcategoryId = $('#subCategoryInput').val();
                    if (subcategoryId) {
                        $.ajax({
                            url: `{{ route('get-courses-by-subcategory', '') }}/${subcategoryId}`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                subcategory_id: subcategoryId,
                                item_id: itemId,
                                session_id: sessionId,
                                type: type
                            },
                            success: function(courses) {
                                renderCourses(courses, categoryId);
                            },
                            error: function() {
                                if (type == 'free-courses') {
                                    coursesList.html(
                                        '<p>No free Talent & Skills courses available for this plan.</p>'
                                    );
                                } else {
                                    coursesList.html('<p>Loading Error... </p>');
                                }
                            }
                        });
                    }
                }
            }

            // Function to render courses
            function renderCourses(courses, categoryId) {
                const coursesList = $('#courses-list');
                coursesList.html('');

                if (courses.length > 0) {
                    $.each(courses, function(index, course) {
                        const cartImage = course.in_cart ?
                            '{{ asset('frontend/images/cart-icon-saved.svg') }}' :
                            '{{ asset('frontend/images/cart-icon.svg') }}';
                        const cartAddedClass = course.in_cart ? 'cartAdded' : '';
                        const wishlistImage = course.in_wishlist ?
                            '{{ asset('frontend/images/red-heart-icon.svg') }}' :
                            '{{ asset('frontend/images/heart-icon.svg') }}';
                        const defaultImg = 'uploads/course_files/default-image.jpg';
                        // Only show class and subject info for academic courses (categoryId = 1)
                        const classSubjectHtml = categoryId == 1 ?
                            `<div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                <span><img
                                        src="{{ asset('frontend/images/student-icon.svg') }}"
                                        alt="mittlearn-image" width="14">${course.class_name}
                                    </span>
                                <span><img
                                        src="{{ asset('frontend/images/student-icon.svg') }}"
                                        alt="mittlearn-image" width="14">${course.subject_name}</span>
                            </div>` : '';

                        const popularTagHtml = index < 5 ? '<div class="labelTxt">Popular</div>' : '';
                        // console.log(course);

                        let courseHtml = `
                            <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                <div class="coursesBox">
                                    <figure>
                                        <img src="{{ Storage::url('') }}${categoryId == 1 ? (course.thumbnail_image ? course.thumbnail_image : course.book_cover_image) : (course.banner_image ? course.banner_image : defaultImg)}"
                                            alt="Book-Cover-Image">
                                        ${popularTagHtml}
                                        <button type="button" class="bg-transparent border-0 p-0 wishlistButton" 
                                            data-course-id="${course.id}" data-item-id="${itemId}" data-item-type="${categoryId == 1 ? 'academic_course' : 'nonacademic_course'}">
                                            <img src="${wishlistImage}" class="wishlist-icon-${course.id}" width="20">
                                        </button>
                                    </figure>
                                    <h3 class="px-2">${course.course_name ?? 'No Course Name'}</h3>
                                    ${classSubjectHtml}
                                    <div class="d-flex gap-2 justify-content-between px-2 mt-2">
                                        <div class="blogProfile">
                                            <strong><b>Mittlearn</b></strong>
                                        </div>
                                        <div class="d-flex gap-3">
                                            <button type="button" class="bg-transparent border-0 p-0 add-to-cart-btn crtBtn ${cartAddedClass}"
                                                data-item-id="${itemId}" data-item-type="${categoryId == 1 ? 'academic_course' : 'nonacademic_course'}" 
                                                data-course-id="${course.id}" data-course-full-price="${course.price}" 
                                                data-course-price="${
                                                    course.discount_type === 'flat' ? 
                                                    course.price - course.discount_value : 
                                                    course.discount_type === 'percent' ? 
                                                    course.price - (course.discount_value / 100) * course.price : 
                                                    course.price
                                                }">
                                                <img src="${cartImage}" alt="Cart Icon" class="cart-icon-${course.id}" width="20">
                                            </button>
                                            <input type="hidden" name="cart_id" id="savedCartId" value="">
                                            <input type="hidden" name="wishlist_id" id="savedWishlistId" value="">
                                            <input type="hidden" name="user_id" id="userAuthId" value="{{ auth()->check() ? auth()->id() : null }}">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                        <div class="pricetag">
                                            <span>₹ ${course.price}</span>
                                            ${course.discount_type === 'flat' ? 
                                            `₹ ${course.price - course.discount_value}` : 
                                            course.discount_type === 'percent' ? 
                                            `₹ ${course.price - (course.discount_value / 100) * course.price}` :
                                            `₹ ${course.price}`}
                                        </div>
                                        <a href="${categoryId == 1 ? '{{ route('about-acadcourse', '') }}' : '{{ route('about-nonacadcourse', '') }}'}/${course.slug}" 
                                            class="btn btn-primary-gradient rounded-1">Know more</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        coursesList.append(courseHtml);
                    });
                } else {
                    coursesList.html('<p>No courses available for this selection.</p>');
                }
            }
        });
    </script>
@endsection
