@extends('schoolPortal.layouts.master')

@section('content')
    @include('admin.layouts.flash-messages')
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">Faculty Development Contents</h5>
                    <p>Mittsure offers a Faculty Development Program to enhance teaching skills with modern methods and
                        practical insights.
                        Access curated content designed to improve classroom effectiveness and learning outcomes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="teacherRighr position-relative">
                    <img src="{{ asset('frontend/images/student-manager-img.svg') }}" alt=""
                        class="teacherImg studentImg">
                </div>
            </div>
        </div>
    </div>
    <div class="cardBox">

        <div class="headingBx d-block d-md-flex">
            <h4 class="fs-5 mb-2 mb-md-0">Contents</h4>
            <div class="d-flex align-items-center gap-2">
                <div class="searchContent">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search content...">
                </div>
            </div>
        </div>

        <div class="row m-0">
            <div id="search-results" class="row mt-3 mb-2">

                @if ($contents && $contents->isNotEmpty())
                    @foreach ($contents as $content)
                        <div class="col-md-4 col-xl-3 px-2 mb-3 class-item" data-title="{{ strtolower($content->title) }}">

                            <a href="{{ route('sp.teacher.development.videos', $content->id) }}"
                                class="digitaluplBox h-100 d-flex flex-column p-3"
                                style="background-color: #DBF8EA; border-radius: 12px; text-decoration: none;">

                                {{-- Folder icon --}}
                                <div class="text-center my-2">
                                    <img src="{{ asset('frontend/images/folder-yellow.svg') }}" alt="folder"
                                        style="height: 55px;">
                                </div>

                                {{-- Title --}}
                                <div class="text-center mt-2">
                                    <div class="fw-semibold" style="color: #000; font-size: 14px;"
                                        title="{{ $content->title }}">
                                        {{ Str::limit($content->title, 22, '...') }}
                                    </div>

                                    {{-- Video count --}}
                                    <span class="badge bg-success mt-1" style="color:#ffffff !important">
                                        {{ $content->videos_count }}
                                        {{ Str::plural('Video', $content->videos_count) }}
                                    </span>

                                    {{-- Access type --}}
                                    {{-- <div class="mt-1">
                                        <span class="badge {{ $content->is_for_all_schools ? 'bg-success' : 'bg-warning text-dark' }}"
                                              style="font-size: 10px;">
                                            {{ $content->is_for_all_schools ? 'All Schools' : 'Selected Schools' }}
                                        </span>
                                    </div> --}}
                                </div>

                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <p class="text-muted">
                            No Teacher Development content has been assigned to your school yet.
                            Please check back later.
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.class-item').forEach(el => {
                el.style.display = el.dataset.title.includes(q) ? '' : 'none';
            });
        });
    </script>

@endsection
