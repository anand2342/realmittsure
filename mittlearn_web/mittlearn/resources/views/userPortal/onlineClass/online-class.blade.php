@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 pe-md-1 mb-3 mb-lg-0">
                <div class="cardBox">
                    <h2 class="fs-6 fw-semibold mb-4">Online Classes</h2>
                    <ul class="nav nav-tabs tbs border-0 onlineTabs widthFit mb-4">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#onlineongointTb"
                                type="button">
                                Ongoing Classes
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#onlineupcomingTb" type="button">
                                Upcoming Classes
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#onlinepastTb" type="button">
                                Past Classes
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Ongoing Classes Tab -->
                        <div class="tab-pane fade show active" id="onlineongointTb">
                            <div class="row px-md-1">
                                @if ($data['ongoingOnlineClasses']->isNotEmpty())
                                    @foreach ($data['ongoingOnlineClasses'] as $class)
                                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                            <div class="joinclassBox">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <figure class="m-0">
                                                        <img src="{{ asset('frontend/images/subject-icon-3.svg') }}"
                                                            alt="">
                                                    </figure>
                                                    <div class="joinTxt">
                                                        <h3>{{ $class->title }}</h3>
                                                        <figure class="m-0">{{ $class->instructor->name }}</figure>
                                                    </div>
                                                </div>
                                                <p>{{ $class->agenda }}</p>
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
                                                    class="btn-primary-gradient rounded-1 w-100"
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

                                                <hr class="mb-1">
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                <p class="fw-medium">Ongoing Classes are not available right now. Once created, you'll find them here. Stay tuned!</p>
                                @endif
                            </div>
                        </div>

                        <!-- Upcoming Classes Tab -->
                        <div class="tab-pane fade" id="onlineupcomingTb">
                            <div class="row px-md-1">
                                @if ($data['upcomingOnlineClasses']->isNotEmpty())
                                @foreach ($data['upcomingOnlineClasses'] as $class)
                                    <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                        <div class="joinclassBox">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <figure class="m-0">
                                                    <img src="{{ asset('frontend/images/subject-icon-3.svg') }}"
                                                        alt="">
                                                </figure>
                                                <div class="joinTxt">
                                                    <h3>{{ $class->title }}</h3>
                                                    <figure class="m-0">{{ $class->instructor->name }}</figure>
                                                </div>
                                            </div>
                                            <p>{{ $class->agenda }}</p>
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
                                            <button type="button" class="btn-primary-gradient rounded-1 w-100"
                                                onclick="window.location.href='{{ $class->join_link }}'">
                                                Start on this date
                                                {{ \Carbon\Carbon::parse($class->class_date)->format('d-m-Y') }}
                                            </button>
                                            <hr class="mb-1">
                                            <a href="{{ route('up.online.class.digital.content', $class->id) }}"
                                                class="d-flex justify-content-between align-items-center">
                                                <span>View Digital Content</span>
                                                <button type="button" class="btnremoveBg">
                                                    <lottie-player src="{{ asset('frontend/images/arrow-download.json') }}"
                                                        autoplay loop style="width: 35px;height:35px;"></lottie-player>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                <p class="fw-medium">Past Classes are not available right now. Check back later to see them once they're added.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Past Classes Tab -->
                        <div class="tab-pane fade" id="onlinepastTb">
                            <div class="row px-md-1">
                                @if ($data['pastOnlineClasses']->isNotEmpty())
                                @foreach ($data['pastOnlineClasses'] as $class)
                                    <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                        <div class="joinclassBox">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <figure class="m-0">
                                                    <img src="{{ asset('frontend/images/subject-icon-3.svg') }}"
                                                        alt="">
                                                </figure>
                                                <div class="joinTxt">
                                                    <h3>{{ $class->title }}</h3>
                                                    <figure class="m-0">{{ $class->instructor->name }}</figure>
                                                </div>
                                            </div>
                                            <p>{{ $class->agenda }}</p>
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
                                            <a href="{{ route('up.online.class.digital.content', $class->id) }}"
                                                class="d-flex justify-content-between align-items-center">
                                                <span>View Digital Content</span>
                                                <button type="button" class="btnremoveBg">
                                                    <lottie-player src="{{ asset('frontend/images/arrow-download.json') }}"
                                                        autoplay loop style="width: 35px;height:35px;"></lottie-player>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                <p class="fw-medium">Upcoming Classes are not available right now. Stay tuned – they'll appear here once available!</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
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
                    fetch('{{ route('join.class') }}', {
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
