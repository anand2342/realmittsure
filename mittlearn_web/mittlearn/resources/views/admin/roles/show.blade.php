@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <div class="container">
            <h1>Role Details</h1>

            <div class="card">
                <div class="card-header">
                    <h2>{{ $role->role_name }}</h2>
                </div>
                <div class="card-body">
                    <p><strong>Description:</strong> {{ $role->description }}</p>
                    <p><strong>Status:</strong> {{ $role->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
            </div>

            <h3 class="mt-4">Permissions</h3>

            @if ($role->permissions->isEmpty())
                <p>No permissions assigned to this role.</p>
            @else
                <ul class="list-group">
                    @foreach ($role->permissions as $permission)
                        <li class="list-group-item">
                            <strong>{{ $permission->title }}</strong>: {{ $permission->description }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-4">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back to Roles List</a>
            </div>
        </div>
    </div>
@endsection
