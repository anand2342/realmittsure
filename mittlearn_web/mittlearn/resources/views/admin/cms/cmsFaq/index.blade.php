@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>FAQs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">FAQs</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <h4 class="card-title mb-0">All FAQs</h4>

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

                                    @isPermission('cms-faq.add')
                                        <a class="btn btn-success" href="{{ route('cms-faq.add') }}">Add New FAQ</a>
                                    @endisPermission
                                </div>
                            </div>

                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th> Name</th>
                                            <th> Sort Order</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getData as $data)
                                            <tr>
                                                <td>{{ $getData->currentPage() * $getData->perPage() - $getData->perPage() + $loop->iteration . '.' }}
                                                <td>{{ $data->question }}</td>
                                                <td>{{ $data->sort_order }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $data->is_active ? 'text-success' : 'text-danger' }}">{{ $data->is_active === 1 ? 'Active' : 'Deactivated' }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @isPermission('cms-faq.edit')
                                                            <a class="btn btn-warning btn-sm me-2"
                                                                href="{{ route('cms-faq.edit', $data->id) }}" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        @endisPermission

                                                        @isPermission('cms-faq.delete')
                                                            <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                onclick="confirmDelete('{{ route('cms-faq.delete', $data->id) }}')"
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endisPermission

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $getData->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
