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
                    <h1>Olympiad Courses</h1>
                    <p>Sharpen Your Skills, Ace the Olympiads</p>
                    <span>Browse through our expert-designed Olympiad courses for Science, Math, Reasoning and more.
                        Start your preparation and get ahead with structured learning.</span>
                    {{-- <a href="" class="btn btn-primary-gradient rounded-1 fs-7 px-4">Explore Courses</a> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="courseSection viewCourse">
        <div class="container">
            <div class="d-md-flex justify-content-between gap-3">
                <div class="section-heading text-start mx-0">
                    <h2 class="text-black"><span class="greenBorder"></span>
                        Free MOM Contents Preview</h2>
                    <p>Explore the curated contents for Mittsure Olympiad Masters - MOM before you start the magnificent
                        journey</p>
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
                                            <div class="languageBox subjectListDiv h-100 postion-relative accordion-body ">
                                                {{-- Show lessons if available --}}
                                                @if (!empty($subject->lessons))
                                                    @foreach ($subject->lessons as $lessonIndex => $lesson)
                                                        @php
                                                            $uniqueId =
                                                                'lesson_' . $key . '_' . $index . '_' . $lessonIndex;
                                                        @endphp
                                                        <div class="col-md-12">
                                                            <div class="card shadow-sm border-0 h-100">
                                                                @if (!empty($lesson->filtered_video))
                                                                    <div class="card-body d-flex justify-content-between align-items-center"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#coursePreview{{ $uniqueId }}"
                                                                        style="border: 1px solid #dbe0e3;border-radius: 8px; margin-top: 5px">
                                                                        <div>
                                                                            <h6 class="mb-1">Lesson
                                                                                {{ $lesson->sort_order ?? $lessonIndex + 1 }}
                                                                            </h6>
                                                                            <p class="mb-0 text-muted"
                                                                                style="font-size: 14px;">
                                                                                {{ $lesson->chapter_name }}
                                                                            </p>
                                                                        </div>

                                                                        <button class="btn btn-outline-primary btn-sm">
                                                                            <i class="bi bi-play-fill"></i> Preview
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            @if (!empty($lesson->filtered_video))
                                                                <!-- Modal -->
                                                                <div class="modal fade previewVdo"
                                                                    id="coursePreview{{ $uniqueId }}" tabindex="-1"
                                                                    aria-labelledby="coursePreviewLabel-{{ $uniqueId }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content rounded-0 border-0"
                                                                            style="background: rgba(0, 0, 0, .5); color: #fff;">
                                                                            <div class="modal-header border-0">
                                                                                <h1 class="modal-title fs-5 fw-normal "
                                                                                    id="coursePreviewLabel-{{ $uniqueId }}">
                                                                                    Course Preview
                                                                                </h1>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body p-0">
                                                                                <p class="py-2 px-3 fs-8 mb-0">
                                                                                    {{ $lesson->chapter_name }}</p>
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
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <p class="text-muted text-center">No lessons available</p>
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
