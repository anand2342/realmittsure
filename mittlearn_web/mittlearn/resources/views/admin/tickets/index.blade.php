@extends('admin.layouts.master')
@section('content')
@php
$canManage = in_array(getUserRoles(), ['admin', 'qd_developer']);
@endphp
<div class="pagetitle">
    
    <h1>Ticket Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Tickets</li>
        </ol>
    </nav>
</div>

<section class="section">
    @if (in_array(getUserRoles(), ['qd_developer']))
    {{-- Timelog  Summary boxes dashboard data for tickets --}}
    <div class="row mt-3">
        <!-- Previous Month Box -->
        <div class="col-md-6">
            <div class="card text-center border-info shadow-sm" style="height: 140px">
                <div class="card-body">
                    <h5 class="card-title text-info">📆 Previous Month Summary</h5>
                    <div class="row mt-3">
                        <div class="col-4">
                            <h6 class="text-muted">Allotted</h6>
                            <h4>{{ number_format($monthlyData['previous']['allotted'], 2) }} hrs</h4>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted">Used</h6>
                            <h4>{{ number_format($monthlyData['previous']['used'], 2) }} hrs</h4>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted">Carry Forward</h6>
                            <h4 class="text-success">{{ number_format($monthlyData['previous']['unused'], 2) }} hrs
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Month Box -->
        <div class="col-md-6">
            <div class="card text-center border-primary shadow-sm" style="height: 140px">
                <div class="card-body">
                    <h5 class="card-title text-primary">🗓️ Current Month Summary</h5>
                    <div class="row mt-3">
                        <div class="col-4">
                            <h6 class="text-muted">Available (Allotted + CF)</h6>
                            <h4>{{ number_format($monthlyData['current']['available'], 2) }} hrs</h4>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted">Used</h6>
                            <h4>{{ number_format($monthlyData['current']['used'], 2) }} hrs</h4>
                        </div>
                        <div class="col-4">
                            <h6 class="text-muted">Remaining</h6>
                            <h4 class="text-success">{{ number_format($monthlyData['current']['remaining'], 2) }}
                                hrs</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('tickets.index') }}" class="row g-2 mb-3">
                        <div class="col-md-4 mt-2">
                            <input type="text" name="search" class="form-control" placeholder="Search Issue"
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-4 mt-2">
                            <select name="priority" class="form-select">
                                <option value="">All Priorities</option>
                                @foreach (['low', 'medium', 'high'] as $priority)
                                <option value="{{ $priority }}"
                                    {{ request('priority') == $priority ? 'selected' : '' }}>
                                    {{ ucfirst($priority) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mt-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                @foreach (['open', 'in_progress', 'closed'] as $status)
                                <option value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mt-2" title="created_from date">
                            <input type="date" name="created_from" class="form-control"
                                value="{{ request('created_from') }}" placeholder="From Date">
                        </div>

                        <div class="col-md-4 mt-2" title="created_to date">
                            <input type="date" name="created_to" class="form-control"
                                value="{{ request('created_to') }}" placeholder="To Date">
                        </div>

                        <div class="col-md-2 mt-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                        <div class="col-md-2 mt-2">

                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary w-100">Clear</a>
                        </div>
                    </form>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5 class="card-title pb-0">All Tickets</h5>
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
                                <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Ticket</a>

                            </div>
                    </div>



                    <hr class="form-divider">

                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive tbleDiv ">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S no.</th>
                                    <th>Ticket Id</th>
                                    <th>Issue</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    {{-- <th>Assignee</th> --}}
                                    <th>Creator</th>
                                    <th>Work Duration (hrs)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                <tr>
                                    <td>{{ $tickets->currentPage() * $tickets->perPage() - $tickets->perPage() + $loop->iteration . '.' }}
                                     <td>{{ ucfirst($ticket->ticket_id) }} </td>
                                    <td>
                                        {!! Str::limit(strip_tags($ticket->issue), 80) !!}
                                        @if (strlen(strip_tags($ticket->issue)) > 80)
                                        <i class="bi bi-info-circle text-black ms-5 " style="font-size: 1.2rem;color:"
                                            data-bs-toggle="tooltip" title="{{ strip_tags($ticket->issue) }}">
                                        </i>
                                        @endif
                                    </td>

                                    @php
                                    $priorityColors = [
                                    'low' => 'bg-success',
                                    'medium' => 'bg-warning',
                                    'urgent' => 'bg-dark',
                                    'critical' => 'bg-danger',
                                    ];
                                    $priority = strtolower($ticket->priority);
                                    $priorityClass = $priorityColors[$priority] ?? 'bg-secondary';
                                    @endphp

                                    @php
                                    $priorityColors = [
                                    'low' => 'border-success text-success',
                                    'medium' => 'border-warning text-warning',
                                    'high' => 'border-dark text-dark',
                                    ];
                                    $priority = strtolower($ticket->priority);
                                    $priorityClass =
                                    $priorityColors[$priority] ?? 'border-secondary text-secondary';
                                    @endphp

                                    <td>
                                        <span class="badge border-1 {{ $priorityClass }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>

                                    @php
                                    $statusColors = [
                                    'open' => 'border-success text-success',
                                    'pending' => 'border-warning text-warning',
                                    'closed' => 'border-danger text-danger',
                                    'in_progress' => 'border-info text-info',
                                    ];
                                    $status = strtolower($ticket->status);
                                    $badgeClass =
                                    $statusColors[$status] ?? 'border-secondary text-secondary';
                                    @endphp

                                    <td>
                                        <span class="badge border-1 {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        </span>
                                    </td>

                                    {{-- <td>{{ $ticket->assignee->name ?? '—' }}</td> --}}
                                    <td>{{ $ticket->creator->name ?? '—' }}</td>
                                    @php
                                    $isWithinDays =
                                    \Carbon\Carbon::parse($ticket->created_at)->diffInDays(now()) <= 30;
                                        @endphp
                                        <td>
                                        {{ number_format($ticket->total_time_logs, 2) }}

                                        @if (!empty($ticket->remarks_qd) && $isWithinDays)
                                        <span class="badge bg-success text-white ms-2" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="{{ $ticket->remarks_qd }}"
                                            style="cursor: pointer;">
                                            Developer Remark
                                        </span>
                                        @endif
                                        </td>
                                        <td>
                                            @if (getUserRoles() === 'qd_developer')
                                            <a href="{{ route('tickets.edit', $ticket) }}"
                                                class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                                            @endif
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
                                            {{-- <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                    onclick="confirmDelete('{{ route('tickets.destroy', $ticket) }}')"
                                            title="Delete">
                                            <i class="fa fa-trash"></i></a> --}}
                                        </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No tickets found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-right text-right">
                        {!! $tickets->links('pagination::bootstrap-4') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
