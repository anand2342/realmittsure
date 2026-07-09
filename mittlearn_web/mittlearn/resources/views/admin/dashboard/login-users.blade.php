@extends('admin.layouts.master')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@section('content')
    <div class="pagetitle">
        <h1>Login Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Login Users</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @php
                            $roleName = App\Models\Role::where('role_slug', request('role'))->value('role_name');
                        @endphp
                        {{-- Header Row with Title and Filter --}}
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="card-title m-0">
                                {{ $type === 'live' ? 'Live Sessions' : 'Logged In' }} {{ $roleName }}
                            </h5>
                            @if ($type !== 'live')
                                <form method="GET"
                                    action="{{ route('login.users.view', ['role' => $role, 'type' => $type]) }}"
                                    id="filterForm" class="d-flex flex-wrap gap-2">
                                    @php
                                        $today = now()->format('Y-m-d');
                                    @endphp
                                    <input type="text" name="date_range" id="dateRange" class="form-control"
                                        placeholder="{{ $today . ' to ' . $today }}"
                                        value="{{ request('start_date') && request('end_date') ? request('start_date') . ' to ' . request('end_date') : '' }}"
                                        autocomplete="off" style="min-width: 220px;">

                                    <input type="hidden" name="start_date" id="startDate"
                                        value="{{ request('start_date') }}">
                                    <input type="hidden" name="end_date" id="endDate" value="{{ request('end_date') }}">

                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('login.users.view', ['role' => $role]) }}"
                                        class="btn btn-secondary">Clear</a>
                                </form>
                            @endif
                        </div>

                        <hr class="formdivider">

                        {{-- Table --}}
                        <div class="table-responsive tbleDiv">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Name</th>
                                        @if ($role === 'school_admin')
                                            <th>District</th>
                                            <th>State</th>
                                        @endif
                                        @if (in_array($role, ['school_teacher', 'school_student']))
                                            <th>School Name</th>
                                        @endif
                                        @if ($role === 'd2c_user')
                                            <th>Category Name</th>
                                        @endif
                                        <th>Login At</th>
                                        <th>Logout At</th>
                                        <th>IP Address</th>
                                        <th>From</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logins as $index => $log)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                {{ optional($log->user)->name ?? 'Not Found' }}
                                                @if ($role === 'school_admin' && optional($log->schools)->postal_code)
                                                    - {{ $log->schools->postal_code }}
                                                @endif
                                            </td>

                                            @if ($role === 'school_admin')
                                                <td>{{ optional($log->district)->city ?? 'NA' }}</td>
                                                <td>{{ optional($log->state)->name ?? 'NA' }}</td>
                                            @endif

                                            @if (in_array($role, ['school_teacher', 'school_student']))
                                                <td>
                                                    {{ optional($log->schoolName)->name ?? 'NA' }}
                                                    @if (optional($log->schoolName)->postal_code)
                                                        - {{ $log->schoolName->postal_code }}
                                                    @endif
                                                </td>
                                            @endif

                                            @if ($role === 'd2c_user')
                                                <td>{{ optional($log->category)->name ?? 'NA' }}</td>
                                            @endif

                                            <td>{{ $log->login_at ? \Carbon\Carbon::parse($log->login_at)->format('d M Y h:i A') : 'NA' }}
                                            </td>
                                            <td>{{ $log->logout_at ? \Carbon\Carbon::parse($log->logout_at)->format('d M Y h:i A') : 'NA' }}
                                            </td>
                                            <td>{{ $log->ip_address ?? 'NA' }}</td>
                                            <td>{{ ucfirst($log->platform ?? 'NA') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No users found for this role
                                                and date.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dateRange').daterangepicker({
                autoUpdateInput: false,
                showDropdowns: true,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Week': [moment().startOf('week'), moment().endOf('week')],
                    'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week')
                        .endOf('week')
                    ],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Last 12 Months': [moment().subtract(11, 'months').startOf('month'), moment().endOf(
                        'month')],
                    'Year to Date': [moment().startOf('year'), moment()]
                },
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear',
                    applyLabel: 'Apply',
                    customRangeLabel: "Custom Range"
                }
            });

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
                $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                $('#startDate').val('');
                $('#endDate').val('');
            });
        });
    </script>
@endsection
