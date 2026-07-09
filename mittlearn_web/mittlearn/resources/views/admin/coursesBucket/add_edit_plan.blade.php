@extends('admin.layouts.master')
@section('content')
    @php
        $isEditMode = 0;
        $heading = 'Add';
        if (isset($data_row) && !empty($data_row)) {
            $isEditMode = 1;
            $heading = 'Update';
        }
    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Courses Bucket</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            @if ($isEditMode == 1)
                                {{ Form::model($data_row, ['url' => route('course-bucket.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('course-bucket.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                            @endif
                            <h5 class="card-title pb-0">Courses Bucket Info</h5>
                            <hr class="form-divider">

                            <!-- Plan Fields -->
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                {!! Form::label('series', 'Book Series', ['class' => 'form-label']) !!}
                                {{ Form::select('series', $series, null, ['class' => 'form-select']) }}
                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                {!! Form::label('class', 'Classes', ['class' => 'form-label']) !!}
                                {{ Form::select('class', ['all' => 'All'], null, ['class' => 'form-select', 'placeholder' => '--select--']) }}
                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                {!! Form::label('subject', 'Subject', ['class' => 'form-label']) !!}
                                {{ Form::select('subject', ['all' => 'All'], null, ['class' => 'form-select', 'placeholder' => '--select--']) }}
                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                {!! Form::label('discount_type', 'Discount Type', ['class' => 'form-label']) !!}
                                {{ Form::select('discount_type', config('constants.DISCOUNT_TYPES'), null, ['class' => 'form-select', 'placeholder' => '--select--']) }}
                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                {!! Form::label('discount_value', 'Discount Value', ['class' => 'form-label']) !!}
                                {!! Form::number('discount_value', null, ['class' => 'form-control', 'placeholder' => 'Enter Discount Value']) !!}
                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label']) !!}
                                {{ Form::select('is_active', config('constants.STATUS_LIST'), null, ['class' => 'form-select']) }}
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
