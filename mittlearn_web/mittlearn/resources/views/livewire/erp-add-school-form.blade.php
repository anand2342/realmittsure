<div>
    <style>
        .FromErpBx {
            background-color: #eceef2;
        }
    </style>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if ($flag == 1)
                            {{ Form::model($data, ['url' => route('erp-data.save.schools'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('id', null) }}
                            {{ Form::hidden('schid', $erpData->schid) }}
                        @else
                            {{ Form::open(['url' => route('erp-data.save.schools'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                            {{ Form::hidden('schid', $erpData->schid) }}
                        @endif
                        <h5 class="card-title pb-0">School Details</h5>
                        <hr class="form-divider">
                        <div class="row g-3">
                            <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('assign_to', 'Assign To', ['class' => 'form-label required']) !!}
                                {{ Form::select('assign_to', $salesman, $this->erpData->assign_to ?? ' N/A', ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                        ERP : </span><strong>{{ $this->erpData->assign_to ?? ' N/A' }}</strong></div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('name', 'School Name', ['class' => 'form-label required ']) !!} {!! Form::text('name', $this->erpData->schoolName ?? ' N/A', [
                                'class' => 'form-control',
                                'placeholder' => 'Enter School Name',
                            ]) !!}
                                <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                        ERP : </span><strong>
                                        {{ $this->erpData->schoolName ?? ' N/A' }}</strong></div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('username', 'Username', ['class' => 'form-label  ']) !!} {!! Form::text('username', $this->erpData->name ?? ' N/A', [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Username',
                            ]) !!}
                                <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                        ERP : </span><strong> {{ $this->erpData->name ?? ' N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('password', 'Password', ['class' => 'form-label required ']) !!} {!! Form::text('password', $this->erpData->password ?? 'Mitt@123', [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Password',
                            ]) !!}
                                <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                        ERP : </span><strong> {{ $this->erpData->password ?? ' N/A' }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('school_type', 'School Type', ['class' => 'form-label required']) !!}
                                {{ Form::select('school_type', config('constants.SCHOOL_TYPES'), $school_type ?? ' N/A', [
                                    'class' => 'form-select',
                                    'wire:model' => 'schoolType',
                                    'wire:change' => 'getSchoolType($event.target.value)',
                                ]) }}
                                <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                        ERP : </span><strong>
                                        {{ $this->erpData->school_type ?? ' N/A' }}</strong></div>
                            </div>
                            @if ($schoolType == 'group')
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('p', 'School Role', ['class' => 'form-label ']) !!}
                                    {{ Form::select(
                                        'school_role',
                                        config('constants.SCHOOL_ROLE'),
                                        $userData->userAdditionalDetail->school_role ?? null,
                                        [
                                            'class' => 'form-select',
                                            'wire:model' => 'schoolRole',
                                            'wire:change' => 'getSchoolRole($event.target.value)',
                                            'placeholder' => '--Select--',
                                            'disabled' => $viewOnly ? 'disabled' : null,
                                        ],
                                    ) }}
                                </div>
                                @endif@if ($schoolRole == 'branch')
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('parent_school_name', 'Parent School Name', ['class' => 'form-label ']) !!}
                                        {{ Form::select('parent_school_name', $schoolList, $userData->userAdditionalDetail->parent_school_name ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null]) }}
                                    </div>
                                @endif
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('email', 'Email', ['class' => 'form-label required']) !!} {!! Form::text('email', $this->erpData->email ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Email',
                                    ]) !!} <div
                                        class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->email ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('academic_session_id', 'Academic Session', ['class' => 'form-label required']) !!}
                                    {{ Form::select('academic_session_id', $academicSessions, $selectedSession ?? ' N/A', [
                                        'class' => 'form-select',
                                        'placeholder' => '--Select--',
                                        'wire:model' => 'selectedSession',
                                        'wire:change' => 'getSessionBatches($event.target.value)',
                                    ]) }}
                                    <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->academic_session_id ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('batch_id', 'Academic Batch', ['class' => 'form-label required']) !!}
                                    {{ Form::select('batch_id', $batches, $userData->schoolDetails->batch_id ?? null, [
                                        'class' => 'form-select',
                                        'placeholder' => '--Select--',
                                        'wire:model' => 'selectedBatch',
                                        'disabled' => $viewOnly ? 'disabled' : null,
                                    ]) }}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('website', 'Website', ['class' => 'form-label ']) !!}
                                    {!! Form::text('website', $this->erpData->website ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Website',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->website ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('decision_maker', 'Decision Maker', ['class' => 'form-label ']) !!}
                                    {!! Form::text('decision_maker', $this->erpData->contactName ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Decision Maker Name',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->contactName ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('decision_maker_mobile_no', 'Decision Maker Mobile No.', ['class' => 'form-label required']) !!}
                                    {!! Form::text('decision_maker_mobile_no', $this->erpData->contactNo ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Decision Maker Mobile',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->contactNo ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('decision_maker_role', 'Decision Maker Role', ['class' => 'form-label']) !!}
                                    {{ Form::select('decision_maker_role', $roles, $this->erpData->decision_maker_role ?? ' N/A', ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                    <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->decision_maker_role ?? ' N/A' }}</strong></div>
                                </div>
                                @php
                                    $boardId = \App\Models\Board::where(
                                        'name',
                                        explode(' ', $this->erpData->board)[0] ?? null,
                                    )->value('id');
                                    $mediumMap = [
                                        'EM' => 'English',
                                        'HM' => 'Hindi',
                                    ];
                                    $mediumCode = explode(' ', $this->erpData->board)[1] ?? null;
                                    $mediumName = $mediumMap[$mediumCode] ?? null;
                                    $mediumId = \App\Models\Medium::where('name', $mediumName ?? null)->value('id');
                                @endphp <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('school_board', 'School Board', ['class' => 'form-label required']) !!}
                                    {{ Form::select('school_board', $boards, $boardId ?? null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                    <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ explode(' ', $this->erpData->board)[0] ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('school_medium', 'School Medium', ['class' => 'form-label required']) !!}
                                    {{ Form::select('school_medium', $mediums, $mediumId ?? null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                    <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ explode(' ', $this->erpData->board)[1] ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('strength', 'Strength', ['class' => 'form-label ']) !!}
                                    {!! Form::text('strength', $this->erpData->strength ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Strength',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->strength ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('grade', 'Grade', ['class' => 'form-label ']) !!}
                                    {{ Form::select('grade', $grades, $this->erpData->sch_grade ?? ' N/A', ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                    <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->sch_grade ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('school_affiliation', 'School Affiliation Number/PAN Number', [
                                    'class' => 'form-label ',
                                ]) !!}
                                    {!! Form::text('school_affiliation', $this->erpData->school_affiliation_no ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter School Affiliation Number',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->school_affiliation_no ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('school_registration_no', 'School Registration Number', ['class' => 'form-label  ']) !!}
                                    {!! Form::text('school_registration_no', $this->erpData->school_registration_no ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter School Registration Number',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->school_registration_no ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('incorporation_date', 'Incorporation Date', ['class' => 'form-label']) !!}
                                    {!! Form::date('incorporation_date', $this->erpData->incorporation_date ?? ' N/A', [
                                        'class' => 'form-control',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->incorporation_date ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('assign_distributor', 'Assign Distributor', ['class' => 'form-label ']) !!}
                                    {{ Form::select('assign_distributor', $distributors, $this->erpData->distributor ?? ' N/A', ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                                    <div class="mt-1 p-2 border rounded small FromErpBx"><span class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->distributor ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('gst_no', 'GST No.', ['class' => 'form-label ']) !!}
                                    {!! Form::text('gst_no', $userData->userAdditionalDetail->gst ?? ' N/A', [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter GST No.',
                                    ]) !!} <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                            class="text-muted">From
                                            ERP : </span><strong>
                                            {{ $this->erpData->gst ?? ' N/A' }}</strong></div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group bginput mb-3">{!! Form::label('class', 'Assign Classes', ['class' => 'form-label required']) !!} <select name="class[]"
                                            class="js-select2 form-select", multiple="multiple" placeholder="Select">
                                            @foreach ($classes as $id => $name)
                                                <option value="{{ $id }}">{{ $name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">@php
                                        $classes = DB::connection('erp')
                                            ->table('class')
                                            ->where('schid', $this->erpData->schid)
                                            ->select('id', 'name', 'schid')
                                            ->get();
                                        $uniqueClasses = $classes->unique('name');
                                    @endphp <div
                                            class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>ERP
                                                Data: </small>
                                                @if ($uniqueClasses->isNotEmpty())
                                                    @foreach ($uniqueClasses as $class)
                                                        <span class="badge bg-info">{{ $class->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No Classes</span>
                                                @endif
                                        </div>
                                    </div>
                                </div>
                                <hr class="form-divider">
                                <h5 class="card-title pb-0">Address Details</h5>
                                <hr class="form-divider">
                                <div class="row g-3">
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('pincode', 'Pin Code', ['class' => 'form-label required ']) !!}
                                        {!! Form::text('pincode', $this->erpData->postal_code ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter PIN Code',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->postal_code ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('state', 'State', ['class' => 'form-label required ']) !!}
                                        {{ Form::select('state', $states, $selectedState ?? null, [
                                            'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : null),
                                            'placeholder' => 'Select',
                                            'id' => 'state-select',
                                            'wire:model' => 'selectedState',
                                            'wire:change' => 'stateChanged($event.target.value)',
                                        ]) }}
                                        @php
                                            $stateName = DB::connection('erp')
                                                ->table('state_table')
                                                ->where('id', $this->erpData->state)
                                                ->value('name');
                                            $districtName = DB::connection('erp')
                                                ->table('district_table')
                                                ->where('id', $this->erpData->district)
                                                ->value('name');
                                        @endphp <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong> {{ $stateName ?? ' N/A' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('district', 'District', ['class' => 'form-label required ']) !!}
                                        {{ Form::select('district', $cities, $this->cities ?? null, [
                                            'class' => 'form-select',
                                            'placeholder' => 'Select',
                                            'id' => 'city-select',
                                            'wire:model' => 'city',
                                        ]) }}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong> {{ $districtName ?? ' N/A' }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('address_1', 'Address Line 1', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('address_1', $this->erpData->address ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Address',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->address ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('address_2', 'Address Line 2', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('address_2', $this->erpData->address ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Address',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->address ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('uniqueId', 'School Unique Id', ['class' => 'form-label  ']) !!}
                                        {!! Form::text(
                                            'uniqueId',
                                            isset($userData) ? $this->erpData->unique_id ?? ($uniqueId ?? ' N/A') : $uniqueId ?? ' N/A',
                                            [
                                                'class' => 'form-control',
                                                'readonly' => true,
                                            ],
                                        ) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->unique_id ?? ' N/A' }}</strong></div>
                                    </div>
                                </div>
                                <hr class="form-divider">
                                <h5 class="card-title pb-0">Bank Details</h5>
                                <hr class="form-divider">
                                <div class="row g-3">
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('bank_name', 'Bank Name', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('bank_name', $this->erpData->bank_name ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Bank Name',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->bank_name ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('acc_holder_name', 'Bank Account Holder Name', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('acc_holder_name', $this->erpData->acc_holder_name ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Account Holder Name',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->acc_holder_name ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('bank_branch_name', 'Branch Name', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('bank_branch_name', $this->erpData->bank_branch_name ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Branch Name',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->bank_branch_name ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('acc_no', 'Bank Account Number', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('acc_no', $this->erpData->acc_no ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter Account Number',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->acc_no ?? ' N/A' }}</strong></div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">{!! Form::label('ifsc_code', 'IFSC Code', ['class' => 'form-label  ']) !!}
                                        {!! Form::text('ifsc_code', $this->erpData->ifsc_code ?? ' N/A', [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter IFSC Code',
                                        ]) !!}
                                        <div class="mt-1 p-2 border rounded small FromErpBx"><span
                                                class="text-muted">From
                                                ERP : </span><strong>
                                                {{ $this->erpData->ifsc_code ?? ' N/A' }}</strong></div>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-end"><button type="submit"
                                        class="btn btn-primary">Submit</button><button type="reset"
                                        class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                                </div>{{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>
