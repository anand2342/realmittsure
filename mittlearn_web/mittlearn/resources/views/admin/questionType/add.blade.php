@extends('admin.layouts.master')
@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Question Type</h1>
            {{-- <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item" >Home</li>
          <li class="breadcrumb-item active">Users</li>
        </ol>
      </nav> --}}
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('question-type.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('question-type.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            <h5 class="card-title pb-0">Question Type Info</h5>
                            <hr class="form-divider">


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('name', ' Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'id' => 'vallidateName',
                                    'placeholder' => 'Enter name',
                                    'required',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('is_online', ' Test Type', ['class' => 'form-label required']) !!}
                                {!! Form::select('is_online', config('constants.TEST_TYPE'), null, [
                                    'class' => 'form-control',
                                    'id' => 'vallidateName',
                                    'placeholder' => '--select--',
                                    'required',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                                {!! Form::select('is_active', config('constants.STATUS_LIST'), 1, [
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
    </div>
@endsection
