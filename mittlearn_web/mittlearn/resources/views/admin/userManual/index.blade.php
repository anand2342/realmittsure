@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1> User Manual</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">User Manual</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h5 class="card-title mb-0">All User Manual</h5>

                            <div class="d-flex align-items-center gap-2">
                                <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
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

                                {{-- @isPermission('user-manual.add') --}}
                                <a href="{{ route('user-manual.add') }}" class="btn btn-success">
                                    Add New
                                </a>
                                {{-- @endisPermission --}}
                            </div>
                        </div>

                        <hr class="formdivider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Title</b></th>
                                        <th><b>Visible To</b></th>
                                        <th><b>Created Date</b></th>
                                        <th><b>Status</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data->isNotEmpty())
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                                </td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ implode(', ', $item->visible_role_names) }}</td>
                                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                        {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('user-manual.edit', $item->id) }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                        onclick="confirmDelete('{{ route('user-manual.delete', $item->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No records found.</td>
                                        </tr>
                                    @endif
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
