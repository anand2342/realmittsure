@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>View Enquiry</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Enquiry</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="text-end mb-2">
                    <a href="{{ route('enquiries') }}" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            {{ Form::model($data, ['url' => route('subject.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}

                            <h5 class="card-title pb-0">Enquiry Info</h5>
                            <hr class="form-divider">


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('name', 'Name', ['class' => 'form-label ']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('email', 'Email', ['class' => 'form-label ']) !!}
                                {!! Form::text('email', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label ']) !!}
                                {!! Form::text('mobile_no', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('subject', 'Subject', ['class' => 'form-label ']) !!}
                                {!! Form::text('subject', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('created_at', 'Date', ['class' => 'form-label ']) !!}
                                {!! Form::text('created_at', \Carbon\Carbon::parse($data->created_at)->format('d/m/Y'), [
                                    'class' => 'form-control',
                                    'disabled' => 'disabled',
                                ]) !!}
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                                {!! Form::label('message', 'Message', ['class' => 'form-label ']) !!}
                                {!! Form::textarea('message', null, ['class' => 'form-control', 'rows' => '1', 'disabled' => 'disabled']) !!}
                            </div>

                            {{ Form::close() }}


                            {{ Form::model($data, ['url' => route('enquiry.save', $data->id), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}

                            <h5 class="card-title pb-0">Reply</h5>
                            <hr class="form-divider">

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('email', 'Email', ['class' => 'form-label ']) !!}
                                {!! Form::text('email', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('reply_subject', 'Reply Subject', ['class' => 'form-label ']) !!}
                                {!! Form::text('reply_subject', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                                {!! Form::label('reply_message', 'Reply Message', ['class' => 'form-label ']) !!}
                                {!! Form::textarea('reply_message', null, ['class' => 'form-control', 'rows' => '1']) !!}
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
        @endsection
