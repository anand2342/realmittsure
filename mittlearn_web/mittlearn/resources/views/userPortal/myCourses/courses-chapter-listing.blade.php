@extends('userPortal.layouts.master')
@section('content')
    <style>
        .video-play-btn.active {
            background-color: #cfe2ff;
            /* Or any color you prefer */
            border-left: 3px solid #007bff;
            /* Optional: add an accent border */
            color: #007bff;
            /* Optional: change text color */
        }
    </style>
    <div class="dashboardMain p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('up.my.courses') }}">My Courses</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('up.course.listing', ['slug' => $data['course']->slug]) }}">Course Listing</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $data['course']->course_name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="cardBox chapterSide h-100">
                    <h3 class="fs-6 fw-semibold">{{ $data['course']->course_name }}</h3>
                    <div class="tableSearch my-3">
                        <input type="text" id="searchInput" class="form-control w-100" placeholder="Search Chapter">
                    </div>
                    <h5>CHAPTERS({{ $data['coursesChapter']->count() }})</h5>
                    {{--  <ul class="chapterList">
                        @if (isset($data['coursesChapter']))
                            @foreach ($data['coursesChapter']->sortBy('sort_order') as $item)
                                @php
                                    $videoDuration = 0;

                                    if ($item->chapters && $item->chapters->isNotEmpty()) {
                                        $videoDuration += $item->chapters->sum('video_duration');
                                    }

                                    $formattedDuration = gmdate('H:i:s', $videoDuration);
                                @endphp

                                <li>
                                    <button type="button" class="chapterBtn" data-id="{{ $item->id }}"
                                        data-sort="{{ $item->sort_order }}" data-name="{{ $item->chapter_name }}"
                                        data-duration="{{ $formattedDuration }}" onclick="loadChapterDetails(this)">
                                        <div class="w-100">
                                            <p>{{ $item->chapter_name }}</p>
                                            <div>
                                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                        width="12">
                                                    {{ $formattedDuration }}
                                                </span>
                                            </div>
                                        </div>
                                    </button>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <p>No chapter Available</p>
                            </li>
                        @endif
                    </ul>  --}}
                    <div class="accordion" id="chapterAccordion">
                        @if (isset($data['coursesChapter']))
                            @foreach ($data['coursesChapter']->sortBy('sort_order') as $key => $item)
                                @php
                                    // Your existing PHP duration calculation code
                                    $videoDuration = 0;
                                    if ($item->chapters && $item->chapters->isNotEmpty()) {
                                        foreach ($item->chapters as $chapter) {
                                            $durationParts = explode(':', $chapter->video_duration);
                                            if (count($durationParts) === 3) {
                                                [$hours, $minutes, $seconds] = $durationParts;
                                                $videoDuration += $hours * 3600 + $minutes * 60 + $seconds;
                                            } elseif (is_numeric($chapter->video_duration)) {
                                                $videoDuration += (int) $chapter->video_duration;
                                            }
                                        }
                                    }
                                    $formattedDuration = gmdate('H:i:s', $videoDuration);
                                @endphp

                                <!-- Hidden button for navigation (keeps your script working) -->
                                <button class="chapterBtn d-none" data-id="{{ $item->id }}"
                                    data-sort="{{ $item->sort_order ?? '' }}"
                                    data-name="{{ $item->chapter_name }}"></button>

                                <!-- Your existing accordion item -->
                                <div class="accordion-item border rounded mb-3 shadow-sm" data-id="{{ $item->id }}"
                                    data-sort="{{ $item->sort_order ?? '' }}" data-name="{{ $item->chapter_name }}">

                                    <h2 class="accordion-header" id="heading{{ $key }}">
                                        <button
                                            class="accordion-button collapsed d-flex justify-content-between align-items-center"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $key }}" aria-expanded="false"
                                            aria-controls="collapse{{ $key }}"
                                            onclick="loadChapterDetails(this.closest('.accordion-item'))">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-1 fw-bold">{{ $item->chapter_name }}</h6>
                                                    <small class="text-muted d-flex align-items-center">
                                                        <img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                            width="14" class="me-1">
                                                        {{ $formattedDuration }}
                                                    </small>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $key }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $key }}" data-bs-parent="#chapterAccordion">
                                        <div class="accordion-body pt-2">

                                            @if ($item->chapters && $item->chapters->isNotEmpty())
                                                <ul class="list-group list-group-flush chapterList">
                                                    @php $index = 0; @endphp

                                                    @foreach ($item->chapters as $chapter)
                                                        @if (Str::startsWith($chapter->mime_type, 'video'))
                                                            @php
                                                                $durationText = '00:00:00';
                                                                if ($chapter->video_duration) {
                                                                    $durationParts = explode(
                                                                        ':',
                                                                        $chapter->video_duration,
                                                                    );
                                                                    if (count($durationParts) === 3) {
                                                                        $durationText = $chapter->video_duration;
                                                                    } elseif (is_numeric($chapter->video_duration)) {
                                                                        $durationText = gmdate(
                                                                            'H:i:s',
                                                                            $chapter->video_duration,
                                                                        );
                                                                    }
                                                                }

                                                                $videoPath = $chapter->attachment_file
                                                                    ? Storage::url(
                                                                        'uploads/course_chapter_files/' .
                                                                            $chapter->attachment_file,
                                                                    )
                                                                    : '';
                                                            @endphp

                                                            <li class="list-group-item px-1 border-0">
                                                                <button type="button"
                                                                    class="video-play-btn w-100 text-start rounded p-2 border d-flex justify-content-between align-items-center"
                                                                    data-id="{{ $chapter->id }}"
                                                                    data-sort="{{ $chapter->sort_order ?? '' }}"
                                                                    data-name="{{ $chapter->original_name }}"
                                                                    data-duration="{{ $durationText }}"
                                                                    @if ($videoPath) data-video-src="{{ $videoPath }}"
                                                                            data-video-id="{{ $chapter->id }}"
                                                                            data-course-id="{{ $item->course_id }}"
                                                                            data-chapter-id="{{ $item->id }}" @endif>
                                                                    <div>
                                                                        <strong>{{ ++$index }}.</strong>
                                                                        <span>{{ $chapter->file_name ?: $chapter->original_name ?? 'No name available' }}</span>
                                                                    </div>
                                                                    <small class="text-muted d-flex align-items-center">
                                                                        <img src="{{ asset('frontend/images/clock.svg') }}"
                                                                            alt="" width="12" class="me-1">
                                                                        {{ $durationText }}
                                                                    </small>
                                                                </button>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">No videos available</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No chapter available</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8 ps-md-1 mb-3">
                <div class="cardBox p-0 chapterDetail h-100">
                    <!-- Video Section -->
                    <div class="videoContainer">
                        @php
                            $bannerImage = $data['course']->metadataValues
                                ->where('field_name', 'book_cover_image')
                                ->first();
                            $thumbnailImage = $data['course']->metadataValues
                                ->where('field_name', 'thumbnail_image')
                                ->first();
                            $introVideo = $data['course']->metadataValues->where('field_name', 'intro_video')->first();
                        @endphp

                        @if ($introVideo)
                            <video style="width: 100%; height:260px; object-fit: cover; border-radius: 6px;" controls
                                controlsList="nodownload" oncontextmenu="return false;">
                                <source src="{{ Storage::url($introVideo->field_value) }}" type="video/mp4">
                            </video>
                        @elseif ($bannerImage || $thumbnailImage)
                            <img src="{{ Storage::url($bannerImage ? $bannerImage->field_value : $thumbnailImage->field_value) }}"
                                class="bookThumbnailImg">
                        @else
                            <img src="{{ asset('frontend/images/default-image.jpg') }}" class="bookThumbnailImg">
                        @endif
                    </div>

                    <div class="p-3">
                        <div class="d-md-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="chapterNme">
                                <h4 id="chapterTitle"></h4>
                                <span class="me-3"><small id="videoDetail"></small></span>
                            </div>
                            <div class="chapterSlide d-flex align-items-center gap-2 justify-content-end">
                                <span class="me-2" id="chapterProgress">
                                    CHAPTER <span id="currentChapter">0</span> / {{ $data['coursesChapter']->count() }}
                                </span>
                                <!-- Next/Prev Buttons -->
                                <button type="button" class="prevBtn" id="prevBtn">
                                    <img src="{{ asset('frontend/images/previcon.svg') }}" width="30">
                                </button>
                                <button type="button" class="nextBtn" id="nextBtn">
                                    <img src="{{ asset('frontend/images/nexticon.svg') }}" width="30">
                                </button>
                            </div>
                        </div>
                        <ul class="nav nav-tabs mb-3 tbs">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#classDetail"
                                    type="button">
                                    Chapter Details
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#studyDetail"
                                    type="button">
                                    Study Materials
                                </button>
                            </li>
                            @if ($data['course']->category_id == 2)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#nonAcadBookDetail"
                                        type="button">Course Details</button>
                                </li>
                            @endif
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bookDetail"
                                    type="button">
                                    {{ $data['course']->category_id == 1 ? 'Book Details' : 'Instructor Details' }}</button>
                            </li>
                            @if ($data['course']->category_id == 2)
                                @if ($data['certificateAvailable'])
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#CertificationTb"
                                            type="button">
                                            Certification</button>
                                    </li>
                                @endif
                            @endif
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="classDetail">
                                <p id="chapterDescription">
                                </p>
                            </div>
                            <div class="tab-pane fade" id="studyDetail">
                                <ul class="docxList" id="studyMaterial">
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="nonAcadBookDetail">
                                <div class="row" id="nonAcadBookDetailMaterial">
                                    <h6>Course Overview</h6>
                                    <p>{{ optional($data['course']->metadataValues->where('field_name', 'course_overview')->first())->field_value }}
                                    </p>
                                    <h6>What Will You Learn</h6>
                                    <p>{!! optional($data['course']->metadataValues->where('field_name', 'what_you_will_learn')->first())->field_value !!}
                                    </p>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="bookDetail">
                                <div class="row" id="bookDetailMaterial">
                                    @if ($data['course']->category_id == 1)
                                        <p>{{ optional($data['course']->metadataValues->where('field_name', 'description')->first())->field_value }}
                                        </p>
                                    @else
                                        @php
                                            $instructorImage = $data['course']->metadataValues
                                                ->where('field_name', 'instructor_image')
                                                ->value('field_value');
                                        @endphp
                                        @if ($instructorImage)
                                            <img src="{{ Storage::url($data['course']->metadataValues->where('field_name', 'instructor_image')->first()->field_value) }}"
                                                alt="Instructor" class="img-fluid"
                                                style="width: 80px; height: auto; object-fit: cover;">
                                        @endif
                                        <div class="instructor-details">
                                            <h6 class="mb-0 fw-semibold mb-2">Instructor:</h6>

                                            <strong class="mt-3"
                                                style="border-bottom: 2px solid #044783; color: #044783;">{{ optional($data['course']->metadataValues->where('field_name', 'instructor_name')->first())->field_value }}
                                            </strong>
                                            <br>
                                            <p class="mt-2">
                                                {{ optional($data['course']->metadataValues->where('field_name', 'instructor')->first())->field_value }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="CertificationTb">
                                <div class="row" id="CertificationTbMaterial">
                                    @if ($data['certificateAvailable'])
                                        <h6>Certification:</h6>
                                        <p>We’re guessing you’ve been making solid progress
                                            on this course — about 70% of the expected time has passed.<br>
                                            So, we’re going to assume you’re nearly done or
                                            already finished.
                                            Your certificate is now available. Feel free to
                                            download it<br> and show off
                                            your achievement.</p>
                                        <a href="{{ route('up.courses.certificate.download', $data['course']->id) }}"
                                            class="btn btn-primary mt-4">
                                            🚀 Download Certificate
                                        </a>
                                    @else
                                        <p class="text-muted">Certificate will be available after
                                            {{ $data['certificateDate'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let currentChapterId = parseInt(document.getElementById("currentChapter").innerText) || 0;
        let totalChapters = {{ $data['coursesChapter']->count() }};
        let currentChapterElement = document.querySelector(".chapterBtn.selected");

        // Modified function to handle video playback (without auto-play)
        function playVideo(videoId, videoSrc, courseId, chapterId) {
            let videoContainer = document.querySelector(".chapterDetail .videoContainer");
            videoContainer.innerHTML = `
    <video id="video-${videoId}" style="width: 100%; height:400px; object-fit: cover;" controls controlsList="nodownload"
        oncontextmenu="return false;" ontimeupdate="updateProgress(${videoId})"
        onloadedmetadata="setVideoDuration(${videoId}, ${courseId}, ${chapterId})">
        <source src="${videoSrc}" type="video/mp4" data-quality="HD">
    </video>`;

            // REMOVED the auto-play code completely
        }

        document.addEventListener('DOMContentLoaded', function() {
            // REMOVED the automatic chapter initialization that might trigger playback

            // Navigation buttons
            document.querySelector('#nextBtn').addEventListener('click', function() {
                navigateChapter('next');
            });
            document.querySelector('#prevBtn').addEventListener('click', function() {
                navigateChapter('prev');
            });

            // Accordion clicks
            document.querySelectorAll('.accordion-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    const chapterBtn = this.querySelector('.chapterBtn') ||
                        document.querySelector(
                            `.chapterBtn[data-id="${this.getAttribute('data-id')}"]`);
                    if (chapterBtn) {
                        loadChapterDetails(chapterBtn);
                        currentChapterElement = chapterBtn;
                        currentChapterId = parseInt(chapterBtn.getAttribute('data-sort')) || 1;
                        updateChapterDisplay();
                    }
                });
            });

            // Video play button clicks (unchanged, works fine)
            document.querySelectorAll('.video-play-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    const videoSrc = this.getAttribute('data-video-src');
                    if (videoSrc) {
                        playVideo(
                            this.getAttribute('data-video-id'),
                            videoSrc,
                            this.getAttribute('data-course-id'),
                            this.getAttribute('data-chapter-id')
                        );

                        // Update active states
                        document.querySelectorAll('.video-play-btn').forEach(b => {
                            b.classList.remove('active');
                        });
                        this.classList.add('active');
                    }
                });
            });
        });

        function navigateChapter(direction) {
            if (direction === 'next' && currentChapterId < totalChapters) {
                currentChapterId++;
            } else if (direction === 'prev' && currentChapterId > 1) {
                currentChapterId--;
            } else {
                return;
            }

            let nextChapterElement = document.querySelector(`.chapterBtn[data-sort="${currentChapterId}"]`);
            if (nextChapterElement) {
                loadChapterDetails(nextChapterElement);
                currentChapterElement = nextChapterElement;
                updateChapterDisplay();

                // Optional: Open the accordion for the current chapter
                const accordionItem = nextChapterElement.closest('.accordion-item') ||
                    document.querySelector(`.accordion-item[data-id="${nextChapterElement.getAttribute('data-id')}"]`);
                if (accordionItem) {
                    const collapseId = accordionItem.querySelector('.accordion-button').getAttribute('data-bs-target');
                    const collapseElement = document.querySelector(collapseId);
                    if (collapseElement) {
                        new bootstrap.Collapse(collapseElement, {
                            toggle: true
                        });
                    }
                }
            }
        }

        function updateChapterDisplay() {
            document.getElementById("currentChapter").innerText = currentChapterId;
        }

        function loadChapterDetails(element) {
            let chapterId = element.getAttribute("data-id");
            currentChapterId = parseInt(element.getAttribute("data-sort")) || 1;

            // Remove selected class from all buttons and add it to the clicked one
            document.querySelectorAll(".chapterBtn").forEach(btn => btn.classList.remove("selected"));
            element.classList.add("selected");
            const chapterDetailsRoute = @json(route('mittbunny.courses.chapter.details', ['id' => '__ID__']));

            const url = chapterDetailsRoute.replace('__ID__', chapterId);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("Chapter not found");
                        return;
                    }

                    document.getElementById("chapterTitle").innerText = data.chapter_name;
                    document.getElementById("chapterDescription").innerText = data.description ||
                        "No description available.";
                    document.getElementById("currentChapter").innerText = currentChapterId;
                    document.getElementById("videoDetail").innerText =
                        'For the next video, check the Study Material section.';

                    let studyMaterial = document.getElementById("studyMaterial");
                    studyMaterial.innerHTML = "";
                    let queBankResources = document.getElementById("queBankResources");
                    queBankResources.innerHTML = "";

                    let videoContainer = document.querySelector(".chapterDetail .videoContainer");
                    videoContainer.innerHTML = "";

                    // Filter and get video files
                    let videoFiles = [
                        ...data.files.filter(file =>
                            /\.(mp4|avi|mov|m4v|m4p|mpg|mp2|mpeg|mpe|mpv|m2v|wmv|flv|mkv|webm|3gp|3gp|m2ts|ogv|ts|mxf|ogg')$/i
                            .test(file.file_name)),
                        ...data.supportingFolder.filter(file =>
                            /\.(mp4|avi|mov|m4v|m4p|mpg|mp2|mpeg|mpe|mpv|m2v|wmv|flv|mkv|webm|3gp|3gp|m2ts|ogv|ts|mxf|ogg)$/i
                            .test(file.file_name))
                    ];

                    if (videoFiles.length > 0) {
                        let file = videoFiles[0]; // Take the first video file
                        playVideo(
                            file.video_id,
                            file.file_path,
                            data.course_id,
                            data.chapter_id
                        );
                    } else {
                        let bannerImage = @json(
                            $bannerImage
                                ? Storage::url($bannerImage->field_value)
                                : ($thumbnailImage
                                    ? Storage::url($thumbnailImage->field_value)
                                    : null));

                        if (bannerImage) {
                            videoContainer.innerHTML =
                                `<img src="${bannerImage}" class="bookThumbnailImg">`;
                        } else {
                            let defaultImage =
                                "{{ asset('frontend/images/default-image.jpg') }}";
                            videoContainer.innerHTML =
                                `<img src="${defaultImage}" class="bookThumbnailImg">`;
                        }
                    }

                    // Rest of your existing code for study materials...
                    // Function to append study material files
                    function appendStudyMaterial(file) {
                        let isVideo = ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v',
                            'wmv', 'flv', 'mkv', 'webm', '3gp', '3gp', 'm2ts', 'ogv', 'ts', 'mxf', 'ogg'
                        ].includes(file.file_type.toLowerCase());
                        let fileElement;

                        if (isVideo) {
                            fileElement = `
                            <div class="col-md-6 px-2 mb-3">
                                <div class="studyMaterials">
                                    <figure class="m-0">
                                        <a href="javascript:void(0);" class="docxTxt" data-file-path="${file.file_path}" 
                                           data-file-id="${file.video_id}" data-course-id="${data.course_id}" data-chapter-id="${data.chapter_id}">
                                            <lottie-player src="${getFileIcon(file.file_type)}" background="transparent"
                                                speed="1" style="width: 40px; height: 60px;" loop autoplay>
                                            </lottie-player>
                                            <h6>${file.file_name ? file.file_name : file.original_name}</h6>
                                        </a>
                                    </figure>
                                </div>
                            </div>`;
                        } else {
                            fileElement = `
                            <div class="col-md-6 px-2 mb-3">
                                <div class="studyMaterials">
                                    <figure class="m-0">
                                        <a href="${file.file_path}" class="docxTxt" target="_blank">
                                            <lottie-player src="${getFileIcon(file.file_type)}" background="transparent"
                                                speed="1" style="width: 40px; height: 60px;" loop autoplay>
                                            </lottie-player>
                                            <h6>${file.file_name ? file.file_name : file.original_name}</h6>
                                        </a>
                                    </figure>
                                </div>
                            </div>`;
                        }

                        studyMaterial.innerHTML += fileElement;
                    }

                    // Function to append study supporting document files
                    function appendQueBankResources(file) {
                        let fileElement;

                        if (file.attachment_file && file.attachment_file.startsWith('QuestionBank')) {
                            fileElement = `
                            <hr class="form-divider-school">
                            <h6>Resources<h6>
                            <div class="col-md-6 px-2 mb-3">
                                <div class="studyMaterials">
                                    <figure class="m-0">
                                        <a href="${file.file_path}" class="docxTxt" target="_blank">
                                            <lottie-player src="${getFileIcon(file.file_type)}" background="transparent"
                                                speed="1" style="width: 40px; height: 60px;" loop autoplay>
                                            </lottie-player>
                                            <h6>QuestionBank</h6>
                                        </a>
                                    </figure>
                                </div>
                            </div>`;
                        } else {
                            fileElement = '';
                        }
                        queBankResources.innerHTML += fileElement;
                    }

                    if (data.files.length > 0) {
                        data.files.forEach(file => {
                            appendStudyMaterial(file);
                        });
                    } else {
                        studyMaterial.innerHTML = "<p>No files available</p>";
                    }

                    if (data.supportingFolder.length > 0) {
                        data.supportingFolder.forEach(file => {
                            appendQueBankResources(file);
                        });
                    }

                    document.querySelectorAll('.docxTxt[data-file-path]').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            let videoContainer = document.querySelector(
                                ".chapterDetail .videoContainer");
                            videoContainer.innerHTML = "";
                            let filePath = this.getAttribute('data-file-path');
                            let videoId = this.getAttribute('data-file-id');
                            let courseId = this.getAttribute('data-course-id');
                            let chapterId = this.getAttribute('data-chapter-id');

                            videoContainer.innerHTML = `
                            <video id="video-${videoId}" style="width: 100%; height:400px; object-fit: cover;" controls controlsList="nodownload"
                                oncontextmenu="return false;" ontimeupdate="updateProgress(${videoId})"
                                onloadedmetadata="setVideoDuration(${videoId}, ${courseId}, ${chapterId})">
                                <source src="${filePath}" type="video/mp4" data-quality="HD">
                            </video>`;
                        });
                    });
                })
                .catch(error => console.error("Error fetching chapter details:", error));
        }

        function getFileIcon(fileType) {
            fileType = fileType.toLowerCase();

            if (['mp3', 'wav'].includes(fileType)) {
                return "/frontend/images/audio-icon.svg";
            } else if (['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v', 'wmv', 'flv', 'mkv',
                    'webm', '3gp', '3gp', 'm2ts', 'ogv', 'ts', 'mxf'
                ].includes(fileType)) {
                return "/frontend/images/video-icon.svg";
            } else if (['jpg', 'png'].includes(fileType)) {
                return "/frontend/images/jpg-icon.svg";
            } else if (fileType === 'pdf') {
                return "/frontend/images/pdf-icon.svg";
            } else if (fileType === 'xlsx') {
                return "/frontend/images/xls-img.svg";
            } else if (fileType === 'docx') {
                return "/frontend/images/wordpress-icon.svg";
            } else {
                return "/frontend/images/default-icon.svg"; // Default fallback icon
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    // Loop through each accordion item (each chapter)
                    const accordionItems = document.querySelectorAll('.accordion-item');

                    accordionItems.forEach(item => {
                        const chapterName = item.getAttribute('data-name')?.toLowerCase() || '';

                        if (chapterName.includes(query)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>

@endsection
