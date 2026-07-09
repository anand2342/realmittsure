@extends('admin.layouts.master')
@section('content')
@php 
      $flag=0;
      $heading=('Add');
      if(isset($role) && !empty($role)){
          $flag=1;
          $heading=('Update');
      }
  @endphp

  <div>
    <div class="pagetitle">
      <h1>{{$heading}} Role</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item" >Home</li>
          <li class="breadcrumb-item active">Roles</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">

              @if($flag==1)
                  {{ Form::model($role,array('url'=>route('roles.store'),'id'=>"edit-plan-form", 'class'=>"row g-3")) }}
                  {{Form::hidden('id',null)}}
              @else
                  {{ Form::open(array('url'=>route('roles.store'),'id'=>"add-plan-form", 'class'=>"row g-3")) }}
              @endif
                <h5 class="card-title pb-0">Role Info</h5>
                <hr class="form-divider">

                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('role_name', 'Role Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('role_name',null, ['class' => 'form-control','placeholder' => 'Enter Role name' ]) !!}
                </div>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('description', 'Description', ['class' => 'form-label ']) !!}
                    {!! Form::textarea('description',null, ['class' => 'form-control','placeholder' => 'Enter Description', 'rows' => '1' ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                  {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                  {!! Form::select('is_active', config('constants.STATUS_LIST'), null, ['class' => 'form-control form-select fs-8 ', 'placeholder' => '--Select--']) !!}
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
    </section>
  </div>
@endsection

