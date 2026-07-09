@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">
        <div class="pagetitle">
            <h1>Edit Role</h1>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Role</h5>

                            <!-- General Form Elements -->
                            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <label for="inputText" class="col-sm-2 col-form-label">Role Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="role_name" name="role_name" value="{{ $role->role_name }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Select</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="role_slug" name="role_slug" required>
                                            <option value="admin" {{ $role->role_slug == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="school_admin"
                                                {{ $role->role_slug == 'school_admin' ? 'selected' : '' }}>School Admin
                                            </option>
                                            <option value="teacher" {{ $role->role_slug == 'teacher' ? 'selected' : '' }}>
                                                Teacher</option>
                                            <option value="student" {{ $role->role_slug == 'student' ? 'selected' : '' }}>
                                                Student</option>
                                            <option value="parent" {{ $role->role_slug == 'parent' ? 'selected' : '' }}>
                                                Parent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description">{{ $role->description }}</textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Role</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </div>
@endsection
