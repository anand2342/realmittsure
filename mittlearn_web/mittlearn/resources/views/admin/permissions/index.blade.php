@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <div class="container">
            <h1>Permissions</h1>
            @isPermission('permissions.create')
                <a href="{{ route('permissions.create') }}" class="btn btn-success mb-3">Create Permission</a>
            @endisPermission
            <div class="table-responsive tbleDiv ">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Slug</th>
                            <th>Category</th>
                            <th>Permission Type</th>
                            <th>Accessible For</th>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td>{{ $permission->slug }}</td>
                                <td>{{ $permission->category }}</td>
                                <td>{{ $permission->permission_type }}</td>
                                <td>{{ $permission->accessable_for }}</td>
                                <td>{{ $permission->title }}</td>
                                <td>
                                    @isPermission('permissions.edit')
                                        <a href="{{ route('permissions.edit', $permission->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                    @endisPermission
                                    @isPermission('permissions.destroy')
                                        <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                            onclick="confirmDelete('{{ route('permissions.destroy', $permission->id) }}')">
                                            <i class="fa fa-trash"></i></a>
                                    @endisPermission
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
