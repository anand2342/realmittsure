@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">
        <div class="d-flex flex-wrap">
            <div class="leftpanel">
                <div class="helloSection pb-1">
                    <div class=" pe-md-5">
                        <h2><b>MY</b> Courses</h2>
                        <p>Unlock your potential and dive into a world of knowledge with our exciting courses!</p>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <span class="badge">{{ $currentUser->studentDetails->className->name }}</span>
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div>
                <hr class="mt-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('mittbunny.courses') }}">Subjects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course Listing</li>
                    </ol>
                </nav>
                @php
                    $subject = $courses['courses']->first()['metadataValues']->where('field_name', 'subject')->first();

                    $subjectName = $subject->subjectInfo->name ?? '';
                @endphp
                <div class="cardBox myCourses">
                    <div class="headingBx d-flex align-items-center pb-3">
                        <h4 class="text-black fs-6">{{ $subjectName }}</h4>
                        <lottie-player src="{{ asset('mittbunny/images/cat-faded.json') }}"
                            style="width: 50px;height: 50px;" loop autoplay></lottie-player>
                    </div>

                    <div class="table-responsive tbleDiv ">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>Book Name</th>
                                    <th>Subject</th>
                                    <th>Total Chapters</th>
                                    <th>Content Group</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($courses['courses']->isNotEmpty())
                                    @foreach ($courses['courses'] as $course)
                                        @php
                                            $subject = $course['metadataValues']
                                                ->where('field_name', 'subject')
                                                ->first();

                                            $subjectName = $subject->subjectInfo->name ?? 'N/A';
                                            $bannerImage = $course['metadataValues']
                                                ->where('field_name', 'book_cover_image')
                                                ->value('field_value');
                                            $thumbnailImage = $course['metadataValues']
                                                ->where('field_name', 'thumbnail_image')
                                                ->value('field_value');
                                            $bannerImage2 = $course['metadataValues']
                                                ->where('field_name', 'banner_image')
                                                ->value('field_value');
                                            $courseGroup = App\Models\Category::where('status', 1)
                                                ->where('id', $course->category_id)
                                                ->value('name');

                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center myCourseLft">
                                                    <figure>
                                                        @if ($bannerImage)
                                                            <img src="{{ $bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-image.jpg') }}"
                                                                alt="course image">
                                                        @elseif ($thumbnailImage)
                                                            <img src="{{ $thumbnailImage ? Storage::url($thumbnailImage) : asset('frontend/images/default-image.jpg') }}"
                                                                alt="{{ $course['course_name'] }}-image">
                                                        @else
                                                            <img src="{{ $bannerImage2 ? Storage::url($bannerImage2) : asset('frontend/images/default-image.jpg') }}"
                                                                alt="{{ $course['course_name'] }}-image">
                                                        @endif
                                                    </figure>
                                                    <div class="coursesName coursesNameTbl">
                                                        <h3>{{ $course['course_name'] }}</h3>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="white-space: normal;">{{ $subjectName }}</td>
                                            <td>{{ $course->totalChapters->count() }}</td>
                                            <td>{{ $courseGroup }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="bg-transparent border-0 p-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <img src="{{ asset('frontend/images/action-icon.svg') }}"
                                                            alt="" width="28">
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('mittbunny.courses.chapter.listing', ['slug' => $course->slug, 'id' => $course->id]) }}">View
                                                                Details</a></li>
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('mittbunny.course.digital-content', $course->id) }}">View
                                                                Digital Content</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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
