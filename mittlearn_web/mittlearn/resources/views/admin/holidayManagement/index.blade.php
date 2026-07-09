@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Holidays</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Holidays</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <h5 class="card-title mb-0">All Holiday</h5>

                            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                <label for="paginationSelectOnpage" class="me-2 mb-0">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>
                                        --Select--</option>
                                    @foreach ([10, 20, 30, 40, 50] as $option)
                                        <option value="{{ $option }}"
                                            {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>

                                @isPermission('add.holiday')
                                    <a href="{{ route('add.holiday') }}" class="btn btn-success btn-sm">Add New</a>
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
                                    @foreach ($holidays as $item)
                                        <tr>
                                            <td>{{ $holidays->currentPage() * $holidays->perPage() - $holidays->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->holiday_name }}</td>
                                            <td>
                                                <span class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                </span>
                                            </td>
                                            <td>
                                                @isPermission('edit.holiday')
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('edit.holiday', $item->id) }}"><i
                                                            class="fa fa-pencil"></i></a>
                                                @endisPermission
                                                @isPermission('delete.holiday')
                                                    <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                        onclick="confirmDelete('{{ route('delete.holiday', $item->id) }}')">
                                                        <i class="fa fa-trash"></i></a>
                                                @endisPermission
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $holidays->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
