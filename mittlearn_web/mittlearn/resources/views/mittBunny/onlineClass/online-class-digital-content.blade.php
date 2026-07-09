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
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent" speed="1"
                            style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div>
                <div class="cardBox">
                    <div class="chapterBox p-0 border-0">
                        <div class="chapterVideos p-0 bg-white">
                            <div class="mb-4">
                                <h4 class="fs-6 fw-semibold">{{ ucFirst($onlineClassName) }}
                                    Document<b>({{ $data['content']->count() }})</b></h4>
                                <ul class="chapterList documentList">
                                    @foreach ($data['content'] as $data)
                                        <li>
                                            <div class="chapterBtn">
                                                <figure>
                                                    <figure>
                                                        @if (str_contains($data->file_extension, 'mp3') || str_contains($data->file_extension, 'wav'))
                                                            <a href="{{ Storage::url('uploads/media-files/' . $data->attachment_file) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('frontend/images/audio-icon.svg') }}"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        @elseif (in_array($data->file_extension, ['mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','m2ts','ogv','ts','mxf']))

                                                            <!-- For video files, display video icon -->
                                                            <img src="{{ asset('frontend/images/video-icon.svg') }}"
                                                                alt="Video Icon" />
                                                            <a href="javascript:void(0);" class="plybtn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#coursePreview{{ $data->id }}">
                                                            </a>
                                                            <div class="modal coursePrv"
                                                                id="coursePreview{{ $data->id }}">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content rounded-0 border-0">
                                                                        <div class="modal-header border-0">
                                                                            <h1 class="modal-title fs-5 fw-normal">Course
                                                                                Preview
                                                                            </h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body p-0">
                                                                            <p class="py-2 px-3 fs-8">Ceramic How to use
                                                                                Ceramic
                                                                                Cone, Designs on Paper by Ceramic cone.</p>
                                                                            <video width="100%" height="240"
                                                                                controls="" controls
                                                                                controlsList="nodownload"
                                                                                oncontextmenu="return false;">
                                                                                <source
                                                                                    src="{{ Storage::url('uploads/media-files/' . $data->attachment_file) }}"
                                                                                    type="video/mp4" width="100%">
                                                                            </video>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif (str_contains($data->file_extension, 'jpg') || str_contains($data->file_extension, 'png'))
                                                            <a href="{{ Storage::url('uploads/media-files/' . $data->attachment_file) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('frontend/images/jpg-icon.svg') }}"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        @elseif (str_contains($data->file_extension, 'pdf'))
                                                            <a href="{{ Storage::url('uploads/media-files/' . $data->attachment_file) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('frontend/images/pdf-icon.svg') }}"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        @elseif (str_contains($data->file_extension, 'xlsx'))
                                                            <a href="{{ Storage::url('uploads/media-files/' . $data->attachment_file) }}"
                                                                target="_blank">
                                                                <img src="{{ asset('frontend/images/xls-img.svg') }}"
                                                                    alt="xls Icon">
                                                            </a>
                                                        @elseif (str_contains($data->file_extension, 'docx'))
                                                            <a href="{{ Storage::url('uploads/media-files/' . $data->attachment_file) }}"
                                                                target="_blank"> <img
                                                                    src="{{ asset('frontend/images/wordpress-icon.svg') }}"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        @else
                                                            <img src="{{ asset('frontend/images/default-icon.svg') }}"
                                                                alt="Default Icon">
                                                        @endif
                                                    </figure>
                                                </figure>
                                                <div class="w-100 p-2">
                                                    <p>{{ $data->original_name }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
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
@endsection
