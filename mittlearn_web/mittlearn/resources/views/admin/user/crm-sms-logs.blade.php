@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>SMS Logs</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">SMS Logs</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                {{-- ── FILTERS ── --}}
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('crm.sms.logs') }}">
                            <div class="row">

                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Mobile No."
                                        name="sent_to" value="{{ request('sent_to') }}">
                                </div>

                                <div class="col mb-3">
                                    <select name="template_key" class="form-select">
                                        <option value="">All Templates</option>
                                        @foreach ($templateKeys as $key)
                                            <option value="{{ $key }}"
                                                {{ request('template_key') == $key ? 'selected' : '' }}>
                                                {{ $key }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col mb-3">
                                    <select name="triggered_by" class="form-select">
                                        <option value="">All Sources</option>
                                        @foreach ($triggeredBys as $trigger)
                                            <option value="{{ $trigger }}"
                                                {{ request('triggered_by') == $trigger ? 'selected' : '' }}>
                                                {{ $trigger }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col mb-3">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="sent"   {{ request('status') == 'sent'   ? 'selected' : '' }}>Sent</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>

                                <div class="col mb-3">
                                    <input type="date" class="form-control" name="date_from"
                                        placeholder="From Date" value="{{ request('date_from') }}">
                                </div>

                                <div class="col mb-3">
                                    <input type="date" class="form-control" name="date_to"
                                        placeholder="To Date" value="{{ request('date_to') }}">
                                </div>

                                <div class="col mb-3">
                                    <input type="number" class="form-control" placeholder="School ID"
                                        name="school_id" value="{{ request('school_id') }}">
                                </div>

                                <div class="col mb-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('crm.sms.logs') }}" class="btn btn-secondary">Clear</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── TABLE ── --}}
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title mb-0">
                                SMS Logs
                                <span class="badge bg-success text-white ms-2">Total: {{ $logs->total() }}</span>
                            </h5>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-success">✓ Sent on page: {{ $logs->where('status', 'sent')->count() }}</span>
                                <span class="badge bg-danger">✗ Failed on page: {{ $logs->where('status', 'failed')->count() }}</span>

                                {{-- <div class="d-flex align-items-center gap-2">
                                    <label for="paginationSelectOnpage" class="mb-0">Per Page:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                        <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>--</option>
                                        @foreach ([10, 20, 30, 50, 100] as $option)
                                            <option value="{{ $option }}"
                                                {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                        </div>

                        <hr class="form-divider">

                        <div class="table-responsive tbleDiv">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date & Time</th>
                                        <th>Sent To</th>
                                        <th>School</th>
                                        <th>RM</th>
                                        <th>Template</th>
                                        {{-- <th>Triggered By</th> --}}
                                        <th>Sent By</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                        <th>Error</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $index => $log)
                                        <tr>
                                            <td>{{ $logs->firstItem() + $index }}.</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}<br>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->format('h:i A') }}
                                                </small>
                                            </td>

                                            <td><b>{{ $log->sent_to ?? '—' }}</b></td>

                                            <td>
                                                @if ($log->school)
                                                    {{ $log->school->name ?? 'School #' . $log->related_school_id }}
                                                @elseif ($log->related_school_id)
                                                    <span class="text-muted">ID: {{ $log->related_school_id }}</span>
                                                @else
                                                    —
                                                @endif
                                            </td>

                                            <td>
                                                @if ($log->rm)
                                                    {{ $log->rm->name }}<br>
                                                    <small class="text-muted">{{ $log->rm->mobile_no }}</small>
                                                @else
                                                    —
                                                @endif
                                            </td>

                                            <td>
                                                <span class="badge text-bg-info">
                                                    {{ $log->template_key ?? '—' }}
                                                </span>
                                            </td>

                                            {{-- <td>
                                                <span class="badge text-bg-secondary">
                                                    {{ $log->triggered_by ?? '—' }}
                                                </span>
                                            </td> --}}

                                            <td>{{ $log->senderUser->name ?? '—' }}</td>

                                            <td>
                                                @if ($log->status === 'sent')
                                                    <span class="badge text-success">✓ Sent</span>
                                                @else
                                                    <span class="badge text-danger">✗ Failed</span>
                                                @endif
                                            </td>

                                            <td style="max-width: 200px;">
                                                <span class="d-inline-block text-truncate" style="max-width: 180px;"
                                                    title="{{ $log->message }}">
                                                    {{ $log->message }}
                                                </span>
                                                <a href="#" class="small text-primary"
                                                    onclick="showMessage(`{{ addslashes($log->message) }}`); return false;">
                                                    view
                                                </a>
                                            </td>

                                            <td class="text-danger small">
                                                {{ $log->error_message ?? '—' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted py-4">
                                                No SMS logs found for the selected filters.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                Showing {{ $logs->firstItem() }} – {{ $logs->lastItem() }} of {{ $logs->total() }} records
                            </small>
                            <div>
                                {!! $logs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── MESSAGE MODAL ── --}}
    <div class="modal fade" id="messageModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Full SMS Message</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessageText" class="mb-0" style="white-space: pre-wrap; word-break: break-word;"></p>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function showMessage(message) {
            document.getElementById('modalMessageText').textContent = message;
            new bootstrap.Modal(document.getElementById('messageModal')).show();
        }
    </script>
@endpush