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
            <h1>{{ $heading }} Book Series</h1>
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
                                {{ Form::model($data, ['url' => route('book.series.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('book.series.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            <h5 class="card-title pb-0">Book Series Info</h5>
                            <hr class="form-divider">

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('board_id', 'Board Name', ['class' => 'form-label required ']) !!}
                                {!! Form::select('board_id', $boards, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('medium_id', 'Medium Name', ['class' => 'form-label required ']) !!}
                                {!! Form::select('medium_id', $mediums, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>
                            <div id="class-subject-container">
                                @if (!empty($classSubjects))
                                    @foreach ($classSubjects as $index => $classSubject)
                                        <div class="row class-subject-row">
                                            <div class="col-md-6">
                                                {!! Form::label("class[$index]", 'Assign Class', ['class' => 'form-label required']) !!}
                                                <select name="class[{{ $index }}]" class="form-control class-select">
                                                    <option value="">--Select Class--</option>
                                                    @foreach ($classes as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ $id == $classSubject['class_id'] ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-5">
                                                {!! Form::label("subject[$index][]", 'Assign Subjects', ['class' => 'form-label required']) !!}
                                                <select name="subject[{{ $index }}][]"
                                                    class="js-select2 form-select subject-select" multiple="multiple">
                                                    @foreach ($subjects as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ in_array($id, $classSubject['subject_ids']) ? 'selected' : '' }}>
                                                            {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-1 d-flex align-items-end">
                                                @if ($index > 0)
                                                    <button type="button"
                                                        class="btn btn-danger remove-class-row"><i class="fa fa-trash"></i></button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row class-subject-row">
                                        <div class="col-md-6">
                                            {!! Form::label('class[0]', 'Assign Class', ['class' => 'form-label required']) !!}
                                            <select name="class[0]" class="form-control class-select">
                                                <option value="">--Select Class--</option>
                                                @foreach ($classes as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            {!! Form::label('subject[0][]', 'Assign Subjects', ['class' => 'form-label required']) !!}
                                            <select name="subject[0][]" class="js-select2 form-select subject-select"
                                                multiple="multiple">
                                                @foreach ($subjects as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 d-flex align-items-end">
                                            <!-- No remove button for the first row -->
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Button to add more rows -->
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-success" id="add-class-row">Add More Classes</button>
                            </div>

                            {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('class', 'Assign Class', ['class' => 'form-label required']) !!}
                                    <select name="class[]" class="form-control class-select">
                                        <option value="">--Select Class--</option>
                                        @foreach ($classes as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ in_array($id, $selectedClasses ?? []) ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group bginput mb-3">
                                    {!! Form::label('subject', 'Assign Subjects', ['class' => 'form-label required']) !!}
                                    <select name="subject[0][]" class="js-select2 form-select subject-select"
                                        multiple="multiple">
                                        @foreach ($subjects as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ in_array($id, $selectedSubjects ?? []) ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="additional-classes"></div>
                            <div class="col-md-12 col-sm-12 col-xs-12 text-end">
                                <button type="button" id="add-class-row" class="btn btn-success">Add More Classes</button>
                            </div> --}}

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('name', 'Book Series Name', ['class' => 'form-label required']) !!}
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
                                {!! Form::label('short_code', 'Short Code', ['class' => 'form-label required']) !!}
                                {!! Form::text('short_code', null, ['class' => 'form-control', 'placeholder' => 'Enter Short Code', 'required']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('image', 'Branding Animation', ['class' => 'form-label required']) !!}
                                <small class="form-text text-muted">(Allowed formats: JSON, GIF. Dimensions: 280x300
                                    px.)</small>
                                {!! Form::file('image', [
                                    'class' => 'form-control',
                                    $flag === 0 ? 'required' : '',
                                    'accept' => 'image/gif,application/json',
                                ]) !!}

                                @if ($flag === 1 && isset($data->image))
                                    <img src="{{ Storage::url('uploads/book-series/' . $data->image) }}" alt="Image"
                                        width="200" height="100">
                                @endif
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
            <script>
                $(document).ready(function() {
                    // Initialize Select2 with custom checkboxes
                    document.addEventListener('DOMContentLoaded', function() {
                        const multiSelect = document.getElementById('multiSelect');
                    });

                });

                function initSelect2() {
                    $(".js-select2").select2({
                        closeOnSelect: false,
                        placeholder: "Select",
                        allowClear: false,
                        tags: true
                    });
                }
                document.addEventListener("DOMContentLoaded", function() {
                    initSelect2();
                });
            </script>

            <script>
                $(document).ready(function() {
                    let classIndex = {{ count($classSubjects ?? []) }} || 1; // Ensure classIndex starts at 1 if empty

                    // Add a new row
                    $("#add-class-row").click(function() {
                        let newRow = `
                                    <div class="row class-subject-row">
                                        <div class="col-md-6">
                                            <label class="form-label required">Assign Class</label>
                                            <select name="class[${classIndex}]" class="form-control class-select">
                                                <option value="">--Select Class--</option>
                                                @foreach ($classes as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-5">
                                            <label class="form-label required">Assign Subjects</label>
                                            <select name="subject[${classIndex}][]" class="js-select2 form-select subject-select" multiple="multiple">
                                                @foreach ($subjects as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-class-row"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>`;

                        $("#class-subject-container").append(newRow);
                        $(".js-select2").select2(); // Reinitialize Select2 for new elements
                        classIndex++;
                    });

                    // Remove a row (but not the first one)
                    $(document).on("click", ".remove-class-row", function() {
                        if ($(".class-subject-row").length > 1) {
                            $(this).closest(".class-subject-row").remove();
                        }
                    });
                });
            </script>
        @endsection
