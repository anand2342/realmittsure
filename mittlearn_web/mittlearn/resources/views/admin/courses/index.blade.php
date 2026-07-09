@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Digital Content Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Digital Content Management</li>
                    @if ($group === 'academic-digital-content')
                        <li class="breadcrumb-item active">Books</li>
                    @elseif ($group === 'talent-skills')
                        <li class="breadcrumb-item active">Talent Skills</li>
                    @elseif ($group === 'olympiad')
                        <li class="breadcrumb-item active">Olympiad</li>
                    @elseif ($group === 'jaadui-pitara-kit')
                        <li class="breadcrumb-item active">Jaadui Pitara Kit</li>
                    @elseif ($group === 'activity-worksheets')
                        <li class="breadcrumb-item active">Activities / Worksheets</li>
                    @else
                        <li class="breadcrumb-item active">Books</li>
                    @endif
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('course.index', ['group' => $group]) }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="course_name" class="form-control"
                                            placeholder="Search by Book Name" value="{{ request('course_name') }}" />
                                    </div>
                                    @if (request()->segment(count(request()->segments())) !== 'talent-skills')
                                        <div class="col-md-3">
                                            <select name="series_id" id="series_idfilter" class="form-select">
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
                                            <select name="class_id" id="class_id" class="form-select">
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
                                            <select name="subject_id" id="subject_id" class="form-select">
                                                <option value="">Select Subject</option>
                                                @foreach ($subjects as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == request('subject_id') ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-md-3">
                                            <select name="sub_category_id" class="form-select">
                                                <option value="">Select Talent-Skill Category</option>
                                                @foreach ($subcategories as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $id == request('sub_category_id') ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-md-2">
                                        <input type="hidden" class="form-control" placeholder="Search by Generated User"
                                            name="generated_by" value="{{ request('generated_by') }}">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('course.index', ['group' => 'academic-digital-content']) }}"
                                            class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <h5 class="card-title mb-0">All Books</h5>

                                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                    <label for="paginationSelectOnpage" class="me-2 mb-0">Per Page Records:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                        style="width: 80px;">
                                        <option value="" disabled
                                            {{ session('per_page_records') ? '' : 'selected' }}>--Select--</option>
                                        @foreach ([10, 20, 30, 40, 50] as $option)
                                            <option value="{{ $option }}"
                                                {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @isPermission('course.create')
                                        <a class="btn btn-success btn-sm addnew" href="{{ route('course.create') }}">Add
                                            New</a>
                                    @endisPermission
                                </div>
                            </div>
                        </div>
                        <hr class="formdivider">
                        {{-- Tab Navigation --}}
                        <ul class="nav nav-tabs nav-tabs-bordered mb-3">
                            <li class="nav-item">
                                <a class="nav-link {{ $group === 'academic-digital-content' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'academic-digital-content') }}">Books</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $group === 'talent-skills' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'talent-skills') }}">Talent Skills</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link {{ $group === 'academic_activities' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'academic_activities') }}">Activities</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link {{ $group === 'olympiad' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'olympiad') }}">Olympiad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $group === 'jaadui-pitara-kit' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'jaadui-pitara-kit') }}">Jaadui Pitara Kit</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $group === 'activity-worksheets' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'activity-worksheets') }}">Activities / Worksheets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $group === 'others' ? 'active' : '' }}"
                                    href="{{ route('course.index', 'others') }}">Others</a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            @if ($group === 'academic-digital-content')
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                            @elseif ($group === 'talent-skills')
                                                @include('admin.courses.index-tallent', [
                                                    'courses' => $unAcadCourses,
                                                ])
                                            @elseif ($group === 'academic_activities')
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                            @elseif ($group === 'olympiad')
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                            @elseif ($group === 'jaadui-pitara-kit')
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                            @elseif ($group === 'activity-worksheets')
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                            @elseif ($group === 'others')
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                            @else
                                                @include('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ])
                                                {{-- <div class="alert alert-warning">Invalid course category.</div> --}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
    <script>
        $(document).ready(function() {

            // When Series changes
            $('#series_idfilter').on('change', function() {
                let seriesId = $(this).val();

                $('#class_id').html('<option value="">Select Class</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');

                if (seriesId) {
                    $.ajax({
                        url: '{{ url('/courses/get-classes') }}/' + seriesId,
                        type: 'GET',
                        success: function(response) {
                            console.log(response.classes);

                            if (response.classes) {
                                $.each(response.classes, function(id, name) {
                                    $('#class_id').append(
                                        `<option value="${id}">${name}</option>`);
                                });
                            }
                        }
                    });
                }
            });

            // When Class changes (after Series is selected)
            $('#class_id').on('change', function() {
                let classId = $(this).val();
                let seriesId = $('#series_idfilter').val();

                $('#subject_id').html('<option value="">Select Subject</option>');

                if (seriesId && classId) {
                    $.ajax({
                        url: '{{ url('/courses/get-subjects') }}/' + seriesId + '/' +
                            classId,
                        type: 'GET',
                        success: function(response) {
                            if (response.subjects) {
                                $.each(response.subjects, function(id, name) {
                                    $('#subject_id').append(
                                        `<option value="${id}">${name}</option>`);
                                });
                            }
                        }
                    });
                }
            });

        });
    </script>

@endsection
