@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="row">
            <div class="col-md-12">
                <div class="cardBox classDetails">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                        <h6 class="m-0 fw-semibold">Media Gallery: <span class="m-0 text-muted">{{ $data['folderId']->gallery_name }} </span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Event Name:  <span class="m-0 text-muted">{{ $data['folderId']->event_name }}</span></h6>
                    </div>
                    <div class="classesCourse mb-4">
                        <div class="row">
                            <div id="search-results" class="row mt-3">
                                @if ($data['files'] && $data['files']->count() > 0)
                                    @foreach ($data['files'] as $file)
                                        <div class="col-xl-2 col-lg-3 col-md-3 mb-3 px-2 position-relative class-item"
                                            data-title="{{ $file->original_name }}" title="{{ $file->original_name }}">
                                            <div class="classesBx">
                                                <figure class="thumbnail-container">
                                                    <!-- Download Button (added here) -->
                                                    <div class="media-download-container">
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}" 
                                                           download="{{ $file->original_name }}"
                                                           class="media-download-btn"
                                                           title="Download">
                                                            <img src="{{ asset('frontend/images/download-icon.svg') }}" alt="Download">
                                                        </a>
                                                    </div>

                                                    @if (in_array($file->file_extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <!-- For image files - show actual thumbnail -->
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                            target="_blank">
                                                            <img class="classesBxImg"
                                                                src="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                                alt="{{ $file->original_name }}"
                                                                class="media-thumbnail">
                                                        </a>
                                                    @elseif (in_array($file->file_extension, [
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
                                                            'm2ts',
                                                            'ogv',
                                                            'ts',
                                                            'mxf',
                                                        ]))
                                                        <!-- For video files - show thumbnail with play icon overlay -->
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                            target="_blank" class="video-thumbnail">
                                                            <video class="media-thumbnail" muted>
                                                                <source
                                                                    src="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                                    type="video/mp4">
                                                            </video>
                                                            <div class="play-icon-overlay">
                                                                <img src="{{ asset('frontend/images/video-icon.svg') }}"
                                                                    alt="Play">
                                                            </div>
                                                        </a>
                                                    @elseif (str_contains($file->file_extension, 'mp3') || str_contains($file->file_extension, 'wav'))
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/audio-icon.svg') }}"
                                                                alt="Audio Icon" class="media-icon">
                                                        </a>
                                                    @elseif (str_contains($file->file_extension, 'pdf'))
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/pdf-icon.svg') }}"
                                                                alt="PDF Icon" class="media-icon">
                                                        </a>
                                                    @elseif (str_contains($file->file_extension, 'xlsx'))
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/xls-img.svg') }}"
                                                                alt="Excel Icon" class="media-icon">
                                                        </a>
                                                    @elseif (str_contains($file->file_extension, 'docx'))
                                                        <a href="{{ Storage::url('uploads/media-gallery/' . $file->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/wordpress-icon.svg') }}"
                                                                alt="Word Icon" class="media-icon">
                                                        </a>
                                                    @else
                                                        <img src="{{ asset('frontend/images/default-icon.svg') }}"
                                                            alt="Default Icon" class="media-icon">
                                                    @endif
                                                </figure>
                                                <p>{{ \Carbon\Carbon::parse($file->created_at)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-xl-2 col-lg-3 col-md-3 mb-3 px-2">
                                        <span>No Data Available</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
