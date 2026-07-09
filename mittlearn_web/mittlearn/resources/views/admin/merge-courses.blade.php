@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Courses Merege</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Courses</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    {{-- Instructions --}}
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-2 mb-0">
                                    <i class="bi bi-info-circle"></i> Instructions for Course Merge
                                </h5>
                                <button class="btn btn-success" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#instructionContent" aria-expanded="false"
                                    aria-controls="instructionContent">
                                 <i class="bi bi-eye-fill"></i> View Instructions
                                </button>
                            </div>

                            <div class="collapse" id="instructionContent">
                                                            <hr class="form-divider">

                                <ul class="mb-0 ps-3 mt-2">
                                    <li>Select <b>Series</b>, <b>Class</b> and <b>Subject(s)</b> to filter available
                                        courses.</li>
                                    <li>Courses are paired based on the selected filters. You will see
                                        <b>Course 1 (Bilingual)</b> and <b>Course 2 (English)</b>.
                                    </li>
                                    <li>Merging works <b>chapter by chapter</b>. Chapters are matched if their titles are
                                        the same
                                        (case-insensitive, spaces ignored).
                                    </li>
                                    <li>After merging:
                                        <ul>
                                            <li><b>Course 1</b> chapters become
                                                <span class="badge" style="background-color:#00438C;">Bilingual
                                                    Lang.</span>.
                                            </li>
                                            <li><b>Course 2</b> chapters merge into Course 1 as
                                                <span class="badge bg-success">English Lang.</span>.
                                            </li>
                                        </ul>
                                    </li>
                                    <li>Please confirm that <b>both courses have exactly the same chapter titles</b> before
                                        merging.</li>
                                    <li>Once merged, courses cannot be separated again.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('merge.course') }}">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <select name="series_id" class="form-select">
                                            <option value="">Select Series</option>
                                            @foreach ($bookSeries as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $id == request('series_id') ? 'selected' : '' }}>{{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="class_id" class="form-select">
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $id == request('class_id') ? 'selected' : '' }}>{{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="subject_id[]" class="form-select subject-select" multiple>
                                            @foreach ($subjects as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ in_array($id, request()->get('subject_id', [])) ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('merge.course') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @if (!empty($pairedCourses))
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">All Academic Activities</h5>
                                </div>
                                <hr class="formdivider">
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Course 1 (Bilingual)</th>
                                                <th>Course 2 (English)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pairedCourses as $index => $pair)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $pair['course1']->display_name .'**' .$pair['course1']->id }}</td>
                                                    <td>{{ $pair['course2']->display_name .'**' .$pair['course2']->id}}</td>
                                                    <td>
                                                        <form action="{{ route('merge.course.submit') }}" method="POST"
                                                            onsubmit="return confirm('⚠️ Are you sure you want to merge?\n\nIf courses are merged, they will be combined based on the chapter title.\n\nPlease confirm that the chapter names are the same before proceeding.\n\nClick OK to continue, or Cancel to review again.');">
                                                            @csrf
                                                            <input type="hidden" name="course1_id"
                                                                value="{{ $pair['course1']->id }}">
                                                            <input type="hidden" name="course2_id"
                                                                value="{{ $pair['course2']->id }}">
                                                            @if ($pair['course1']->is_merged == 0 || $pair['course2']->is_merged == 0)
                                                                <button class="btn btn-sm btn-success">Merge</button>
                                                            @else
                                                                <button class="btn btn-sm btn-secondary" disabled>Already
                                                                    merged</button>
                                                            @endif
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif(request()->hasAny(['series_id', 'class_id', 'subject_id']))
                        <div class="alert alert-warning">
                            No paired courses found for the selected filters.
                        </div>
                    @endif

                </div>
            </div>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            $('.subject-select').select2({
                placeholder: "Select subjects",
                width: '100%' // optional
            });
        });
    </script>
@endsection
