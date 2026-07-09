@extends('admin.layouts.master')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="pagetitle">
                    <h1> School Details</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active"> School Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Role</h5>
                        <div class="col-lg-12">
                            <h5 class="card-title pb-0">School Details</h5>
                            <hr class="form-divider">
                            {{ Form::model($userData, ['url' => route('school.update'), 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
                            {{ Form::hidden('id', $userData->id) }}
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('assign_to', 'Assign To', ['class' => 'form-label required']) !!}
                                    {{ Form::select(
                                        'assign_to',
                                        $users,
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->assign_to : null,
                                        [
                                            'class' => 'form-select',
                                            'placeholder' => '--Select--',
                                        ],
                                    ) }}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('lead', 'Lead', ['class' => 'form-label']) !!}
                                    {{ Form::select(
                                        'lead',
                                        $users,
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->lead : null,
                                        [
                                            'class' => 'form-select',
                                            'placeholder' => '--Select--',
                                        ],
                                    ) }}
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('name', 'School Name', ['class' => 'form-label required ']) !!}
                                    {!! Form::text('name', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter School Name',
                                    ]) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('parent_school_name', 'Parent School Name', ['class' => 'form-label']) !!}
                                    {!! Form::text(
                                        'parent_school_name',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->parent_school_name : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Parent School Name',
                                        ],
                                    ) !!}
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('email', 'Email', ['class' => 'form-label ']) !!}
                                    {!! Form::text('email', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Email',
                                    ]) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('website', 'Website', ['class' => 'form-label']) !!}
                                    {!! Form::text(
                                        'website',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->website : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Website',
                                        ],
                                    ) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('decision_maker', 'Decision Maker', ['class' => 'form-label']) !!}
                                    {!! Form::text(
                                        'decision_maker',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->decision_maker : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Decision Maker Name',
                                        ],
                                    ) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('decision_maker_mobile_no', 'Decision Maker Mobile No.', ['class' => 'form-label']) !!}
                                    {!! Form::text(
                                        'decision_maker_mobile_no',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->decision_maker_mobile_no : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Decision Maker Mobile',
                                        ],
                                    ) !!}
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('decision_maker_role', 'Decision Maker Role', ['class' => 'form-label']) !!}
                                    {{ Form::select('decision_maker_role', $roles, isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->decision_maker_role : null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('school_board', 'School Board', ['class' => 'form-label required']) !!}
                                    {{ Form::select('school_board', $boards, isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->school_board : null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('school_medium', 'School Medium', ['class' => 'form-label required']) !!}
                                    {{ Form::select('school_medium', $mediums, isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->school_medium : null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('strength', 'Strength', ['class' => 'form-label ']) !!}
                                    {!! Form::text(
                                        'strength',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->strength : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Strength',
                                        ],
                                    ) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('grade', 'Grade', ['class' => 'form-label required']) !!}
                                    {{ Form::select('grade', config('constants.SUBSCRIPTION_PLAN_TYPES'), isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->grade : null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('school_affiliation', 'School Affiliation Number/PAN Number', [
                                        'class' => 'form-label required',
                                    ]) !!}
                                    {!! Form::text(
                                        'school_affiliation',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->school_affiliation_no : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter School Affiliation Number',
                                        ],
                                    ) !!}
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('school_registration_no', 'School Registration Number', ['class' => 'form-label required ']) !!}
                                    {!! Form::text(
                                        'school_registration_no',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->school_registration_no : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter School Registration Number',
                                        ],
                                    ) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'form-label']) !!}
                                    {!! Form::date(
                                        'incorporation_date',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->incorporation_date : null,
                                        [
                                            'class' => 'form-control',
                                        ],
                                    ) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('assign_distributor', 'Assign Distributor', ['class' => 'form-label required']) !!}
                                    {{ Form::select('assign_distributor', config('constants.SUBSCRIPTION_PLAN_TYPES'), isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->assign_distributor : null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('gst_no', 'GST No.', ['class' => 'form-label ']) !!}
                                    {!! Form::text(
                                        'gst_no',
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->gst_no : null,
                                        [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter GST No.',
                                        ],
                                    ) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group bginput mb-3">
                                        {!! Form::label('class', 'Assign Classes', ['class' => 'form-label required']) !!}
                                        {!! Form::select(
                                            'class[]',
                                            $classes,
                                            isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->class : null,
                                            [
                                                'class' => 'js-select2 form-select',
                                                'multiple' => 'multiple',
                                                'placeholder' => '--Select--',
                                            ],
                                        ) !!}
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::checkbox(
                                        'onboardERP',
                                        1,
                                        isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->board_erp : null,
                                        [
                                            'class' => 'form-check-input',
                                            'id' => 'onboardERP',
                                        ],
                                    ) !!}
                                    {!! Form::label('onboardERP', 'On Board for ERP?', ['class' => 'form-label required']) !!}
                                </div>

                            </div>

                            <hr class="form-divider">
                            <h5 class="card-title pb-0">Address Details</h5>
                            <hr class="form-divider">

                            <div class="row g-3">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('pincode', 'Pin Code', ['class' => 'form-label required ']) !!}
                                    {!! Form::text('pincode', isset($userData->schoolDetails) ? $userData->schoolDetails->postal_code : null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter PIN Code',
                                    ]) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('state', 'State', ['class' => 'form-label required ']) !!}
                                    {!! Form::text('state', isset($userData->schoolDetails) ? $userData->schoolDetails->state : null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter State Name',
                                    ]) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('district', 'District', ['class' => 'form-label required ']) !!}
                                    {!! Form::text('district', isset($userData->schoolDetails) ? $userData->schoolDetails->city : null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter District Name',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('address_1', 'Address Line 1', ['class' => 'form-label required ']) !!}
                                {!! Form::text('address_1', isset($userData->schoolDetails) ? $userData->schoolDetails->address : null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Address',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('address_2', 'Address Line 2', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'address_2',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->address : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Address',
                                    ],
                                ) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('landmark', 'Landmark', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'landmark',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->landmark : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Landmark',
                                    ],
                                ) !!}
                            </div>

                        </div>

                        <hr class="form-divider">
                        <h5 class="card-title pb-0">Bank Details</h5>
                        <hr class="form-divider">

                        <div class="row g-3">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('bank_name', 'Bank Name', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'bank_name',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->bank_name : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Bank Name',
                                    ],
                                ) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('acc_holder_name', 'Bank Account Holder Name', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'acc_holder_name',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->acc_holder_name : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Account Holder Name',
                                    ],
                                ) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('branch_name', 'Branch Name', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'branch_name',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->branch_name : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Branch Name',
                                    ],
                                ) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('acc_no', 'Bank Account Number', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'acc_no',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->acc_no : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Account Number',
                                    ],
                                ) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('ifsc_code', 'IFSC Code', ['class' => 'form-label required ']) !!}
                                {!! Form::text(
                                    'ifsc_code',
                                    isset($userData->userAdditionalDetail) ? $userData->userAdditionalDetail->ifsc_code : null,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter IFSC Code',
                                    ],
                                ) !!}
                            </div>
                        </div>
                        <div class="text-end">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                            {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

    </section>
    <script>
        $(".js-select2").select2({
            closeOnSelect: false,
            placeholder: "Select",
            allowClear: false,
            tags: true
        });
    </script>
@endsection
