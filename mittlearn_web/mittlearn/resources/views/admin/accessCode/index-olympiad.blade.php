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
                                <form method="GET" action="{{ route('access.code.olympiad.index') }}">
                                    <div class="row">

                                        <div class="col mb-3">
                                            <input type="text" class="form-control" placeholder="Search by Code"
                                                name="access_code" value="{{ request('access_code') }}">
                                        </div>
                                        <div class="col mb-3">
                                            {!! Form::select('is_active', config('constants.STATUS_LIST') ?? [], request('is_active'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Search by Status',
                                            ]) !!}
                                        </div>
                                        <div class="col mb-3">
                                            {!! Form::select('class_id', $class ?? [], request('class_id'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Search by Class',
                                            ]) !!}
                                        </div>
                                        <div class="col mb-3">
                                            {!! Form::select('subject_id', $subject ?? [], request('subject_id'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Search by subject',
                                            ]) !!}
                                        </div>
                                        <div class="col mb-3">
                                            <select class="form-control" name="usage_status">
                                                <option value="">Search by Usage Status</option>
                                                <option value="used"
                                                    {{ request('usage_status') == 'used' ? 'selected' : '' }}>Used</option>
                                                <option value="unused"
                                                    {{ request('usage_status') == 'unused' ? 'selected' : '' }}>Unused
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 mb-3">
                                            <label for="generation_date" style="font-size: 13px;">Generation Date</label>
                                            <input type="date" class="form-control" placeholder="Select Date"
                                                name="generation_date" value="{{ request('generation_date') }}">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="expiration_date" style="font-size: 13px;">Expiration Date</label>
                                            <input type="date" class="form-control" placeholder="Select Date"
                                                name="expiration_date" value="{{ request('expiration_date') }}">
                                        </div>
                                        <div class="col-md-3 mb-3 pt-4">
                                            <input type="hidden" class="form-control"
                                                placeholder="Search by Generated User" name="generated_by"
                                                value="{{ request('generated_by') }}">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="{{ route('access.code.olympiad.index') }}"
                                                class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-4">
                                        <h5 class="card-title mb-0">All Access Code</h5>
                                    </div>

                                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <label for="paginationSelectOnpage" class="mb-0 me-2">Per Page Records:</label>
                                            <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                                style="width: 80px;">
                                                <option value="" disabled
                                                    {{ session('per_page_records') ? '' : 'selected' }}>--Select--</option>
                                                @foreach ([40, 80, 120, 160, 200, 400, 800, 1200, 1600, 3200, 5000] as $option)
                                                    <option value="{{ $option }}"
                                                        {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                        <div id="exportDropdownContainer" class="dropdown d-inline-block d-none">
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
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item export-option"
                                                        data-type="print">
                                                        Print Code
                                                    </a>
                                                    <form id="printForm" method="POST"
                                                        action="{{ route('access.code.olympiad.print') }}" target="_blank">
                                                        @csrf
                                                        <input type="hidden" name="ids" id="printIds">
                                                    </form>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <hr class="formdivider">
                                <form id="exportForm" method="POST" action="{{ route('access.code.olympiad.export') }}">
                                    @csrf
                                    <input type="hidden" name="ids" id="selectedIds" value="">
                                    <input type="hidden" name="type" id="exportType" value="">
                                </form>
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-striped table-bordered ">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>S.No</th>
                                                <th><b>Code</b></th>
                                                <th><b>Content Group</b></th>
                                                <th><b>Video Access Validity</b></th>
                                                <th><b>Generator User Name</b></th>
                                                <th><b>Generated By</b></th>
                                                <th><b>Status</b></th>
                                                <th><b>Used By</b></th>
                                                <th><b>Action</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accessCode as $item)
                                                <tr>
                                                    <td>
                                                        @if ($loop->first)
                                                            <input type="checkbox" id="selectAll"
                                                                style="margin-top: -61px; display: block">All
                                                        @endif
                                                        <hr class="formdivider" style="color: #ffffff !important;">
                                                        <input type="checkbox" class="row-checkbox"
                                                            value="{{ $item->id }}">
                                                    </td>
                                                    <td>{{ $accessCode->currentPage() * $accessCode->perPage() - $accessCode->perPage() + $loop->iteration . '.' }}
                                                    </td>
                                                    <td>{{ $item->access_code ?? 'NA' }}</td>
                                                    <td>{{ $item->class->name . ' -  ' . $item->subject->name ?? 'NA' }}
                                                    </td>
                                                    <td>{{ $item->expiration_date ?? 'N/A' }}</td>
                                                    <td>{{ $item->code_generator_name ?? 'N/A' }}</td>
                                                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                                                    <td>{{ ucwords($item->status) }}</td>
                                                    <td>{{ $item->usedBy->name ?? '-' }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            {{-- @isPermission('access.code.olympiad.activate') --}}
                                                            <a class="btn btn-sm statusBtn {{ $item->is_active == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                href="javascript:void(0);"
                                                                onclick="confirmStatus('{{ route('access.code.olympiad.activate', $item->id) }}', {{ $item->is_active }})">
                                                                {{ $item->is_active == 1 ? 'Active' : 'Inactive' }}
                                                            </a>

                                                            @if ($item->user_id && $item->status == 'active')
                                                                {{-- <button type="button"
                                                                    class="btn btn-sm btn-primary revoke-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#revokeConfirmModal{{ $item->id }}">
                                                                    Revoke
                                                                </button> --}}

                                                                <!-- Revoke Access Code Modal -->
                                                                <div class="modal fade"
                                                                    id="revokeConfirmModal{{ $item->id }}"
                                                                    tabindex="-1"
                                                                    aria-labelledby="revokeConfirmModalLabel{{ $item->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <form method="POST"
                                                                            action="{{ route('revoke.access.code.olympiad') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="access_code_id"
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
                                                                                    It is currently used by a User:
                                                                                    <b>{{ $item->usedBy->name ?? '-' }}</b>.
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
                                                            {{-- @endisPermission --}}
                                                            {{-- @isPermission('access.code.olympiad.edit') --}}
                                                            {{-- <a class="btn
                                                                btn-sm btn-warning"
                                                                href="{{ route('access.code.olympiad.edit', $item->id) }}"><i
                                                                    class="fa fa-pencil"></i></a> --}}
                                                            {{-- @endisPermission --}}


                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $accessCode->appends(
                                            array_merge(request()->query(), [
                                                'per_page' => request('per_page', Cookie::get('perPage')),
                                            ]),
                                        )->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const exportDropdownContainer = document.getElementById('exportDropdownContainer');
            const selectedIdsInput = document.getElementById('selectedIds');

            let selectedIds = new Set();

            // Toggle "Select All" functionality
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;

                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    if (isChecked) {
                        selectedIds.add(checkbox.value);
                    } else {
                        selectedIds.delete(checkbox.value);
                    }
                });

                updateSelectedIdsInput();
                toggleExportDropdown();
            });

            // Handle individual checkbox changes
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedIds.add(this.value);
                    } else {
                        selectedIds.delete(this.value);
                    }

                    updateSelectAllState();
                    updateSelectedIdsInput();
                    toggleExportDropdown();
                });

                if (selectedIds.has(checkbox.value)) {
                    checkbox.checked = true;
                }
            });

            // Handle export option click
            document.querySelectorAll('.export-option').forEach(option => {
                option.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    if (selectedIds.size === 0) {
                        alert('Please select at least one access code.');
                        return;
                    }

                    if (type === 'print') {
                        // Set IDs to hidden input and submit the form via POST
                        document.getElementById('printIds').value = Array.from(selectedIds).join(
                            ',');
                        document.getElementById('printForm').submit();
                    } else {
                        // Handle other export types
                        document.getElementById('exportType').value = type;
                        document.getElementById('exportForm').submit();
                    }
                });
            });


            function updateSelectedIdsInput() {
                selectedIdsInput.value = Array.from(selectedIds).join(',');
            }

            function updateSelectAllState() {
                const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
                const noneChecked = Array.from(rowCheckboxes).every(checkbox => !checkbox.checked);

                if (allChecked) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (noneChecked) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.indeterminate = true;
                }
            }

            function toggleExportDropdown() {
                if (selectedIds.size > 0) {
                    exportDropdownContainer.classList.remove('d-none');
                } else {
                    exportDropdownContainer.classList.add('d-none');
                }
            }
        });

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
@endsection
