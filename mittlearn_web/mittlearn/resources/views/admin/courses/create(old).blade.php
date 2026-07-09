@extends('admin.layouts.master')
<style>
    .insertButton {
        margin-left: 78%;
    }
</style>
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Course</h1>
                <nav style="--bs-breadcrumb-divider: '>';">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Course</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add </h4>
                            {{ Form::open(['url' => route('course.store'), 'class' => 'row g-3', 'files' => true]) }}

                           <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', 'Group', ['class' => 'form-label']) !!}
                                {!! Form::select('id', ['' => '--Select Group--'] + $category->toArray(), null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', 'Board', ['class' => 'form-label']) !!}
                                {!! Form::select('id', ['' => '--Select Board--'] + $boards->toArray(), null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', ' Medium', ['class' => 'form-label']) !!}
                                  {!! Form::select('id', ['' => '--Select Medium--'] + $mediums->toArray(), null, ['class' => 'form-control', 'required']) !!}
                            </div>

                             <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', ' Book Series', ['class' => 'form-label']) !!}
                                  {!! Form::select('id', ['' => '--Select Book Series--'] + $bookseries->toArray(), null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', ' Class', ['class' => 'form-label']) !!}
                                  {!! Form::select('id', ['' => '--Select Class--'] + $classes->toArray(), null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', ' Subject', ['class' => 'form-label']) !!}
                                  {!! Form::select('id', ['' => '--Select Subject--'] + $subjects->toArray(), null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('title', ' Content Language', ['class' => 'form-label']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('title', ' No. of Activities', ['class' => 'form-label']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('title', ' Title', ['class' => 'form-label required']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            </div>



                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('price', ' Price', ['class' => 'form-label required']) !!}
                                {!! Form::text('price', null, ['class' => 'form-control', 'required']) !!}
                            </div>

                                                 
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('status', ' Status', ['class' => 'form-label']) !!}
                                {!! Form::select('status', [1 => 'Active', 0 => 'Inactive'], null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2, 'cols' => 50, 'required']) !!}
                            </div>

                             <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('featured_image', ' Thumbnail Image', ['class' => 'form-label']) !!}
                                {!! Form::file('featured_image', null, ['class' => 'form-control']) !!}
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
