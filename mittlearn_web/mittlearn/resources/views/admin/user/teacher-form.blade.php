<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">Teacher Details</h5>
            <hr class="form-divider">
            <div class="row g-3">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_id', 'School Name', ['class' => 'form-label required']) !!}
                    {!! Form::select('school_id', $schools, $userData->userAdditionalDetail->school_id ?? null, [
                        'class' => 'form-select',
                        'wire:model' => 'selectedSchool',
                        'wire:change' => 'schoolChanged($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                        'placeholder' => '--Select--',
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'Name', ['class' => 'form-label required ']) !!}
                    {!! Form::text('name', $data->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter First Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                {{-- @dump($userData->userAdditionalDetail) --}}
                {{--  <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('last_name', 'Last Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('last_name', $userData->userAdditionalDetail->last_name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Last Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>  --}}

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('gender', 'Gender', ['class' => 'form-label ']) !!}
                    {{ Form::select('gender', config('constants.GENDER'), $userData->userAdditionalDetail->gender ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('age', 'Age', ['class' => 'form-label  ']) !!}
                    {!! Form::text('age', $userData->userAdditionalDetail->age ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Age',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('email', 'Email', ['class' => 'form-label required']) !!}
                    {!! Form::text('email', $data->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required']) !!}
                    {!! Form::text('mobile_no', $data->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('address', 'Address', ['class' => 'form-label ']) !!}
                    {!! Form::text('address', $userData->userAdditionalDetail->address ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('country', 'Country', ['class' => 'form-label required']) !!}

                    {{ Form::select(
                        'country',
                        ['india' => 'India'],
                        old('country', $userData->userAdditionalDetail->country ?? null),
                        [
                            'class' => 'form-select',
                            'placeholder' => 'Select',
                        ],
                    ) }}
                </div> --}}


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('state', 'State', ['class' => 'form-label ']) !!}

                    {{ Form::select('state', $states, old('state', $userData->userAdditionalDetail->state ?? null), [
                        'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'id' => 'state-select',
                        'wire:model' => 'selectedState', // Livewire binding
                        'wire:change' => 'stateChanged($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('city', 'City', ['class' => 'form-label ']) !!}

                    {{ Form::select('city', $cities, old('city', null), [
                        'class' => 'form-select',
                        'placeholder' => 'Select',
                        'id' => 'city-select',
                        'wire:model' => 'city', // Livewire binding
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('qualification', 'Qualification', ['class' => 'form-label ']) !!}
                    {!! Form::text('qualification', $userData->userAdditionalDetail->qualification ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Qualification',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                {{-- @dd($userData->userAdditionalDetail->dob) --}}
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('dob', 'Dob', ['class' => 'form-label ']) !!}
                    {!! Form::date('dob', $userData->userAdditionalDetail->dob ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter dob',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group bginput mb-3">
                        {!! Form::label('class', 'Assign Classes', ['class' => 'form-label required']) !!}
                        <select name="class[]" class="js-select2 form-select" ,
                            @if ($viewOnly) disabled @endif , multiple="multiple"
                            placeholder="Select">
                            @if (!empty($loadClasses))
                                @foreach ($loadClasses as $id => $name)
                                    <option value="{{ $id }}"
                                        @if (in_array($id, $selectedTeacherClasses)) selected @endif>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group bginput mb-3">
                        {!! Form::label('subject', 'Assign Subjects', ['class' => 'form-label required']) !!}
                        <select name="subject[]" class="js-select2 form-select" ,
                            @if ($viewOnly) disabled @endif , multiple="multiple"
                            placeholder="Select">
                            @foreach ($subjects as $id => $name)
                                <option value="{{ $id }}" @if (in_array($id, $selectedTeacherSubjects)) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('experience', 'Experience', ['class' => 'form-label ']) !!}
                    {!! Form::text('experience', $userData->userAdditionalDetail->experience ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Experience',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('password', 'Password', ['class' => 'form-label required ']) !!}
                    {!! Form::text('password', $userData->validate_string ?? 'Mitt@123', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    ]) !!}
                </div>

            </div>
            <div class="col-sm-12 text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
            </div>
        </div>
    </div>
</section>
