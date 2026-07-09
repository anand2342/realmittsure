@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Roles</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Roles</li>
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
                                <div class="card-title">All Roles</div>
                            </div>
                            <div class="col-sm-6 text-end mt-3">
                                @isPermission('roles.create')
                                    <a href="{{ route('roles.create') }}" class="btn btn-success">
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
                                    @foreach ($roles as $item)
                                        <tr>
                                            <td>{{ $roles->currentPage() * $roles->perPage() - $roles->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->role_name }}</td>
                                            <td>
                                                <span class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                </span>
                                            </td>
                                            <td>
                                                @isPermission('roles.edit')
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('roles.edit', $item->id) }}"><i
                                                            class="fa fa-pencil"></i></a>
                                                @endisPermission
                                                @if ($item->is_default === 0)
                                                    @isPermission('roles.destroy')
                                                        <meta name="csrf-token" content="{{ csrf_token() }}">
                                                        <a class="btn btn-sm btn-danger"
                                                            onclick="confirmRoleDelete('{{ route('roles.destroy', $item->id) }}')">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endisPermission
                                                @endif
                                                {{-- <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('roles.destroy', $item->id) }}')">
                                        <i class="fa fa-trash"></i>
                                    </button> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $roles->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
