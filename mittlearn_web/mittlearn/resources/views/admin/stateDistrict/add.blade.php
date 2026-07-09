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
            <h1>{{ $heading }} State </h1>
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
                                {{ Form::model($data, ['url' => route('state.district.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('state.district.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            <h5 class="card-title pb-0">State Info</h5>
                            <hr class="form-divider">

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {!! Form::label('name', 'State  Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'id' => 'vallidateName',
                                    'placeholder' => 'Enter name',
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
