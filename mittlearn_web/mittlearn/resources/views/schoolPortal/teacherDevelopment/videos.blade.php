@extends('schoolPortal.layouts.master')

@section('content')
    @include('admin.layouts.flash-messages')

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item">
                <a href="{{ route('sp.teacher.development.index') }}">Teacher Development</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ $content->title }}
            </li>
        </ol>
    </nav>

    <div class="cardBox classDetails">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <div>
                <h6 class="m-0 fw-semibold">{{ $content->title }}</h6>
                @if ($content->description)
                    <p class="text-muted mb-0 mt-1" style="font-size: 13px;">
                        {{ $content->description }}
                    </p>
                @endif
            </div>
            {{-- <span class="badge {{ $content->is_for_all_schools ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ $content->is_for_all_schools ? 'All Schools' : 'Selected Schools' }}
            </span> --}}
        </div>

        {{-- Videos grid --}}
        <div class="row">
            @if ($videos && $videos->isNotEmpty())
                @foreach ($videos as $video)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 px-2">
                        <div class="classesBx h-100 d-flex flex-column"
                            style="border-radius: 10px; overflow: hidden; border: 1px solid #e0e0e0;">

                            {{-- Inline video player --}}
                            <div class="ratio ratio-16x9 bg-dark" style="border-radius: 10px 10px 0 0; overflow: hidden;">
                                <video src="{{ Storage::url($video->video_file) }}" controls
                                    controlsList="nodownload noplaybackrate" disablePictureInPicture preload="metadata"
                                    style="object-fit: cover; width: 100%; height: 100%;">
                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            {{-- Video info --}}
                            <div class="p-2 d-flex flex-column flex-grow-1" style="background: #f9f9f9;">

                                <div class="fw-semibold mb-1" style="font-size: 13px; color: #222;"
                                    title="{{ $video->video_title }}">
                                    {{ Str::limit($video->video_title, 30, '...') }}
                                </div>

                                {{-- <small class="text-muted mt-auto">
                                    Uploaded: {{ $video->created_at->format('d M Y') }}
                                </small> --}}

                                {{-- Open full screen --}}
                                {{-- <a href="{{ Storage::url($video->video_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary mt-2 w-100">
                                    <i class="fa fa-play-circle me-1"></i> Open Full Screen
                                </a> --}}
                                <button class="btn btn-sm btn-outline-primary mt-2 w-100"
                                    onclick="openVideoModal('{{ Storage::url($video->video_file) }}')">
                                    <i class="fa fa-play-circle me-1"></i> Open Full Screen
                                </button>

                            </div>

                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <p class="text-muted">No videos available for this content yet.</p>
                </div>
            @endif
        </div>

    </div>
    <div class="modal fade" id="videoModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark">

                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0">
                    <video id="modalVideo" controls controlsList="nodownload noplaybackrate" disablePictureInPicture
                        style="width:100%; height:auto;">
                    </video>
                </div>

            </div>
        </div>
    </div>
    <script>
        function openVideoModal(videoUrl) {
            let video = document.getElementById('modalVideo');
            video.src = videoUrl;

            let modal = new bootstrap.Modal(document.getElementById('videoModal'));
            modal.show();
        }

        // stop video on close
        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
            let video = document.getElementById('modalVideo');
            video.pause();
            video.src = '';
        });
    </script>
@endsection
