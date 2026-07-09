@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="cardBox">
            <div class="headingBx d-block d-md-flex">
                <h4 class="fs-5 mb-2 mb-md-0">School Media Gallery</h4>
                <div class="d-flex align-items-center gap-2">
                </div>
            </div>
            <div class="row m-0">
                <div id="search-results" class="row mt-3">
                    @if ($data['schoolContent']->isNotEmpty())
                        @foreach ($data['schoolContent'] as $item)
                            <div class="col-md-4 col-xl-2 px-2 mb-3 position-relative class-item"
                                title="{{ $item->gallery_name }}">
                                <a href="{{ route('up.media-gallery.files', $item->id) }}" class="digitaluplBox h-100"
                                    style="background-color:#dbf8ea;">
                                    <figure class="m-0">
                                        <img src="{{ asset('frontend/images/folder-yellow.svg') }}" alt="">
                                    </figure>
                                    <span class="folder-name">{{ Str::limit($item->gallery_name, 12, '...') }}
                                       
                                </a>
                                <!-- Delete Button -->
                            </div>
                        @endforeach
                    @else
                        <p class="fw-medium">Media Gallery is not available right now. Once uploaded, you'll find it here. Stay tuned! </p>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
