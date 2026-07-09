@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2> Show Permission</h2>
                </div>
                <div class="text-end">
                    <a class="btn btn-primary" href="{{ route('permissions.index') }}"> Back</a>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    {{-- {{ $permission->title }} --}}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Permissions:</strong>
                    @if (!empty($permissionPermissions))
                        @foreach ($permissionPermissions as $v)
                            <label class="label label-success">{{ $v->name }},</label>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
