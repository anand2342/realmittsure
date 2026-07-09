@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Planner Info</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Planner Info</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 d-flex justify-content-between align-items-center">
                                <div class="card-title">Planner Details</div>
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#ExtralargeModal">
                                    View & Edit
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive tbleDiv">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><b>S no.</b></th>
                                        <th><b>Lesson Planner Detail</b></th>
                                        <th><b>Action</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{--  @dd($stepTypesName)  --}}
                                    @foreach ($stepTypesName as $key => $step)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $step }}</td>
                                            <td>
                                                @livewire('edit-plannner-lessons', ['plannerDataID' => $plannerData->id, 'stepType' => isset($stepTypes[$key]) ? $stepTypes[$key] : null])
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="ExtralargeModal" tabindex="-1" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Planner Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ Form::model($plannerData, ['url' => route('planner.update'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                        {{ Form::hidden('id', null) }}
                        {{ Form::hidden('_method', 'PUT') }}
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('board_id', 'Board', ['class' => 'form-label required ']) !!}
                            {!! Form::select('board_id', $boards, $plannerData->board_id, [
                                'class' => 'form-control form-select fs-8 ',
                                'placeholder' => '--Select--',
                                'disabled' => 'disabled',
                            ]) !!}
                            @error('board_id')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('medium_id', 'Medium', ['class' => 'form-label required ']) !!}
                            {!! Form::select('medium_id', $mediums, $plannerData->medium_id, [
                                'class' => 'form-control form-select fs-8 ',
                                'placeholder' => '--Select--',
                                'disabled' => 'disabled',
                            ]) !!}
                            @error('medium_id')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('series_id', 'Series', ['class' => 'form-label required ']) !!}
                            {!! Form::select('series_id', $series, $plannerData->series_id, [
                                'class' => 'form-control form-select fs-8 ',
                                'placeholder' => '--Select--',
                                'disabled' => 'disabled',
                            ]) !!}
                            @error('series_id')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('class_id', 'Class', ['class' => 'form-label required ']) !!}
                            {!! Form::select('class_id', $class, $plannerData->class_id, [
                                'class' => 'form-control form-select fs-8 ',
                                'placeholder' => '--Select--',
                                'disabled' => 'disabled',
                            ]) !!}
                            @error('class_id')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('subject_id', 'Subject', ['class' => 'form-label required ']) !!}
                            {!! Form::select('subject_id', $subjects, $plannerData->subject_id, [
                                'class' => 'form-control form-select fs-8 ',
                                'placeholder' => '--Select--',
                                'disabled' => 'disabled',
                            ]) !!}
                            @error('subject_id')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            {!! Form::label('type', 'Planner Type', ['class' => 'form-label required ']) !!}
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        {!! Form::label('type', 'Daily', ['class' => 'form-check-label']) !!}
                                        {!! Form::radio('type', 'daily', false, [
                                            'class' => 'form-check-input',
                                            'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        {!! Form::label('type', 'Weekly', ['class' => 'form-check-label']) !!}
                                        {!! Form::radio('type', 'weekly', false, [
                                            'class' => 'form-check-input',
                                            'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        {!! Form::label('type', 'Monthly', ['class' => 'form-check-label']) !!}
                                        {!! Form::radio('type', 'monthly', false, [
                                            'class' => 'form-check-input',
                                            'disabled' => 'disabled',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($plannerData->type == 'daily')
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('chapter_id[]', 'Chapter Title', ['class' => 'form-label required']) !!}
                                {!! Form::select('chapter_id[]', $chapters, $selectedChapter, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                ]) !!}
                                @error('chapter_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        @else
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('chapter_id[]', 'Chapter Title', ['class' => 'form-label required']) !!}
                                {!! Form::select('chapter_id[]', $chapters, $selectedChapter, [
                                    'class' => 'form-control form-select fs-8 js-select2 ',
                                    'placeholder' => '--Select--',
                                    'multiple' => 'multiple',
                                ]) !!}
                                @error('chapter_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        @livewire('edit-planner-page', ['plannerData' => $plannerData])
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        });
    </script>
@endsection
