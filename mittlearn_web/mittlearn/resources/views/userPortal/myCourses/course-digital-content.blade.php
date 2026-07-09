@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('up.my.courses') }}">My Courses</a></li>
                <li class="breadcrumb-item"><a href="{{ route('up.course.listing', ['slug' => $data['courseSlug']]) }}">Course
                        Listing</a></li>
                @php
                    $courseId = $data['chapters']->first()->course_id ?? null;
                    $courseName = $courseId ? \App\Models\Course::find($courseId)?->course_name : null;
                @endphp
                @if ($courseName)
                    <li class="breadcrumb-item active">{{ $courseName }}</li>
                @endif
            </ol>
        </nav>

        <div class="cardBox">
            <div class="d-flex justify-content-center align-items-center mb-3 position-relative">
                <!-- Left: Overview -->
                <h3 class="fs-6 fw-semibold mb-0 position-absolute start-0">Overview</h3>

                <!-- Center: Form -->
                <form method="GET" action="{{ request()->url() }}" class="d-inline-flex align-items-center gap-2">
                    <label for="chapterLimit" class="fw-semibold mb-0">Content Language</label>
                    <select id="chapterLimit" class="form-select form-select-sm w-auto" name="language"
                        onchange="this.form.submit()">
                        @foreach (config('constants.CONTENT_LANGUAGE') as $key => $lang)
                            <option value="{{ $key }}" {{ request('language') == $key ? 'selected' : '' }}>
                                {{ $lang }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if (isset($data['chapters']) && $data['chapters']->isNotEmpty())
                @foreach ($data['chapters'] as $item)
                    <div class="chapterBox">
                        <div class="d-flex align-items-center gap-3 chapterName mb-4">
                            <div class="chapterNumber">
                                {{ $item->sort_order }}
                            </div>
                            <div>
                                <h3 class="fs-6 fw-semibold mb-0">{{ $item->chapter_name }}</h3>
                                <span>Chapter Description: <b>{{ Str::limit($item->chapter_description, 150, '...') }}
                                    </b></span>
                            </div>
                        </div>
                        <div class="chapterVideos">
                            @php
                                $language = request('language') ?? 'bilingual';
                                $chapterFiles = collect($item->chapterListing)->filter(function ($file) use (
                                    $language,
                                ) {
                                    return $file->language === $language;
                                });
                                $videos = $chapterFiles->whereIn('file_extension', [
                                    'mp4',
                                    'avi',
                                    'mov',
                                    'm4v',
                                    'm4p',
                                    'mpg',
                                    'mp2',
                                    'mpeg',
                                    'mpe',
                                    'mpv',
                                    'm2v',
                                    'wmv',
                                    'flv',
                                    'mkv',
                                    'webm',
                                    '3gp',
                                    '3gp',
                                    'm2ts',
                                    'ogv',
                                    'ts',
                                    'mxf',
                                ]);
                                $documents = $chapterFiles->whereIn('file_extension', [
                                    'pdf',
                                    'docx',
                                    'xlsx',
                                    'jpeg',
                                    'jpg',
                                    'png',
                                ]);
                            @endphp
                            @if ($videos->isNotEmpty())
                                <div class="mb-4">
                                    <h4 class="fs-6 fw-semibold">Video <b>({{ $videos->count() }})</b></h4>
                                    <ul class="chapterList documentList">
                                        @foreach ($videos as $video)
                                            <li>
                                                <div class="chapterBtn">
                                                    <figure class="position-relative">
                                                        <button type="button" class="plybtn" data-bs-toggle="modal"
                                                            data-bs-target="#coursePreview-{{ $video->id }}">
                                                            <img src="{{ asset('frontend/images/video-icon.svg') }}"
                                                                alt="Video Icon" /></button>
                                                    </figure>
                                                    <div class="w-100 p-2 text-center">

                                                        <p>{{ $video->file_name ? $video->file_name : $video->original_name }}
                                                        </p>
                                                        <div class="d-flex align-items-center gap-4">
                                                            {{--  <span><img src="{{ asset('frontend/images/clock.svg') }}"
                                                                    alt="" width="12"> 34:45</span>
                                                            <span>4.6 <img src="{{ asset('frontend/images/star3.svg') }}"
                                                                    alt="" width="18"></span>  --}}
                                                        </div>
                                                    </div>
                                                    <div class="modal modalvid" id="coursePreview-{{ $video->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded-0 border-0"
                                                                style="background: rgba(0, 0, 0, .5);color: #fff;">
                                                                <div class="modal-header border-0">
                                                                    <h1 class="modal-title fs-5 fw-normal">Video Preview
                                                                    </h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <p class="py-2 px-3 fs-8">{{ $item->chapter_name }}</p>
                                                                    {{-- <video id="video-{{ $video->id }}" width="100%"
                                                                        height="240" controls controlsList="nodownload"
                                                                        oncontextmenu="return false;"
                                                                        ontimeupdate="updateProgress({{ $video->id }})"
                                                                        onloadedmetadata="setVideoDuration({{ $video->id }}, {{ $item->course_id }}, {{ $item->id }})">
                                                                        <source src="{{ $video->signed_url }}"
                                                                            type="{{ $video->mime_type }}"
                                                                            data-quality="HD">
                                                                    </video> --}}

                                                                    <video id="video-{{ $video->id }}" width="100%"
                                                                        height="240" controls controlsList="nodownload"
                                                                        oncontextmenu="return false;"
                                                                        ontimeupdate="updateProgress({{ $video->id }})"
                                                                        onloadedmetadata="setVideoDuration({{ $video->id }}, {{ $item->course_id }}, {{ $item->id }})">
                                                                        <source
                                                                            src="{{ Storage::url('uploads/course_chapter_files/' . $video->attachment_file) }}"
                                                                            type="video/mp4">
                                                                    </video>

                                                                    <!-- Video Controls -->
                                                                    {{-- <div class="video-controls">
                                                                        <label
                                                                            for="playback-speed-{{ $video->id }}">Speed:</label>
                                                                        <select id="playback-speed-{{ $video->id }}"
                                                                            onchange="changePlaybackSpeed({{ $video->id }})">
                                                                            <option value="0.5">0.5x</option>
                                                                            <option value="1" selected>1x (Normal)
                                                                            </option>
                                                                            <option value="1.25">1.25x</option>
                                                                            <option value="1.5">1.5x</option>
                                                                            <option value="2">2x</option>
                                                                        </select>

                                                                        <label
                                                                            for="video-quality-{{ $video->id }}">Quality:</label>
                                                                        <select id="video-quality-{{ $video->id }}"
                                                                            onchange="changeVideoQuality({{ $video->id }})">
                                                                            <option value="HD" selected>HD</option>
                                                                            <option value="SD">SD</option>
                                                                        </select>
                                                                    </div> --}}
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($documents->isNotEmpty())
                                <div class="mb-4">
                                    <h4 class="fs-6 fw-semibold">Document <b>({{ $documents->count() }})</b></h4>
                                    <ul class="chapterList documentList">
                                        @foreach ($documents as $document)
                                            <li>
                                                <div class="chapterBtn">
                                                    <figure class="position-relative">
                                                        @if (str_contains($document->file_extension, 'mp3') || str_contains($document->file_extension, 'wav'))
                                                            <a href="{{ Storage::url('uploads/course_chapter_files/' . $document->attachment_file) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('frontend/images/audio-icon.svg') }}"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        @elseif (str_contains($document->file_extension, 'jpg') ||
                                                                str_contains($document->file_extension, 'png') ||
                                                                str_contains($document->file_extension, 'jpeg'))
                                                            <a href="{{ Storage::url('uploads/course_chapter_files/' . $document->attachment_file) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('frontend/images/jpg-icon.svg') }}"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        @elseif (str_contains($document->file_extension, 'pdf'))
                                                            <a href="{{ Storage::url('uploads/course_chapter_files/' . $document->attachment_file) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('frontend/images/pdf-icon.svg') }}"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        @elseif (str_contains($document->file_extension, 'xlsx'))
                                                            <a href="{{ Storage::url('uploads/course_chapter_files/' . $document->attachment_file) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('frontend/images/xls-img.svg') }}"
                                                                    alt="xls Icon">
                                                            </a>
                                                        @elseif (str_contains($document->file_extension, 'docx'))
                                                            <a href="{{ Storage::url('uploads/course_chapter_files/' . $document->attachment_file) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('frontend/images/wordpress-icon.svg') }}"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        @else
                                                            <img src="{{ asset('frontend/images/default-icon.svg') }}"
                                                                alt="Default Icon">
                                                        @endif
                                                    </figure>
                                                    <div class="w-100 p-2 text-center">
                                                        <p>{{ $document->file_name ? $document->file_name : $document->original_name }}
                                                        <div class="d-flex align-items-center gap-4">
                                                            {{--  <span><img src="{{ asset('frontend/images/clock.svg') }}"
                                                                    alt="" width="12"> 34:45</span>
                                                            <span>4.6 <img src="{{ asset('frontend/images/star3.svg') }}"
                                                                    alt="" width="18"></span>  --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($item->activityListing->isNotEmpty())
                                <div class="mb-4">
                                    <h4 class="fs-6 fw-semibold mb-3">Activity</h4>
                                    <ul class="activity-container list-unstyled">
                                        @foreach ($item->activityListing as $activity)
                                            @if ($activity->type === 'activity_worksheet_link' && $activity->link_url)
                                                <li class="activity-item-wrapper">
                                                    <!-- Interactive Activity Card -->

                                                    <div class="activity-card">
                                                        <div class="activity-card-header">
                                                            <span class="activity-icon">
                                                                <i class="bi bi-joystick"></i>
                                                            </span>
                                                            <h6 class="activity-title">
                                                                {{ $activity->file_name ?? $activity->original_name }}
                                                            </h6>
                                                        </div>
                                                        <a href="{{ Str::startsWith($activity->link_url, ['http://', 'https://']) ? $activity->link_url : 'https://' . $activity->link_url }}"
                                                            target="_blank" class="activity-link">
                                                            <div class="activity-card-body">
                                                                <span class="activity-type">Interactive Exercise</span>
                                                                <div class="activity-action">
                                                                    <span class="action-icon">
                                                                        <i class="bi bi-arrow-right-circle"></i>
                                                                    </span>
                                                                    <span class="action-text">Let's Play</span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="chapterBox">
                    <p class="text-muted text-center py-3">Great things are coming to this course soon!</p>
                </div>
            @endif
        </div>
    </div>

@endsection
