@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Email Template</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Email Templates</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <h4 class="card-title mb-0">Templates Information</h4>

                                <div class="d-flex align-items-center gap-2">
                                    <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                        style="width: 80px;">
                                        <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>
                                            --Select--
                                        </option>
                                        @foreach ([10, 20, 30, 40, 50] as $option)
                                            <option value="{{ $option }}"
                                                {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @isPermission('email-template.add')
                                        <a class="btn btn-success" href="{{ route('email-template.add') }}">Add Template</a>
                                    @endisPermission
                                </div>
                            </div>

                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emails as $email)
                                            <tr>
                                                <td>{{ $emails->currentPage() * $emails->perPage() - $emails->perPage() + $loop->iteration . '.' }}
                                                </td>
                                                <td> {{ $email->name }}</td>
                                                <td> {{ $email->subject }}</td>
                                                <td>
                                                    @isPermission('email-template.edit')
                                                        <a class="btn btn-warning btn-sm"
                                                            href='{{ route('email-template.edit', $email->id) }}'>
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    @endisPermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $emails->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
