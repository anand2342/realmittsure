@extends('frontend.layouts.master')

@section('content')
    <div>
        <div class="blogsliderSection">
            <div class="container">
                <div class="d-lg-flex">
                    <div class="sliderBlog">
                        @foreach ($popular_blogs as $blog)
                            @php
                                $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)
                                    ->where('type', 'blog')
                                    ->first();
                            @endphp
                            <div class="item">
                                <div class="blogSliderMain">
                                    <div class="blogsliderleft">
                                        <div class="blogProfile mb-3">
                                            <figure>
                                                <img src="{{ asset('frontend/images/blog-profile.jpg') }}" alt="">
                                            </figure>
                                            <strong><b class="m-0">Mittlearn</b> </strong>
                                        </div>
                                        <h3>{{ $blog->title }}</h3>
                                        @php
                                            $mainCategory = $blog->categories->firstWhere('parent_id', null);
                                            $subCategory = $blog->categories->firstWhere('parent_id', '!=', null);
                                        @endphp

                                        <span class="techLine">
                                            {{ $mainCategory?->name ?? 'Uncategorized' }}
                                            @if ($subCategory)
                                                &rarr; {{ $subCategory->name }}
                                            @endif
                                        </span> {{-- <p>{!! $blog->body !!}</p> --}}
                                        <p>{!! Str::limit($blog->body, 380, '...') !!}</p>

                                        <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                            <span><img src="{{ asset('frontend/images/icon-eye.svg') }}" alt=""
                                                    width="14"> {{ $blog->views_count }}</span>
                                            <span><img src="{{ asset('frontend/images/icon-calender.svg') }}" alt=""
                                                    width="14">{{ dateConvert($blog->published_at, 'd, M Y') }}</span>
                                        </div>
                                        <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}"
                                            class="btn-primary btn-primary-gradient knowMorebtn"><i
                                                class="bi bi-arrow-down-right me-2"></i> Read More</a>
                                    </div>
                                    <div class="sliderRight">
                                        <img src="{{ Storage::url('uploads/blog/' . $image->attachment_file) }}"
                                            alt="" class="imgoneSlider">

                                        <span class="vrLine"></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="blogRightSec">
                        <div class="blogTxt">
                            <strong>Blog.</strong>
                            <div class="">
                                <lottie-player src="{{ asset('frontend/images/arrow.json') }}" background="transparent"
                                    speed="1" style="width: 90px; height: 90px;" loop=""
                                    autoplay=""></lottie-player>
                            </div>
                        </div>
                        <div class="articalMain">
                            <h4>Popular Articles</h4>
                            @foreach ($popular_blogs as $blog)
                                @php
                                    $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)
                                        ->where('type', 'blog')
                                        ->first();
                                @endphp
                                <div class="artificialBx z1">
                                    <figure class="m-0">
                                        <img src="{{ Storage::url('uploads/blog/' . $image->attachment_file) }}">
                                    </figure>
                                    <div>
                                        <h6>{{ $blog->title }}</h6>
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-wrap gap-3 courseInfo">
                                                <span><img src="{{ asset('frontend/images/blog-profile.jpg') }}"
                                                        alt="" width="14">
                                                    {{ $blog->views_count }}</span>
                                                {{-- <span><img src="{{ asset('frontend/images/icon-clock.svg') }}"
                                                        alt="" width="14"> 25
                                                    Min</span> --}}
                                            </div>
                                            <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}"
                                                class="arrowGreen"><i class="bi bi-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mouseLottie">
                                <lottie-player src="{{ asset('frontend/images/Scroll-down.json') }}"
                                    background="transparent" speed="1" style="width: 90px; height: 90px;"
                                    loop="" autoplay=""></lottie-player>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="exclusiveSection">
            <div class="container">
                <div class="section-heading d-flex justify-content-between w-100">
                    <h2 class="text-white"><span class="greenBorder"></span>
                        Exclusive Blog</h2>
                    <div class="exclusiveInput">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search Article">
                    </div>
                </div>
                <div class="row px-md-1" id="blogContainer">
                    @foreach ($blogs as $blog)
                        @php
                            $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)->where('type', 'blog')->first();
                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-4 px-md-3 mb-4 blog-item" data-title="{{ $blog->title }}"
                            data-meta-title="{{ $blog->meta_title }}" data-meta-keywords="{{ $blog->meta_keywords }}"
                            data-meta-description="{{ $blog->meta_description }}" data-body="{{ $blog->body }}">
                            <div class="exclusiveBox h-100">
                                <figure class="blogImg">
                                    <a href="{{ route('blog.details', ['slug' => $blog->slug]) }}">
                                        <img src="{{ Storage::url('uploads/blog/' . $image->attachment_file) }}">
                                    </a>
                                </figure>
                                @php
                                    $mainCategory = $blog->categories->firstWhere('parent_id', null);
                                    $subCategory = $blog->categories->firstWhere('parent_id', '!=', null);
                                @endphp

                                <span class="techLine">
                                    {{ $mainCategory?->name ?? 'Uncategorized' }}
                                    @if ($subCategory)
                                        &rarr; {{ $subCategory->name }}
                                    @endif
                                </span>
                                <b>{{ $blog->title }}</b>
                                <h4><a
                                        href="{{ route('blog.details', ['slug' => $blog->slug]) }}">{{ $blog->meta_title }}</a>
                                </h4>
                                <p>{{ $blog->meta_description }}</p>
                                <div class="blogProfile mb-3">
                                    <figure>
                                        <img src="{{ asset('frontend/images/blog-profile.jpg') }}" alt="">
                                    </figure>
                                    <strong class="m-0">Mittlearn</strong>
                                </div>
                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                    <span><img src="{{ asset('frontend/images/icon-eye.svg') }}" alt=""
                                            width="14"> {{ $blog->views_count }}</span>
                                    <span><img src="{{ asset('frontend/images/icon-calender.svg') }}" alt=""
                                            width="14">{{ dateConvert($blog->published_at, 'd, M Y') }} </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="customPagination mt-4">
                    <ul class="pagination">
                        <li class="page-item {{ $blogs->onFirstPage() ? 'disabled' : '' }} previous-item">
                            <a class="page-link" href="{{ $blogs->previousPageUrl() }}">
                                <span><img src="{{ asset('frontend/images/arrowprw.svg') }}" width="6"></span>
                            </a>
                        </li>

                        @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $blogs->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        <li class="page-item {{ $blogs->hasMorePages() ? '' : 'disabled' }} next-item">
                            <a class="page-link" href="{{ $blogs->nextPageUrl() }}">
                                <span><img src="{{ asset('frontend/images/arrownxt.svg') }}" width="6"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
