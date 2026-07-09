@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="cardBox">
            <div class="headingBx d-block d-md-flex">
                <h4 class="fs-5 mb-2 mb-md-0">School Digital Content</h4>
                <div class="d-flex align-items-center gap-2">
                </div>
            </div>
            <div class="row m-0">
                <div id="search-results" class="row mt-3">
                    @if ($data['schoolContent']->isNotEmpty())
                        @foreach ($data['schoolContent'] as $item)
                            <div class="col-md-4 col-xl-2 px-2 mb-3 position-relative class-item"
                                title="{{ $item->folder_name }}">
                                <a href="{{ route('up.digital-content-files', $item->id) }}" class="digitaluplBox h-100"
                                    style="background-color:{{ $item->folder_color }};">
                                    <figure class="m-0">
                                        <img src="{{ asset($item->folder_icon) }}" alt="">
                                    </figure>
                                    <span class="folder-name">{{ Str::limit($item->folder_name, 12, '...') }}
                                        <b>{{ $item->file_count_count }}
                                            Files</b></span>
                                </a>
                                <!-- Delete Button -->
                            </div>
                        @endforeach
                    @else
                        <p class="fw-medium">School Digital Content is not available right now. Once uploaded, you'll find it here. Stay tuned! </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="cardBox mt-3">
            <div class="headingBx d-block d-md-flex">
                <h4 class="fs-5 mb-2 mb-md-0">Teacher Digital Content</h4>
                <div class="d-flex align-items-center gap-2">
                </div>
            </div>
            <div class="row m-0">
                <div id="search-results" class="row mt-3">
                    @if ($data['teacherContent']->isNotEmpty())
                        @foreach ($data['teacherContent'] as $item)
                            <div class="col-md-4 col-xl-2 px-2 mb-3 position-relative class-item"
                                title="{{ $item->folder_name }}">
                                <a href="{{ route('up.digital-content-files', $item->id) }}" class="digitaluplBox h-100"
                                    style="background-color:{{ $item->folder_color }};">
                                    <figure class="m-0">
                                        <img src="{{ asset($item->folder_icon) }}" alt="">
                                    </figure>
                                    <span class="folder-name">{{ Str::limit($item->folder_name, 12, '...') }}
                                        <b>{{ $item->file_count_count }}
                                            Files</b></span>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <p class="fw-medium">Teacher Digital Content is not available right now. Once uploaded, it'll be here for you. Stay tuned! </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
