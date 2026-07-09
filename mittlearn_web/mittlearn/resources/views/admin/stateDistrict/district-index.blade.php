@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Cities / District</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Cities / District</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('district.index', ['id' => request()->id]) }}">
                            <div class="col-md-3 me-2 mb-2">
                                <input type="text" class="form-control" placeholder="Search by Name" name="name"
                                    value="{{ request('name') }}">
                            </div>
                            <div class="col-md-2 me-2 mb-2">
                                <input type="hidden" class="form-control" placeholder="Search by Email" name="role"
                                    value="{{ request('role') }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('state.district.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                            <!-- Title -->
                            <h5 class="card-title mb-0">All {{ $state->name }}  Cities / District</h5>

                            <!-- Spacer and Controls -->
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <!-- Per Page Selector -->
                                <div class="d-flex align-items-center gap-2">
                                    <label for="paginationSelectOnpage" class="mb-0 text-nowrap">Per Page Records:</label>
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
                                </div>

                                <!-- Add New Button -->
                                <a href="{{ route('district.create', $state) }}" class="btn btn-success">
                                    Add New
                                </a>
                            </div>
                        </div>
                        <hr class="formdivider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Name</b></th>
                                        {{--  <th>Action</th>  --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->city ?? 'NA' }}</td>

                                            {{--  <td>

                                                @isPermission('district.edit')
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('district.edit', $item->id) }}"><i
                                                            class="fa fa-pencil"></i></a>
                                                @endisPermission

                                                @isPermission('district.delete')
                                                    <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                        onclick="confirmDetailsDelete('{{ route('district.delete', $item->id) }}','Book Series')">
                                                        <i class="fa fa-trash"></i></a>
                                                @endisPermission
                                            </td>  --}}
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
