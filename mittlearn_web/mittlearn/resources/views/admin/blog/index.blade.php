@extends('admin.layouts.master')

@section('content')

    <div>
        <div class="pagetitle">
            <h1> Blogs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Blogs</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h5 class="card-title mb-0">All Blogs</h5>

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

                                    @isPermission('blog.create')
                                        <a class="btn btn-success" href="{{ route('blog.create') }}">Create</a>
                                    @endisPermission
                                </div>
                            </div>

                            <hr class="formdivider">
                            <div class="table-responsive tbleDiv">
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Title</th>
                                            <th>
                                                <b>Slug</b>
                                            </th>
                                            <th>Status</th>
                                            <th>Meta-Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($datas)
                                            @foreach ($datas as $data)
                                                <tr>
                                                    <td>{{ $datas->currentPage() * $datas->perPage() - $datas->perPage() + $loop->iteration . '.' }}
                                                    <td>{{ $data->title }}</td>
                                                    <td>{{ $data->slug }}</td>
                                                    <td>{{ $data->status }}</td>
                                                    <td>{{ $data->meta_title }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @isPermission('blog.edit')
                                                                <a class="btn btn-sm btn-warning "
                                                                    href="{{ route('blog.edit', $data->id) }}"><i
                                                                        class="fa fa-pencil"></i></a>
                                                            @endisPermission
                                                            @isPermission('blog.delete')
                                                                <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                    onclick="confirmDelete('{{ route('blog.delete', $data->id) }}')"
                                                                    title="Delete">
                                                                    <i class="fa fa-trash"></i></a>
                                                            @endisPermission
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <td>There is no data</td>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $datas->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>


@endsection
