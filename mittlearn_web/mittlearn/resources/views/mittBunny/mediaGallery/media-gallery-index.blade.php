@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">
        <div class="d-flex flex-wrap">
            <div class="leftpanel">
                <div class="helloSection">
                    <div class=" pe-md-5">
                        <h2><b>Media</b> Gallary</h2>
                        <p>Access school-uploaded media, including gallery images, event albums, and photos from various
                            functions and activities.</p>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <span class="badge">{{ $currentUser->studentDetails->className->name }}</span>
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div>
                <div class="cardBox">
                    <div class="headingBx d-block pb-3">
                        <h4>Uploaded Album/Gallery </h4>
                        <p class="fs-9 mt-1">Pick one folder to view gallery files</p>
                    </div>


                    <ul class="nav nav-tabs cntntTab">
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#schoolTab" type="button">
                                <lottie-player src="{{ asset('mittbunny/images/folder.json') }}" background="transparent"
                                    speed="1" style="width: 70px; height: 70px;" loop autoplay></lottie-player>
                                <p class="mb-0">School Upload Media Gallery<b>{{ $data['schoolContent']->count() }}
                                        Folders</b>
                            </button>
                        </li>


                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane fade" id="schoolTab">
                            @if ($data['schoolContent'] && $data['schoolContent']->isNotEmpty())
                                @foreach ($data['schoolContent'] as $folder)
                                    <div class="classesCourse mb-4">
                                        <div class="courseHeader">
                                            <span>{{ $folder->gallery_name }}</span> <!-- Folder name or category -->
                                            {{-- <b>{{ $folder->mediaGalleryFiles->count() }} Files</b> <!-- File count --> --}}
                                        </div>
                                        <hr class="my-2 mb-3">
                                        <div
                                            class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 px-1">
                                            @foreach ($folder->mediaGalleryFiles as $file)
                                                <div class="col mb-3 px-2">
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
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-content">
                                    <p>No School gallery available at the moment. Please check back later.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div id="dataEmpty" class="dataEmpty position-relative">
                        <lottie-player src="{{ asset('mittbunny/images/deer-animal.json') }}" background="transparent"
                            speed="1"
                            style="width: 180px; height: 180px;position: absolute;bottom: 30px;left: 0;right: 0;margin: auto;"
                            loop autoplay></lottie-player>
                        <img src="{{ asset('mittbunny/images/bgImg.svg') }}" width="500" class="d-block mx-auto">
                    </div>

                </div>
            </div>
            <div class="rightpanel">
                @include('mittBunny.layouts.profile-header')
                @include('mittBunny.layouts.continue-watching-sec')

            </div>
        </div>


    </div>
@endsection
