@extends('mittBunny.layouts.master')
@section('content')
    {{-- @include('admin.layouts.flash-messages') --}}

    <div class="dashboardMain profileMain">
        <div class="helloSection">
            <div class=" pe-md-5">
                <h2><b>My</b> Profile</h2>
                <p>Manage and update your personal information, security settings.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <span class="badge"> @php
                    $userClasses = \App\Models\UserClass::where('user_id', $currentUser->id)
                        ->with('classLabelName')
                        ->get();
                @endphp

                    @if ($userClasses->isNotEmpty())
                        {{ $userClasses->pluck('classLabelName.name')->filter()->join(' & ') }}
                    @elseif($currentUser->studentDetails && $currentUser->studentDetails->className)
                        {{ $currentUser->studentDetails->className->name }}
                    @else
                    @endif
                </span>
                <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent" speed="1"
                    style="width: 80px; height: 80px;" loop autoplay></lottie-player>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3 pe-md-2">
                <div class="leftPart cardBox">
                    <div class="profileUpload">
                        <figure class="position-relative m-0">
                            <img id="profileImage"
                                src="{{ Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('images/default-profile.png') }}"
                                alt="Profile Image">
                            <label for="profileHeader" class="contentprf">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <img src="{{ asset('mittbunny/images/edit-upload.svg') }}" alt=""
                                        width="14">
                                    <input type="file" id="profileHeader" class="d-none">
                                </div>
                            </label>
                        </figure>
                    </div>
                    <span>
                        {{ $currentUser->name ?? '' }}
                        <b>
                            @php
                                $userClasses = \App\Models\UserClass::where('user_id', $currentUser->id)
                                    ->with('classLabelName')
                                    ->get();
                            @endphp

                            @if ($userClasses->isNotEmpty())
                                {{ $userClasses->pluck('classLabelName.name')->filter()->join(' & ') }}
                            @elseif($currentUser->studentDetails && $currentUser->studentDetails->className)
                                {{ $currentUser->studentDetails->className->name }}
                            @else
                            @endif
                        </b>
                    </span>
                    <ul class="subsCribes mt-4">
                        <li><strong>Subscribed Courses <b>{{ $totalSubscribedCourses ?? '0' }}</b></strong></li>
                        <li><strong>Completed Courses
                                <b>{{ $completedAcadCourses + $completedNonAcadCourses }}</b></strong>
                        </li>
                    </ul>
                    <hr>
                    <div class="d-flex  justify-content-between">
                        <button type="button" class="btn btn-danger fs-8" data-bs-toggle="modal" data-bs-target="#logOut">
                            <img src="{{ asset('mittbunny/images/logout.svg') }}" alt="" width="12"
                                class="me-1"> Log out
                        </button>
                        <button type="button" class="btn btn-success fs-8 px-3" data-bs-toggle="modal"
                            data-bs-target="#changePassword">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8 ps-md-2">
                {!! Form::open(['url' => route('mittbunny.update.profile.details'), 'method' => 'post']) !!}
                @csrf
                {!! Form::hidden('id', $currentUser->id ?? '') !!}
                <div class="cardBox mb-3">
                    <h2 class="fs-6 fw-semibold mb-3"> Student Details</h2>
                    <div class="formPanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    {!! Form::label('name', 'Full Name') !!}
                                    {!! Form::text('name', $currentUser->name ?? '', [
                                        'class' => 'form-control ',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    {!! Form::label('parent_name', 'Parent / Guardian Name') !!}
                                    {!! Form::text('parent_name', $currentUser->studentDetails->parent_name ?? '', [
                                        'class' => 'form-control ',
                                    ]) !!}
                                </div>
                            </div>
                            {{--  <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" value="Dummy Text here">
                                </div>
                            </div>  --}}
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
                                    {!! Form::label('dob', 'DOB') !!}
                                    {!! Form::date('dob', $currentUser->studentDetails->dob ?? '', [
                                        'class' => 'form-control ',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    {!! Form::label('class', 'Class Name') !!}
                                    {!! Form::text('class', $currentUser->studentDetails->className->name ?? '', [
                                        'class' => 'form-control readonly ',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
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
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    {!! Form::label('school_id', "School's Name") !!}
                                    {!! Form::text('school_id', $currentUser->studentDetails->schoolDetails->name ?? '', [
                                        'class' => 'form-control readonly ',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="cardBox mb-3">
                    <h2 class="fs-6 fw-semibold mb-3"> Address Details</h2>
                    <div class="formPanel">
                        <div class="row">
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
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center my-2 mt-4">
                    <button type="submit" class="btn btn-primary-gradient fs-7 rounded-2 w-10 ">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="modal fade" id="imageCropModal" tabindex="-1" aria-labelledby="imageCropModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
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
                    <div class="modal-header align-items-start border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form>
                            <lottie-player src="{{ asset('mittbunny/images/sad-monster.json') }}"
                                background="transparent" speed="1" style="width: 140px; height: 140px;margin: auto;"
                                loop="" autoplay=""></lottie-player>
                            <h6 class="text-center fw-semibold mt-3">Logout Account</h6>
                            <p class="text-center fs-8">Are you sure you want to logout? Once you logout you need to
                                login again.
                            </p>
                            <div class="d-flex align-items-center justify-content-end flex-column mt-4">
                                <a href="{{ route('logout') }}" class="btn btn-success rounded-2"
                                    onclick="yourFunction()">Yes</a>
                                <a href="javascript:void(0);" onclick="location.reload();">
                                    <button type="button" class="btn backbtn fw-regular my-2">Back</button>
                                </a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="changePassword">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header align-items-start border-0">
                        <h6 class="modal-title fs-6 fw-semibold">Change Password</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form id="changePasswordForm">
                            @csrf
                            <div class="formPanel passwordChnge">
                                <div class="row">
                                    <!-- Current Password -->
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Current Password</label>
                                            <div class="position-relative">
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Enter current Password" id="current_password">
                                                <span class="eyeBtn eye_icon" data-id="current_password"><i
                                                        class="bi bi-eye-slash "></i></span>
                                                <span class="text-danger error-current_password"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Password -->
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Enter New Password</label>
                                            <div class="position-relative">
                                                <input type="password" name="newpassword" class="form-control"
                                                    placeholder="Enter New Password" id="new_password">
                                                <span class="eyeBtn eye_icon" data-id="new_password"><i
                                                        class="bi bi-eye-slash "></i></span>
                                                <span class="text-danger error-newpassword"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label>Confirm New Password</label>
                                            <div class="position-relative">
                                                <input type="password" name="newpassword_confirmation"
                                                    class="form-control" placeholder="Confirm New Password"
                                                    id="confirm_password">
                                                <span class="eyeBtn eye_icon" data-id="confirm_password"><i
                                                        class="bi bi-eye-slash "></i></span>
                                                <span class="text-danger error-confirm_password"></span>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-end flex-column mt-4">
                                    <button type="button" id="submitPasswordChange"
                                        class="btn btn-success rounded-2">Yes</button>
                                    <button type="button" class="btn backbtn fw-regular my-2"
                                        data-bs-dismiss="modal">No</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                fetch('{{ route('mittbunny.upload.profile.image') }}', {
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
        $(document).ready(function() {
            $("#submitPasswordChange").click(function(e) {
                e.preventDefault();

                let formData = $("#changePasswordForm").serialize();
                $(".text-danger").html(""); // Clear previous errors

                $.ajax({
                    url: "{{ route('mittbunny.change.password') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert(response.message); // Show success message
                            window.location.reload(); // Reset form
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.password) {
                                $(".error-password").html(errors.password[0]);
                            }
                            if (errors.newpassword) {
                                $(".error-newpassword").html(errors.newpassword[0]);
                            }
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    },
                });
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $("#submitPasswordChange").click(function(e) {
                e.preventDefault();

                let formData = $("#changePasswordForm").serialize();
                $(".text-danger").html(""); // Clear previous errors

                $.ajax({
                    url: "{{ route('mittbunny.change.password') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            alert(response.message); // Show success message
                            window.location.reload(); // Reset form
                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        if (errors) {
                            if (errors.password) {
                                $(".error-password").html(errors.password[0]);
                            }
                            if (errors.newpassword) {
                                $(".error-newpassword").html(errors.newpassword[0]);
                            }
                            if (errors.newpassword) {
                                $(".error-confirm_password").html(errors.newpassword[0]);
                            }
                        } else {
                            alert("Something went wrong. Please try again.");
                        }
                    },
                });
            });
        });
    </script>
    <script>
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
@endsection
