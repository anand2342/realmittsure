@extends('admin.layouts.master')

@section('content')
<div class="pagetitle">
    <h1>Access Code</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Access Code</li>
        </ol>
    </nav>
</div>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('access.code.index') }}">
                                <div class="row">

                                    <div class="col mb-1">
                                        <input type="text" class="form-control" placeholder="Search by Code"
                                            name="access_code" value="{{ request('access_code') }}">
                                    </div>
                                    <div class="col mb-1">
                                        <select name="school_id" class="form-select">
                                            <option value="">Select School</option>
                                            @foreach ($accessCodeSchools as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ $id == request('school_id') ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                            @endforeach
                                        </select>


                                    </div>
                                    <div class="col-md-3 mb-1">
                                        {{-- <input type="hidden" class="form-control"
                                                placeholder="Search by Generated User" name="generated_by"
                                                value="{{ request('generated_by') }}"> --}}
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('access.code.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                        <div class="col-md-2 mb-3">
                                            <label for="start_date" style="font-size: 13px;">Start Date</label>
                                            <input type="date" class="form-control" placeholder="Select Date"
                                                name="start_date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="start_date" style="font-size: 13px;">End Date</label>
                            <input type="date" class="form-control" placeholder="Select Date"
                                name="end_date" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3 mb-3 pt-4">
                            <input type="hidden" class="form-control"
                                placeholder="Search by Generated User" name="generated_by"
                                value="{{ request('generated_by') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('access.code.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div> --}}
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-4">
                            <div class="card-title mb-0">All Access Code</div>
                        </div>

                        <div class="col-sm-4">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <select id="paginationSelect" class="form-select form-select-sm"
                                    style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                </select>

                                <div id="exportDropdownContainer" class="dropdown d-none">
                                    <button class="btn btn-primary" id="assignButton" data-bs-toggle="modal"
                                        data-bs-target="#assignModal">
                                        Assign To School
                                    </button>
                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button"
                                        id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Export
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item export-option"
                                                data-type="excel">
                                                Export as Excel
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item export-option"
                                                data-type="csv">
                                                Export as CSV
                                            </a>
                                        </li>
                                        <!-- <li>
                                                                                                                                                                            <a href="javascript:void(0);" class="dropdown-item export-option"
                                                                                                                                                                                data-type="print">
                                                                                                                                                                                Print Code
                                                                                                                                                                            </a>
                                                                                                                                                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 text-end">
                            <div class="border rounded-3 px-3 py-2 d-inline-block shadow-sm">
                                <div class="small fw-normal">Unassigned Access Code Counts</div>
                                <div class="fw-semibold">
                                    Teachlite: {{ $freeTeachliteCount }} • Mittlens: {{ $freeMittlenseCount }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="formdivider">
                    <form id="exportForm" method="POST" action="{{ route('access.code.embibe.export') }}">
                        @csrf
                        <input type="hidden" name="ids" id="selectedIds" value="">
                        <input type="hidden" name="type" id="exportType" value="">
                    </form>
                    <!-- Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered" id="BookTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab === 'teachlite' ? 'active' : '' }}"
                                id="teachliteTab" data-bs-toggle="tab" data-bs-target="#teachlite-Tab"
                                type="button" role="tab" aria-controls="teachlite-Tab"
                                aria-selected="{{ $activeTab === 'teachlite' ? 'true' : 'false' }}">Teachlite
                                (for teachers)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab === 'mittlense' ? 'active' : '' }}"
                                id="mittlenseTab" data-bs-toggle="tab" data-bs-target="#mittlense-Tab"
                                type="button" role="tab" aria-controls="mittlense-Tab"
                                aria-selected="{{ $activeTab === 'mittlense' ? 'true' : 'false' }}">Mittsure
                                Lens (for students)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $activeTab === 'schools_with_lens' ? 'active' : '' }}"
                                id="schoolsWithLensTab" data-bs-toggle="tab"
                                data-bs-target="#schools-with-lens-Tab" type="button" role="tab"
                                aria-selected="{{ $activeTab === 'schools_with_lens' ? 'true' : 'false' }}">
                                Schools with Lens

                            </button>
                        </li>
                    </ul>

                    <!-- Table -->
                    <div class="tab-content pt-2" id="BookTabContent">
                        <div class="tab-pane fade {{ $activeTab === 'teachlite' ? 'show active' : '' }}"
                            id="teachlite-Tab" role="tabpanel" aria-labelledby="teachliteTab">
                            <div class="table-responsive tbleDiv mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="techselectAll"> All
                                            </th>
                                            <th>Status</th>
                                            <th><b>Content Bundle</b></th>
                                            <th><b>Licence Key</b></th>
                                            <th><b>Expiry(days)</b></th>
                                            <th><b>Created At</b></th>
                                            <th><b>School ID / Name</b></th>
                                            <th><b>Action</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachAccessCode as $item)
                                        <tr class="access-row" data-type="{{ $item->type }}">
                                            <td>
                                                @if ($item->school_id == null)
                                                <input type="checkbox" class="techrow-checkbox"
                                                    value="{{ $item->id }}">
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->school_id != null ? 'text-success' : 'text-danger' }}">
                                                    {{ $item->school_id ? 'Assigned' : 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->content_bundle ?? 'N/A' }}</td>
                                            <td>{{ $item->licence_key ?? 'N/A' }}</td>
                                            <td>{{ $item->licence_expiry ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $item->schoolName->schoolDetails->unique_id ?? '-' }}
                                                :
                                                {{ $item->schoolName->name ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button class="btn btn-sm btn-primary"
                                                        data-id="{{ $item->id }}"
                                                        id="accessCodeInfo">
                                                        Info
                                                    </button>
                                                    @if ($item->school_id && $item->is_distribute == 0)
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary revoke-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#revokeConfirmModal{{ $item->id }}">
                                                        Revoke
                                                    </button>

                                                    <!-- Revoke Access Code Modal -->
                                                    <div class="modal fade"
                                                        id="revokeConfirmModal{{ $item->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="revokeConfirmModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="POST"
                                                                action="{{ route('revoke.access.code') }}">
                                                                @csrf
                                                                <input type="hidden"
                                                                    name="access_code_id"
                                                                    value="{{ $item->id }}">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="revokeConfirmModalLabel{{ $item->id }}">
                                                                            Confirm Revocation
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to
                                                                        revoke
                                                                        this access code?
                                                                        It is currently assigned to
                                                                        a
                                                                        school:
                                                                        <b>{{ $item->schoolName->name ?? '-' }}</b>.
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">No</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Yes,
                                                                            Revoke</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $teachAccessCode->appends(
                                array_merge(request()->query(), [
                                'active_tab' => 'teachlite',
                                'per_page' => request('per_page', Cookie::get('perPage')),
                                ]),
                                )->links('pagination::bootstrap-4') !!}
                            </div>
                            {{-- <div class="d-flex justify-content-right text-right">
                                            {!! $teachAccessCode->appends(array_merge(request()->query(), ['active_tab' => 'teachlite']))->links('pagination::bootstrap-4') !!} </div> --}}
                        </div>
                        <div class="tab-pane fade {{ $activeTab === 'mittlense' ? 'show active' : '' }}"
                            id="mittlense-Tab" role="tabpanel" aria-labelledby="mittlenseTab">
                            <div class="table-responsive tbleDiv mt-3">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="mittSelectAll"> All

                                            </th>
                                            <th>Status</th>
                                            <th><b>Content Bundle</b></th>
                                            <th><b>Licence Key</b></th>
                                            <th><b>Expiry(days)</b></th>
                                            <th><b>Created At</b></th>
                                            <th><b>School ID / Name</b></th>
                                            <th><b>Action</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mittAccessCode as $item)
                                        <tr class="access-row" data-type="{{ $item->type }}">
                                            <td>
                                                @if ($item->school_id == null)
                                                <input type="checkbox" class="mittrow-checkbox"
                                                    value="{{ $item->id }}">
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->school_id != null ? 'text-success' : 'text-danger' }}">
                                                    {{ $item->school_id ? 'Assigned' : 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->content_bundle ?? 'N/A' }}</td>
                                            <td>{{ $item->licence_key ?? 'N/A' }}</td>
                                            <td>{{ $item->licence_expiry ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') ?? 'N/A' }}
                                            </td>
                                            <td>{{ $item->schoolName->schoolDetails->unique_id ?? '-' }}
                                                :
                                                {{ $item->schoolName->name ?? '-' }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <button class="btn btn-sm btn-primary"
                                                        data-id="{{ $item->id }}"
                                                        id="accessCodeInfo">
                                                        Info
                                                    </button>
                                                    @if ($item->school_id && $item->is_distribute == 0)
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary revoke-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#revokeConfirmModal{{ $item->id }}">
                                                        Revoke
                                                    </button>

                                                    <!-- Revoke Access Code Modal -->
                                                    <div class="modal fade"
                                                        id="revokeConfirmModal{{ $item->id }}"
                                                        tabindex="-1"
                                                        aria-labelledby="revokeConfirmModalLabel{{ $item->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <form method="POST"
                                                                action="{{ route('revoke.access.code') }}">
                                                                @csrf
                                                                <input type="hidden"
                                                                    name="access_code_id"
                                                                    value="{{ $item->id }}">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="revokeConfirmModalLabel{{ $item->id }}">
                                                                            Confirm Revocation
                                                                        </h5>
                                                                        <button type="button"
                                                                            class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to
                                                                        revoke
                                                                        this access code?
                                                                        It's currently assigned to a
                                                                        school:
                                                                        <b>{{ $item->schoolName->name ?? '-' }}</b>.
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">No</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Yes,
                                                                            Revoke</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $mittAccessCode->appends(
                                array_merge(request()->query(), [
                                'active_tab' => 'mittlense',
                                'per_page' => request('per_page', Cookie::get('perPage')),
                                ]),
                                )->links('pagination::bootstrap-4') !!}
                            </div>
                            {{-- <div class="d-flex justify-content-right text-right">
                                            {!! $mittAccessCode->appends(array_merge(request()->query(), ['active_tab' => 'mittlense']))->links('pagination::bootstrap-4') !!} </div> --}}
                        </div>
                        <div class="tab-pane fade {{ $activeTab === 'schools_with_lens' ? 'show active' : '' }}"
                            id="schools-with-lens-Tab" role="tabpanel" aria-labelledby="schoolsWithLensTab">

                            <div class="table-responsive tbleDiv mt-3">
                                <table class="table table-striped table-bordered" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>School ID</th>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>RM Name</th>
                                            <th>RM Details</th>
                                            <th class="text-center">MittLens</th>
                                            <th class="text-center">TeachLite</th>
                                            <th>Action</th>
                                            <th>SMS Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($schoolsWithLens as $i => $school)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $school['id'] }}</td>
                                            <td>{{ $school['name'] }}</td>
                                            <td>{{ $school['contact'] }}
                                                <br>
                                                <small
                                                    class="text-muted">{{ $school['email'] }}</small>
                                            </td>
                                            <td>{{ $school['rm_name'] }}</td>
                                            <td>
                                                {{ $school['rm_phone'] }}<br>
                                                <small
                                                    class="text-muted">{{ $school['rm_email'] }}</small>
                                            </td>
                                            <td class="text-center">
                                                @if ($school['mitt_count'] > 0)
                                                {{ $school['mitt_count'] }}
                                                @else
                                                <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($school['teach_count'] > 0)
                                                {{ $school['teach_count'] }}
                                                @else
                                                <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary send-lens-sms-btn"
                                                    data-school-id="{{ $school['school_id'] }}"
                                                    data-already-sent="{{ in_array($school['school_id'], $lensSentSchoolIds) ? '1' : '0' }}">
                                                    Send SMS
                                                </button>
                                            </td>
                                            <td>
                                                @if (in_array($school['school_id'], $lensSentSchoolIds))
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i> SMS Already Sent
                                                </span>
                                                @else
                                                <span class="badge bg-warning text-dark">
                                                    Not Sent
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center text-muted py-3">No
                                                schools with lens found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $schoolsWithLens->appends(
                                array_merge(request()->query(), [
                                'active_tab' => 'schools_with_lens',
                                'per_page' => request('per_page', Cookie::get('perPage')),
                                ]),
                                )->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<div class="modal fade" id="accessCodeInfoModal" tabindex="-1" aria-labelledby="accessCodeInfoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accessCodeInfoModalLabel">Access Code Info</h5>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeAssignModal()">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <p>Loading...</p> <!-- Placeholder for dynamic content -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Email Input -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            {{ Form::open(['role' => 'form', 'route' => ['access.code.send', ['type' => 'mail'] + request()->query()], 'class' => 'forms-sample', 'files' => true, 'autocomplete' => 'off']) }}
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="emailModalLabel">Enter Email IDs
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="emailInput" class="form-label">Email IDs (Separate with commas):</label>
                <input type="textarea" name="email" id="emailInput" class="form-control" required
                    placeholder="Enter Email IDs (comma-separated)">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="submitEmails()">Send Mail</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<!-- Modal for mobile number Input -->
