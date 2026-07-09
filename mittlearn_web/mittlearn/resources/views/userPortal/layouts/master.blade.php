<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $links['site_page_title'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- <link href="../css/style.css" rel="stylesheet"> --}}
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet">
    {{-- crooper --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="{{ asset('frontend/js/init.js') }}"></script>

    <script>
        var base_url = "{{ url('/') . '/' }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
</head>

<body style="background-color: #F9F9F9;">


    @include('userPortal.layouts.header')

    <main id="main" class="main">
        @yield('content')
    </main>
    <div class="modal fade" id="profile">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content border-0 rounded-1">
                <div class="modal-body p-0">
                    <div class="profileMain">
                        <div class="profileSidebar ">
                            <div class="profileUpload">
                                <figure class="position-relative m-0">
                                    <img id="profileImage"
                                        src="{{ Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('frontend/images/default-image.jpg') }}"
                                        alt="Profile Image">
                                    <label for="profileHeader" class="contentprf">
                                        <div class="text-white">
                                            <img src="{{ asset('frontend/images/edit-upload.svg') }}"
                                                class="d-block mx-auto mb-3" alt="" width="14">
                                            Click to change profile <br> Image
                                        </div>
                                        <input type="file" name="image" id="profileHeader" class="d-none"
                                            accept="image/*">
                                    </label>
                                </figure>
                            </div>
                            <ul class="nav nav-pills flex-column mb-3 profileTabs">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="pill"
                                        data-bs-target="#schoolDetails" type="button"><img
                                            src="{{ asset('frontend/images/school-details-icon.svg') }}" alt=""
                                            width="14" class="me-3">User Details</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#passwordChange"
                                        type="button"><img
                                            src="{{ asset('frontend/images/password-change-icon.svg') }}" alt=""
                                            width="16" class="me-3">Password Change</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link text-danger"type="button" data-bs-target="#logOut"
                                        data-bs-toggle="modal">
                                        <img src="{{ asset('frontend/images/logout-icon.svg') }}" alt=""
                                            width="14" class="me-3">Log out
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="profileRight">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="schoolDetails">
                                    <h1 class="modal-title fs-4 fw-semibold">Student Details</h1>
                                    <div class="formPanel">
                                        {!! Form::open(['url' => route('up.update.profile.details'), 'method' => 'post']) !!}
                                        @csrf
                                        {!! Form::hidden('id', $currentUser->id ?? '') !!}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('name', 'Full Name') !!}
                                                    {!! Form::text('name', $currentUser->name ?? '', [
                                                        'class' => 'form-control readonly',
                                                        'readonly',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('email', 'Email') !!}
                                                    {!! Form::text('email', $currentUser->email ?? '', [
                                                        'class' => 'form-control readonly',
                                                        'readonly',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('mobile_no', 'Parent Mobile No.') !!}
                                                    {!! Form::text('mobile_no', $currentUser->mobile_no ?? '', [
                                                        'class' => 'form-control readonly',
                                                        'readonly',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('school_id', "School's Name") !!}
                                                    {!! Form::text('school_id', $currentUser->studentDetails->schoolDetails->name ?? '', [
                                                        'class' => 'form-control readonly ',
                                                        'readonly',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('dob', 'Date Of Birth') !!}
                                                    {!! Form::date('dob', $currentUser->studentDetails->dob ?? '', [
                                                        'class' => 'form-control ',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            @if (getUserRoles() == 'b2c_student')
                                                {{-- @dd($studentSelectClasses) --}}
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        {!! Form::label('class', 'Class Name') !!}
                                                        {!! Form::select('class', $studentSelectClasses, old('class', $currentUser->studentDetails->class ?? null), [
                                                            'class' => 'form-select ',
                                                            'placeholder' => 'Select Your Class',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        {!! Form::label('class', 'Class Name') !!}
                                                        {!! Form::text('class', $currentUser->studentDetails->className->name ?? '', [
                                                            'class' => 'form-control readonly ',
                                                            'readonly',
                                                        ]) !!}
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('admission_no', 'Admission Number') !!}
                                                    {!! Form::text('admission_no', $currentUser->userAdditionalDetail->admission_no ?? '', [
                                                        'class' => 'form-control readonly ',
                                                        'readonly',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('doj', 'Date of Joining') !!}
                                                    {!! Form::text('doj', $currentUser->studentDetails->doj ?? '', [
                                                        'class' => 'form-control readonly ',
                                                        'readonly',
                                                    ]) !!}
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('address', 'Address') !!}
                                                    {!! Form::text('address', $currentUser->studentDetails->address ?? '', [
                                                        'class' => 'form-control ',
                                                    ]) !!}
                                                </div>
                                            </div> --}}


                                            <h2 class="fs-6 fw-semibold mb-4 mt-4"> Address Details</h2>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('postal_code', 'Pin Code') !!}
                                                    {!! Form::text('postal_code', $currentUser->userAdditionalDetail->postal_code ?? '', [
                                                        'class' => 'form-control ',
                                                    ]) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('state', 'State') !!}
                                                    {!! Form::select('state', $states, old('state', $currentUser->userAdditionalDetail->state ?? null), [
                                                        'class' => 'form-select ',
                                                        'placeholder' => 'Select',
                                                        'id' => 'state-select',
                                                    ]) !!}
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group mb-3 ">
                                                    {!! Form::label('city', 'District') !!}
                                                    {!! Form::select('city', [], old('city', $currentUser->userAdditionalDetail->city ?? null), [
                                                        'class' => 'form-select',
                                                        'placeholder' => 'Select',
                                                        'id' => 'city-select',
                                                        'data-selected-city' => $currentUser->userAdditionalDetail->city ?? null,
                                                    ]) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    {!! Form::label('address', 'Address') !!}
                                                    {!! Form::text('address', $currentUser->userAdditionalDetail->address ?? '', [
                                                        'class' => 'form-control ',
                                                    ]) !!}
                                                </div>
                                            </div>




                                            <div class="col-md-12 text-center my-2 mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary-gradient fs-7 rounded-2 w-75">Submit</button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                    {{-- <div class="formPanel">
                                        <div class="row">
                                            
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="tab-pane fade" id="passwordChange">
                                    <h1 class="modal-title fs-4 fw-semibold">Change Password</h1>
                                    <div class="formPanel">
                                        <form method="post" action="{{ route('up.change.password') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label>Current Password</label>
                                                        <div class="position-relative">
                                                            <input type="password"
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                id="password" name="password"
                                                                placeholder="Enter current Password">
                                                            <span class="eyeInput eye_icon" data-id="password">
                                                                <i class="bi bi-eye-slash"></i>
                                                            </span>
                                                        </div>
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label>Enter New Password</label>
                                                        <div class="position-relative">
                                                            <input
                                                                class="form-control @error('password') is-invalid @enderror"
                                                                type="password" name="newpassword" id="newpassword"
                                                                required placeholder="Enter New Password">
                                                            @if (session('error'))
                                                                <span>
                                                                    <label
                                                                        class="error">{{ session('error') }}</label>
                                                                </span>
                                                            @endif
                                                            <span class="eyeInput eye_icon" data-id="newpassword">
                                                                <i class="bi bi-eye-slash"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group mb-3">
                                                        <label>Confirm New Password</label>
                                                        <div class="position-relative">
                                                            <input class="form-control" type="password"
                                                                name="newpassword_confirmation"
                                                                id="newpassword_confirmation" required
                                                                placeholder="Confirm New Password">
                                                            @if (session('error'))
                                                                <span>
                                                                    <label
                                                                        class="error">{{ session('error') }}</label>
                                                                </span>
                                                            @endif
                                                            <span class="eyeInput eye_icon"
                                                                data-id="newpassword_confirmation">
                                                                <i class="bi bi-eye-slash"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-center my-2 mt-4">
                                                    <button type="submit"
                                                        class="btn btn-primary-gradient fs-7 rounded-2 w-75">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageCropModal" tabindex="-1" aria-labelledby="imageCropModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageCropModalLabel">Crop Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="imageToCrop" src="" alt="Profile Image to Crop" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropImageBtn">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="logOut">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header justify-content-end align-items-start border-0">
                    <a href="javascript:void(0);" onclick="location.reload();"><button type="button"
                            class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></a>
                </div>
                <div class="modal-body pt-0">
                    <form>
                        <lottie-player src="{{ asset('frontend/images/logout.json') }}" background="transparent"
                            speed="1" style="width: 140px; height: 140px;margin: auto;" loop=""
                            autoplay=""></lottie-player>
                        <h6 class="text-center fw-semibold">Logout Account</h6>
                        <p class="text-center fs-8">Are you sure you want to logout? Once you logout you need to
                            login
                            again.
                        </p>
                        <div class="d-flex align-items-center justify-content-end flex-column mt-4">
                            <a href="{{ route('logout') }}"class="btn btn-primary-gradient fs-7 rounded-2 w-50 mb-2">Yes
                            </a>
                            <a href="javascript:void(0);" onclick="location.reload();">
                                <button type="button" class="btn backbtn fw-regular my-2">Back</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('frontend/js/script.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>

    {{-- croopper --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Target all modals with the 'coursePrv' class
            const modals = document.querySelectorAll('.modalvid');

            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Pause all videos inside the modal
                    const videos = modal.querySelectorAll('video');
                    videos.forEach(video => {
                        video.pause();
                        video.currentTime = 0; // Optional: Reset video to start
                    });
                });
            });
        });


        let cropper;
        let profileImageInput = document.getElementById('profileHeader');
        let profileImagePreview = document.getElementById('profileImage');

        // When a new image is selected
        profileImageInput.addEventListener('change', function(e) {
            const files = e.target.files;

            if (files && files.length > 0) {
                const file = files[0];

                if (!file.type.match('image.*')) {
                    alert('Please select an image file (jpeg, png, jpg, gif)');
                    return;
                }

                const imageURL = URL.createObjectURL(file);
                const imageToCrop = document.getElementById('imageToCrop');
                imageToCrop.src = imageURL;

                if (cropper) {
                    cropper.destroy();
                }

                const cropModal = new bootstrap.Modal(document.getElementById('imageCropModal'));
                cropModal.show();

                setTimeout(() => {
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 0.8,
                        responsive: true
                    });
                }, 200);
            }
        });

        document.getElementById('cropImageBtn').addEventListener('click', function() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                minWidth: 256,
                minHeight: 256,
                fillColor: '#fff',
                imageSmoothingQuality: 'high',
            });

            if (canvas) {
                canvas.toBlob(function(blob) {
                    const file = new File([blob], 'profile-image.jpg', {
                        type: 'image/jpeg'
                    });
                    const formData = new FormData();
                    formData.append('profile_image', file);

                    profileImagePreview.src = URL.createObjectURL(blob);

                    bootstrap.Modal.getInstance(document.getElementById('imageCropModal')).hide();

                    uploadProfileImage(formData);
                }, 'image/jpeg', 0.9);
            }
        });

        document.getElementById('imageCropModal').addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        function uploadProfileImage(formData) {
            fetch('{{ route('sp.upload.profile.image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const timestamp = new Date().getTime();
                        profileImagePreview.src = data.filePath + '?t=' + timestamp;
                        alert('Profile image updated successfully!');
                    } else {
                        throw new Error(data.message || 'Failed to upload profile image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error uploading image: ' + error.message);
                });
        }
    </script>

    <script>
        $('.alertList').slick({
            autoplay: true,
            slidesToShow: 1,
            arrows: false,
            dots: false,
            autoplaySpeed: 0,
            speed: 30000,
            cssEase: 'linear',
            variableWidth: true,
            pauseOnHover: true
        });
        $(document).ready(function() {
            setTimeout(function() {
                $('html, body').animate({
                    scrollTop: $('.dashboardMain').offset().top
                }, 500);
            }, 500);
        });
    </script>

    {{-- <script>
        document.getElementById('profileHeader').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('profile_image', file);

                // Show a temporary preview of the uploaded image
                const previewImage = document.getElementById('profileImage');
                previewImage.src = URL.createObjectURL(file);

                // Make AJAX request to upload the file
                fetch('{{ route('up.upload.profile.image') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            // Force the browser to reload the image with the new file
                            const timestamp = new Date().getTime(); // Unique timestamp
                            previewImage.src = data.filePath + '?t=' + timestamp;

                            alert('Profile image updated successfully!');
                        } else {
                            alert('Failed to upload profile image. Please try again.');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('Something went wrong. Please try again later.');
                    });
            }
        });
    </script> --}}
    <script>
        $('.sliderDate').slick({
            dots: false,
            infinite: true,
            speed: 300,
            vertical: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            prevArrow: '<button class="nextIcon"><img src="../frontend/images/slider-prew-icon.svg"></button>',
            nextArrow: '<button><img src="../frontend/images/slider-nxt-icon.svg"></button>',
            responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            }]
        });

        // Refresh Slick Slider when the Bootstrap modal is shown
        $('#yourModalId').on('shown.bs.modal', function() {
            $('.sliderDate').slick('setPosition');
        });

        $('.toggleBtn2').click(function() {
            $('.studentNav').toggleClass("show");
        });

        // Create the chart
        if ($('#courseStatistics').length) {
            Highcharts.chart('courseStatistics', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Time Spent Per Month'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Minutes Watched'
                    }
                },
                tooltip: {
                    valueSuffix: ' min'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Academic',
                    data: timeSpendingsData.academic,
                    color: '#3043E6'
                }, {
                    name: 'Talent/Skiils',
                    data: timeSpendingsData.non_academic,
                    color: '#02D1FF'
                }]
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            var preSelectedCity = $('#city-select').data('selected-city');

            function loadCities(stateId, preSelectedCity) {
                if (!stateId) {
                    $('#city-select').html('<option value="">Select</option>');
                    return;
                }

                var url = "{{ route('sp.getCities', ':state') }}".replace(':state', stateId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        var options = '<option value="">Select</option>';
                        if (data && Object.keys(data).length) {
                            $.each(data, function(id, name) {
                                options +=
                                    `<option value="${id}" ${id == preSelectedCity ? 'selected' : ''}>${name}</option>`;
                            });
                        } else {
                            options = '<option value="">No cities available</option>';
                        }
                        $('#city-select').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading cities:", error);
                    }
                });
            }

            $('#state-select').on('change', function() {
                var stateId = $(this).val();
                loadCities(stateId, null);
            });

            var initialStateId = $('#state-select').val();
            if (initialStateId) {
                loadCities(initialStateId, preSelectedCity);
            }
        });
    </script>
</body>

</html>
