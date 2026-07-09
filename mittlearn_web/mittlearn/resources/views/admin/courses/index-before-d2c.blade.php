@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Digital Content Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Digital Content Management</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('course.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="course_name" class="form-control"
                                            placeholder="Search by Book Name" value="{{ request('course_name') }}" />
                                    </div>
                                    {{--  <div class="col-md-3">
                                        <select name="category_id" class="form-select">
                                            <option value="">Select Category</option>
                                            @foreach ($categoryId as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == request('category_id') ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>  --}}
                                    <div class="col-md-3">
                                        <select name="series_id" class="form-select">
                                            <option value="">Select Series</option>
                                            @foreach ($bookSeries as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $id == request('series_id') ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="class_id" class="form-select">
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $id == request('class_id') ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="subject_id" class="form-select">
                                            <option value="">Select Subject</option>
                                            @foreach ($subjects as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $id == request('subject_id') ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <input type="hidden" class="form-control" placeholder="Search by Generated User"
                                            name="generated_by" value="{{ request('generated_by') }}">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('course.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">All Books</h5>
                                <div class="text-end">
                                    @isPermission('course.create')
                                        <a class="btn btn-success addnew" href="{{ route('course.create') }}">Add New</a>
                                    @endisPermission
                                </div>
                            </div>
                            <hr class="formdivider">
                            <ul class="nav nav-tabs nav-tabs-bordered" id="BookTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link {{ $activeTab === 'academic_digital_content' ? 'active' : '' }}"
                                        id="Book-tab" data-bs-toggle="tab" data-bs-target="#academicActivityDigitalContent"
                                        type="button" role="tab" aria-controls="academicActivityDigitalContent"
                                        aria-selected="{{ $activeTab === 'academic_digital_content' ? 'true' : 'false' }}">Book
                                        Titles (Academic)</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab === 'talent_skills' ? 'active' : '' }}"
                                        id="Talent-tab" data-bs-toggle="tab" data-bs-target="#talentSkills" type="button"
                                        role="tab" aria-controls="talentSkills"
                                        aria-selected="{{ $activeTab === 'talent_skills' ? 'true' : 'false' }}">Talent-Skills
                                        Course</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab === 'academic_activity' ? 'active' : '' }}"
                                        id="academicActivity-tab" data-bs-toggle="tab" data-bs-target="#academicActivity"
                                        type="button" role="tab" aria-controls="academicActivity"
                                        aria-selected="{{ $activeTab === 'academic_activity' ? 'true' : 'false' }}">Academic
                                        Activities
                                        Course</button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2" id="BookTabContent">
                                <!-- Book Titles Tab -->
                                <div class="tab-pane fade show {{ $activeTab === 'academic_digital_content' ? 'show active' : '' }}"
                                    id="academicActivityDigitalContent" role="tabpanel" aria-labelledby="Book-tab">
                                    <div class="table-responsive tbleDiv">
                                        <table id="datatable-books" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Board</th>
                                                    <th>Medium</th>
                                                    <th>Book Series</th>
                                                    <th>Class</th>
                                                    <th>Subject</th>
                                                    <th>Book Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($acadCourses as $course)
                                                    @php
                                                        $board = $course->metadataValues
                                                            ->where('field_name', 'board')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $medium = $course->metadataValues
                                                            ->where('field_name', 'medium')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $class = $course->metadataValues
                                                            ->where('field_name', 'class')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $series = $course->metadataValues
                                                            ->where('field_name', 'series')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $subject = $course->metadataValues
                                                            ->where('field_name', 'subject')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $boardName = App\Models\Board::where('id', $board)
                                                            ->pluck('name')
                                                            ->first();
                                                        $mediumName = App\Models\Medium::where('id', $medium)
                                                            ->pluck('name')
                                                            ->first();
                                                        $seriesName = App\Models\BookSeries::where('id', $series)
                                                            ->pluck('name')
                                                            ->first();
                                                        $className = App\Models\Classes::where('id', $class)
                                                            ->pluck('name')
                                                            ->first();
                                                        $subjectName = App\Models\Subject::where('id', $subject)
                                                            ->pluck('name')
                                                            ->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $acadCourses->firstItem() + $loop->index }}.</td>
                                                        <td>{{ $boardName ?? '-' }}</td>
                                                        <td>{{ $mediumName ?? '-' }}</td>
                                                        <td>{{ $seriesName ?? '-' }}</td>
                                                        <td>{{ $className ?? '-' }}</td>
                                                        <td>{{ $subjectName ?? 'Talent-Skills' }}</td>
                                                        <td><span>{{ $course->course_name }}</span></td>
                                                        <td>
                                                            @isPermission('course.edit')
                                                                <a class="btn btn-warning btn-sm me-2"
                                                                    href="{{ route('course.edit', $course->id) }}"
                                                                    title="Edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                            @endisPermission
                                                            @isPermission('course.activate')
                                                                <a class="btn btn-sm statusBtn {{ $course->is_active ? 'btn-success' : 'btn-danger' }}"
                                                                    href="javascript:void(0);"
                                                                    onclick="confirmStatus('{{ route('course.activate', $course->id) }}', {{ $course->is_active }})">
                                                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                                                </a>
                                                            @endisPermission
                                                            @isPermission('course.add.chapter')
                                                                <a class="btn btn-primary btn-sm ms-1"
                                                                    href="{{ route('course.add.chapter', $course->id) }}"
                                                                    title="Manage Chapters">
                                                                    Manage Chapters
                                                                </a>
                                                            @endisPermission
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span>Showing {{ $acadCourses->firstItem() }} to
                                                {{ $acadCourses->lastItem() }} of {{ $acadCourses->total() }}
                                                entries</span>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            {!! $acadCourses->appends(array_merge(request()->query(), ['active_tab' => 'academic_digital_content']))->links('pagination::bootstrap-4') !!} </div>
                                    </div>
                                </div>

                                <!-- Talent-Skills Course Tab -->
                                <div class="tab-pane fade {{ $activeTab === 'talent_skills' ? 'show active' : '' }}"
                                    id="talentSkills" role="tabpanel" aria-labelledby="Talent-tab">
                                    <div class="table-responsive tbleDiv">
                                        <table id="datatable-talent" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Group</th>
                                                    <th>Sub-Group</th>
                                                    <th>Book/Course Name</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($unAcadCourses as $course)
                                                    @php
                                                        $class = $course->metadataValues
                                                            ->where('field_name', 'class')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $subject = $course->metadataValues
                                                            ->where('field_name', 'subject')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $className = App\Models\Classes::where('id', $class)
                                                            ->pluck('name')
                                                            ->first();
                                                        $subjectName = App\Models\Subject::where('id', $subject)
                                                            ->pluck('name')
                                                            ->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $unAcadCourses->firstItem() + $loop->index }}.</td>
                                                        <td><span>{{ $course->category->name ?? ' ' }}</span></td>
                                                        <td><span>{{ $course->subCategory->name ?? ' ' }}</span></td>
                                                        <td><span>{{ $course->course_name ?? ' ' }}</span></td>
                                                        <td>
                                                            @isPermission('course.edit')
                                                                <a class="btn btn-warning btn-sm me-2"
                                                                    href="{{ route('course.edit', $course->id) }}"
                                                                    title="Edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                            @endisPermission
                                                            @isPermission('course.activate')
                                                                <a class="btn btn-sm statusBtn {{ $course->is_active ? 'btn-success' : 'btn-danger' }}"
                                                                    href="javascript:void(0);"
                                                                    onclick="confirmStatus('{{ route('course.activate', $course->id) }}', {{ $course->is_active }})">
                                                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                                                </a>
                                                            @endisPermission
                                                            @isPermission('course.add.chapter')
                                                                <a class="btn btn-primary btn-sm ms-1"
                                                                    href="{{ route('course.add.chapter', $course->id) }}"
                                                                    title="Manage Chapters">
                                                                    Manage Content
                                                                </a>
                                                            @endisPermission
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Pagination info and pagination links -->
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <!-- Display current page info -->
                                            <span>Showing {{ $unAcadCourses->firstItem() }} to
                                                {{ $unAcadCourses->lastItem() }} of {{ $unAcadCourses->total() }}
                                                entries</span>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <!-- Display pagination links -->
                                            {!! $unAcadCourses->appends(array_merge(request()->query(), ['active_tab' => 'talent_skills']))->links('pagination::bootstrap-4') !!}
                                        </div>
                                    </div>
                                </div>


                                <!-- Academic Activities Course Tab -->
                                <div class="tab-pane fade {{ $activeTab === 'academic_activity' ? 'show active' : '' }}"
                                    id="academicActivity" role="tabpanel" aria-labelledby="academicActivity-tab">
                                    <div class="table-responsive tbleDiv">
                                        <table id="datatable-academic_activity"
                                            class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Group</th>
                                                    <th>Activity Name</th>
                                                    <th>Book Series</th>
                                                    <th>Class</th>
                                                    <th>Subject</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($acadActivitesQuery as $course)
                                                    @php
                                                        $class = $course->metadataValues
                                                            ->where('field_name', 'class')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $subject = $course->metadataValues
                                                            ->where('field_name', 'subject')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $series = $course->metadataValues
                                                            ->where('field_name', 'series')
                                                            ->pluck('field_value')
                                                            ->first();
                                                        $className = App\Models\Classes::where('id', $class)
                                                            ->pluck('name')
                                                            ->first();
                                                        $subjectName = App\Models\Subject::where('id', $subject)
                                                            ->pluck('name')
                                                            ->first();
                                                        $seriesName = App\Models\BookSeries::where('id', $series)
                                                            ->pluck('name')
                                                            ->first();
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $acadActivitesQuery->firstItem() + $loop->index }}.</td>
                                                        <td><span>{{ $course->category->name ?? ' ' }}</span></td>
                                                        <td><span>{{ $course->course_name ?? ' ' }}</span></td>
                                                        <td><span>{{ $seriesName ?? ' ' }}</span></td>
                                                        <td><span>{{ $className ?? ' ' }}</span></td>
                                                        <td><span>{{ $subjectName ?? ' ' }}</span></td>
                                                        <td>
                                                            @isPermission('course.edit')
                                                                <a class="btn btn-warning btn-sm me-2"
                                                                    href="{{ route('course.edit', $course->id) }}"
                                                                    title="Edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                            @endisPermission
                                                            @isPermission('course.activate')
                                                                <a class="btn btn-sm statusBtn {{ $course->is_active ? 'btn-success' : 'btn-danger' }}"
                                                                    href="javascript:void(0);"
                                                                    onclick="confirmStatus('{{ route('course.activate', $course->id) }}', {{ $course->is_active }})">
                                                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                                                </a>
                                                            @endisPermission
                                                            {{-- @isPermission('course.add.chapter')
                                                                <a class="btn btn-primary btn-sm ms-1"
                                                                    href="{{ route('course.add.chapter', $course->id) }}"
                                                                    title="Manage Chapters">
                                                                    Manage Content
                                                                </a>
                                                            @endisPermission --}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Pagination info and pagination links -->
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <!-- Display current page info -->
                                            <span>Showing {{ $acadActivitesQuery->firstItem() }} to
                                                {{ $acadActivitesQuery->lastItem() }} of
                                                {{ $acadActivitesQuery->total() }}
                                                entries</span>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <!-- Display pagination links -->
                                            {!! $acadActivitesQuery->appends(array_merge(request()->query(), ['active_tab' => 'academic_activity']))->links('pagination::bootstrap-4') !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
