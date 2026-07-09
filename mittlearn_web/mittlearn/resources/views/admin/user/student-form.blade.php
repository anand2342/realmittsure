<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">Student Details</h5>
            <hr class="form-divider">

            <div class="row g-3">

                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_id', 'Select School', ['class' => 'form-label required']) !!}
                    {{ Form::select('school_id', $schoolList, $userData->userAdditionalDetail->school_id ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div> --}}
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
                    {!! Form::label('admission_no', 'Admission No./Sr.No.', ['class' => 'form-label  ']) !!}
                    {!! Form::text('admission_no', $userData->userAdditionalDetail->admission_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Admission Number',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('admission_date', 'Admission Date', ['class' => 'form-label ']) !!}
                    {!! Form::date('admission_date', $userData->studentDetails->doj ?? null, [
                        'class' => 'form-control',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'Student Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('name', $userData->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Student Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('parent_name', 'Parent Name', ['class' => 'form-label ']) !!}
                    {!! Form::text('parent_name', $userData->studentDetails->parent_name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Parent Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('email', 'Email', ['class' => 'form-label ']) !!}
                    {!! Form::text('email',  $userData->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('dob', 'Date of Birth', ['class' => 'form-label ']) !!}
                    {!! Form::date('dob', $userData->studentDetails->dob ?? null, [
                        'class' => 'form-control',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('class', 'Select Class', ['class' => 'form-label required']) !!}
                    {{ Form::select('class', $loadClasses, $userData->studentDetails->class ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div> --}}


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('class', 'Select Class', ['class' => 'form-label required']) !!}
                    {{ Form::select('class', !empty($loadClasses) ? $loadClasses : [], $userData->studentDetails->class ?? null, [
                        'class' => 'form-select',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('section', 'Select Section', ['class' => 'form-label ']) !!}
                    {{ Form::select('section', $sections, $userData->studentDetails->section ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('parent_mobile_no', 'Parent/Guardian Mobile Number', ['class' => 'form-label required ']) !!}
                    {!! Form::text('parent_mobile_no', $userData->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Parent/Guardian Mobile Number',
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
                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>

            </div>
        </div>
    </div>
</section>
