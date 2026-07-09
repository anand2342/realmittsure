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
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"> {{ $heading }} Permission</h5>
                        <hr class="form-divider">

                        @if ($flag == 1)
                            {{ Form::model($data, ['url' => route('permissions.new.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}
                        @else
                            {{ Form::open(['url' => route('permissions.new.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                        @endif

                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('category', 'Category', ['class' => 'form-label required ']) !!}
                            {!! Form::select('category', $data, null, ['class' => 'form-control form-select fs-8 ', 'placeholder' => '--Select--']) !!}
                        </div>

                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('title', 'Title', ['class' => 'form-label required']) !!}
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title']) !!}
                        </div>

                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('slug', 'Route Name', ['class' => 'form-label required']) !!}
                            {!! Form::text('slug', null, ['class' => 'form-control', 'placeholder' => 'Enter Route Name']) !!}
                        </div>

                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('permission_type', 'Permission Type', ['class' => 'form-label required']) !!}
                            {!! Form::select('permission_type', config('constants.PERMISSION_TYPES'),null, ['class' => 'form-control form-select fs-8 ', 'placeholder' => '--Select--']) !!}
                        </div>
{{-- 
                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('accessable_for', 'Accessability', ['class' => 'form-label required']) !!}
                            {!! Form::text('accessable_for', null, ['class' => 'form-control', 'placeholder' => 'Accessability']) !!}
                        </div> --}}

                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('accessable_for', 'Accessability', ['class' => 'form-label required ']) !!}
                            {!! Form::select('accessable_for', config('constants.PERMISSION_FOR_LIST'),null, ['class' => 'form-control form-select fs-8 ', 'placeholder' => '--Select--']) !!}
                        </div>

                        <div class="col-md-4 col-sm-3 col-xs-12">
                            {!! Form::label('description', 'Description', ['class' => 'form-label ']) !!}
                            {!! Form::text('description', null, ['class' => 'form-control', 'placeholder' => 'Enter Description']) !!}
                        </div>

                        <div class="modal-footer">
                            <div class="text-right" >
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>

            </div>
        </div>
    </section>
    
@endsection
