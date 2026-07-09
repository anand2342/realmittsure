<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">Leaders Details</h5>
            <hr class="form-divider">

            <div class="row g-3">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'Name', ['class' => 'form-label required ']) !!}
                    {!! Form::text('name', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>


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
                    {!! Form::label('email', 'Email', ['class' => 'form-label ']) !!}
                    {!! Form::text('email', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label ']) !!}
                    {!! Form::text('mobile_no', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
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
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('image', 'Profile Image', ['class' => 'form-label']) !!}
                    {!! Form::file('image', ['class' => 'form-control', 'disabled' => $viewOnly ? 'disabled' : null]) !!}
                    @if ($flag === 1)
                        @if (!empty(optional($userData->userAdditionalDetail)->image))
                            <img src="{{ Storage::url('uploads/user/leader/' . $userData->userAdditionalDetail->image) }}"
                                alt="image" width="200" height="100">
                        @endif

                    @endif
                </div>
                <div class="col-md-12 col-sm-6 col-xs-12">
                    {!! Form::label('about', 'About Leader', ['class' => 'form-label required']) !!}
                    {!! Form::textarea('about', $userData->userAdditionalDetail->about ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'About Leader',
                        'rows' => '1',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <hr class="form-divider">
                <h5 class="card-title pb-0">Social Media Links</h5>
                <hr class="form-divider">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('facebook', 'Facebook', ['class' => 'form-label ']) !!}
                    {!! Form::text('facebook', $userData->userAdditionalDetail->facebook ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Link',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('instagram', 'Instagram', ['class' => 'form-label ']) !!}
                    {!! Form::text('instagram', $userData->userAdditionalDetail->instagram ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Link',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('linkedin', 'LinkedIn', ['class' => 'form-label ']) !!}
                    {!! Form::text('linkedin', $userData->userAdditionalDetail->linkedin ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Link',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('twitter', 'Twitter', ['class' => 'form-label ']) !!}
                    {!! Form::text('twitter', $userData->userAdditionalDetail->twitter ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Link',
                        'disabled' => $viewOnly ? 'disabled' : null,
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
