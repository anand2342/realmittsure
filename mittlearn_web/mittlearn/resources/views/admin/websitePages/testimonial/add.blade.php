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
    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Testimonial</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Testimonial</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="text-end mb-2">
                <a href="{{ route('testimonial.index') }}" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ $heading }} Testimonial Content</h4>
                        <hr class="form-divider">

                        @if ($flag == 1)
                            {{ Form::model($data, ['url' => route('testimonial.page-content.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route('testimonial.page-content.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                        @endif


                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('name', ' Name ', ['class' => 'form-label required']) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name']) !!}
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('image', ' Image ', ['class' => 'form-label']) !!}
                            <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions: 50x50 pixels)</small>
                            {!! Form::file('image', ['class' => 'form-control']) !!}
                            @if ($flag == 1)
                                <img src="{{ Storage::url('uploads/testimonial-profile/' . $data->image) }}"
                                    alt="Profile Image" width="200" height="100">
                            @endif
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('designation', 'Desgination', ['class' => 'form-label required']) !!}
                            {!! Form::text('designation', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Desgination',
                            ]) !!}
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('comment', 'Comment', ['class' => 'form-label required']) !!}
                            {!! Form::textarea('comment', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Comment',
                                'rows' => '1',
                            ]) !!}
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
