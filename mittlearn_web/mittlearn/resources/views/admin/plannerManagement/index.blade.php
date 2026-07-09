@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Planners</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Planners</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-3">
                                <form method="GET" action="{{ route('planner.index') }}">
                                    <div class="row">

                                        <div class="col mb-3">
                                            <select class="form-control" name="academic_session"
                                                id="academicSessionDropdown">
                                                <option value="" disabled selected>Select Academic Session</option>
                                                @foreach ($academicSession as $session)
                                                    <option value="{{ $session }}"
                                                        {{ request('academic_session') == $session ? 'selected' : '' }}>
                                                        {{ $session }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col mb-3">
                                            <select class="form-control" name="batch" id="batchDropdown">
                                                <option value="" disabled selected>Select Batch</option>
                                            </select>
                                        </div>

                                        <div class="col mb-3">
                                            <select class="form-control" name="series">
                                                <option value="" disabled selected>Select Book Series</option>
                                                @foreach ($series as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ request('series') == $id ? 'selected' : '' }}>{{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col mb-3">
                                            <select class="form-control" name="class">
                                                <option value="" disabled selected>Select Class</option>
                                                @foreach ($classes as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ request('class') == $id ? 'selected' : '' }}>
                                                        {{ $name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col mb-3">
                                            <select class="form-control" name="subject">
                                                <option value="" disabled selected>Select Subject</option>
                                                @foreach ($subject as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ request('subject') == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-1">
                                            <input type="hidden" class="form-control"
                                                placeholder="Search by Generated User" name="generated_by"
                                                value="{{ request('generated_by') }}">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="{{ route('planner.index') }}" class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-2 mb-3">
                                            <label for="start_date" style="font-size: 13px;">Start Date</label>
                                            <input type="date" class="form-control" placeholder="Select Date"
                                                name="start_date" value="{{ request('start_date') }}">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="start_date" style="font-size: 13px;">End Date</label>
                                            <input type="date" class="form-control" placeholder="Select Date"
                                                name="end_date" value="{{ request('end_date') }}">
                                        </div>
                                        <div class="col-md-3 mb-3 pt-4">
                                            <input type="hidden" class="form-control"
                                                placeholder="Search by Generated User" name="generated_by"
                                                value="{{ request('generated_by') }}">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="{{ route('access.code.index') }}" class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div> --}}
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-sm-6">
                                        <div class="card-title">All Planners</div>
                                    </div>
                                    <div class="col-sm-6 text-end mt-3">
                                        <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                                            <div class="d-flex align-items-center">
                                                <label for="roles" class="me-2 mb-0">Per Page Records:</label>
                                                <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                                    style="width: 80px;">
                                                    <option value="" disabled
                                                        {{ session('per_page_records') ? '' : 'selected' }}>
                                                        --Select--
                                                    </option>
                                                    @foreach ([10, 20, 30, 40, 50] as $option)
                                                        <option value="{{ $option }}"
                                                            {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @isPermission('planner.create')
                                                <a href="{{ route('planner.create') }}" class="btn btn-success">
                                                    Add New
                                                </a>
                                            @endisPermission
                                        </div>
                                    </div>
                                </div>

                                <hr class="formdivider">
                                <div class="table-responsive tbleDiv ">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>Chapter Title</b></th>
                                                <th><b>Batch Name</b></th>
                                                <th><b>Board</b></th>
                                                <th><b>Medium</b></th>
                                                <th>Series</th>
                                                <th>Class</th>
                                                <th>Subject</th>
                                                <th>Allotted Days</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                                    </td>
                                                    <td>{{ implode(', ', $item->chapter_names ?? []) }}</td>
                                                    <td>{{ $item->batch->batch_name ?? 'N/A' }}</td>
                                                    <td>{{ $item->board->name }}</td>
                                                    <td>{{ $item->medium->name }}</td>
                                                    <td>{{ $item->series->name }}</td>
                                                    <td>{{ $item->class->name }}</td>
                                                    <td>{{ $item->subject->name }}</td>
                                                    <td>{{ $item->allotted_days }}</td>

                                                    <td>
                                                        @isPermission('planner.view')
                                                            <a class="btn btn-sm btn-info "
                                                                href="{{ route('planner.view', $item->id) }}">Edit & View</a>
                                                        @endisPermission

                                                        @isPermission('planner.delete')
                                                            <a class="btn btn-sm btn-danger"
                                                                onclick="confirmDelete('{{ route('planner.delete', $item->id) }}')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endisPermission

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $data->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        const getBatchRouteTpl = "{{ route('academic-session.get-batch', ['name' => 'SESSION_NAME']) }}";

        function loadBatches(sessionName, selectedBatchId = null) {
            const url = getBatchRouteTpl.replace('SESSION_NAME', encodeURIComponent(sessionName));
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const batchDropdown = document.getElementById('batchDropdown');
                    batchDropdown.innerHTML = '<option value="" disabled selected>Select Batch</option>';

                    if (Array.isArray(data.batches)) {
                        data.batches.forEach(batch => {
                            const option = document.createElement('option');
                            option.value = batch.id;
                            option.textContent = batch.batch_name;

                            if (selectedBatchId && batch.id == selectedBatchId) {
                                option.selected = true;
                            }
                            batchDropdown.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                });

        }
        document.addEventListener('DOMContentLoaded', function() {
            const academicSessionDropdown = document.getElementById('academicSessionDropdown');
            const selectedSession = academicSessionDropdown?.value;
            const selectedBatch = "{{ request('batch') }}";

            if (selectedSession) {
                loadBatches(selectedSession, selectedBatch);
            }

            academicSessionDropdown?.addEventListener('change', function() {
                loadBatches(this.value);
            });
        });
    </script>

@endsection
