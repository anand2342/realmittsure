@extends('schoolPortal.layouts.master')

@section('content')
    {{--  @include('admin.layouts.flash-messages')  --}}

    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($teacherData) && !empty($teacherData)) {
            $flag = 1;
            $heading = 'Edit';
        }
    @endphp

    <!-- Card for Teacher Form -->
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">{{ $heading }} Teacher</h5>
                    <p>Easily add, edit, and bulk upload teacher information to keep your records accurate and up to date.
                    </p>


                </div>
            </div>
            @if ($flag != 1)
                <div class="col-md-6 mb-3">
                    <h6 class="">Bulk Upload Teachers</h6>
                    {{--  <hr class="form-divider w-100 border-top border-secondary opacity-75 my-3">  --}}
                    <div class="col-md-12">
                        @livewire('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_teacher'])
                    </div>
                </div>
            @endif
        </div>
    </div>


    <!-- Card for Add Teacher Form -->
    <div class="cardBox teacherMain py-md-4 mb-3">
        <div class="formPanel">
            <h5 class="mb-3">{{ $heading }} Teacher</h5>

            <!-- Form Start -->
            {{ Form::open(['url' => route('sp.teacher.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
            {{ Form::hidden('role', 'school_teacher') }}
            {{ Form::hidden('id', $teacherData->id ?? null, ['id' => 'teacher_id_field']) }}

            <!-- Name Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('name', 'Name', ['class' => 'form-label']) !!} <b>*</b>
                    {!! Form::text('name', old('name', $teacherData->name ?? null), [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]) !!}
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Gender Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('gender', 'Gender', ['class' => 'form-label']) !!}
                    {{ Form::select(
                        'gender',
                        config('constants.GENDER'),
                        old('gender', $teacherData->userAdditionalDetail->gender ?? null),
                        [
                            'class' => 'form-select' . ($errors->has('gender') ? ' is-invalid' : ''),
                            'placeholder' => 'Select',
                        ],
                    ) }}
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- DOB Field -->
            <div class="col-md-3">
                <div class="form-group mb-2">
                    {!! Form::label('dob', 'DOB', ['class' => 'form-label']) !!}
                    {!! Form::date(
                        'dob',
                        old(
                            'dob',
                            isset($teacherData->userAdditionalDetail->dob)
                                ? \Carbon\Carbon::parse($teacherData->userAdditionalDetail->dob)->format('Y-m-d')
                                : null,
                        ),
                        [
                            'class' => 'form-control dateInput' . ($errors->has('dob') ? ' is-invalid' : ''),
                            'placeholder' => 'DOB',
                        ],
                    ) !!}

                    @error('dob')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('email', 'Enter Email', ['class' => 'form-label']) !!} <b>*</b>
                    {!! Form::text('email', old('email', $teacherData->email ?? null), [
                        'class' => 'form-control email' . ($errors->has('email') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]) !!}
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Mobile Number Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label']) !!} <b>*</b>
                    {!! Form::number('mobile_no', old('mobile_no', $teacherData->mobile_no ?? null), [
                        'class' => 'form-control mobile' . ($errors->has('mobile_no') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]) !!}
                    @error('mobile_no')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Age Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('age', 'Age', ['class' => 'form-label']) !!}
                    {!! Form::number('age', old('age', $teacherData->userAdditionalDetail->age ?? null), [
                        'class' => 'form-control' . ($errors->has('age') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]) !!}
                    @error('age')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Address Field -->
            <div class="col-md-3">
                <div class="form-group mb-2">
                    {!! Form::label('address', 'Address', ['class' => 'form-label']) !!}
                    {!! Form::textarea('address', old('address', $teacherData->userAdditionalDetail->address ?? null), [
                        'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                        'rows' => 1,
                    ]) !!}
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- State Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('state', 'State', ['class' => 'form-label']) !!}
                    {{ Form::select('state', $states, old('state', $teacherData->userAdditionalDetail->state ?? null), [
                        'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'id' => 'state-select',
                    ]) }}
                    @error('state')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- City Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('city', 'City', ['class' => 'form-label']) !!}
                    {{ Form::select('city', [], old('city', $teacherData->userAdditionalDetail->city ?? null), [
                        'class' => 'form-select' . ($errors->has('city') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'id' => 'city-select',
                    ]) }}
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Qualification Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    {!! Form::label('qualification', 'Qualification', ['class' => 'form-label']) !!}
                    {!! Form::text(
                        'qualification',
                        old('qualification', $teacherData->userAdditionalDetail->qualification ?? null),
                        [
                            'class' => 'form-control qualification' . ($errors->has('qualification') ? ' is-invalid' : ''),
                            'placeholder' => 'Enter here',
                        ],
                    ) !!}
                    @error('qualification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Experience Field -->
            <div class="col-md-3">
                <div class="form-group mb-2">
                    {!! Form::label('experience', 'Experience', ['class' => 'form-label']) !!}
                    {!! Form::text('experience', old('experience', $teacherData->userAdditionalDetail->experience ?? null), [
                        'class' => 'form-control experience' . ($errors->has('experience') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]) !!}
                    @error('experience')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Class Assignment Field -->
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label class ="form-label">Assign Class <b>*</b></label>
                    <ul class="typeCheckList">
                        @foreach ($classes as $key => $item)
                            <li>
                                <div class="typeCheck">
                                    <input type="checkbox" id="class_{{ $key }}" name="class[]"
                                        value="{{ $key }}" class="d-none"
                                        {{ in_array($key, old('class', explode(',', $teacherData->userAdditionalDetail->assigned_classes ?? ''))) ? 'checked' : '' }}>
                                    <label for="class_{{ $key }}">
                                        <i class="bi bi-check-lg"></i>{{ $item }}
                                    </label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{-- ← add d-block so it shows without is-invalid sibling --}}
                    @error('class')
                        <div class="text-danger d-block" style="font-size: 0.875em;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Subject Assignment Field -->
            <div class="col-md-4">
                <div class="form-group bginput mb-2">
                    {!! Form::label('subject', 'Assign Subject', ['class' => 'form-label']) !!} <b>*</b>
                    <select name="subject[]" class="js-select2 form-select" multiple="multiple" placeholder="Select">
                        @foreach ($subjects as $id => $name)
                            <option value="{{ $id }}"
                                {{ in_array($id, old('subject', explode(',', $teacherData->userAdditionalDetail->assigned_subjects ?? ''))) ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject')
                        <div class="invalid-feedback d-block">{{ $message }}</div> {{-- ← d-block added --}}
                    @enderror
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
