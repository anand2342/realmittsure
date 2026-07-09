@extends('frontend.layouts.master')

@section('content')
    <style>
        .fa-chevron-down {
            display: none !important;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .accordion-button.collapsed {
            border: 1px solid #007bff;
            box-shadow: 0 0 4px rgba(0, 123, 255, 0.4);
            background-color: #f0f8ff;
        }

        .subject-header {
            background: #f8fafc;
            border-left: 4px solid #007bff;
            transition: background 0.3s ease;
        }

        .subject-header:hover {
            background: #eef5ff;
        }

        .lesson-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .lesson-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
        }
    </style>
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
                    <h1>{{ $bookSeries->name }} Series Courses</h1>
                    <p>Watch videos and experience our interactive digital learning content.</p>
                    <span>
                        Explore the best of {{ $bookSeries->name }} — where books meet digital innovation.<br>
                        Start your journey today and purchase your complete course package.
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="courseSection viewCourse">
        <div class="container">
            <div class="d-md-flex justify-content-between gap-3">
                <div class="section-heading text-start mx-0">
                    <h2 class="text-black"><span class="greenBorder"></span>
                        Free Digital Contents Preview</h2>
                    <p>
                        Explore free previews of our digital courses and experience how learning comes alive with
                        interactive content.
                    </p>
                </div>
                <div class="viewCategory">
                    <form method="GET" action="{{ request()->url() }}"
                        class="d-flex flex-wrap gap-2 align-items-center mb-0">
                        <div class="d-flex flex-column">
                            <label class="small mb-1">Content Language</label>
                            <select class="form-control form-select" name="language" onchange="this.form.submit()"
                                style="min-width: 150px;">
                                @foreach (config('constants.CONTENT_LANGUAGE') as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ request('language') == $key ? 'selected' : 'bilingual' }}>
                                        {{ $label }}
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
                <!-- Accordion Start -->
                <div class="accordion" id="classAccordion">
                    @foreach ($classCourses as $key => $data)
                        <div class="accordion-item mb-3">
                            <h2 class="accordion-header" id="heading{{ $key }}">
                                <h2 class="accordion-header" id="heading{{ $key }}">
                                    <button
                                        class="accordion-button collapsed d-flex justify-content-between align-items-center"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                                        aria-controls="collapse{{ $key }}">

                                        <div class="d-flex align-items-center gap-3">
                                            <figure class="m-0 position-relative">
                                                <span
                                                    class="rounded-circle d-inline-flex justify-content-center align-items-center first-circle">
                                                    <span class="rounded-circle second-circle">
                                                        {{ substr($data->class->name ?? 'N/A', 0, 1) }}
                                                    </span>
                                                </span>
                                            </figure>
                                            <span class="fw-bold">{{ $data->class->name ?? 'N/A' }}</span>
                                        </div>

                                        {{-- Custom blinking icon --}}
                                        <i class="fa fa-chevron-down blink-icon ms-auto"></i>
                                    </button>
                                </h2>

                            </h2>
                            <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $key }}" data-bs-parent="#classAccordion">
                                <div class="accordion-body">
                                    <div class="row px-md-1">
                                        @foreach ($data->subjects as $index => $subject)
                                            <div class="col-md-12 mb-4"
                                                style="border: solid 1px #dee2e6; border-radius:8px">
                                                <!-- Subject + Book Header -->
                                                <div
                                                    class="subject-header bg-light border p-3 rounded d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <h5 class="mb-1 text-primary">{{ $subject->name ?? 'Subject' }}
                                                        </h5>
                                                        <p class="mb-0 text-muted">Book:
                                                            {{ $subject->course_name ?? 'N/A' }}</p>
                                                    </div>
                                                    @if ($subject->book_cover_image)
                                                        <img src="{{ Storage::url($subject->book_cover_image) }}"
                                                            alt="Book Cover" style="height: 50px; border-radius: 6px;">
                                                    @endif
                                                </div>

                                                <!-- Demo Lessons -->
                                                @if (!empty($subject->lessons))
                                                    <div class="row mt-3">
                                                        @foreach ($subject->lessons as $lessonIndex => $lesson)
                                                            @php
                                                                $uniqueId =
                                                                    'lesson_' .
                                                                    $key .
                                                                    '_' .
                                                                    $index .
                                                                    '_' .
                                                                    $lessonIndex;
                                                            @endphp
                                                            @if (!empty($lesson->filtered_video))
                                                                <div class="col-md-4 mb-3">
                                                                    <div class="card border-0 h-100 lesson-card"style='box-shadow: 5px 5px 5px 5px #dee2e6;'
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#coursePreview{{ $uniqueId }}">
                                                                        <div
                                                                            class="card-body d-flex flex-column justify-content-between">
                                                                            <div>
                                                                                <h6 class="text-dark mb-1">
                                                                                    {{ $lesson->chapter_name }}</h6>
                                                                                <p class="text-muted small mb-2">Preview
                                                                                    Video
                                                                                </p>
                                                                            </div>
                                                                            <button
                                                                                class="btn btn-outline-primary btn-sm w-100">
                                                                                <i class="bi bi-play-fill"></i> Watch
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Video Modal -->
                                                                <div class="modal fade previewVdo"
                                                                    id="coursePreview{{ $uniqueId }}" tabindex="-1"
                                                                    aria-labelledby="coursePreviewLabel-{{ $uniqueId }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content rounded-0 border-0"
                                                                            style="background: rgba(0, 0, 0, .5); color: #fff;">
                                                                            <div class="modal-header border-0">
                                                                                <h1 class="modal-title fs-5 fw-normal"
                                                                                    id="coursePreviewLabel-{{ $uniqueId }}">
                                                                                    {{ $lesson->chapter_name }}
                                                                                </h1>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body p-0">
                                                                                <video width="100%" height="240"
                                                                                    controls controlsList="nodownload"
                                                                                    oncontextmenu="return false;">
                                                                                    <source
                                                                                        src="{{ Storage::url('uploads/course_chapter_files/' . $lesson->filtered_video->attachment_file) }}"
                                                                                        type="{{ $lesson->filtered_video->mime_type === 'video/quicktime' ? 'video/mp4' : $lesson->filtered_video->mime_type }}">
                                                                                </video>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="text-muted text-center mt-2">No demo lessons available for
                                                        this subject.</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Accordion End -->
            </div>
        </div>
    </div>
    </div>
@endsection
