<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <h5 class="card-title pb-0">School Details</h5>
            <hr class="form-divider">
            <div class="row g-3">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('assign_to', 'Assign To', ['class' => 'form-label required']) !!}
                    {{ Form::select('assign_to', $salesman, old('assign_to', $userData->userAdditionalDetail->assign_to ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                {!! Form::hidden('verify', $verify ?? null) !!}
                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('lead', 'Lead', ['class' => 'form-label']) !!}
                    {{ Form::select('lead', $salesman, $userData->userAdditionalDetail->lead ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div> --}}

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'School Name', ['class' => 'form-label required ']) !!}
                    {!! Form::text('name', old('name', $userData->name ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter School Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('username', 'Username', ['class' => 'form-label  ']) !!}
                    {!! Form::text('username', old('username', $userData->username ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Username',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('password', 'Password', ['class' => 'form-label required ']) !!}
                    {!! Form::text('password', old('password', $userData->validate_string ?? 'Mitt@123'), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_type', 'School Type', ['class' => 'form-label required']) !!}
                    {{ Form::select('school_type', config('constants.SCHOOL_TYPES'), old('school_type', $school_type ?? null), [
                        'class' => 'form-select',
                        'wire:model' => 'schoolType', // Bind this dropdown to Livewire
                        'wire:change' => 'getSchoolType($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                @if ($schoolType == 'group')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('school_role', 'School Role', ['class' => 'form-label ']) !!}
                        {{ Form::select(
                            'school_role',
                            config('constants.SCHOOL_ROLE'),
                            old('school_role', $userData->userAdditionalDetail->school_role ?? null),
                            [
                                'class' => 'form-select',
                                'wire:model' => 'schoolRole',
                                'wire:change' => 'getSchoolRole($event.target.value)',
                                'placeholder' => '--Select--',
                                'disabled' => $viewOnly ? 'disabled' : null,
                            ],
                        ) }}
                    </div>
                @endif
                @if ($schoolRole == 'branch')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('parent_school_name', 'Parent School Name', ['class' => 'form-label ']) !!}
                        {{ Form::select('parent_school_name', $schoolList, old('parent_school_name', $userData->userAdditionalDetail->parent_school_name ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                    </div>
                @endif

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('email', 'Email', ['class' => 'form-label required']) !!}
                    {!! Form::text('email', old('email', $userData->email ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}

                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('academic_session_id', 'Academic Session', ['class' => 'form-label required']) !!}
                    {{ Form::select(
                        'academic_session_id',
                        $academicSessions,
                        old('academic_session_id', $selectedSession ?? null),
                        [
                            'class' => 'form-select',
                            'placeholder' => '--Select--',
                            'wire:model' => 'selectedSession',
                            'wire:change' => 'getSessionBatches($event.target.value)',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) }}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('batch_id', 'Academic Batch', ['class' => 'form-label required']) !!}
                    {{ Form::select('batch_id', $batches, old('batch_id', $userData->schoolDetails->batch_id ?? null), [
                        'class' => 'form-select',
                        'placeholder' => '--Select--',
                        'wire:model' => 'selectedBatch',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('website', 'Website', ['class' => 'form-label ']) !!}
                    {!! Form::text('website', old('website', $userData->userAdditionalDetail->website ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Website',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('decision_maker', 'Decision Maker', ['class' => 'form-label ']) !!}
                    {!! Form::text(
                        'decision_maker',
                        old('decision_maker', $userData->userAdditionalDetail->decision_maker ?? null),
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Decision Maker Name',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('decision_maker_mobile_no', 'Decision Maker Mobile No.', ['class' => 'form-label required']) !!}
                    {!! Form::text(
                        'decision_maker_mobile_no',
                        old('decision_maker_mobile_no', $userData->userAdditionalDetail->decision_maker_mobile_no ?? null),
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Decision Maker Mobile',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('decision_maker_role', 'Decision Maker Role', ['class' => 'form-label']) !!}
                    {{ Form::select('decision_maker_role', $roles, old('decision_maker_role', $userData->userAdditionalDetail->decision_maker_role ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>
                {{--  @dd(old('assign_to', $userData->userAdditionalDetail->school_board, $boards)  --}}
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_board', 'School Board', ['class' => 'form-label required']) !!}
                    {{ Form::select('school_board', $boards, old('assignschool_board_to', $userData->userAdditionalDetail->school_board ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_medium', 'School Medium', ['class' => 'form-label required']) !!}
                    {{ Form::select('school_medium', $mediums, old('school_medium', $userData->userAdditionalDetail->school_medium ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('strength', 'Strength', ['class' => 'form-label ']) !!}
                    {!! Form::text('strength', old('strength', $userData->userAdditionalDetail->strength ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Strength',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('grade', 'Grade', ['class' => 'form-label ']) !!}
                    {{ Form::select('grade', $grades, old('grade', $userData->userAdditionalDetail->grade ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_affiliation', 'School Affiliation Number/PAN Number', [
                        'class' => 'form-label ',
                    ]) !!}
                    {!! Form::text(
                        'school_affiliation',
                        old('school_affiliation', $userData->userAdditionalDetail->school_affiliation_no ?? null),
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter School Affiliation Number',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_registration_no', 'School Registration Number', ['class' => 'form-label  ']) !!}
                    {!! Form::text(
                        'school_registration_no',
                        old('school_registration_no', $userData->userAdditionalDetail->school_registration_no ?? null),
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter School Registration Number',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'form-label']) !!}
                    {!! Form::date(
                        'incorporation_date',
                        old('incorporation_date', $userData->userAdditionalDetail->incorporation_date ?? null),
                        [
                            'class' => 'form-control',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('assign_distributor', 'Assign Distributor', ['class' => 'form-label ']) !!}
                    {{ Form::select('assign_distributor', $distributors, old('assign_distributor', $userData->userAdditionalDetail->assign_distributor ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('customer_type', 'Customer Type', ['class' => 'form-label required']) !!}
                    {{ Form::select('customer_type', config('constants.CUSTOMER_TYPE'), old('customer_type', $userData->userAdditionalDetail->customer_type ?? null), ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('gst_no', 'GST No.', ['class' => 'form-label ']) !!}
                    {!! Form::text('gst_no', old('gst_no', $userData->userAdditionalDetail->gst_no ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter GST No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group bginput mb-3">
                        {!! Form::label('class', 'Assign Classes', ['class' => 'form-label required']) !!}
                        <select name="class[]" class="js-select2 form-select" ,
                            @if ($viewOnly) disabled @endif , multiple="multiple"
                            placeholder="Select">
                            @foreach ($classes as $id => $name)
                                <option value="{{ $id }}" @if (in_array($id, $selectedClasses)) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::checkbox('onboardERP', 1, $userData->userAdditionalDetail->board_erp ?? null, [
                        'class' => 'form-check-input',
                        'id' => 'onboardERP',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                    {!! Form::label('onboardERP', 'On Board for ERP?', ['class' => 'form-label']) !!}
                </div> --}}
            </div>

            <hr class="form-divider">
            <h5 class="card-title pb-0">Address Details</h5>
            <hr class="form-divider">

            <div class="row g-3">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('pincode', 'Pin Code', ['class' => 'form-label required ']) !!}
                    {!! Form::text('pincode', old('pincode', $userData->schoolDetails->postal_code ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter PIN Code',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('state', 'State', ['class' => 'form-label required ']) !!}

                    {{ Form::select(
                        'state',
                        $states, // Dynamic states array
                        old('state', $userData->schoolDetails->state ?? null), // Pre-fill value or old input
                        [
                            'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                            'placeholder' => 'Select',
                            'id' => 'state-select',
                            'wire:model' => 'selectedState',
                            'wire:change' => 'stateChanged($event.target.value)',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) }}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('district', 'District', ['class' => 'form-label required ']) !!}

                    {{ Form::select(
                        'district',
                        $cities, // Dynamically populated cities based on selected state
                        old('district', $this->cities ?? null), // Pre-fill value or retain old input
                        [
                            'class' => 'form-select',
                            'placeholder' => 'Select',
                            'id' => 'city-select',
                            'wire:model' => 'city',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) }}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('address_1', 'Address Line 1', ['class' => 'form-label  ']) !!}
                    {!! Form::text('address_1', old('address_1', $userData->schoolDetails->address ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('address_2', 'Address Line 2', ['class' => 'form-label  ']) !!}
                    {!! Form::text('address_2', old('address_2', $userData->userAdditionalDetail->address ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('uniqueId', 'School Unique Id', ['class' => 'form-label  ']) !!}
                    {!! Form::text(
                        'uniqueId',
                        old('uniqueId', isset($userData) ? $userData->schoolDetails->unique_id ?? ($uniqueId ?? '') : $uniqueId ?? ''),
                        [
                            'class' => 'form-control',
                            'readonly' => true,
                        ],
                    ) !!}
                </div>
            </div>

            <hr class="form-divider">
            <h5 class="card-title pb-0">Bank Details</h5>
            <hr class="form-divider">

            <div class="row g-3">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('bank_name', 'Bank Name', ['class' => 'form-label  ']) !!}
                    {!! Form::text('bank_name', old('bank_name', $userData->userAdditionalDetail->bank_name ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Bank Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('acc_holder_name', 'Bank Account Holder Name', ['class' => 'form-label  ']) !!}
                    {!! Form::text(
                        'acc_holder_name',
                        old('acc_holder_name', $userData->userAdditionalDetail->acc_holder_name ?? null),
                        [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Account Holder Name',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('branch_name', 'Branch Name', ['class' => 'form-label  ']) !!}
                    {!! Form::text('branch_name', old('branch_name', $userData->userAdditionalDetail->branch_name ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Branch Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('acc_no', 'Bank Account Number', ['class' => 'form-label  ']) !!}
                    {!! Form::text('acc_no', old('acc_no', $userData->userAdditionalDetail->acc_no ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Account Number',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('ifsc_code', 'IFSC Code', ['class' => 'form-label  ']) !!}
                    {!! Form::text('ifsc_code', old('ifsc_code', $userData->userAdditionalDetail->ifsc_code ?? null), [
                        'class' => 'form-control',
                        'placeholder' => 'Enter IFSC Code',
                        'disabled' => $viewOnly ? 'disabled' : null,
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
