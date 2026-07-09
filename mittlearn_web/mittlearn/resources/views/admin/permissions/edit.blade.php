@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <div class="container">
            <h1>Edit Permission</h1>

            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" value="{{ $permission->slug }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category"
                        value="{{ $permission->category }}" required>
                </div>

                <div class="mb-3">
                    <label for="permission_type" class="form-label">Permission Type</label>
                    <select class="form-select" id="permission_type" name="permission_type" required>
                        <option value="route" {{ $permission->permission_type == 'route' ? 'selected' : '' }}>Route
                        </option>
                        <option value="menu" {{ $permission->permission_type == 'menu' ? 'selected' : '' }}>Menu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="accessable_for" class="form-label">Accessible For</label>
                    <select class="form-select" id="accessable_for" name="accessable_for" required>
                        <option value="web" {{ $permission->accessable_for == 'web' ? 'selected' : '' }}>Web</option>
                        <option value="app" {{ $permission->accessable_for == 'app' ? 'selected' : '' }}>App</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ $permission->title }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description">{{ $permission->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Permission</button>
            </form>
        </div>
    </div>
@endsection
