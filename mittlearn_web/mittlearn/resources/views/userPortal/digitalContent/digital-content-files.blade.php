@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="row">
            <div class="col-md-12">
                <div class="cardBox classDetails">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                        <h6 class="m-0 fw-semibold">{{ $data['folderId']->folder_name }}</h6>
                    </div>
                    <div class="classesCourse mb-4">
                        <div class="row">
                            <div id="search-results" class="row mt-3">
                                @if ($data['files'] && $data['files']->count() > 0)
                                    @foreach ($data['files'] as $item)
                                        <div class="col-xl-2 col-lg-3 col-md-3 mb-3 px-2 position-relative class-item"
                                            data-title="{{ $item->original_name }}" title="{{ $item->original_name }}">
                                            <div class="classesBx">
                                                <figure>
                                                    @if (str_contains($item->file_extension, 'mp3') || str_contains($item->file_extension, 'wav'))
                                                        <a href="{{ Storage::url('uploads/media-files/' . $item->attachment_file) }}"
                                                            target="_blank"> <img
                                                                src="{{ asset('frontend/images/audio-icon.svg') }}"
                                                                alt="Audio Icon">
                                                        </a>
                                                    @elseif (in_array($item->file_extension, ['mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','m2ts','ogv','ts','mxf']))
                                                        <!-- For video files, display video icon -->
                                                        <a href="{{ Storage::url('uploads/media-files/' . $item->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/video-icon.svg') }}"
                                                                alt="Video Icon" />
                                                        </a>
                                                    @elseif (str_contains($item->file_extension, 'jpg') || str_contains($item->file_extension, 'png'))
                                                        <a href="{{ Storage::url('uploads/media-files/' . $item->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/jpg-icon.svg') }}"
                                                                alt="Audio Icon">
                                                        </a>
                                                    @elseif (str_contains($item->file_extension, 'pdf'))
                                                        <a href="{{ Storage::url('uploads/media-files/' . $item->attachment_file) }}"
                                                            target="_blank"> <img
                                                                src="{{ asset('frontend/images/pdf-icon.svg') }}"
                                                                alt="PDF Icon">
                                                        </a>
                                                    @elseif (str_contains($item->file_extension, 'xlsx'))
                                                        <a href="{{ Storage::url('uploads/media-files/' . $item->attachment_file) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('frontend/images/xls-img.svg') }}"
                                                                alt="xls Icon">
                                                        </a>
                                                    @elseif (str_contains($item->file_extension, 'docx'))
                                                        <a href="{{ Storage::url('uploads/media-files/' . $item->attachment_file) }}"
                                                            target="_blank"> <img
                                                                src="{{ asset('frontend/images/wordpress-icon.svg') }}"
                                                                alt="PDF Icon">
                                                        </a>
                                                    @else
                                                        <img src="{{ asset('frontend/images/default-icon.svg') }}"
                                                            alt="Default Icon">
                                                    @endif

                                                </figure>
                                                <span>{{ Str::limit($item->original_name, 12, '...') }}</span>
                                                <p>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</p>
                                            </div>
                                            <button type="button"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-5 mediaDelete"
                                                onclick="confirmDelete('{{ route('content.file.delete', $item->id) }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
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
