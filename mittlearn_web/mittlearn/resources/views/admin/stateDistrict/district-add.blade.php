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
            <h1>{{ $heading }} City / District </h1>
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
                                {{ Form::model($data, ['url' => route('district.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('district.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            <h5 class="card-title pb-0">City / District Info</h5>
                            <hr class="form-divider">

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('state_id', 'Select State', ['class' => 'form-label required']) !!}
                                {!! Form::select('state_id', $states, $stateName->id ?? null, [
                                    'class' => 'form-control form-select fs-8',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('city', 'City  Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('city', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter city name',
                                    'required',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
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
