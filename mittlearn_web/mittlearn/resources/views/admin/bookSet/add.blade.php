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
            <h1>{{ $heading }} BookSet</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">BookSets</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('bookset.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('bookset.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                            @endif
                            <h5 class="card-title pb-0">BookSet Info</h5>
                            <hr class="form-divider">

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('name', 'Book Set Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'id' => 'vallidateName',
                                    'placeholder' => 'Enter book set name',
                                    'required',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('sku_code', 'Book Set SKU Code', ['class' => 'form-label required']) !!}
                                {!! Form::text('sku_code', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter book set SKU code',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('board_id', 'Select Board', ['class' => 'form-label']) !!}
                                    {!! Form::select('board_id', $board, null, [
                                        'class' => 'form-select',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('medium_id', 'Select Medium', ['class' => 'form-label']) !!}
                                    {!! Form::select('medium_id', $medium, null, [
                                        'class' => 'form-select',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('series_id', 'Select Series', ['class' => 'form-label']) !!}
                                    {!! Form::select('series_id', $bookSeries, null, [
                                        'class' => 'form-select', 'id'=>'series_id',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('class_id', 'Select Class', ['class' => 'form-label required']) !!}
                                    {!! Form::select('class_id', [], null, [
                                        'class' => 'form-select',
                                        'id' => 'class_id',
                                        'placeholder' => '--Select Class--',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('subject_id', 'Select Subject', ['class' => 'form-label required']) !!}
                                    {!! Form::select('subject_id[]', [], null, [
                                        'class' => 'js-select2 form-select',
                                        'id' => 'subject_id',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label required']) !!}
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
    </div>
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 30px !important;
            height: 40px !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "--Select--",
                allowClear: false,
                tags: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#series_id').change(function() {

                let seriesId = $(this).val();

                $('#class_id').html(
                    '<option value="">Loading...</option>'
                );

                $('#subject_id').empty().trigger('change');

                if (seriesId) {

                    $.get(
                        '/courses/get-classes/' + seriesId,
                        function(res) {

                            let options =
                                '<option value="">--Select Class--</option>';

                            $.each(res.classes, function(id, name) {
                                options +=
                                    '<option value="' + id + '">' + name + '</option>';
                            });

                            $('#class_id').html(options);

                        }
                    );

                }

            });


            $('#class_id').change(function() {

                let classId = $(this).val();
                let seriesId = $('#series_id').val();

                $('#subject_id').empty();

                if (classId && seriesId) {

                    $.get(
                        '/courses/get-subjects/' +
                        seriesId + '/' + classId,

                        function(res) {

                            $('#subject_id').empty();

                            $.each(res.subjects, function(id, name) {

                                let option =
                                    new Option(name, id, false, false);

                                $('#subject_id').append(option);

                            });

                            $('#subject_id').trigger('change');

                        });

                }

            });

        });
    </script>
@endsection
