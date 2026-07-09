@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Edit Access Code</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Access Code</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">

                        {{ Form::model($data ?? null, ['url' => route('access.code.olympiad.save'), 'id' => 'edit-access-code-form', 'class' => 'row g-3']) }}
                        {{ Form::hidden('id', $accessCode->id) }}
                        <h5 class="card-title pb-0">Access Code</h5>
                        <hr class="form-divider">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('access_code', 'Access Code', ['class' => 'form-label']) !!}
                            {!! Form::text('access_code', old('access_code', $accessCode->access_code ?? ''), [
                                'class' => 'form-control readonly','readonly',
                                'required' => true,
                            ]) !!}
                            @error('access_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('class_id', 'Select class', ['class' => 'form-label required']) !!}
                            {!! Form::select('class_id', $classes, explode(',', $accessCode->class_id), [
                                'class' => 'form-select',
                            ]) !!}
                            @error('class_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('subject_id', 'Select Subject', ['class' => 'form-label required']) !!}
                            {!! Form::select('subject_id', $subjects, explode(',', $accessCode->subject_id), [
                                'class' => 'form-select',
                            ]) !!}
                            @error('subject_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                       
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('expiration_date', 'Video Content Access Validity', ['class' => 'form-label required']) !!}
                            {!! Form::date('expiration_date', old('expiration_date', $accessCode->expiration_date ?? ''), ['class' => 'form-control']) !!}
                            @error('expiration_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

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



        <script>
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "--Select--",
                allowClear: false,
                tags: true
            });
        </script>
    @endsection
