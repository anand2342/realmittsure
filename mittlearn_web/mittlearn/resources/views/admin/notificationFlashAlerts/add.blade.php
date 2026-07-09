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
            <h1>{{ $heading }} Alert Notifications</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Alert Notifications</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">Alert Notification Info</h5>
                            <hr class="form-divider">

                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('flash.notification.alerts.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('flash.notification.alerts.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            {{-- @dd($data) --}}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('image', 'Marketing Banner', ['class' => 'form-label required']) !!}
                                <small>
                                    (Allowed formats: PNG, JPG, JPEG, SVG , GIF and Videos. dimensions: 300x250 pixels)
                                </small>
                                {!! Form::file('image', [
                                    'class' => 'form-control',
                                ]) !!}

                                @if ($flag === 1 && isset($data->marketing_banner))
                                    @php
                                        $file = $data->marketing_banner;
                                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                                        $videoExtensions = [
                                            'mp4',
                                            'avi',
                                            'mov',
                                            'm4v',
                                            'm4p',
                                            'mpg',
                                            'mp2',
                                            'mpeg',
                                            'mpe',
                                            'mpv',
                                            'm2v',
                                            'wmv',
                                            'flv',
                                            'mkv',
                                            'webm',
                                            '3gp',
                                            '3gp',
                                            'm2ts',
                                            'ogv',
                                            'ts',
                                            'mxf',
                                            'ogg',
                                        ];
                                    @endphp

                                    <div class="mt-2">
                                        @if (in_array(strtolower($extension), $videoExtensions))
                                            <video width="200" height="100" autoplay loop muted playsinline
                                                class="img-thumbnail">
                                                <source src="{{ Storage::url('uploads/marketing_banner/' . $file) }}"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <img src="{{ Storage::url('uploads/marketing_banner/' . $file) }}"
                                                alt="Marketing Banner" class="img-thumbnail" width="200" height="100">
                                        @endif
                                    </div>
                                @endif

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('message', 'Alert Message', ['class' => 'form-label required']) !!}
                                {!! Form::text('message', $data->message ?? null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter Alert Message',
                                    'id' => 'vallidateName',
                                    'required',
                                    'maxlength' => '200',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('redirection_url', 'Redirection URL', ['class' => 'form-label ']) !!}
                                {!! Form::text('redirection_url', $data->redirection_url ?? null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter URL',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('role_visibility', 'Alert Visibility To', ['class' => 'form-label required']) !!}
                                {!! Form::select('role_visibility[]', $roles, explode(',', $data['role_visibility'] ?? ''), [
                                    'placeholder' => '--Select Roles--',
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
            document.addEventListener('DOMContentLoaded', function() {
                const messageInput = document.getElementById('vallidateName');
                const errorMessage = document.getElementById('vallidateNameError');
                const maxLength = 200;

                messageInput.addEventListener('input', function() {
                    const currentLength = messageInput.value.length;
                    if (currentLength > maxLength) {
                        errorMessage.textContent = 'You cannot write more than 200 characters.';
                        errorMessage.style.display = 'block';
                        messageInput.value = messageInput.value.substring(0,
                            maxLength);
                    } else {
                        errorMessage.style.display = 'none';
                    }
                });
            });

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
