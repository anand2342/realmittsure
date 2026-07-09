<div class="col-lg-12">
    <div class="card">
        <div class="card-body row">
            <div class="col-md-9">
                <form method="GET" action="{{ route('erp-data.schools.index') }}">
                    <div class="d-flex flex-wrap align-items-center ">
                        <div class="col-md-3 me-2 mb-2">
                            <label for="name" class="form-label">Search by name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Enter name" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-3 me-2 mb-2">
                            <label for="name" class="form-label">Search Username</label>
                            <input type="text" id="username" name="username" class="form-control"
                                placeholder="Enter Username" value="{{ request('username') }}">
                        </div>
                        <div class="col-md-2 me-2 mb-2 mt-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('erp-data.schools.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <label for="paginationSelectOnpage" class="me-2 mb-0 text-nowrap">Per Page
                    Records:</label>
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
            </div>
        </div>
    </div>
</div>

{{-- <button class="btn btn-primary mb-2 d-none" id="moveToLmsBtn" data-bs-toggle="modal" data-bs-target="#lmsModal">Move To
    LMS</button> --}}

<div class="table-responsive tbleDiv ">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                {{-- <th><input type="checkbox" id="selectAll"> S.No</th> --}}
                <th>--</th>
                <th>S.No</th>
                <th><b>School Name</b></th>
                <th><b>Username</b></th>
                <th><b>Password</b></th>
                <th><b>DM Mobile</b></th>
                <th><b>DM Name</b></th>
                <th><b>Alias Name</b></th>
                <th><b>Distributor</b></th>
                <th><b>Board</b></th>
                <th><b>State</b></th>
                <th><b>District</b></th>
                <th><b>Updated Time</b></th>
                <th><b>Updated By</b></th>
                <th><b>Classes</b></th>

            </tr>
        </thead>
        <tbody>
            @foreach ($datalist as $item)
                {{-- @dd($item->status) --}}
                <tr>
                    @php
                        $isExisitsInLMS = App\Models\User::whereNotNull('erp_schid')
                            ->where('erp_schid', $item->schid)
                            ->first();
                    @endphp
                    @if ($isExisitsInLMS)
                        <td>
                            <a class="btn btn-sm btn-success"
                                onclick="isExitsInLMs('{{ route('erp-data.add.schools', $item->id) }}')">Already In LMS
                            </a>
                        </td>
                    @else
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('erp-data.add.schools', $item->id) }}">Move
                                to LMS
                            </a>
                        </td>
                    @endif
                    {{-- <td><input type="checkbox" class="rowCheckbox" value="{{ $item->id }}"></td> --}}
                    <td>{{ $datalist->currentPage() * $datalist->perPage() - $datalist->perPage() + $loop->iteration . '.' }}
                    </td>
                    <td>{{ $item->schoolName }}</td>
                    <td>{{ $item->username }}</td>
                    <td>{{ $item->password }}</td>
                    <td>{{ $item->contactNo ?? '' }}</td>
                    <td>{{ $item->contactName ?? '' }}</td>
                    <td>{{ $item->aliasName ?? '' }}</td>
                    <td>{{ $item->distributor ?? '' }}</td>
                    <td>{{ $item->board ?? '' }}</td>
                    @php
                        $stateName = DB::connection('erp')
                            ->table('state_table')
                            ->where('id', $item->state)
                            ->value('name');
                        $districtName = DB::connection('erp')
                            ->table('district_table')
                            ->where('id', $item->district)
                            ->value('name');
                    @endphp
                    <td>{{ $stateName ?? '' }}</td>
                    <td>{{ $districtName ?? '' }}</td>
                    <td>{{ $item->update_time ?? '' }}</td>
                    <td>{{ $item->updated_by ?? '' }}</td>
                    <td style="white-space: normal; max-width: 400px;">
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                            @php
                                $classes = $schoolClasses[$item->schid] ?? collect();
                                $uniqueClasses = collect($classes)->unique('name');
                            @endphp

                            @if ($uniqueClasses->isNotEmpty())
                                @foreach ($uniqueClasses as $class)
                                    <span class="badge bg-info">{{ $class->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No Classes</span>
                            @endif
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if ($datalist->count() > 0)
    <div class="d-flex justify-content-right text-right">
        {!! $datalist->links('pagination::bootstrap-4') !!}
    </div>
@endif
<!-- Modal -->
<div class="modal fade" id="lmsModal" tabindex="-1" aria-labelledby="lmsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lmsModalLabel">Selected Schools</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="selectedDataTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>School Name</th>
                            <!-- Add more headers as needed -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filled via JS -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="proceedBtn">Proceed</button>
            </div>
        </div>
    </div>
</div>

<script>
    const datalist = @json($datalist);

    // Show/hide "Move To LMS" button
    const toggleMoveButton = () => {
        const checked = document.querySelectorAll('.rowCheckbox:checked').length;
        document.getElementById('moveToLmsBtn').classList.toggle('d-none', checked === 0);
    };

    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.rowCheckbox').forEach(cb => cb.checked = this.checked);
        toggleMoveButton();
    });

    document.querySelectorAll('.rowCheckbox').forEach(cb => {
        cb.addEventListener('change', toggleMoveButton);
    });
    // Proceed to save selected data
    document.getElementById('moveToLmsBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked')).map(cb => cb.value);

        fetch("{{ route('erp-data.moveToLms') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                alert('Data moved successfully!');
                location.reload();
            });
    });
</script>
