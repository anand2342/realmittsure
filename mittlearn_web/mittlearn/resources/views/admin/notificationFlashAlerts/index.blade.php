@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1> Flash Alert Notifications</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Flash Alert Notifications</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h5 class="card-title mb-0">All Flash Alert Notifications</h5>

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

                                {{-- @isPermission('flash.notification.alerts.add') --}}
                                <a href="{{ route('flash.notification.alerts.add') }}" class="btn btn-success">
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
                                        <th><b>Notification Message</b></th>
                                        <th><b>Visible To</b></th>
                                        <th><b>Created Date</b></th>
                                        <th><b>Status</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->message }}</td>
                                            {{-- @php
                                                $slugs = explode(',', $item->role_visibility);

                                                return Role::whereIn('slug', $slugs)->pluck('name')->toArray();

                                            @endphp --}}
                                            <td>{{ implode(', ', $item->visible_role_names) }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{-- @isPermission('flash.notification.alerts.edit') --}}
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('flash.notification.alerts.edit', $item->id) }}"><i
                                                        class="fa fa-pencil"></i></a>
                                                {{-- @endisPermission --}}
                                                {{-- @isPermission('flash.notification.alerts.delete') --}}
                                                <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                    onclick="confirmDelete('{{ route('flash.notification.alerts.delete', $item->id) }}')">
                                                    <i class="fa fa-trash"></i></a>
                                                {{-- @endisPermission --}}
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
