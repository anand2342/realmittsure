@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">
        <div class="row">
            <div class="col-md-5 mb-3 pe-md-4">
                <nav aria-label="breadcrumb ">
                    <ol class="breadcrumb my-3">
                        <li class="breadcrumb-item"><a href="{{ route('mittbunny.courses') }}">Subjects</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('mittbunny.course.listing', ['slug' => $data['course']->slug]) }}">Course
                                Listing</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course Details</li>
                    </ol>
                </nav>
                <div class="p-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fs-7 fw-normal mb-0 text-secondary">
                            @if ($data['course']->category_id == 1)
                                Book Name -
                            @else
                                Course Name -
                            @endif
                            <b class="fw-semibold text-black">
                                ({{ $data['course']->course_name }})</b>
                        </h2>
                        <lottie-player src="{{ asset('mittbunny/images/turtle-course.json') }}" background="transparent"
                            speed="1" style="width: 60px; height: 60px;" loop autoplay></lottie-player>
                    </div>
                    <div class="chapterSide h-100">
                        <div class="tableSearch my-3">
                            <input type="text" class="form-control w-100" placeholder="Search Chapter" id="searchInput">
                        </div>
                        <h5 class="text-secondary fs-8 mb-3">CHAPTERS({{ $data['coursesChapter']->count() }})</h5>
                        <ul class="chapterList">
                            @if (isset($data['coursesChapter']))
                                @foreach ($data['coursesChapter']->sortBy('sort_order') as $item)
                                    @php
                                        $videoDuration = 0;

                                        if ($item->chapters && $item->chapters->isNotEmpty()) {
                                            $videoDuration += $item->chapters->sum('video_duration');
                                        }

                                        $formattedDuration = gmdate('H:i:s', $videoDuration);
                                        $plannerId = isset($item->planner) ? $item->planner->first()->id : null;

                                    @endphp

                                    <li>
                                        <button type="button" class="chapterBtn" data-id="{{ $item->id }}"
                                            data-sort="{{ $item->sort_order }}" data-name="{{ $item->chapter_name }}"
                                            data-duration="{{ $formattedDuration }}" data-planner-id="{{ $plannerId }}"
                                            onclick="loadChapterDetails(this)">
                                            <div class="w-100">
                                                <p>{{ $item->chapter_name }}</p>
                                                <div>
                                                    <span><img src="{{ asset('frontend/images/clock.svg') }}"
                                                            alt="" width="12">
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
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-7 ps-md-4 mb-3">
                <div class="cardBox chapterDetail h-100">
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
                        <div class="d-flex flex-wrap justify-content-between mb-3">
                            <div class="chapterNme">
                                <h4 id="chapterTitle"></h4>
                                <span class="me-3"><small id="videoDetail"></small></span>
                            </div>
                            <div class="chapterSlide d-flex align-items-center gap-2 justify-content-end">
                                <span class="me-2">
                                    CHAPTER <span id="currentChapter">0</span> / {{ $data['coursesChapter']->count() }}
                                </span>
                                <button type="button" class="prevBtn" id="prevBtn">
                                    <img src="{{ asset('frontend/images/previcon.svg') }}" width="35">
                                </button>
                                <button type="button" class="nextBtn" id="nextBtn">
                                    <img src="{{ asset('frontend/images/nexticon.svg') }}" width="35">
                                </button>
                            </div>
                        </div>


                        <div class="actualStatus mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-semibold">EXPECTED STATUS</span>
                                <span id="progressPercentage" class="badge bg-success rounded-pill">0%</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #f0f4f8;">
                                <div id="progressBar" class="progress-bar progress-bar-striped bg-success"
                                    style="width: 0%; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1" id="progressStatusText">Not Yet Marked</small>
                        </div>
                        <div class="actualStatus mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small fw-semibold">ACTUAL STATUS</span>
                                <span id="actualProgressPercentage" class="badge bg-success rounded-pill">0%</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #f0f4f8;">
                                <div id="actualprogressBar" class="progress-bar progress-bar-striped bg-success"
                                    style="width: 0%; transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                                </div>
                            </div>
                            <small class="text-muted d-block mt-1" id="actualProgressStatusText">Not Yet Marked</small>
                        </div>

                        <ul class="nav nav-tabs ViewTabs mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#classDetail"
                                    type="button">Chapter Details</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#studyDetail"
                                    type="button">Study Materials</button>
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
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="classDetail">
                                <p id="chapterDescription">
                                </p>
                            </div>
                            <div class="tab-pane fade" id="studyDetail">
                                <div class="row" id="studyMaterial">
                                </div>
                                <div class="row" id="queBankResources">
                                </div>
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
                                        <div
                                            class="instructor-container d-flex align-items-start gap-3 mb-4 p-3 bg-light rounded">
                                            <div class="flex-shrink-0">
                                                <img src="{{ Storage::url($data['course']->metadataValues->where('field_name', 'instructor_image')->first()->field_value) }}"
                                                    alt="Instructor" class="img-fluid"
                                                    style="width: 80px; height: auto; object-fit: cover;">
                                            </div>

                                            <div class="instructor-details">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                    <h6 class="mb-0 fw-semibold">Instructor:</h6>
                                                    <p class="mb-0 text-muted">
                                                        {{ optional($data['course']->metadataValues->where('field_name', 'instructor_name')->first())->field_value }}
                                                    </p>
                                                </div>
                                                <p class="mb-0 text-muted small">
                                                    {{ optional($data['course']->metadataValues->where('field_name', 'instructor')->first())->field_value }}
                                                </p>
                                            </div>
                                        </div>
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

        if (currentChapterId < 1) {
            currentChapterId = 0;
        } else if (currentChapterId > totalChapters) {
            currentChapterId = totalChapters;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('#nextBtn').addEventListener('click', function() {
                navigateChapter('next');
            });
            document.querySelector('#prevBtn').addEventListener('click', function() {
                navigateChapter('prev');
            });

            document.querySelectorAll('.chapterBtn').forEach(function(button) {
                button.addEventListener('click', function() {
                    loadChapterDetails(this);
                    currentChapterElement = this;
                    currentChapterId = parseInt(this.getAttribute('data-sort')) || 1;
                    updateChapterDisplay();
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
            let nextChapterElement = document.querySelector(`[data-sort="${currentChapterId}"]`);
            if (nextChapterElement) {
                loadChapterDetails(nextChapterElement);
                currentChapterElement = nextChapterElement;
                updateChapterDisplay();
            }
        }

        function updateChapterDisplay() {
            document.getElementById("currentChapter").innerText = currentChapterId;
        }

        function loadChapterDetails(element) {
            let chapterId = element.getAttribute("data-id");
            let plannerId = element.getAttribute('data-planner-id');
            console.log(plannerId)

            currentChapterId = parseInt(element.getAttribute("data-sort")) || 1;

            // Remove selected class from all buttons and add it to the clicked one
            document.querySelectorAll(".chapterBtn").forEach(btn => btn.classList.remove("selected"));
            element.classList.add("selected");
            const chapterDetailsRoute = @json(route('mittbunny.courses.chapter.details', ['id' => '__ID__', 'plannerId' => '__PLANNERID__']));

            let url;
            if (plannerId) {
                url = chapterDetailsRoute.replace('__ID__', chapterId).replace('__PLANNERID__', plannerId);
            } else {
                url = chapterDetailsRoute.replace('/__PLANNERID__', '').replace('__ID__', chapterId);
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert("Chapter not found");
                        return;
                    } 

                    updateProgressBar(data.estimatedPercentage || 0);
                    updateActualProgressBar(data.actualPercentage || 0);

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
                        let videoElement = `
                          <video id="video-${file.video_id}" style="width: 100%; height:400px; object-fit: cover;" controls controlsList="nodownload"
                             oncontextmenu="return false;" ontimeupdate="updateProgress(${file.video_id})"
                             onloadedmetadata="setVideoDuration(${file.video_id}, ${data.course_id}, ${data.chapter_id})">
                            <source src="${file.file_path}" type="video/mp4" data-quality="HD">
                          </video>`;

                        videoContainer.innerHTML = videoElement;
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
                                "{{ asset('frontend/images/default-image.jpg') }}"; // Replace with your default image path
                            videoContainer.innerHTML =
                                `<img src="${defaultImage}" class="bookThumbnailImg">`;
                        }
                    }

                    // Function to append study material files
                    function appendStudyMaterial(file) {
                        let isVideo = ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v',
                            'wmv', 'flv', 'mkv', 'webm', '3gp', '3gp', 'm2ts', 'ogv', 'ts', 'mxf', 'ogg'
                        ].includes(file.file_type.toLowerCase());
                        let fileElement;

                        if (isVideo) {
                            // For video files, add a clickable link that loads the video in the video container
                            fileElement = `
                            <div class="col-md-6 px-2 mb-3">
                                <div class="studyMaterials">
                                    <figure class="m-0">
                                        <a href="javascript:void(0);" class="docxTxt" data-file-path="${file.file_path}" 
                                           data-file-id="${file.video_id}" data-course-id="${data.course_id}" data-chapter-id="${data.chapter_id}">
                                            <lottie-player src="${getLottieAnimation(file.file_type)}" background="transparent"
                                                speed="1" style="width: 40px; height: 60px;" loop autoplay>
                                            </lottie-player>
                                            <h6>${file.file_name}</h6>
                                        </a>
                                    </figure>
                                </div>
                            </div>`;
                        } else {
                            // For non-video files, create a link that opens in a new tab
                            fileElement = `
                            <div class="col-md-6 px-2 mb-3">
                                <div class="studyMaterials">
                                    <figure class="m-0">
                                        <a href="${file.file_path}" class="docxTxt" target="_blank">
                                            <lottie-player src="${getLottieAnimation(file.file_type)}" background="transparent"
                                                speed="1" style="width: 40px; height: 60px;" loop autoplay>
                                            </lottie-player>
                                            <h6>${file.file_name}</h6>
                                        </a>
                                    </figure>
                                </div>
                            </div>`;
                        }

                        // Append the element to the study material section
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
                                            <lottie-player src="${getLottieAnimation(file.file_type)}" background="transparent"
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
                        // Append the element to the study material section
                        queBankResources.innerHTML += fileElement;
                    }


                    // Append course chapter files (excluding videos)
                    if (data.files.length > 0) {
                        data.files.forEach(file => {
                            appendStudyMaterial(file);
                        });
                    } else {
                        studyMaterial.innerHTML = "<p>No files available</p>";
                    }

                    // Append supporting folder files (excluding videos)
                    if (data.supportingFolder.length > 0) {
                        data.supportingFolder.forEach(file => {
                            appendQueBankResources(file);

                        });
                    }

                    // Add event listeners to newly added video links
                    document.querySelectorAll('.docxTxt[data-file-path]').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            let videoContainer = document.querySelector(
                                ".chapterDetail .videoContainer");
                            videoContainer.innerHTML = ""; // Clear the existing content
                            let filePath = this.getAttribute('data-file-path');
                            let videoId = this.getAttribute('data-file-id');
                            let courseId = this.getAttribute('data-course-id');
                            let chapterId = this.getAttribute('data-chapter-id');

                            // Embed the video in the container
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

        // function updateProgressBar(percentage) {
        //     const roundedPercentage = Math.round(percentage * 100) / 100;
        //     document.getElementById('progressPercentage').textContent = `${roundedPercentage}%`;
        //     document.getElementById('progressBar').style.width = `${roundedPercentage}%`;
        //     document.querySelector('.actualStatus .progress').setAttribute('aria-valuenow', roundedPercentage);
        // }
        function updateProgressBar(percentage) {
            const rounded = Math.round(percentage);
            document.getElementById('progressPercentage').textContent = `${rounded}%`;
            document.getElementById('progressBar').style.width = `${rounded}%`;
            document.getElementById('progressBar').setAttribute('aria-valuenow', rounded);

            // Update status text
            const statusText = document.getElementById('progressStatusText');
            if (rounded === 0) {
                statusText.textContent = 'Not Yet Marked';
            } else if (rounded < 50) {
                statusText.textContent = 'In progress';
            } else if (rounded < 100) {
                statusText.textContent = 'More than halfway';
            } else {
                statusText.textContent = 'Completed!';
            }
        }

        function updateActualProgressBar(percentage) {
            const rounded = Math.round(percentage);
            document.getElementById('actualProgressPercentage').textContent = `${rounded}%`;
            document.getElementById('actualprogressBar').style.width = `${rounded}%`;
            document.getElementById('actualprogressBar').setAttribute('aria-valuenow', rounded);

            // Update status text
            const statusText = document.getElementById('actualProgressStatusText');
            if (rounded === 0) {
                statusText.textContent = 'Not Yet Marked';
            } else if (rounded < 50) {
                statusText.textContent = 'In progress';
            } else if (rounded < 100) {
                statusText.textContent = 'More than halfway';
            } else {
                statusText.textContent = 'Completed!';
            }
        }

        updateProgressBar(data.estimatedPercentage);
        updateActualProgressBar(data.actualPercentage);


        function getLottieAnimation(fileType) {
            fileType = fileType.toLowerCase();
            if (fileType === 'pdf') {
                return "/mittbunny/images/pdf.json";
            } else if (fileType === 'docx') {
                return "/mittbunny/images/doc.json";
            } else {
                return "/mittbunny/images/doc.json";
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const chapterButtons = document.querySelectorAll('.chapterBtn'); // Get all buttons in the list

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase(); // Get the search query and convert to lowercase

                    chapterButtons.forEach(button => {
                        const chapterName = button.getAttribute('data-name')
                            .toLowerCase(); // Get the chapter name from the data-name attribute

                        // Check if the chapter name includes the search query
                        if (chapterName.includes(query)) {
                            button.closest('li').style.display =
                                ''; // Show the <li> containing the chapter
                        } else {
                            button.closest('li').style.display =
                                'none'; // Hide the <li> containing the chapter
                        }
                    });
                });
            }
        });
    </script>


@endsection
