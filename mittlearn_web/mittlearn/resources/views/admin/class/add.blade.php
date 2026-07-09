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
            <h1>{{ $heading }} Class</h1>
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

               @if($flag==1)
                  {{ Form::model($data,array('url'=>route('class.save'),'id'=>"edit-plan-form", 'class'=>"row g-3")) }}
                  {{Form::hidden('id',null)}}
              @else
                  {{ Form::open(array('url'=>route('class.save'),'id'=>"add-plan-form", 'class'=>"row g-3")) }}
              @endif
                <h5 class="card-title pb-0">Class Info</h5>
                <hr class="form-divider">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'Class Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('name',null, ['class' => 'form-control required','id' => 'vallidateName','placeholder' => 'Enter class name','required' ]) !!}
                    <small id="vallidateNameError" class="form-text text-danger mt-1" style="display:none;"></small>
                  </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                    {!! Form::select('is_active', config('constants.STATUS_LIST'), null, ['class' => 'form-control form-select fs-8 ', 'placeholder' => '--Select--','required']) !!}
                </div>
            </div>
          </div>


          <div class="card">
            <div class="card-body">
                <h5 class="card-title pb-0">Class Settings</h5>
                <hr class="form-divider">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('landing_ui', 'Landing Environment (design)', ['class' => 'form-label required']) !!}
                    {!! Form::select('landing_ui', config('constants.LANDING_UI'), null, ['class' => 'form-control form-select fs-8 ', 'placeholder' => '--Select--','required']) !!}
                </div>

                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
                
              {{Form::close()}}

            </div>
          </div>
        </div>

    </div>
@endsection
