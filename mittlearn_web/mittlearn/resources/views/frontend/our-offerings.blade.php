@extends('frontend.layouts.master')

@section('content')
    <div>
        <div class="offeringsBanner">
            <div class="lottieSquare">
                <lottie-player src="{{ asset('frontend/images/square-shape-loading.json') }}" autoPlay loop
                    style="width: 120px; height: 120px;"></lottie-player>
            </div>
            <img src="{{ asset('frontend/images/blue-square.svg') }}" alt="" width="80" class="squareImg">
            <div class="container">
                <div class="bannerTxt">
                    <h1>Our <b> Offerings</b></h1>
                    <p>Explore MITTSURE’s full range of educational books, digital content, and learning solutions in one
                        place.</p>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="contactUs">
            <div class="container">
                <div class="row justify-content-center">
                    @foreach ($offerings as $data)
                        @php
                            $linkAndDesc = json_decode($data->description);
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-4 px-md-2 mb-3 mt-3">
                            <div class="detailsContact h-100 section-heading offringData">
                                <a target="_blank" href="{{ $linkAndDesc->redirection_link }}">


                                    @if (!empty($data))
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('uploads/website-pages/our-offerings/' . $data->image) }}"
                                                alt="Academic Image" width="500" height="250">
                                        </div>
                                    @endif
                                    <h2 class="mt-3">{{ $data->title ?? null }}</h2>
                                    <hr>
                                    <p class="mt-2"> <strong>{{ $linkAndDesc->ourOfferings_desc }}</strong></p>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <div class="customPagination mt-4">
                        <ul class="pagination">
                            <li class="page-item {{ $offerings->onFirstPage() ? 'disabled' : '' }} previous-item">
                                <a class="page-link" href="{{ $offerings->previousPageUrl() }}">
                                    <span><img src="{{ asset('frontend/images/arrowprw.svg') }}" width="6"></span>
                                </a>
                            </li>

                            @foreach ($offerings->getUrlRange(1, $offerings->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $offerings->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <li class="page-item {{ $offerings->hasMorePages() ? '' : 'disabled' }} next-item">
                                <a class="page-link" href="{{ $offerings->nextPageUrl() }}">
                                    <span><img src="{{ asset('frontend/images/arrownxt.svg') }}" width="6"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
