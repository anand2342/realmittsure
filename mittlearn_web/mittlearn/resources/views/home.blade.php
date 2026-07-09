@extends('layouts.app')
@section('content')
    <div class="loginMain">
        <div class="loginSec">
            <div class="pb-3 text-center">
                <a href="{{ route('/') }}"><img src="{{ asset(config('constants.SITE_LOGO')) }}" alt=""
                        width="200" /></a>
            </div>
            <div>
                <div class="loginFormBox {{ $subscription ? 'd-none' : '' }}">
                    <div class="card-header">
                        <h3>Welcome to the Homepage!</h3>
                    </div>
                    {{-- @if ($isFreeTrialAvailable && !$subscription)
                        <div class="text-center mt-5">
                            <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal"
                                data-bs-target="#basicModal">
                                Access Free Trial
                            </button>
                        </div>
                    @endif --}}

                    <div class="text-center mt-5">
                        <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Display Subscription Plan Details if Available -->
        @if ($subscription)
            <style>
                .subscriptionBox {
                    max-width: 500px;
                    margin: auto;
                    background: #fff;
                    padding: 45px;
                    box-shadow: 0px 0px 6px #00000029;
                    border-radius: 20px;
                    min-height: 400px;
                }

                .subscriptionBox p strong {
                    color: #000
                }

                .subscriptionBox h6 {
                    font-size: 18px;
                    font-weight: 600;
                    position: relative;
                    margin-bottom: 30px
                }

                .subscriptionBox h6::after {
                    content: '';
                    background: transparent linear-gradient(90deg, #044783 0%, #00C056 100%) 0% 0% no-repeat padding-box;
                    border-radius: 10px;
                    position: absolute;
                    bottom: -6px;
                    left: 0;
                    width: 60px;
                    height: 3px;
                }
            </style>
            <div class="loginFormBox subscriptionBox {{ $subscription ? '' : 'd-none' }}">

                <h6>Your Active Subscription</h6>
                <p><strong>Plan Name:</strong> {{ $subscription->plan_json['name'] }}</p>
                <p><strong>Price:</strong> {{ '00:00' }}</p>
                <p><strong>Start Date:</strong> {{ $subscription['start_date'] }}</p>
                <p><strong>End Date:</strong> {{ $subscription['end_date'] }}</p>
                <div class="mb-3">
                    <b>Academic Courses:</b>
                    <ul class="list-group mt-2">
                        @foreach ($subscription->courses_json['academic_courses'] as $course)
                            <li class="list-group-item">{{ $course['course_name'] }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="mb-3">

                    <b class="">Talent & Skills Courses:</b>
                    <ul class="list-group mt-2">
                        @foreach ($subscription->courses_json['non_academic_courses'] as $course)
                            <li class="list-group-item">{{ $course['course_name'] }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('/') }}" class="btn btn-success">Home</a>
                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
                </div>
            </div>
        @endif


        <div class="mainBanner p-0">
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
    </div>
    {{-- <div class="modal fade" id="basicModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Available Free Plans</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ Form::open(['url' => route('purchase.subscription'), 'class' => '', 'files' => true, 'id' => 'subscriptionForm']) }}
                    <!-- Select for Academic Courses -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            {!! Form::label('Academic', 'Academic', ['class' => 'form-label']) !!}
                            {!! Form::select('class_id', $className, null, [
                                'class' => 'form-select',
                                'required',
                                'id' => 'classSelect',
                                'onchange' => 'fetchClassCourses()',
                                'placeholder' => 'Select Academic Class',
                            ]) !!}
                            <ul class="list-group" id="academicCourseList" style="display: none;"></ul>
                        </div>

                        <!-- List of Non-Academic Courses -->
                        <div class="col-md-6 mb-3">
                            {!! Form::label('Non-Academic', 'Non-Academic', ['class' => 'form-label']) !!}
                            <ul class="list-group">
                                @foreach ($nonAcademic as $course)
                                    <li class="list-group-item">
                                        <input type="hidden" name="non_academic_courses[]" value="{{ $course->id }}">
                                        {{ $course->course_name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Hidden Fields for Plan ID and Selected Courses -->
                    <input type="hidden" name="plan_id" id="planId" value="{{ $isFreeTrialAvailable->id }}">
                    <input type="hidden" name="academic_courses" id="academicCourses">

                    <div class=" border-top mt-4 pt-4  text-center">
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {{ Form::close() }}
                </div>

            </div>
        </div>
    </div> --}}
@endsection
<script>
    function fetchClassCourses() {
        const classId = document.getElementById('classSelect').value;

        if (classId) {
            fetch(`/get-class-courses/${classId}`)
                .then(response => response.json())
                .then(data => {
                    const academicCourseList = document.getElementById('academicCourseList');
                    academicCourseList.innerHTML = '';

                    // Check if courses are returned
                    if (data.courses.length > 0) {
                        // Make the list visible
                        academicCourseList.style.display = 'block';

                        let courseIds = [];
                        data.courses.forEach(course => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item';
                            listItem.textContent = course.course_name;

                            // Capture course ID
                            courseIds.push(course.id);

                            academicCourseList.appendChild(listItem);
                        });

                        // Store course IDs as a comma-separated string in the hidden field
                        document.getElementById('academicCourses').value = courseIds.join(',');
                    } else {
                        academicCourseList.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('academicCourseList').style.display = 'none';
                });
        } else {
            document.getElementById('academicCourseList').style.display = 'none';
        }
    }
</script>
