@extends('schoolPortal.layouts.master')

@section('content')
    {{--  @include('admin.layouts.flash-messages')  --}}

    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($studentData) && !empty($studentData)) {
            $flag = 1;
            $heading = 'Edit';
        }
    @endphp

    <!-- Card for Teacher Form -->
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">{{ $heading }} Student</h5>
                    <p>Easily add, edit, and bulk upload Student information to keep your records accurate and up to date.
                    </p>


                </div>
            </div>
            @if ($flag != 1)
                <div class="col-md-6 mb-3">
                    <h6 class="">Bulk Upload Student</h6>
                    {{--  <hr class="form-divider w-100 border-top border-secondary opacity-75 my-3">  --}}
                    <div class="col-md-12">
                        @livewire('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_student'])
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- Card for Add Teacher Form -->
    <div class="cardBox teacherMain py-md-4 mb-3">
        <div class="formPanel">
            <h5 class="mb-3">{{ $heading }} Student</h5>

            <!-- Form Start -->
            {{ Form::open(['url' => route('sp.student.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
            {{ Form::hidden('role', 'school_student') }}
            {{ Form::hidden('id', $studentData->id ?? null, ['id' => 'student_id_field']) }}

            <div class="formPanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('admission_no', 'Admission No.') !!}
                            {!! Form::text('admission_no', old('admission_no', $studentData->userAdditionalDetail->admission_no ?? null), [
                                'class' => 'form-control qualification ' . ($errors->has('admission_no') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('admission_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            {!! Form::label('admission_date', 'Admission Date') !!}
                            {!! Form::date('admission_date', $studentData->studentDetails->doj ?? null, [
                                'class' => 'form-control  dateInput' . ($errors->has('admission_date') ? 'is-invalid' : ''),
                            ]) !!}
                            @error('admission_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('name', 'Name') !!} <b>*</b>
                            {!! Form::text('name', old('name', $studentData->name ?? null), [
                                'class' => 'form-control ' . ($errors->has('name') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('parent_name', 'Parent Name') !!}
                            {!! Form::text('parent_name', old('parent_name', $studentData->studentDetails->parent_name ?? null), [
                                'class' => 'form-control ' . ($errors->has('parent_name') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('parent_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::text('email', old('email', $studentData->email ?? null), [
                                'class' => 'form-control ' . ($errors->has('email') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            {!! Form::label('dob', 'DOB') !!}
                            {!! Form::date('dob', old('dob', $studentData->studentDetails->dob ?? null), [
                                'class' => 'form-control dateInput ' . ($errors->has('dob') ? 'is-invalid' : ''),
                                'id' => 'date-input',
                                'placeholder' => 'Select date',
                            ]) !!}
                            @error('dob')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    @if (getUserRoles() == 'school_teacher')
                        <div class="col-md-4">
                            <div class="form-group bginput mb-3">
                                {!! Form::label('class', 'Select Class') !!} <b>*</b>
                                {!! Form::select('class', $teacherClasses, old('class', $studentData->studentDetails->class ?? null), [
                                    'class' => 'form-select ' . ($errors->has('class') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ]) !!}
                                @error('class')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="col-md-4">
                            <div class="form-group bginput mb-3">
                                {!! Form::label('class', 'Select Class') !!} <b>*</b>
                                {!! Form::select('class', $classes, old('class', $studentData->studentDetails->class ?? null), [
                                    'class' => 'form-select ' . ($errors->has('class') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ]) !!}
                                @error('class')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('section', 'Select Section') !!}
                            {!! Form::select('section', $sections, old('section', $studentData->studentDetails->section ?? null), [
                                'class' => 'form-select ' . ($errors->has('section') ? 'is-invalid' : ''),
                                'placeholder' => 'Select',
                            ]) !!}
                            @error('section')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('parent_mobile_no', 'Parent/Guardian Mobile No.') !!} <b>*</b>
                            {!! Form::text('parent_mobile_no', old('parent_mobile_no', $studentData->mobile_no ?? null), [
                                'class' => 'form-control mobile ' . ($errors->has('parent_mobile_no') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('parent_mobile_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer">
                <div class="d-flex align-items-center justify-content-end gap-4">
                    <a href="{{ url()->previous() }}" class="btn backbtn">Back</a>
                    <button type="Submit" class="btn btn-primary-gradient rounded-1">Submit</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#edit-teacher-btn').on('click', function() {
                var teacherId = $(this).data('id');
                var stateId = $(this).data('state');
                var cityId = $(this).data('city');

                $('#state-select').val(stateId);
                loadCities(stateId, cityId); // Pass cityId properly
            });

            $('#state-select').on('change', function() {
                var stateId = $(this).val();
                if (stateId) {
                    loadCities(stateId, null); // No pre-selected city on state change
                } else {
                    $('#city-select').html('<option value="">Select</option>');
                }
            });

            function loadCities(stateId, preSelectedCity) {
                if (!stateId) {
                    $('#city-select').html('<option value="">Select</option>');
                    return;
                }

                var url = "{{ route('sp.getCities', ':state') }}".replace(':state', stateId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#city-select').html('<option value="">Select</option>');

                        if (data && Object.keys(data).length > 0) {
                            $.each(data, function(id, name) {
                                var isSelected = (parseInt(id) === parseInt(preSelectedCity)) ?
                                    'selected' : '';
                                $('#city-select').append('<option value="' + id + '" ' +
                                    isSelected + '>' + name + '</option>');
                            });

                            // After appending, if preSelectedCity exists but not matched exactly by ID comparison,
                            // force set the selected value
                            if (preSelectedCity) {
                                $('#city-select').val(preSelectedCity);
                            }
                        } else {
                            $('#city-select').html('<option value="">No cities available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error loading cities:", error);
                    }
                });
            }

            // Automatically load cities if state already selected on page load
            var initialStateId = $('#state-select').val();
            var initialCityId = "{{ old('city', $teacherData->userAdditionalDetail->city ?? null) }}";

            if (initialStateId) {
                loadCities(initialStateId, initialCityId);
            }
        });
    </script>
@endsection
