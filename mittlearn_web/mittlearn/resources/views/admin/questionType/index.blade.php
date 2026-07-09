@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Question Type</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Question Type</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">All Question Type</div>
                            </div>
                            <div class="col-sm-6 text-end mt-3">
                                @isPermission('question-type.create')
                                    <a href="{{ route('question-type.create') }}" class="btn btn-success">
                                        Add New
                                    </a>
                                @endisPermission
                            </div>
                        </div>
                        <hr class="formdivider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Name</b></th>
                                        <th><b>Status</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <span class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                </span>
                                            </td>
                                            <td>
                                                @isPermission('question-type.edit')
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('question-type.edit', $item->id) }}"><i
                                                            class="fa fa-pencil"></i></a>
                                                @endisPermission
                                                {{-- @isPermission('question-type.delete')
                                                    <a class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('{{ route('question-type.delete', $item->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endisPermission --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $data->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
