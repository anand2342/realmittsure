<section class="section">
    <div class="row">
        <div class="col-lg-12">

            @php
                $heading =
                    $role == 'salesman'
                        ? 'Relationship Manager(RM)'
                        : ($role == 'distributors'
                            ? 'Distributor'
                            : 'Instructor');
            @endphp
            <h5 class="card-title pb-0">{{ $heading }} Details</h5>
            <hr class="form-divider">

            <div class="row g-3">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'Name', ['class' => 'form-label required ']) !!}
                    {!! Form::text('name', $userData->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Name',
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

                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('gender', 'Gender', ['class' => 'form-label']) !!}
                    {{ Form::select('gender', config('constants.GENDER'), $userData->userAdditionalDetail->gender ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div> --}}
                @if ($role == 'salesman')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('employee_id', 'Employee ID', ['class' => 'form-label ']) !!}
                        {!! Form::text('employee_id', $userData->userAdditionalDetail->employee_id ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Employee ID',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) !!}
                    </div>
                @elseif ($role == 'distributors')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('distributor_id', 'Distributor ID', ['class' => 'form-label ']) !!}
                        {!! Form::text('distributor_id', $userData->userAdditionalDetail->distributor_id ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Distributor ID',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) !!}
                    </div>
                @elseif ($role == 'instructor')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('about', 'About Instructor', ['class' => 'form-label ']) !!}
                        {!! Form::text('about', $userData->userAdditionalDetail->about ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter About Instructor',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) !!}
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('designation', 'Instructor Post/Designation', ['class' => 'form-label ']) !!}
                        {!! Form::text('designation', $userData->userAdditionalDetail->designation ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Instructor Post/Designation',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) !!}
                    </div>
                @endif


                <div class="col-md-6 col-sm-6 col-xs-12">
                    @if ($role == 'instructor')
                        {!! Form::label('email', 'Email', ['class' => 'form-label']) !!}
                    @else
                        {!! Form::label('email', 'Email', ['class' => 'form-label required']) !!}
                    @endif
                    {!! Form::text('email', $userData->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    @if ($role == 'instructor')
                        {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label ']) !!}
                    @else
                        {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required']) !!}
                    @endif
                    {!! Form::text('mobile_no', $userData->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('address', 'Address', ['class' => 'form-label']) !!}
                    {!! Form::text('address', $userData->userAdditionalDetail->address ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                @if ($role == 'instructor')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('image', 'Profile Image', ['class' => 'form-label required']) !!}
                        {!! Form::file('image', ['class' => 'form-control', 'disabled' => $viewOnly ? 'disabled' : null]) !!}
                        @if ($flag === 1)
                            <img src="{{ Storage::url('uploads/user/profile_image/' . $userData->image) }}"
                                alt="image" width="200" height="100">
                        @endif
                    </div>
                @endif
                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('country', 'Country', ['class' => 'form-label']) !!}
                    {!! Form::select('country', ['india' => 'India'], $userData->userAdditionalDetail->country ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Select Country',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div> --}}

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('state', 'State', ['class' => 'form-label']) !!}

                    {{ Form::select('state', $states, old('state', $userData->userAdditionalDetail->state ?? null), [
                        'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'wire:model' => 'selectedState',
                        'wire:change' => 'stateChanged($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('city', 'City', ['class' => 'form-label']) !!}

                    {{ Form::select('city', $cities, old('city', null), [
                        'class' => 'form-select',
                        'placeholder' => 'Select',
                        'wire:model' => 'city', // Livewire binding
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                {{--  <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('qualification', 'Qualification', ['class' => 'form-label required']) !!}
                    {!! Form::text('qualification', $userData->userAdditionalDetail->qualification ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Qualification',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('experience', 'Experience', ['class' => 'form-label required']) !!}
                    {!! Form::text('experience', $userData->userAdditionalDetail->experience ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Experience',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('designation', 'Designation', ['class' => 'form-label required']) !!}
                    {!! Form::text('designation', $userData->userAdditionalDetail->designation ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Designation',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>  --}}

                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('about', 'About ' . $heading, ['class' => 'form-label']) !!}
                    {!! Form::text('about', $userData->userAdditionalDetail->about ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter About',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('password', 'Password', ['class' => 'form-label required ']) !!}
                    {!! Form::text('password', $userData->validate_string ?? 'Mitt@123', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    ]) !!}
                </div> --}}

                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
            </div>
        </div>
    </div>
</section>