<div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{ Form::open(['role' => 'form', 'route' => ['access.code.send', ['type' => 'sms'] + request()->query()], 'class' => 'forms-sample', 'files' => true, 'autocomplete' => 'off']) }}
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="smsModalLabel">Enter Mobile Number
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="mobileInput" class="form-label">Mobile Number (Separate with commas):</label>
                <input type="textarea" name="mobile_number" id="mobileInput" class="form-control" required
                    placeholder="Enter Mobile Number (comma-separated)">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="submitEmails()">Send SMS</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Assign Access Codes
                </h5>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeAssignModal()">
                    <span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form id="assignForm" method="post" action="{{ route('assign.to.school') }}">
                    @csrf
                    <input type="hidden" name="access_codes" id="selectedAccessCodes">

                    <div class="row mt-2">
                        <div class=" col-md-6 form-group">
                            {!! Form::label('state', 'State', ['class' => 'form-label required']) !!}
                            {{ Form::select('state', $states, null, [
                                    'class' => 'form-select',
                                    'placeholder' => '--Select--',
                                    'id' => 'state-select',
                                    'required',
                                ]) }}
                        </div>

                        <div class=" col-md-6 form-group">
                            {!! Form::label('city', 'District', ['class' => 'form-label ']) !!}
                            {{ Form::select('city', [], null, [
                                    'class' => 'form-select',
                                    'placeholder' => '--Select--',
                                    'id' => 'city-select',
                                ]) }}
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        {!! Form::label('school_id', 'Select School', ['class' => 'form-label required']) !!}
                        {{ Form::select('school_id', [], null, [
                                'class' => 'form-select',
                                'placeholder' => '--Select--',
                                'id' => 'school-select',
                                'required',
                            ]) }}
                    </div>
                    <div class="modal-footer mt-3 mb-2">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Tab URL sync
        document.querySelector('#schoolsWithLensTab')?.addEventListener('click', function() {
            const url = new URL(window.location);
            url.searchParams.set('active_tab', 'schools_with_lens');
            window.history.pushState({}, '', url);
        });

        // Send SMS
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.send-lens-sms-btn');
            if (!btn) return;

            const schoolId = btn.getAttribute('data-school-id');
            const alreadySent = btn.getAttribute('data-already-sent') === '1';
            const tdEl = btn.closest('td');

            if (alreadySent) {
                if (!confirm('SMS was already sent to this school. Do you want to resend?')) return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';

            fetch(`/admin/access-code/send-lens-sms/${schoolId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Update TD to show sent badge + resend
                        tdEl.innerHTML = `
                    <span class="badge bg-success d-block mb-1">
                        <i class="bi bi-check-circle me-1"></i> SMS Sent
                    </span>
                    <button class="btn btn-sm btn-outline-primary w-100 send-lens-sms-btn"
                        data-school-id="${schoolId}"
                        data-already-sent="1">
                        <i class="bi bi-arrow-repeat me-1"></i> Resend
                    </button>
                `;
                    } else {
                        alert('Failed: ' + data.message);
                        btn.disabled = false;
                        btn.innerHTML = ' Send SMS';
                    }
                })
                .catch(() => {
                    alert('Something went wrong. Please try again.');
                    btn.disabled = false;
                    btn.innerHTML = ' Send SMS';
                });
        });

    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get active tab from URL parameter or use default
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('active_tab') || 'teachlite';

        // Activate the appropriate tab
        if (activeTab === 'mittlense') {
            // Remove active class from teachlite tab and add to mittlense
            document.querySelector('#teachliteTab').classList.remove('active');
            document.querySelector('#mittlenseTab').classList.add('active');

            document.querySelector('#teachlite-Tab').classList.remove('show', 'active');
            document.querySelector('#mittlense-Tab').classList.add('show', 'active');
        }

        // Update tab click handlers to maintain the active_tab parameter
        document.querySelectorAll('.nav-tabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                const tabId = this.id.replace('Tab', '');
                const activeTab = tabId === 'mittlense' ? 'mittlense' : 'teachlite';

                // Update URL with active_tab parameter without reloading
                const url = new URL(window.location);
                url.searchParams.set('active_tab', activeTab);
                window.history.pushState({}, '', url);
            });
        });
    });
</script>
<script>
    function closeAssignModal() {
        $('.modal').modal('hide');
        $('.modal').removeAttr('aria-hidden'); // Remove aria-hidden attribute
    }
</script>
<script>
    // city select
    $(document).ready(function() {
        function loadDropdown(url, selectId, preSelectedId) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $(selectId).html('<option value="">Select</option>');
                    if (data && Object.keys(data).length) {
                        $.each(data, function(id, name) {
                            var isSelected = (id == preSelectedId) ? 'selected' : '';
                            $(selectId).append(
                                `<option value="${id}" ${isSelected}>${name}</option>`);
                        });
                    } else {
                        $(selectId).html('<option value="">No Data</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error loading data:", error);
                }
            });
        }

        $('#state-select').on('change', function() {
            var stateId = $(this).val();
            if (stateId) {
                var dtata = loadDropdown("{{ route('sp.getCities', ':state') }}".replace(':state',
                        stateId),
                    '#city-select');
                loadDropdown("{{ route('sp.getSchools', ['state' => ':state', 'city' => ':city']) }}"
                    .replace(':state', stateId).replace(':city', ''), '#school-select');
            } else {
                $('#city-select, #school-select').html('<option value="">Select</option>');
            }
        });

        $('#city-select').on('change', function() {
            var stateId = $('#state-select').val();
            var cityId = $(this).val();
            if (stateId && cityId) {
                loadDropdown("{{ route('sp.getSchools', ['state' => ':state', 'city' => ':city']) }}"
                    .replace(':state', stateId).replace(':city', cityId), '#school-select');
            } else {
                $('#school-select').html('<option value="">Select</option>');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mittSelectAllCheckbox = document.getElementById('mittSelectAll');
        const techselectAllCheckbox = document.getElementById('techselectAll');
        const techrowCheckboxes = document.querySelectorAll('.techrow-checkbox');
        const mittrowCheckboxes = document.querySelectorAll('.mittrow-checkbox');
        const assignForm = document.getElementById('assignForm');
        const selectedAccessCodesInput = document.getElementById('selectedAccessCodes');

        let selectedIds = new Set();

        // Toggle "Select All" for Mitt
        mittSelectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            mittrowCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                if (isChecked) selectedIds.add(checkbox.value);
                else selectedIds.delete(checkbox.value);
            });
            toggleExportDropdown();
        });

        // Toggle "Select All" for Tech
        techselectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            techrowCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                if (isChecked) selectedIds.add(checkbox.value);
                else selectedIds.delete(checkbox.value);
            });
            toggleExportDropdown();
        });

        // Handle individual checkbox changes (Tech)
        techrowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) selectedIds.add(this.value);
                else {
                    selectedIds.delete(this.value);
                    techselectAllCheckbox.checked = false;
                }
                toggleExportDropdown();
            });
        });

        // Handle individual checkbox changes (Mitt)
        mittrowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) selectedIds.add(this.value);
                else {
                    selectedIds.delete(this.value);
                    mittSelectAllCheckbox.checked = false;
                }
                toggleExportDropdown();
            });
        });

        const exportForm = document.getElementById('exportForm');
        const selectedIdsInput = document.getElementById('selectedIds');
        const exportTypeInput = document.getElementById('exportType');

        // Handle export button clicks
        document.querySelectorAll('.export-option').forEach(option => {
            option.addEventListener('click', function() {
                const exportType = this.getAttribute('data-type');

                // Set the form values
                selectedIdsInput.value = Array.from(selectedIds).join(',');
                exportTypeInput.value = exportType;

                // Submit the form
                exportForm.submit();
            });
        });

        // Handle form submission
        assignForm.addEventListener('submit', function(e) {
            const manuallySelectedIds = Array.from(selectedIds);

            if (manuallySelectedIds.length === 0) {
                alert('Please select at least one access code.');
                e.preventDefault();
                return false;
            }

            // Always send the comma-separated list of selected IDs
            selectedAccessCodesInput.value = manuallySelectedIds.join(',');
            return true;
        });

        // Show/hide export dropdown based on selection
        function toggleExportDropdown() {
            const exportDropdownContainer = document.getElementById('exportDropdownContainer');
            if (selectedIds.size > 0) {
                exportDropdownContainer.classList.remove('d-none');
            } else {
                exportDropdownContainer.classList.add('d-none');
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('accessCodeInfoModal'));

        document.querySelectorAll('#accessCodeInfo').forEach(button => {
            button.addEventListener('click', function() {
                const accessCodeId = this.getAttribute('data-id');
                const modalBody = document.querySelector(
                    '#accessCodeInfoModal .modal-body .row');

                // Show loading text
                modalBody.innerHTML = '<p>Loading...</p>';
                modal.show();

                // Fetch data from the server
                fetch(`/admin/access-code-info/${accessCodeId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update modal content
                        modalBody.innerHTML = `
                            <div class="col-md-6">
                                <p><strong>Licence Key:</strong> ${data.licence_key}</p>
                                <p><strong>Ip:</strong> ${data.ip}</p>
                                <p><strong>Device Id:</strong> ${data.device_id}</p>
                                <p><strong>Activation Date:</strong> ${data.activation_date}</p>
                                <p><strong>Activation Updated At:</strong> ${data.activation_updatedAt}</p>
                                <p><strong>Org Id:</strong> ${data.org_id}</p>
                                <p><strong>Activation Limit:</strong> ${data.activation_limit}</p>
                                <p><strong>Licence Expiry:</strong> ${data.licence_expiry}</p>
                                <p><strong>Content Bundle:</strong> ${data.content_bundle}</p>
                                <p><strong>Content Bundle Id:</strong> ${data.content_bundle_id}</p>
                                <p><strong>Notes:</strong> ${data.notes}</p>
                                <p><strong>Config:</strong> ${data.config}</p>
                                
                                </div>
                            <div class="col-md-6">
                                <p><strong>Request By:</strong> ${data.requestBy}</p>
                                <p><strong>Request Team:</strong> ${data.requestTeam}</p>
                                <p><strong>Request Person Name:</strong> ${data.requestPersonName}</p>
                                <p><strong>Customer Name:</strong> ${data.customerName}</p>
                                <p><strong>Platform:</strong> ${data.platform}</p>
                                <p><strong>Board:</strong> ${data.board}</p>
                                <p><strong>Grades:</strong> ${data.grades}</p>
                                <p><strong>Resolution:</strong> ${data.resolution}</p>
                                <p><strong>License Created At:</strong> ${data.license_createdAt}</p>
                                <p><strong>License Updated At:</strong> ${data.license_updatedAt}</p>
                                <p><strong>Type:</strong> ${data.type}</p>
                                <p><strong>Created By:</strong> ${data.created_by}</p>
                            </div>
                        `;
                    })
                    .catch(error => {
                        modalBody.innerHTML =
                            '<p>Error loading data. Please try again later.</p>';
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Tagify for emails
        var input = document.getElementById('emailInput');
        var tagify = new Tagify(input, {
            delimiters: ",",
            pattern: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/, // email validation
        });

        input.form.addEventListener('submit', function() {
            var emails = tagify.value.map(function(tag) {
                return tag.value;
            }).join(',');
            input.value = emails;
        });

        // Initialize Tagify for mobile numbers
        var input = document.getElementById('mobileInput');
        var tagify = new Tagify(input, {
            delimiters: ",",
            pattern: /^[1-9][0-9]{9}$/,
        });

        // Ensure input is correctly formatted for form submission
        input.form.addEventListener('submit', function() {
            var mobileNumbers = tagify.value.map(function(tag) {
                return tag.value;
            }).join(',');
            input.value = mobileNumbers; // Set the input value to the list of numbers
        });
    });
</script>
<script>
    function filterAccessCodes() {
        const bookSeriesId = document.getElementById('bookSeiresFilter').value;
        const url = new URL(window.location.href);
        url.searchParams.set('book_series', bookSeriesId);
        window.location.href = url.toString();
    }
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const bookSeriesId = urlParams.get("book_series"); // Extract 'book_series' parameter from URL

        const bookSeriesFilter = document.getElementById("bookSeriesFilter");
        if (bookSeriesId) {
            bookSeriesFilter.value = bookSeriesId; // Set the dropdown to the corresponding value
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paginationSelect = document.getElementById('paginationSelect');

        // Get the per_page value from URL or cookie
        const urlParams = new URLSearchParams(window.location.search);
        const urlPerPage = urlParams.get('per_page');
        const savedPerPage = getCookie('perPage');

        // Determine which value to use (URL param takes precedence)
        const perPage = urlPerPage || savedPerPage;

        // Set the select value if we have a valid value
        if (perPage && paginationSelect.querySelector(`option[value="${perPage}"]`)) {
            paginationSelect.value = perPage;
        }

        // Handle pagination change
        paginationSelect.addEventListener('change', function() {
            const perPage = this.value;
            // Save to cookie
            document.cookie = `perPage=${perPage}; path=/; max-age=${30*24*60*60}`;

            // Update URL and reload
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    });
</script>
@endsection