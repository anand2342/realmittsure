@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Uploaded Content</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Uploaded Content</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('folder.index') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">School</label>
                                    {{ Form::select('school_id', $schools,  request()->filled('user_id') ? request('user_id') : request('school_id'), ['class' => 'form-select', 'placeholder' => '--Select--', 'id' => 'selectedSchool']) }}


                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Teacher</label>
                                    {{ Form::select('teacher_id', [], null, ['class' => 'form-select', 'placeholder' => '--Select--', 'id' => 'selectedTeacher']) }}
                                </div>
                                <div class="col-md-2 mt-2 pt-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('folder.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">All Uploaded Content</div>
                            </div>
                            <hr class="formdivider">

                        </div>
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Name</b></th>
                                        <th><b>Total Files</b></th>
                                        <th><b>Created By</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($folderListing))
                                        @foreach ($folderListing as $item)
                                            <tr>
                                                <td>
                                                    {{ ($folderListing->currentPage() - 1) * $folderListing->perPage() + $loop->iteration }}.
                                                </td>
                                                <td>{{ $item->folder_name }}</td>
                                                <td>{{ $item->fileCount->count() }}</td>
                                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                                <td>
                                                    @isPermission('files.index')
                                                        <a class="btn btn-sm btn-info me-1"
                                                            href="{{ route('files.index', $item->id) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endisPermission

                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No data available. Please select a school
                                                name and search.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @if (isset($folderListing))
                            <div class="d-flex justify-content-right text-right">
                                {!! $folderListing->links('pagination::bootstrap-4') !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#selectedSchool').on('change', function() {
                var schoolId = $(this).val();
                $('#selectedTeacher').html('<option value="">Select</option>');
                if (schoolId) {
                    var url = "{{ route('folder.teacher', ':id') }}".replace(':id', schoolId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data && Object.keys(data).length > 0) {
                                $.each(data, function(id, name) {
                                    $('#selectedTeacher').append('<option value="' +
                                        id +
                                        '">' + name + '</option>');
                                });
                            } else {
                                $('#selectedTeacher').html(
                                    '<option value="">No teacher available </option>');
                            }
                        },
                    });
                }
            });
        });
    </script>
@endsection
