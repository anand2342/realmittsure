@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('up.my.courses') }}">My Courses</a></li>
                <li class="breadcrumb-item active" aria-current="page">Course Listing</li>
            </ol>
        </nav>
        <div class="cardBox myCourses">
            <h2 class="fs-6 fw-semibold mb-3">English</h2>
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
                                    $subject = $course['metadataValues']->where('field_name', 'subject')->first();

                                    $subjectName = $subject->subjectInfo->name ?? 'N/A';
                                    $bannerImage = $course['metadataValues']
                                        ->where('field_name', 'thumbnail_image')
                                        ->value('field_value');
                                    $bookCoverImage = $course['metadataValues']
                                        ->where('field_name', 'book_cover_image')
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
                                                        alt="{{ $course['course_name'] }}-image">
                                                @elseif ($bookCoverImage)
                                                    <img src="{{ $bookCoverImage ? Storage::url($bookCoverImage) : asset('frontend/images/default-image.jpg') }}"
                                                        alt="{{ $course['course_name'] }}-image">
                                                @else
                                                    <img src="{{ $bannerImage2 ? Storage::url($bannerImage2) : asset('frontend/images/default-image.jpg') }}"
                                                        alt="{{ $course['course_name'] }}-image">
                                                @endif
                                            </figure>
                                            <div class="coursesName">
                                                <h3>{{ $course['course_name'] }}</h3>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $subjectName }}</td>
                                    <td>{{ $course->totalChapters->count() }}</td>
                                    <td>{{ $courseGroup }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="bg-transparent border-0 p-0" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{{ asset('frontend/images/action-icon.svg') }}" alt=""
                                                    width="28">
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('up.courses.chapter.listing', ['slug' => $course->slug, 'id' => $course->id]) }}">View
                                                        Details</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ route('up.course.digital-content', $course->id) }}">View
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
    <div class="offcanvas offcanvas-end viewAccess " id="viewAccess">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fs-6 fw-semibold">View Access Code Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <div class="accessDetail mb-3">
                <div class="d-flex justify-content-between mb-2 gap-2">
                    <div class="w-50">
                        <span class="text-success">Occupied Access Code</span>
                        <b>50</b>
                    </div>
                    <div class="w-50">
                        <span class="text-primary">Total Access Code</span>
                        <b>55</b>
                    </div>

                </div>
                <div class="progress-stacked bg-transparent mb-2">
                    <div class="progress" role="progressbar" aria-label="Segment two" aria-valuenow="30" aria-valuemin="0"
                        aria-valuemax="100" style="width: 80%">
                        <div class="progress-bar bg-success"></div>
                    </div>
                    <div class="progress" role="progressbar" aria-label="Segment three" aria-valuenow="20" aria-valuemin="0"
                        aria-valuemax="100" style="width: 20%">
                        <div class="progress-bar bg-primary"></div>
                    </div>
                </div>
                <p>Remaining Code ~ 5</p>
            </div>
            <div class="table-responsive tbleDiv ">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Student List</th>
                            <th colspan="3">
                                <div class="d-flex align-items-center gap-3 justify-content-end">
                                    <span>Assigned</span>
                                    <div class="toggleBtn">
                                        <input type="checkbox" id="switch" /><label for="switch">Toggle</label>
                                    </div>
                                    <span>Not Assigned</span>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th>Student Name</th>
                            <th>Access Code</th>
                            <th>Redeemed Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><a href=""><u>Manish Prajapat</u></a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Vidhi Yadav</u></a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Richi Jain</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Nishta Sharma</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Pankaj Gupta</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Manish Prajapat</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Rishika Pareek</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Dhwani Sharma</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Pankaj Gupta</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="notassignStatus">Assign</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Pankaj Gupta</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="assignStatus">Assigned</div>
                            </td>
                        </tr>
                        <tr>
                            <td><a href="">Rishika Pareek</a></td>
                            <td>1234</td>
                            <td>12/03/2023</td>
                            <td>
                                <div class="notassignStatus">Assign</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <button type="button" class="btn backbtn">Back</button>
                <button type="button" class="btn btn-primary-gradient rounded-1">Submit</button>
            </div>
        </div>
    </div>
@endsection
