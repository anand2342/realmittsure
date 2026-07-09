@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>CRM Automation Dashboard</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary text-center" title="Go Back">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
            @php
                $now = \Carbon\Carbon::now('Asia/Kolkata');
                $hour = $now->hour;

                // Show between 8 PM (20) to 8 AM (8)
                $showButton = $hour >= 20 || $hour < 8;
            @endphp

            @if (Auth::user()->is_admin == 1 && $showButton)
                <a href="{{ route('crm.automation.log') }}" class="btn btn-outline-secondary text-center" title="Go Back">
                    <span>Log</span> <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>
    </div>
    <section class="section dashboard mt-1">
        {{-- ── STAT BOXES ─────────────────────────────────────────────────── --}}
        @php
            $boxes = [
                ['key' => 'total', 'label' => 'CRM Fetched Schools', 'icon' => 'bi-building', 'color' => '#4472C4'],
                [
                    'key' => 'activated',
                    'label' => 'Activated Schools',
                    'icon' => 'bi-check-circle',
                    'color' => '#70AD47',
                ],
                ['key' => 'not_activated', 'label' => 'Not Activated', 'icon' => 'bi-x-circle', 'color' => '#FF0000'],
                [
                    'key' => 'logged_once',
                    'label' => 'Logged In Once',
                    'icon' => 'bi-person-check',
                    'color' => '#ED7D31',
                ],
                ['key' => 'not_logged', 'label' => 'Not Logged In Yet', 'icon' => 'bi-person-x', 'color' => '#9E480E'],
                [
                    'key' => 'addon_alloted',
                    'label' => 'Licence Alloted',
                    'icon' => 'bi-patch-check',
                    'color' => '#7030A0',
                ],
            ];
        @endphp

        <div class="row g-3 mb-4">
            @foreach ($boxes as $box)
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <a href="{{ route('crm.automation.dashboard', ['filter' => $box['key']]) }}"
                        class="text-decoration-none">
                        <div class="card h-100 shadow-sm stat-box {{ $filter === $box['key'] ? 'active-box' : '' }}"
                            style="border-top: 4px solid {{ $box['color'] }}; cursor: pointer;">
                            <div class="card-body text-center py-3">
                                <i class="bi {{ $box['icon'] }} fs-2" style="color: {{ $box['color'] }}"></i>
                                <h3 class="fw-bold mt-2 mb-0" style="color: {{ $box['color'] }}">
                                    {{ $stats[$box['key']] }}
                                </h3>
                                <p class="text-muted mb-0" style="font-size: 13px;">{{ $box['label'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        {{-- ── SCHOOL LIST ─────────────────────────────────────────────────── --}}
        @if ($filter)
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            Schools —
                            @php
                                $labelMap = [
                                    'total' => 'CRM Fetched Schools',
                                    'activated' => 'Activated Schools',
                                    'not_activated' => 'Not Activated Schools',
                                    'logged_once' => 'Logged In Once',
                                    'not_logged' => 'Not Logged In Yet',
                                    'addon_alloted' => 'Licence Alloted',
                                ];
                            @endphp
                            <span class="text-primary">{{ $labelMap[$filter] ?? $filter }}</span>
                            @if ($schools instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <span class="badge bg-success text-white ms-1">{{ $schools->total() }}</span>
                            @endif
                        </h5>

                        <a href="{{ route('crm.automation.dashboard.export', ['filter' => $filter]) }}"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
                        </a>
                    </div>

                    <form method="GET" action="{{ route('crm.automation.dashboard') }}" id="searchForm">
                        <input type="hidden" name="filter" value="{{ $filter }}">

                        <div class="d-flex align-items-center justify-content-between mb-3">

                            {{-- LEFT SIDE --}}
                            <div class="d-flex align-items-center gap-2">

                                {{-- Search Input --}}
                                <div class="input-group" style="width: 320px;">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>

                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Search by School Name or SOID" value="{{ request('search') }}">
                                </div>

                                {{-- Search Button --}}
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>

                                {{-- Clear Button --}}
                                <a href="{{ route('crm.automation.dashboard', ['filter' => $filter, 'per_page' => request('per_page', 10)]) }}"
                                    class="btn btn-secondary">
                                    Clear
                                </a>


                            </div>

                            {{-- RIGHT SIDE --}}
                            <div class="d-flex align-items-center gap-1">
                                <label class="mb-0 text-muted" style="font-size:13px;">Per Page</label>
                                <select name="per_page" class="form-select form-select-sm" style="width: 80px;"
                                    onchange="this.form.submit()">
                                    @foreach ([10, 20, 30, 50, 100] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ request('per_page', 10) == $opt ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </form>

                    @if ($schools->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SOID</th>
                                        <th>School Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Decision Maker</th>
                                        <th>RM Name</th>
                                        <th>RM Mobile</th>
                                        <th>Series</th>
                                        <th>LMS Status</th>
                                        <th>Last Login</th>
                                        <th>Mittlens</th>
                                        <th>Techlite</th>
                                        <th>Onboarded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($schools as $i => $school)
                                        @php
                                            $user = $school->user;
                                            $uad = $school->user_additional_details;
                                            $rmId = $uad->assign_to ?? null;
                                            $rm = $rmId ? \App\Models\User::find($rmId) : null;
                                            $addon = $user
                                                ? \App\Models\CrmSchoolAddon::where('user_id', $user->id)
                                                    ->where('series_name', 'think trail')
                                                    ->first()
                                                : null;

                                            $series = \App\Models\SchoolAssignedDigitalContent::where(
                                                'school_id',
                                                $school->user_id,
                                            )
                                                ->join(
                                                    'book_series',
                                                    'book_series.id',
                                                    '=',
                                                    'school_assigned_digital_contents.series_id',
                                                )
                                                ->distinct()
                                                ->pluck('book_series.name');

                                            $chipColors = [
                                                '#4472C4',
                                                '#70AD47',
                                                '#ED7D31',
                                                '#7030A0',
                                                '#FF0000',
                                                '#0F6E56',
                                            ];
                                            $lastLogin = $user->loginLogs()->latest('login_at')->first();

                                            // Serial number accounting for pagination offset
                                            $serial = ($schools->currentPage() - 1) * $schools->perPage() + $i + 1;
                                        @endphp
                                        <tr>
                                            <td>{{ $serial }}</td>
                                            <td>{{ $user->soid ?? '—' }}</td>
                                            <td class="fw-semibold">{{ $school->name ?? '—' }}</td>
                                            <td>{{ $user->email ?? '—' }}</td>
                                            <td>{{ $user->mobile_no ?? '—' }}</td>
                                            <td>
                                                {{ $uad->decision_maker ?? '—' }}<br>
                                                <small
                                                    class="text-muted">{{ $uad->decision_maker_mobile_no ?? '' }}</small>
                                            </td>
                                            <td>{{ $rm->name ?? '—' }}</td>
                                            <td>{{ $rm->mobile_no ?? '—' }}</td>
                                            <td>
                                                @foreach ($series as $si => $sName)
                                                    <span
                                                        style="display:inline-block;padding:1px 8px;border-radius:999px;
                                                background:{{ $chipColors[$si % count($chipColors)] }}22;
                                                border:1px solid {{ $chipColors[$si % count($chipColors)] }};
                                                color:{{ $chipColors[$si % count($chipColors)] }};
                                                font-size:11px;white-space:nowrap;">
                                                        {{ $sName }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if ($user && $user->status == 1)
                                                    <span class="badge bg-success">Approved in LMS</span>
                                                @else
                                                    <span class="badge bg-danger">Not Approved</span>
                                                @endif
                                            </td>
                                            <td>{{ $lastLogin ? \Carbon\Carbon::parse($lastLogin->login_at)->format('d M Y') : '—' }}
                                            </td>
                                            <td class="text-center">{{ $addon->mittleance ?? 0 }}</td>
                                            <td class="text-center">{{ $addon->techlite ?? 0 }}</td>
                                            <td>{{ $school->created_at ? $school->created_at->format('d M Y') : '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- ── Pagination Controls ──────────────────────────────── --}}
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <div class="text-muted" style="font-size:13px;">
                                Showing {{ $schools->firstItem() }} to {{ $schools->lastItem() }}
                                of {{ $schools->total() }} entries
                                @if (request('search'))
                                    <span class="text-primary">(filtered by "{{ request('search') }}")</span>
                                @endif
                            </div>
                            <div>
                                {{ $schools->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">No schools found for this filter.</div>
                    @endif

                </div>
            </div>
        @endif

    </section>

    <style>
        .stat-box {
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12) !important;
        }

        .active-box {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.25) !important;
            transform: translateY(-2px);
        }
    </style>
@endsection
