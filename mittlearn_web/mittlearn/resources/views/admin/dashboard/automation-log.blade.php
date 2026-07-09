@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>Automation Logs</h1>
        </div>
        <div>
            <a href="{{ route('crm.automation.dashboard') }}" class="btn btn-outline-secondary text-center" title="Go Back">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- 🔍 Search Card -->
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="soid" class="form-control" placeholder="Search by SOID"
                                        value="{{ request('soid') }}">
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary">Search</button>
                                    <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                                </div>

                                <div class="col-md-2 offset-md-4 text-end">
                                    <select id="perPage" class="form-select">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 📊 Table Card -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">CRM Automation Logs List</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>SOID</th>
                                        <th>Date</th>
                                        <th>Payload</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $row)
                                        <tr>
                                            <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</td>
                                            <td>{{ $row->soid }}</td>
                                            <td>{{ $row->created_at }}</td>

                                            <td>
                                                <button class="btn btn-sm btn-primary"
                                                    onclick="toggleJsonRow({{ $row->id }})">
                                                    View
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- 🔽 Expandable Full Row -->
                                        <tr id="expand-{{ $row->id }}" class="expand-row" style="display:none;">
                                            <td colspan="4" style="background:#f8f9fa;">
                                                <div style="max-height:500px; overflow:auto;">
                                                    <pre style="white-space:pre-wrap; font-size:13px;">
                                                      {{ json_encode(json_decode($row->payload), JSON_PRETTY_PRINT) }}
                                                     </pre>
                                                </div>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No Data Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- 📄 Pagination -->
                        <div class="d-flex justify-content-end">
                            {!! $logs->appends(request()->query())->links('pagination::bootstrap-4') !!}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        let openRow = null;

        function toggleJsonRow(id) {
            let currentRow = document.getElementById('expand-' + id);

            // Close previous open row
            if (openRow && openRow !== currentRow) {
                openRow.style.display = 'none';
            }

            // Toggle current row
            if (currentRow.style.display === 'none') {
                currentRow.style.display = 'table-row';
                openRow = currentRow;
            } else {
                currentRow.style.display = 'none';
                openRow = null;
            }
        }
        // per page dropdown
        document.getElementById('perPage').addEventListener('change', function() {
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });

        // set selected value
        let perPage = new URLSearchParams(window.location.search).get('per_page');
        if (perPage) {
            document.getElementById('perPage').value = perPage;
        }
    </script>
@endsection
