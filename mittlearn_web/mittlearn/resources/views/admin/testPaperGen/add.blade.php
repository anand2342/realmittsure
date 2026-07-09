@extends('admin.layouts.master')
@section('content')
    <div>
        @php
            $flag = 0;
            $heading = 'Add';
            if (isset($data) && !empty($data)) {
                $flag = 1;
                $heading = 'Edit';
            }
        @endphp
        <div class="pagetitle">
            <h1>{{ $heading }} Test Paper</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Test Papers</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('test-paper.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('test-paper.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            <h5 class="card-title pb-0">Test-paper Info</h5>
                            <hr class="form-divider">


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('board_id', 'Board', ['class' => 'form-label required ']) !!}
                                {!! Form::select('board_id', $boards, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                ]) !!}
                                @error('board_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('medium_id', 'Medium', ['class' => 'form-label required ']) !!}
                                {!! Form::select('medium_id', $mediums, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                ]) !!}
                                @error('medium_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('series_id', 'Series', ['class' => 'form-label required ']) !!}
                                {!! Form::select('series_id', $series, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                ]) !!}
                                @error('series_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('class_id', 'Class', ['class' => 'form-label required ']) !!}
                                {!! Form::select('class_id', $class, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                ]) !!}
                                @error('class_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('subject_id', 'Subject', ['class' => 'form-label required ']) !!}
                                {!! Form::select('subject_id', $subject, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                ]) !!}
                                @error('subject_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('chapter_ids', 'Chapter Title', ['class' => 'form-label required']) !!}
                                {!! Form::select('chapter_ids[]', $selectedChapters ?? [], $chapters ?? [], [
                                    'class' => 'form-control form-select fs-8 js-select2',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'chapter_ids',
                                    'id' => 'chapter_select',
                                    'multiple' => 'multiple',
                                ]) !!}
                                @error('chapter_ids')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('title', 'Test Title', ['class' => 'form-label required ']) !!}
                                {!! Form::text('title', null, [
                                    'class' => 'form-control  ',
                                    'placeholder' => 'Enter title',
                                ]) !!}
                                @error('title')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('description', 'Test Description', ['class' => 'form-label required ']) !!}
                                {!! Form::text('description', null, [
                                    'class' => 'form-control  ',
                                    'placeholder' => 'Enter description',
                                ]) !!}
                                @error('description')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('duration', 'Duration (Time in minutes)', ['class' => 'form-label required']) !!}
                                {!! Form::text('duration', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Duration in minutes',
                                ]) !!}
                                @error('duration')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('min_passing_percentage', 'Min Passing percentage', ['class' => 'form-label required ']) !!}
                                {!! Form::text('min_passing_percentage', null, [
                                    'class' => 'form-control  ',
                                    'placeholder' => 'Enter Minimum Passing Percentage (Do not use the % symbol)',
                                ]) !!}
                                @error('min_passing_percentage')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('question_order_type', 'Question Order Type', ['class' => 'form-label required ']) !!}
                                {!! Form::select('question_order_type', config('constants.QUESTION_ORDER_TYPE'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    // 'required',
                                ]) !!}
                                @error('question_order_type')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                                {!! Form::select('is_active', config('constants.STATUS_LIST'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    // 'required',
                                ]) !!}
                                @error('is_active')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
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
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(".js-select2").select2({
                    closeOnSelect: false,
                    placeholder: "Select",
                    allowClear: false,
                    tags: true
                });
                $(document).on('change', '#series_id', function() {
                    var seriesId = $('#series_id').val();
                    if (seriesId) {
                        $.ajax({
                            url: '{{ route('get.classes.by.series') }}',
                            type: 'GET',
                            data: {
                                series_id: seriesId,
                            },
                            success: function(response) {
                                $('#class_id').empty().append(
                                    '<option value="">--Select Class--</option>');

                                $.each(response, function(classId, className) {
                                    $('#class_id').append('<option value="' + classId +
                                        '">' + className + '</option>');
                                });

                                $('#class_id').trigger(
                                    'change');
                            },
                            error: function(xhr, status, error) {
                                console.log('Error fetching classes:', error);
                            }
                        });
                    }
                });

                $(document).on('change', '#class_id', function() {
                    var classId = $('#class_id').val();
                    var seriesId = $('#series_id').val();

                    if (classId && seriesId) {
                        $.ajax({
                            url: '{{ route('get.subjects.by.class') }}',
                            type: 'GET',
                            data: {
                                class_id: classId,
                                series_id: seriesId,
                            },
                            success: function(response) {
                                $('#subject_id').empty().append(
                                    '<option value="">--Select Subject--</option>');

                                $.each(response, function(subjectId, subjectName) {
                                    $('#subject_id').append('<option value="' + subjectId +
                                        '">' + subjectName + '</option>');
                                });
                                $('#subject_id').trigger(
                                    'change');
                            },
                            error: function(xhr, status, error) {
                                console.log('Error fetching subjects:', error);
                            }
                        });
                    }
                });

                $(document).on('change', '#board_id, #medium_id, #series_id, #class_id, #subject_id', function() {
                    var boardId = $('#board_id').val();
                    var mediumId = $('#medium_id').val();

                    $(document).on('change', '#board_id, #medium_id', function() {
                        var boardId = $('#board_id').val();
                        var mediumId = $('#medium_id').val();

                        if (boardId && mediumId) {
                            $.ajax({
                                url: '{{ route('test-paper.get.book.series') }}',
                                type: 'GET',
                                data: {
                                    board_id: boardId,
                                    medium_id: mediumId
                                },
                                success: function(response) {
                                    $('#series_id').empty().append(
                                        '<option value="">--Select Series--</option>');

                                    $.each(response, function(seriesId, seriesName) {
                                        $('#series_id').append('<option value="' +
                                            seriesId + '">' + seriesName +
                                            '</option>');
                                    });
                                    $('#series_id').trigger('change');
                                },
                                error: function(xhr, status, error) {
                                    console.log('Error fetching series:', error);
                                }
                            });
                        }
                    });
                    var seriesId = $('#series_id').val();
                    var classId = $('#class_id').val();
                    var subjectId = $('#subject_id').val();

                    if (boardId && mediumId && seriesId && classId && subjectId) {
                        $.ajax({
                            url: '{{ route('planner.get.chapters') }}',
                            type: 'GET',
                            data: {
                                board_id: boardId,
                                medium_id: mediumId,
                                series_id: seriesId,
                                class_id: classId,
                                subject_id: subjectId,
                            },
                            success: function(response) {
                                $('#chapter_select').empty();
                                $('#chapter_select').append('<option value="">--Select--</option>');

                                $.each(response, function(chapterId, chapterTitle) {
                                    $('#chapter_select').append(
                                        '<option value="' + chapterId + '">' +
                                        chapterTitle + '</option>'
                                    );
                                });

                                // Reinitialize Select2 after updating options
                                $('#chapter_select').trigger('change');
                            },
                            error: function(xhr, status, error) {},
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
