@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Update Fee Header</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Fee Header</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            {{ Form::model($data, ['url' => route('save.fee.header'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id',$data->id) }}
                            <h5 class="card-title pb-0">Fee Header Info</h5>
                            <hr class="form-divider">

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('fee_name', 'Fees Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('fee_name', null, [
                                    'class' => 'form-control',
                                    'id' => 'vallidateName',
                                    'placeholder' => 'Enter Fees name',
                                    'required',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('fees_type', 'Fees type', ['class' => 'form-label required']) !!}
                                {!! Form::select('fees_type', config('constants.FEES_TYPE'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('fees_cycle', 'Fees Cycle', ['class' => 'form-label required']) !!}
                                {!! Form::select('fees_cycle', config('constants.FEES_DURATION_TYPES'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-sm-12 text-end">
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
    @endsection
