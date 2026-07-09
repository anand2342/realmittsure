@extends('admin.layouts.master')
@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($holiday) && !empty($holiday)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Holiday</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Holidays</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            @if ($flag == 1)
                                {{ Form::model($holiday, ['url' => route('save.holiday'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('save.holiday'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                            @endif
                            <h5 class="card-title pb-0">Holiday Info</h5>
                            <hr class="form-divider">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('holiday_name', 'Holiday Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('holiday_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Holiday name','id' => 'vallidateName','required']) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1" style="display:none;"></small>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('from_date', 'From Date', ['class' => 'form-label required']) !!}
                                {!! Form::date('from_date', null, ['class' => 'form-control', 'id' => 'from_date','required']) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('to_date', 'To Date', ['class' => 'form-label required']) !!}
                                {!! Form::date('to_date', null, ['class' => 'form-control', 'id' => 'to_date','required']) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('day', 'Day', ['class' => 'form-label required']) !!}
                                {!! Form::text('day', null, ['class' => 'form-control', 'placeholder' => 'Days', 'id' => 'day', 'readonly']) !!}
                            </div>
                            {!! Form::hidden('country', 'India') !!}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('state_id', 'Select State', ['class' => 'form-label required']) !!}
                                {!! Form::select('state_id[]', $state, $selectedStates, [
                                    'class' => 'js-select2 form-select',
                                    'multiple' => 'multiple',
                                    'id' => 'state-select',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                                {!! Form::select('is_active', config('constants.STATUS_LIST'), 1, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required'
                                ]) !!}
                            </div>
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 30px !important;
            height: 40px !important;
        }
    </style>
    <script>
        var globalVar = {
            page: 'holiday',
        };
    </script>
@endsection
