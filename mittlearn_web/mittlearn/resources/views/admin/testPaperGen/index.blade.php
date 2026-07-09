@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Test Paper Gen.(TPG)</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Test Paper Gen.(TPG)</li>
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
                                <form method="GET" action="{{ route('test-paper.index') }}">
                                    <div class="row">

                                        <div class="row">
                                            <div class="col mb-3">
                                                <select class="form-control" name="board_id">
                                                    <option value="">Search by Board</option>
                                                    @foreach ($boards as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ request('board_id') == $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <select class="form-control" name="medium_id">
                                                    <option value="">Search by Medium</option>
                                                    @foreach ($mediums as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ request('medium_id') == $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <select class="form-control" name="series_id">
                                                    <option value="">Search by Series</option>
                                                    @foreach ($series as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ request('series_id') == $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <select class="form-control" name="class_id">
                                                    <option value="">Search by Class</option>
                                                    @foreach ($class as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ request('class_id') == $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <select class="form-control" name="subject_id">
                                                    <option value="">Search by Subject</option>
                                                    @foreach ($subject as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ request('subject_id') == $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col mb-3 text-end">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="{{ route('test-paper.index') }}" class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card-title">All Test Paper List</div>
                                    </div>
                                    <div class="col-sm-6 text-end mt-3">
                                        {{--  @isPermission('test-paper.create')  --}}
                                        <a href="{{ route('test-paper.create') }}" class="btn btn-success">
                                            Add New
                                        </a>
                                        {{--  @endisPermission  --}}
                                    </div>
                                </div>
                                <hr class="formdivider">
                                <div class="table-responsive tbleDiv ">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>Title</b></th>
                                                <th><b>Class</b></th>
                                                <th><b>Subject</b></th>
                                                <th><b>Chapters</b></th>
                                                <th><b>Duration (In minutes)</b></th>
                                                <th><b>Status</b></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tests as $item)
                                                <tr>
                                                    <td>{{ $tests->currentPage() * $tests->perPage() - $tests->perPage() + $loop->iteration . '.' }}
                                                    </td>
                                                    <td>{{ $item->title ?? '' }}</td>
                                                    <td>{{ $item->Class->name ?? '' }}</td>
                                                    <td>{{ $item->Subject->name ?? '' }}</td>
                                                    <td>{{ implode(', ', $item->chapter_names ?? []) }}</td>
                                                    <td>{{ $item->duration ?? '' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                            {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{--  @isPermission('subject.edit')  --}}
                                                        <a class="btn btn-sm btn-success"
                                                            href="{{ route('question.add', $item->id) }}">Add Questions</a>
                                                        {{--  @endisPermission  --}}
                                                        {{--  @isPermission('subject.edit')  --}}
                                                        <a class="btn btn-sm btn-warning"
                                                            href="{{ route('test-paper.edit', $item->id) }}"><i
                                                                class="fa fa-pencil"></i></a>
                                                        {{--  @endisPermission  --}}

                                                        {{--  @isPermission('subject.delete')  --}}
                                                        {{-- <a class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('{{ route('test-paper.delete', $item->id) }}')">
                                                            <i class="fa fa-trash"></i>
                                                        </a> --}}
                                                        {{--  @endisPermission  --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $tests->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
