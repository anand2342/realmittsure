@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('up.online.class') }}">Online Classes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Digital Content</li>
            </ol>
        </nav>

        <div class="cardBox">
            <h3 class="fs-6 mb-3 fw-semibold">Online Classes</h3>
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
                                                @elseif (in_array($data->file_extension, [
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
                                                    <!-- For video files, display video icon -->
                                                    <img src="{{ asset('frontend/images/video-icon.svg') }}"
                                                        alt="Video Icon" />
                                                    <a href="javascript:void(0);" class="plybtn" data-bs-toggle="modal"
                                                        data-bs-target="#coursePreview{{ $data->id }}">
                                                    </a>
                                                    <div class="modal coursePrv" id="coursePreview{{ $data->id }}">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded-0 border-0">
                                                                <div class="modal-header border-0">
                                                                    <h1 class="modal-title fs-5 fw-normal">Course Preview
                                                                    </h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <p class="py-2 px-3 fs-8">Ceramic How to use Ceramic
                                                                        Cone, Designs on Paper by Ceramic cone.</p>
                                                                    <video width="100%" height="240" controls=""
                                                                        controls controlsList="nodownload"
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
                                            {{--  <div>
                                                <span><img src="{{ asset('frontend/images/clock.svg') }}" alt=""
                                                        width="12"> 34:45</span>
                                            </div>  --}}
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
@endsection
