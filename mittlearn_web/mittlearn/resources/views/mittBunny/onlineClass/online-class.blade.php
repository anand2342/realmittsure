@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">

        <div class="d-flex flex-wrap">
            <div class="leftpanel">
                <div class="helloSection">
                    <div class=" pe-md-5">
                        <h2><b>Online</b> Classes</h2>
                        <p>Explore your online class options here.</p>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        @php
                            $student = session('student_class');
                        @endphp
                        @if ($student)
                            <span class="badge">{{ $student['class'] }}</span>
                        @endif
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div>
                <div class="cardBox">
                    <h2 class="fs-6 fw-semibold mb-4">Online Classes</h2>
                    <ul class="nav nav-tabs tbs border-0 onlineTabs widthFit mb-4 ">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#onlineongointTb"
                                type="button">Ongoing Classes</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#onlineupcomingTb"
                                type="button">Upcoming Classes</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#onlinepastTb" type="button">Past
                                Classes</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="onlineongointTb">
                            <div class="row px-md-1">
                                @if ($data['ongoingOnlineClasses']->isNotEmpty())
                                    @foreach ($data['ongoingOnlineClasses'] as $index => $class)
                                        @php
                                            $bgClr = [
                                                'colorPeach',
                                                'colorLightPink',
                                                'colorSky',
                                                'colorSkyDark',
                                                'colorSkyDark',
                                            ];
                                            $lottieImages = [
                                                '../mittbunny/images/lion-courses.json',
                                                '../mittbunny/images/fox-courses.json',
                                                '../mittbunny/images/elephant-courses.json',
                                                '../mittbunny/images/rabbit-courses.json',
                                                '../mittbunny/images/zebra-woods-courses.json',
                                            ];

                                            $className = $bgClr[$index % count($bgClr)];
                                            $lottieImage = $lottieImages[$index % count($lottieImages)];
                                        @endphp
                                        <div class="col-md-12 col-xxl-6 px-md-2 mb-3">
                                            <div class="joinclassBox" style="background-color:{{ $className }}">
                                                <!-- code -->
                                                <lottie-player src="{{ asset($lottieImage) }}"
                                                    style="width: 180px;height: 180px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                                <div class="joinTxt">
                                                    <h3>{{ $class->title }}</h3>
                                                    <figure class="m-0">{{ $class->instructor->name }}</figure>
                                                </div>
                                                <ul>
                                                    <li><strong>Date
                                                            <b>{{ \Carbon\Carbon::parse($class->class_date)->format('d-m-Y') }}</b></strong>
                                                    </li>
                                                    <li><strong>Subject <b>{{ $class->class->name }}</b></strong></li>
                                                    <li><strong>Class start time
                                                            <b>{{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}</b></strong>
                                                    </li>
                                                    <li><strong>Class end time
                                                            <b>{{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}</b></strong>
                                                    </li>
                                                </ul>
                                                <button type="button" id="joinClassButton-{{ $class->id }}"
                                                    class="btn-primary-gradient rounded-1 px-2 w-75 "
                                                    data-join-link="{{ $class->join_link }}">
                                                    Join Class
                                                </button>
                                                {{-- used the hidden form to save the log of the user joined class --}}
                                                <form id="joinClassForm" action="{{ route('join.class') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    <input type="hidden" name="class_id" id="class_id">
                                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-medium">Ongoing Classes are not available right now. Once
                                        created, you'll find them here. Stay tuned!</p>
                                @endif
                            </div>
                        </div>
                        <div class=" tab-pane fade" id="onlineupcomingTb">
                            <div class="row px-md-1">
                                @if ($data['upcomingOnlineClasses']->isNotEmpty())
                                    @foreach ($data['upcomingOnlineClasses'] as $index => $class)
                                        @php
                                            $bgClr = ['#F0F5FF', '#EDF8ED', '#DFE3FF', '#FFF4D3', '#FFE4CD'];
                                            $lottieImages = [
                                                '../mittbunny/images/lion-courses.json',
                                                '../mittbunny/images/fox-courses.json',
                                                '../mittbunny/images/elephant-courses.json',
                                                '../mittbunny/images/rabbit-courses.json',
                                                '../mittbunny/images/zebra-woods-courses.json',
                                            ];

                                            $className = $bgClr[$index % count($bgClr)];
                                            $lottieImage = $lottieImages[$index % count($lottieImages)];
                                        @endphp
                                        <div class="col-md-12 col-xxl-6 px-md-2 mb-3">
                                            <div class="joinclassBox" style="background-color:{{ $className }}">
                                                <lottie-player src="{{ asset($lottieImage) }}"
                                                    style="width: 180px;height: 180px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                                <div class="joinTxt">
                                                    <h3>{{ $class->title }}</h3>
                                                    <figure class="m-0">{{ $class->instructor->name }}</figure>
                                                </div>
                                                <ul>
                                                    <li><strong>Date
                                                            <b>{{ \Carbon\Carbon::parse($class->class_date)->format('d-m-Y') }}</b></strong>
                                                    </li>
                                                    <li><strong>Subject <b>{{ $class->class->name }}</b></strong></li>
                                                    <li><strong>Class start time
                                                            <b>{{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}</b></strong>
                                                    </li>
                                                    <li><strong>Class end time
                                                            <b>{{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}</b></strong>
                                                    </li>
                                                </ul>
                                                <div class="d-flex d-xxl-block gap-2">
                                                    <button type="button" class="btn-primary-gradient rounded-1 px-2"
                                                        onclick="window.location.href='{{ $class->join_link }}'">
                                                        Start on this date
                                                        {{ \Carbon\Carbon::parse($class->class_date)->format('d-m-Y') }}
                                                    </button>
                                                    <a href="{{ route('mittbunny.online.class.digital.content', $class->id) }}"
                                                        class="btn-success rounded-1 px-2">
                                                        <span>View Digital Content</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-medium">Upcoming Classes are not available right now. Stay
                                        tuned – they'll appear here once available!</p>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="onlinepastTb">

                            <div class="row px-md-1">
                                @if ($data['pastOnlineClasses']->isNotEmpty())
                                    @foreach ($data['pastOnlineClasses'] as $index => $class)
                                        @php
                                            $bgClr = [
                                                'colorPeach',
                                                'colorLightPink',
                                                'colorSky',
                                                'colorSkyDark',
                                                'colorSkyDark',
                                            ];
                                            $lottieImages = [
                                                '../mittbunny/images/lion-courses.json',
                                                '../mittbunny/images/fox-courses.json',
                                                '../mittbunny/images/elephant-courses.json',
                                                '../mittbunny/images/rabbit-courses.json',
                                                '../mittbunny/images/zebra-woods-courses.json',
                                            ];

                                            $className = $bgClr[$index % count($bgClr)];
                                            $lottieImage = $lottieImages[$index % count($lottieImages)];
                                        @endphp
                                        <div class="col-md-12 col-xxl-6 px-md-2 mb-3">
                                            <div class="joinclassBox" style="background-color:{{ $className }}">
                                                <lottie-player src="{{ asset($lottieImage) }}"
                                                    style="width: 180px;height: 180px;margin: auto;" loop
                                                    autoplay></lottie-player>
                                                <div class="joinTxt">
                                                    <h3>{{ $class->title }}</h3>
                                                    <figure class="m-0">{{ $class->instructor->name }}</figure>
                                                </div>
                                                <ul>
                                                    <li><strong>Date
                                                            <b>{{ \Carbon\Carbon::parse($class->class_date)->format('d-m-Y') }}</b></strong>
                                                    </li>
                                                    <li><strong>Subject <b>{{ $class->class->name }}</b></strong></li>
                                                    <li><strong>Class start time
                                                            <b>{{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }}</b></strong>
                                                    </li>
                                                    <li><strong>Class end time
                                                            <b>{{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}</b></strong>
                                                    </li>
                                                </ul>
                                                <hr class="mb-1">
                                                <a href="{{ route('mittbunny.online.class.digital.content', $class->id) }}"
                                                    class="btn-success rounded-1 px-2">
                                                    <span>View Digital Content</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="fw-medium">Past Classes are not available right now. Check back
                                        later to see them once they're added.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rightpanel">
                @include('mittBunny.layouts.profile-header')
                @include('mittBunny.layouts.continue-watching-sec')

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[id^="joinClassButton-"]').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const classId = button.id.replace('joinClassButton-', '');
                    const joinLink = button.getAttribute('data-join-link');
                    document.getElementById('class_id').value = classId;
                    const formData = new FormData(document.getElementById('joinClassForm'));
                    fetch('{{ route('mittbunny.join.class') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = joinLink;
                            } else {
                                alert('There was an error saving log.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while processing your request.');
                        });
                });
            });
        });
    </script>
@endsection
