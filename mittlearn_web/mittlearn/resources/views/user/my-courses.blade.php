@extends('user.layouts.master')

@section('content')
    <div class="dashboardMain">
        <!-- Alerts Section -->
        <div class="alertsSec cardBox mb-3 d-lg-none">
            <h3 class="fs-6 fw-regular d-flex align-items-center gap-2 mb-0">
                <img src="{{ asset('frontend/images/alert.svg') }}" alt="" width="20"> Alerts
            </h3>
            <div class="alertList">
                <a href="javascript:void(0);">Simply dummy text of the printing and typesetting industry.</a>
                <a href="javascript:void(0);">Simply dummy text of the printing and typesetting industry.</a>
            </div>
        </div>
        <div id="successMessage" class="alert alert-success mt-3 d-none">
            🎉 Congratulations! Access Code is valid. You can now access your digital content.
        </div>
        <!-- Courses Section -->
        <div class="cardBox">
            <div class="d-md-flex justify-content-between align-items-center mb-3">
                <h2 class="fs-5 fw-semibold mb-3">My Courses</h2>
            </div>

            <div class="row px-md-1">
                <!-- Active Subscription -->
                {{-- <div class="col-md-4 col-lg-3 mb-3 px-md-2 {{ $subscription ? '' : 'd-none' }}">
                    <div class="exploreBox h-100 p-4 bg-light shadow-sm rounded">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="fw-bold">Your Active Subscription</span>
                        </div>
                    </div>
                </div> --}}

                <!-- Access Code -->
                <div class="col-md-4 col-lg-3 mb-3 px-md-2">
                    <div class="exploreBox h-100 text-center p-4 bg-light shadow-sm rounded">
                        <span class="fw-semibold">Access Code</span>
                        <p class="mt-2">Please enter your Access Code to get your school's courses.</p>
                        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#accessCodeModal">
                            Enter Access Code
                        </button>
                    </div>
                </div>

                <!-- Subscription Details -->
                @if (isset($subscription) && !empty($subscription))
                    <div class="col-md-8 col-lg-6 mb-3 px-md-2 {{ $subscription ? '' : 'd-none' }}">
                        <div class="card p-4 shadow-sm rounded">
                            <h5 class="fw-bold mb-4">Your Active Subscription Details</h5>

                            <div class="mb-3">
                                <p><strong>Plan Name:</strong> {{ $subscription->plan_json['name'] }}</p>
                                <p><strong>Price:</strong> {{ '₹ 00:00' }}</p>
                                <p><strong>Start Date:</strong> {{ dateConvert($subscription['start_date'], 'd, M Y') }}</p>
                                <p><strong>End Date:</strong> {{ dateConvert($subscription['end_date'], 'd, M Y') }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold">Academic Courses:</h6>
                                <ul class="list-group mt-2">
                                    @foreach ($subscription->courses_json['academic_courses'] as $course)
                                        <li class="list-group-item">{{ $course['course_name'] }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h6 class="fw-bold">Talent & Skill Courses:</h6>
                                <ul class="list-group mt-2">
                                    @foreach ($subscription->courses_json['non_academic_courses'] as $course)
                                        <li class="list-group-item">{{ $course['course_name'] }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="d-flex justify-content-center gap-3 mt-5">
                                <a href="{{ route('/') }}" class="btn btn-success">Go to Home</a>
                                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-4 col-lg-3 mb-3 px-md-2 ">
                        <div class="exploreBox h-100 text-center p-4 bg-light shadow-sm rounded">
                            <span class="fw-semibold">No Active Subscription</span>
                            <p class="mt-2">Please subscribe to get access to courses.</p>
                            <a href="{{ route('/') }}" class="btn btn-primary mt-3">Explore Plans</a>
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </div>
    <!-- Access Code Modal -->
    <div class="modal fade" id="accessCodeModal" tabindex="-1" aria-labelledby="accessCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accessCodeModalLabel">Enter Access Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="accessCodeForm">
                        <div class="mb-3">
                            <label for="accessCode" class="form-label">Access Code</label>
                            <input type="text" class="form-control" id="accessCode" name="access_code"
                                placeholder="Enter your Access Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="schoolName" class="form-label">School Name</label>
                            {!! Form::select('school_id', $getSchool, null, [
                                'class' => 'form-control',
                                'placeholder' => 'Select your School',
                                'id' => 'schoolName',
                            ]) !!}
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('accessCodeForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Get access code value
            const accessCode = document.getElementById('accessCode').value;
            const schoolName = document.getElementById('schoolName').value;
            // Make an AJAX request to the Laravel route
            fetch("{{ route('validate.access.code') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf_token
                    },
                    body: JSON.stringify({
                        access_code: accessCode,
                        school_id: schoolName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        document.getElementById('successMessage').classList.remove('d-none');

                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'accessCodeModal'));
                        modal.hide();
                    } else {
                        alert(data.message || "Invalid Access Code. Please try again.");
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'accessCodeModal'));
                        modal.hide();
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Something went wrong. Please try again.");
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'accessCodeModal'));
                    modal.hide();
                });
        });
    </script>

@endsection
