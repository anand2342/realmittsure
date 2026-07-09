@extends('admin.layouts.master')

@section('content')
    <style>
        .btn-border-only {
            background-color: transparent !important;
            border: 1px solid #00438C !important;
            color: inherit;
            line-height: 1.2;
        }

        .btn-border-only:hover,
        .btn-border-only:focus,
        .btn-border-only:active {
            background-color: transparent !important;
            /* still no bg on hover/focus */
            box-shadow: none;
            /* remove glow */
        }
    </style>

    <div class="pagetitle">
        <h1>User Activity Log</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Log</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title mb-0">All Activities</h5>

                            <div class="d-flex align-items-center gap-2">
                                <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>
                                        --Select--
                                    </option>
                                    @foreach ([10, 20, 30, 40, 50] as $option)
                                        <option value="{{ $option }}"
                                            {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="form-divider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>When</th>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Event</th>
                                        <th>Description</th>
                                        <th>Properties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        {{-- Assuming $logs is a collection of activity logs --}}
                                        @php
                                            // Continuous SR No. if $logs is a LengthAwarePaginator; fallback to loop index.
                                            $srNo = method_exists($logs, 'firstItem')
                                                ? $logs->firstItem() + $loop->index
                                                : $loop->iteration;

                                            // Prepare pretty JSON for properties
                                            $propsArray = $log->properties?->toArray() ?? [];
                                            $prettyJson = json_encode(
                                                $propsArray,
                                                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE,
                                            );
                                            $preview = \Illuminate\Support\Str::limit($prettyJson, 140); // short preview
                                            $collapseId = 'props_' . $log->id . '_' . $loop->index;
                                        @endphp

                                        <tr>
                                            <td>{{ $srNo }}</td>
                                            <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                            <td>{{ optional($log->causer)->name ?? 'System' }}</td>
                                            <td>{{ $log->causer->userRole->role->role_name ?? '' }}</td>
                                            <td>{{ $log->event }}</td>
                                            <td>{{ $log->description }}</td>
                                            <td style="min-width:220px;">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    {{-- Preview (short) --}}
                                                    <div class="small text-muted mb-1 me-2" style="flex: 1;">
                                                        <code class="d-block text-truncate"
                                                            style="max-width: 360px;">{{ $preview }}</code>
                                                    </div>

                                                    {{-- Eye Button on Right --}}
                                                    <a class="btn btn-sm btn-border-only" data-bs-toggle="collapse"
                                                        href="#{{ $collapseId }}" role="button" aria-expanded="false"
                                                        aria-controls="{{ $collapseId }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>

                                                {{-- Full JSON (collapsed) --}}
                                                <div class="collapse mt-2" id="{{ $collapseId }}">
                                                    <pre class="mb-0 small" style="max-height: 300px; overflow:auto;">{{ $prettyJson }}</pre>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $logs->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
