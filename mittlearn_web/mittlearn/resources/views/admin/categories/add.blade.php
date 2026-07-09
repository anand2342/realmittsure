@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Group</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Group</li>
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
                            <h4 class="card-title">Add </h4>
                            <hr class="form-divider">
                            {{ Form::open(['url' => route('sub-category.save'), 'class' => 'row g-3', 'files' => true]) }}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('name', ' Group', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name']) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('featured_image', 'Featured Image', ['class' => 'form-label']) !!}
                                {!! Form::file('featured_image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('icon', 'Icon Image', ['class' => 'form-label']) !!}
                                {!! Form::file('icon', ['class' => 'form-control', 'id' => 'formFile']) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('status', 'Status', ['class' => 'form-label required']) !!}
                                {!! Form::select('status', config('constants.STATUS_LIST'), null, [
                                    'class' => 'form-control form-select',
                                ]) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('description', ' Description', ['class' => 'form-label']) !!}
                                {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Enter Description']) !!}
                            </div>
                            <div class="text-end">
                                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                                {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
