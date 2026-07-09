<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">User Details</h5>
            <hr class="form-divider">


            <div class="row g-3">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', ' Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('name', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('email', 'Email ID', ['class' => 'form-label ', 'disabled' => $viewOnly ?? false]) !!}
                    {!! Form::text('email', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required', 'disabled' => $viewOnly ?? false]) !!}
                    {!! Form::text('mobile_no', null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
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

                {{-- @if ($viewOnly != false)
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('password', 'Password', ['class' => 'form-label ', 'disabled' => $viewOnly ?? false]) !!}
                        {!! Form::text('password', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Password ',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) !!}
                @endif --}}
                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
            </div>


        </div>
    </div>
    </div>
</section>
