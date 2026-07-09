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
            <h1>{{ $heading }} User Manual</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">User Manual</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">User Manual Info</h5>
                            <hr class="form-divider">

                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('user-manual.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('user-manual.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            {{-- @dd($data) --}}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('title', 'Manual Title', ['class' => 'form-label required']) !!}
                                {!! Form::text('title', null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter Title',
                                    'required',
                                ]) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('description', 'Manual Description', ['class' => 'form-label required']) !!}
                                {!! Form::text('description', null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter description',
                                    'required',
                                ]) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('pdf_path', 'Manual PDF', ['class' => 'form-label required']) !!}
                                <small>(Allowed format: PDF only)</small>

                                {!! Form::file('pdf_path', ['class' => 'form-control', 'accept' => 'application/pdf']) !!}

                                @if ($flag === 1 && isset($data->pdf_path))
                                    <div class="mt-2">
                                        <a href="{{ Storage::url('uploads/user_manuals/' . $data->pdf_path) }}"
                                            target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-file-pdf-o"></i> View PDF
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('video_path', 'Manual Video', ['class' => 'form-label']) !!}
                                <small>(Allowed format: Videos only)</small>

                                {!! Form::file('video_path', ['class' => 'form-control', 'accept' => 'application/pdf']) !!}

                                @if ($flag === 1 && isset($data->video_path))
                                    <div class="mt-2">
                                        <a href="{{ Storage::url('uploads/user_manuals/' . $data->video_path) }}"
                                            target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-file-video-o"></i> View Video
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('visible_to_roles', 'Manual For', ['class' => 'form-label required']) !!}
                                {!! Form::select('visible_to_roles[]', $roles, explode(',', $data['visible_to_roles'] ?? ''), [
                                    'class' => 'js-select2 form-select',
                                    'multiple' => 'multiple',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                                {!! Form::select('is_active', config('constants.STATUS_LIST'), null, [
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
        <script>
            $(document).ready(function() {
                $(".js-select2").select2({
                    closeOnSelect: false,
                    placeholder: "Select",
                    allowClear: false,
                    tags: true
                });

            });
        </script>
    @endsection
