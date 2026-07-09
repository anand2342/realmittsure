@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Schools List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Schools List</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if (request('crm_removed') == '1')
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="crmRemovedAlert">
                        School removed successfully.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <script>
                        setTimeout(() => {
                            const el = document.getElementById('crmRemovedAlert');
                            if (el) {
                                el.classList.remove('show');
                                setTimeout(() => el.remove(), 300);
                            }
                        }, 4000);
                    </script>
                @endif
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('school.list') }}">
                            <div class="row">

                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by School Name"
                                        name="school_name" value="{{ request('school_name') }}">
                                </div>
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Unique Id"
                                        name="unique_id" value="{{ request('unique_id') }}">
                                </div>
                                <div class="col mb-3">
                                    <select name="state_id" id="state-select" class="form-control">
                                        <option value="">Search by State</option>
                                        @foreach ($states as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ request('state_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <select name="district_id" id="district-select" class="form-control">
                                        <option value="">Search by District</option>
                                        @foreach ($cities as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ request('district_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('school.list') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="card-title">All Schools</div>
                            <div class="d-flex align-items-center">
                                <label for="roles" class="me-2">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>
                                        --Select--</option>
                                    @foreach ([10, 20, 30, 40, 50] as $option)
                                        <option value="{{ $option }}"
                                            {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                {{--  @isPermission('online.class.logs')  --}}
                                <a class="btn btn-success btn-sm ms-2 text-nowrap" href="{{ route('all-school-export') }}">
                                    Export All Schools </a>
                                {{--  @endisPermission  --}}
                            </div>
                        </div>
                        <hr class="formdivider">

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs nav-tabs-bordered" id="schoolTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'PartnerSchools' ? 'active' : '' }}"
                                    id="partner-tab" data-bs-toggle="tab" data-bs-target="#partner-schools" type="button"
                                    role="tab" aria-controls="partner-schools"
                                    aria-selected="{{ $activeTab == 'PartnerSchools' ? 'true' : 'false' }}">
                                    Partner Schools
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'NonPartnerSchools' ? 'active' : '' }}"
                                    id="non-partner-tab" data-bs-toggle="tab" data-bs-target="#non-partner-schools"
                                    type="button" role="tab" aria-controls="non-partner-schools"
                                    aria-selected="{{ $activeTab == 'NonPartnerSchools' ? 'true' : 'false' }}">
                                    Non-Partner Schools
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab == 'CrmSchools' ? 'active' : '' }}" id="crm-tab"
                                    data-bs-toggle="tab" data-bs-target="#crm-schools" type="button" role="tab"
                                    aria-controls="crm-schools"
                                    aria-selected="{{ $activeTab == 'CrmSchools' ? 'true' : 'false' }}">
                                    Schools From CRM
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content pt-2" id="schoolTabsContent">
                            <!-- Partner Schools Tab -->
                            <div class="tab-pane fade {{ $activeTab == 'PartnerSchools' ? 'show active' : '' }}"
                                id="partner-schools" role="tabpanel" aria-labelledby="partner-tab">
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>Name</b></th>
                                                <th><b>Unique ID</b></th>
                                                <th><b>Approved By</b></th>
                                                <th><b>Status</b></th>
                                                <th>View</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($partnerSchools) && $partnerSchools->count() > 0)
                                                @foreach ($partnerSchools as $item)
                                                    <tr>
                                                        <td>{{ $partnerSchools->currentPage() * $partnerSchools->perPage() - $partnerSchools->perPage() + $loop->iteration . '.' }}
                                                        </td>
                                                        <td>{{ $item->name ?? '' }}</td>
                                                        <td>{{ $item->unique_id ?? '' }}</td>
                                                        <td>{{ $item->userSchool->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge text-success">
                                                                Verified
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary btn-sm me-2"
                                                                href="{{ route('school.users', ['school_id' => $item->id]) }}">
                                                                Users
                                                            </a>
                                                            @isPermission('online.class.logs')
                                                                <a class="btn btn-primary btn-sm me-2"
                                                                    href="{{ route('online.class.logs', ['school_id' => $item->id]) }}">
                                                                    Online Classes</a>
                                                            @endisPermission

                                                            @isPermission('folder.index')
                                                                <a class="btn btn-primary btn-sm me-2"
                                                                    href="{{ route('folder.index', ['user_id' => $item->user_id]) }}">
                                                                    Uploaded Content
                                                                </a>
                                                            @endisPermission
                                                        </td>
                                                        <td>
                                                            @if ($item->user)
                                                                <a class="btn btn-sm statusBtn {{ $item->user->status == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                    onclick="confirmStatus('{{ route('user.active.inactive', $item->user->id) }}')">
                                                                    {{ $item->user->status == 1 ? 'Active' : 'Inactive' }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">No user</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No Partner Schools found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $partnerSchools->appends(array_merge(request()->query(), ['active_tab' => 'PartnerSchools']))->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>

                            <!-- Non-Partner Schools Tab -->
                            <div class="tab-pane fade {{ $activeTab == 'NonPartnerSchools' ? 'show active' : '' }}"
                                id="non-partner-schools" role="tabpanel" aria-labelledby="non-partner-tab">
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>Name</b></th>
                                                <th><b>Status</b></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($nonPartnerSchools) && $nonPartnerSchools->count() > 0)
                                                @foreach ($nonPartnerSchools as $item)
                                                    <tr>
                                                        <td>{{ $nonPartnerSchools->currentPage() * $nonPartnerSchools->perPage() - $nonPartnerSchools->perPage() + $loop->iteration . '.' }}
                                                        </td>
                                                        <td>{{ $item->name ?? '' }}</td>
                                                        <td>
                                                            <span class="badge text-danger">
                                                                Unverified
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-primary"
                                                                href="{{ route('user.edit', ['id' => $item->user_id, 'verify' => 'verifySchool']) }}"
                                                                title="Verify">
                                                                Verify
                                                            </a>
                                                        </td>
                                                        {{-- <td>
                                                            @if ($item->user)
                                                                <a class="btn btn-sm statusBtn {{ $item->user->status == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                    onclick="confirmStatus('{{ route('user.active.inactive', $item->user->id) }}')">
                                                                    {{ $item->user->status == 1 ? 'Active' : 'Inactive' }}
                                                                </a>
                                                            @else
                                                                <span class="text-muted">No user</span>
                                                            @endif
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No Non-Partner Schools found
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $nonPartnerSchools->appends(array_merge(request()->query(), ['active_tab' => 'NonPartnerSchools']))->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>

                            <!-- From CRM Schools Tab -->
                            <div class="tab-pane fade {{ $activeTab == 'CrmSchools' ? 'show active' : '' }}"
                                id="crm-schools" role="tabpanel" aria-labelledby="crm-tab">
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>Order ID</b></th>
                                                <th><b>Name</b></th>
                                                <th><b>Email</b></th>
                                                <th><b>Mobile</b></th>
                                                <th><b>Series Name</b></th>
                                                <th><b>RM Name</b></th>
                                                <th><b>RM Mobile</b></th>
                                                <th>Action
                                                    <a class="btn btn-success btn-sm ms-2 text-nowrap"
                                                        href="{{ route('crm.sms.logs') }}">
                                                        CRM SMS Logs </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (isset($crmSchools) && $crmSchools->count() > 0)
                                                @foreach ($crmSchools as $item)
                                                    @php
                                                        $rmDetail = \App\Models\User::where(
                                                            'id',
                                                            $item->user_additional_details->assign_to ?? null,
                                                        )->first();

                                                        // Fetch unique series names assigned to this school
                                                        $assignedSeries = \App\Models\SchoolAssignedDigitalContent::where(
                                                            'school_id',
                                                            $item->user_id,
                                                        )
                                                            ->join(
                                                                'book_series',
                                                                'book_series.id',
                                                                '=',
                                                                'school_assigned_digital_contents.series_id',
                                                            )
                                                            ->select('book_series.name')
                                                            ->distinct()
                                                            ->pluck('book_series.name');

                                                        // Chip colors cycling through a fixed palette
                                                        $chipColors = [
                                                            [
                                                                'bg' => '#E1F5EE',
                                                                'border' => '#0F6E56',
                                                                'text' => '#085041',
                                                            ], // teal
                                                            [
                                                                'bg' => '#EEEDFE',
                                                                'border' => '#534AB7',
                                                                'text' => '#3C3489',
                                                            ], // purple
                                                            [
                                                                'bg' => '#FAEEDA',
                                                                'border' => '#854F0B',
                                                                'text' => '#633806',
                                                            ], // amber
                                                            [
                                                                'bg' => '#E6F1FB',
                                                                'border' => '#185FA5',
                                                                'text' => '#0C447C',
                                                            ], // blue
                                                            [
                                                                'bg' => '#FAECE7',
                                                                'border' => '#993C1D',
                                                                'text' => '#712B13',
                                                            ], // coral
                                                            [
                                                                'bg' => '#EAF3DE',
                                                                'border' => '#3B6D11',
                                                                'text' => '#27500A',
                                                            ], // green
                                                        ];
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {{ $crmSchools->currentPage() * $crmSchools->perPage() - $crmSchools->perPage() + $loop->iteration . '.' }}
                                                        </td>
                                                        <td>{{ $item->user->soid ?? '' }}</td>
                                                        <td>{{ $item->name ?? '' }}</td>
                                                        <td>{{ $item->user->email ?? '' }}</td>
                                                        <td>{{ $item->user->mobile_no ?? '' }}</td>

                                                        {{-- Series Name Chips --}}
                                                        <td>
                                                            @if ($assignedSeries->isNotEmpty())
                                                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                                                    @foreach ($assignedSeries as $index => $seriesName)
                                                                        @php
                                                                            $chip =
                                                                                $chipColors[
                                                                                    $index % count($chipColors)
                                                                                ];
                                                                        @endphp
                                                                        <span
                                                                            style="display: inline-block;padding: 2px 10px;border-radius: 999px;border: 1px solid {{ $chip['border'] }};background: {{ $chip['bg'] }};
                                                                            color: {{ $chip['text'] }};font-size: 12px;font-weight: 500;white-space: nowrap;">
                                                                            {{ $seriesName }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted" style="font-size: 12px;">—</span>
                                                            @endif
                                                        </td>

                                                        <td>{{ $rmDetail->name ?? '' }}</td>
                                                        <td>{{ $rmDetail->mobile_no ?? '' }}</td>

                                                        <td>
                                                            @if (
                                                                $item->user &&
                                                                    ((filled($item->user->email) && $item->user->email !== 'N/A') ||
                                                                        (filled($item->user->mobile_no) && $item->user->mobile_no !== 'N/A')))
                                                                <button class="btn btn-sm btn-success me-1"
                                                                    onclick="confirmVerifySchool('{{ route('crm.school.verify', $item->id) }}','{{ addslashes($item->name) }}')">
                                                                    Activate
                                                                </button>
                                                            @else
                                                                <a class="btn btn-sm btn-primary me-1"
                                                                    href="{{ route('user.edit', ['id' => $item->user_id, 'verify' => 'verifySchool']) }}"
                                                                    title="Verify">
                                                                    Verify
                                                                </a>
                                                                <button class="btn btn-sm btn-warning"
                                                                    onclick="confirmSmsToRM('{{ route('crm.school.sms.rm', $item->id) }}','{{ addslashes($item->name) }}' )"
                                                                    title="Send SMS to RM For School Details">
                                                                    Send SMS to RM
                                                                </button>
                                                            @endif
                                                            {{-- Remove button (soft-delete with remark) --}}
                                                            @if (Auth::user()->is_admin = 2)
                                                                <button class="btn btn-sm btn-danger"
                                                                    onclick="openRemoveModal({{ $item->id }},'{{ addslashes($item->name) }}')">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center">No CRM Schools found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $crmSchools->appends(array_merge(request()->query(), ['active_tab' => 'CrmSchools']))->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ══════════════════════════════════════════════════════════════════════
     Remove Modal  –  asks for a remark (min 4 words) before soft-delete
     ══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="removeCrmSchoolModal" tabindex="-1" aria-labelledby="removeCrmSchoolModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="removeCrmSchoolModalLabel">
                        <i class="fas fa-trash-alt me-2"></i>Remove School
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-1 text-muted" style="font-size:13px;">You are about to remove:</p>
                    <p class="fw-semibold mb-3" id="removeSchoolNameLabel" style="font-size:15px;"></p>

                    <label for="removeRemark" class="form-label" style="font-size:13px;font-weight:500;">
                        Reason for removal <span class="text-danger">*</span>
                        <small class="text-muted fw-normal ms-1">(minimum 4 words)</small>
                    </label>
                    <textarea id="removeRemark" class="form-control" rows="3" maxlength="500"
                        placeholder="e.g. Duplicate entry created by CRM team by mistake" oninput="crmRemoveValidate()"
                        style="font-size:13px;resize:vertical;"></textarea>

                    <div class="d-flex justify-content-between mt-1">
                        <span id="remarkWordError" class="text-danger" style="font-size:12px;display:none;">
                            Please enter at least 4 words.
                        </span>
                        <span id="remarkWordCount" class="text-muted ms-auto" style="font-size:11px;">0 words</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-danger" id="confirmRemoveBtn" disabled
                        onclick="submitCrmRemove()">
                        <span id="confirmRemoveBtnText">Confirm Remove</span>
                        <span id="confirmRemoveSpinner" class="spinner-border spinner-border-sm ms-1 d-none"
                            role="status"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>


    <script>
        (function() {
            // ── state ────────────────────────────────────────────────────────────────
            let _schoolId = null;
            let _schoolName = '';
            let _modalInst = null;

            // ── open ─────────────────────────────────────────────────────────────────
            window.openRemoveModal = function(schoolId, schoolName) {
                _schoolId = schoolId;
                _schoolName = schoolName;

                document.getElementById('removeSchoolNameLabel').textContent = schoolName;
                document.getElementById('removeRemark').value = '';
                document.getElementById('remarkWordError').style.display = 'none';
                document.getElementById('remarkWordCount').textContent = '0 words';
                document.getElementById('confirmRemoveBtn').disabled = true;
                document.getElementById('confirmRemoveBtnText').textContent = 'Confirm Remove';
                document.getElementById('confirmRemoveSpinner').classList.add('d-none');

                _modalInst = new bootstrap.Modal(document.getElementById('removeCrmSchoolModal'));
                _modalInst.show();
            };

            // ── live validation ───────────────────────────────────────────────────────
            window.crmRemoveValidate = function() {
                const raw = document.getElementById('removeRemark').value.trim();
                const words = raw === '' ? [] : raw.split(/\s+/).filter(w => w.length > 0);
                const wordCount = words.length;
                const valid = wordCount >= 4;

                document.getElementById('remarkWordCount').textContent = wordCount + ' word' + (wordCount === 1 ?
                    '' : 's');
                document.getElementById('confirmRemoveBtn').disabled = !valid;
                document.getElementById('remarkWordError').style.display = (raw.length > 0 && !valid) ? 'block' :
                    'none';
            };

            // ── submit ────────────────────────────────────────────────────────────────
            window.submitCrmRemove = function() {
                const remark = document.getElementById('removeRemark').value.trim();
                const wordCount = remark === '' ? 0 : remark.split(/\s+/).filter(w => w.length > 0).length;

                if (wordCount < 4) {
                    document.getElementById('remarkWordError').style.display = 'block';
                    return;
                }

                // Show spinner, disable button
                document.getElementById('confirmRemoveBtn').disabled = true;
                document.getElementById('confirmRemoveBtnText').textContent = 'Removing…';
                document.getElementById('confirmRemoveSpinner').classList.remove('d-none');

                fetch('{{ route('crm.school.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            school_id: _schoolId,
                            remark: remark
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            _modalInst.hide();
                            const row = document.getElementById('crm-school-row-' + _schoolId);
                            if (row) {
                                row.style.transition = 'opacity .3s';
                                row.style.opacity = '0';
                            }
                            // Reload after fade, landing on CrmSchools tab
                            setTimeout(() => {
                                const url = new URL(window.location.href);
                                url.searchParams.set('active_tab', 'CrmSchools');
                                url.searchParams.set('crm_removed', '1');
                                window.location.href = url.toString();
                            }, 200);
                        } else {
                            alert(data.message ?? 'Something went wrong. Please try again.');
                            document.getElementById('confirmRemoveBtn').disabled = false;
                            document.getElementById('confirmRemoveBtnText').textContent = 'Confirm Remove';
                            document.getElementById('confirmRemoveSpinner').classList.add('d-none');
                        }
                    })
                    .catch(() => {
                        alert('Request failed. Please check your connection and try again.');
                        document.getElementById('confirmRemoveBtn').disabled = false;
                        document.getElementById('confirmRemoveBtnText').textContent = 'Confirm Remove';
                        document.getElementById('confirmRemoveSpinner').classList.add('d-none');
                    });
            };
        })();
    </script>
@endsection

@push('scripts')
    <script>
        function confirmVerifySchool(url, schoolName) {
            if (confirm('Are you sure you want to verify "' + schoolName + '"? This will mark the school as verified')) {
                window.location.href = url;
            }
        }

        function confirmSmsToRM(url, schoolName) {
            if (confirm('Send SMS to RM for "' + schoolName + '"? The RM will be notified to provide contact details.')) {
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'SMS sent to RM successfully.');
                        } else {
                            alert(data.message || 'Failed to send SMS.');
                        }
                    })
                    .catch(() => alert('Something went wrong. Please try again.'));
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            // Handle state change and update districts dropdown
            $('#state-select').on('change', function() {
                var stateId = $(this).val();
                $('#district-select').html('<option value="">Search by District</option>');

                if (stateId) {
                    var url = "{{ route('school.getCities', ':state') }}".replace(':state', stateId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data && Object.keys(data).length > 0) {
                                $.each(data, function(id, name) {
                                    $('#district-select').append('<option value="' +
                                        id + '">' + name + '</option>');
                                });
                            } else {
                                $('#district-select').html(
                                    '<option value="">No districts available</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading districts:', error);
                            $('#district-select').html(
                                '<option value="">Error loading districts</option>');
                        }
                    });
                }
            });
        });
    </script>
@endpush
