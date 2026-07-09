@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Question Bank</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Question Bank</li>
                <li class="breadcrumb-item active">Questions</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('question-bank.index') }}">
                            <div class="row">

                                <div class="row">
                                    <div class="col mb-3">
                                        <select class="form-control" name="question_type">
                                            <option value="">Select Type</option>
                                            @foreach ($questionType as $slug => $name)
                                                <option value="{{ $slug }}"
                                                    {{ request('question_type') == $slug ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                    <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">All Questions </div>
                            </div>
                            <div class="col-sm-6 text-end mt-3">
                                {{--  @isPermission('test-paper.create')  --}}
                                <a href="{{ route('question-bank.create') }}" class="btn btn-success">
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
                                        <th><b>Question</b></th>
                                        <th><b>Question Type</b></th>
                                        <th><b>Class</b></th>
                                        <th><b>Subject</b></th>
                                        <th><b>Marks</b></th>
                                        <th><b>Diff. Level</b></th>
                                        <th><b>Status</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $question)
                                        @php
                                            if ($question->question_type === 'passage') {
                                                $data = json_decode($question->additional_data, true);
                                            }
                                        @endphp

                                        <tr>
                                            <td>{{ $questions->currentPage() * $questions->perPage() - $questions->perPage() + $loop->iteration . '.' }}

                                            <td data-toggle="tooltip" title="{!! $question->question ?? ($data['paragraph'] ?? 'No data available') !!}"
                                                style="word-wrap: break-word; white-space: normal; max-width: 200px;">
                                                {!! $question->question ?? ($data['paragraph'] ?? 'No data available') !!}
                                            </td>

                                            <td>{{ $question->question_type ?? '' }}</td>
                                            <td>{{ $question->class->name ?? '' }}</td>
                                            <td>{{ $question->subject->name ?? '' }}</td>
                                            <td>{{ $question->marks ?? '' }}</td>
                                            <td>
                                                {{ config('constants.DIFFICULTY_LEVEL.' . $question->difficulty_level) }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $question->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$question->is_active] ?? 'Unknown Status' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{--  @isPermission('subject.edit')  --}}
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('question-bank.edit', $question->id) }}"><i
                                                        class="fa fa-pencil"></i></a>
                                                {{--  @endisPermission  --}}

                                                {{--  @isPermission('subject.delete')  --}}
                                                {{--  <a class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete('{{ route('question-bank.delete', $question->id) }}')">
                                                    <i class="fa fa-trash"></i>
                                                </a>  --}}
                                                {{--  @endisPermission  --}}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $questions->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
