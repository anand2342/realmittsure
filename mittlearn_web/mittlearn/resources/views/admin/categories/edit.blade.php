@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Edit Group</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Edit Group</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="{{ route('category.index') }}" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Group Information</h4>
                            <hr class="form-divider">
                            <div class="mb-4">
                                <h5>{{ $categoryHierarchy }} </h5>
                            </div>
                            {!! Form::model($subCategory, [
                                'route' => ['sub-category.update', $subCategory->id],
                                'method' => 'PUT',
                                'files' => true,
                                'class' => 'row g-3',
                            ]) !!}
                            {!! Form::hidden('id', $subCategory->id) !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('name', 'Category Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>
                            @if ($subCategory->parent_id == null)
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('featured_image', 'Featured Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('featured_image', ['class' => 'form-control']) !!}
                                    <img src="{{ Storage::url('uploads/categories/featuredImage/' . $subCategory->featured_image) }}"
                                        alt="featuredImage" width="200" height="100">
                                </div>
                            @endif
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('icon', 'Icon', ['class' => 'form-label']) !!}
                                <small class="form-text text-muted">(Allowed formats: PNG, JPG. Dimensions recommended:
                                    256×256
                                    px.)</small>
                                {!! Form::file('icon', ['class' => 'form-control']) !!}
                                <img src="{{ Storage::url('uploads/categories/icon/' . $subCategory->icon) }}"
                                    alt="icon Image" width="200" height="100">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('status', 'Status', ['class' => 'form-label required']) !!}
                                {!! Form::select('status', config('constants.STATUS_LIST'), null, [
                                    'class' => 'form-control form-select',
                                    'placeholder' => '--select--',
                                ]) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                {!! Form::text('description', null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="text-end">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                                {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
