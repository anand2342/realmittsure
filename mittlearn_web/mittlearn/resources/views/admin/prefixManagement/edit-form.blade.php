@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="pagetitle">
                    <h1> Prefix Details</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active"> Prefix Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Role</h5>
                        <h5 class="card-title pb-0">Prefix Details</h5>
                        <hr class="form-divider">
                        {{ Form::model($prefix, ['url' => route('prefix.update'), 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
                        {{ Form::hidden('id', $prefix->id) }}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('prefix', 'Prefix', ['class' => 'form-label required ']) !!}
                            {!! Form::text('prefix', $prefix->prefix, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Prefix',
                            ]) !!}
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                            {!! Form::text('description', $prefix->description, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Prefix',
                            ]) !!}
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('is_active', 'Status', ['class' => 'form-label required']) !!}
                            {!! Form::select('is_active', config('constants.STATUS_LIST'), null, [
                                'class' => 'form-control form-select fs-8 ',
                                'placeholder' => '--Select--',
                            ]) !!}
                        </div>

                        <div class="text-end">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                            {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    </section>
    <script>
        $(".js-select2").select2({
            closeOnSelect: false,
            placeholder: "Select",
            allowClear: false,
            tags: true
        });
    </script>
@endsection
