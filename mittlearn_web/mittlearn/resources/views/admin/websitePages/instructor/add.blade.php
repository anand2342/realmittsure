@extends('admin.layouts.master')

@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'Edit';
        }
    @endphp
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Instructor</h1>
                <nav>
                    <ol class="breadcrumb"></ol>
                </nav>
            </div>
            <section class="section">
                <div class="row">
                    <div class="text-end mb-2">
                        <a href="{{ route('home.instructor.index') }}" class="btn btn-primary"><i
                                class="ri-arrow-left-line"></i></a>
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">{{ $heading }} Instructor Content</h4>
                                <hr class="form-divider">

                                @if ($flag == 1)
                                    {{ Form::model($data, ['url' => route('home.instructor.page-content.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                    {{ Form::hidden('id', null) }}
                                @else
                                    {{ Form::open(['url' => route('home.instructor.page-content.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                @endif
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('name', 'Name', ['class' => 'form-label required']) !!}
                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('category', 'Category', ['class' => 'form-label required']) !!}
                                    {!! Form::text('category', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('instructor_description', 'Instructor Description', ['class' => 'form-label required']) !!}
                                    {!! Form::text('instructor_description', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('facebook', 'Facebook Link', ['class' => 'form-label']) !!}
                                    {!! Form::text('facebook', null, ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('linkedin', 'LinkedIn Link', ['class' => 'form-label']) !!}
                                    {!! Form::text('linkedin', null, ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('twitter', 'Twitter Link', ['class' => 'form-label']) !!}
                                    {!! Form::text('twitter', null, ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="col-md-4 col-sm-3 col-xs-12">
                                    {!! Form::label('profile_image', 'Profile Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('profile_image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                    @if ($flag == 1)
                                        <img src="{{ Storage::url('uploads/instructor-profile/' . $data->profile_image) }}"
                                            alt="Profile Image" width="200" height="100">
                                    @endif
                                </div>
                                <div class="text-end">
                                    {!! Form::submit($flag == 1 ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
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
