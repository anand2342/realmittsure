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

                                        <div class="col mb-3">
                                            <input type="text" class="form-control" placeholder="Search by Code"
                                                name="access_code" value="{{ request('access_code') }}">
                                        </div>
                                        <div class="col mb-3">
                                            <input type="text" class="form-control" placeholder="Search by Series."
                                                name="book_series" value="{{ request('book_series') }}">
                                        </div>
                                        <div class="col mb-3">
                                            <input type="text" class="form-control" placeholder="Search by Status"
                                                name="status" value="{{ request('status') }}">
                                        </div>
                                        <div class="col mb-3">
                                            <select name="school_name" class="form-select">
                                                <option value="">Search by School</option>
                                                @foreach ($schools as $school)
                                                    <option value="{{ $school->id }}"
                                                        {{ $school->id == request('school_name') ? 'selected' : '' }}>
                                                        {{ $school->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col mb-3">
                                            <select name="book_series_name" class="form-select">
                                                <option value="">Search by BookSeries</option>
                                                @foreach ($book_series as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == request('book_series_name') ? 'selected' : '' }}>
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
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
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card-title">All Access Code</div>
                                    </div>
                                    <div class="col-sm-6 text-end mt-3">
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
                                                </li>
                                            </ul>
                                        </div>
                                        <a href="{{ route('school.list') }}" class="btn btn-secondary">
                                            School Access codes
                                        </a>
                                    </div>
                                </div>
                                <hr class="formdivider">
                                <form id="exportForm" method="POST" action="{{ route('access.code.export') }}">
                                    @csrf
                                    <input type="hidden" name="ids" id="selectedIds" value="">
                                    <input type="hidden" name="type" id="exportType" value="">
                                </form>
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-striped table-bordered datatable ">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>S.No</th>
                                                <th><b>Code</b></th>
                                                <th><b>Book Series</b></th>
                                                {{-- <select id="bookSeriesFilter" class="book-series-filter"
                                                            onchange="filterAccessCodes()">
                                                            <option value="" selected>Book Series </option>
                                                            <option value="1">Digital Content</option>
                                                            <option value="3">Luma Learn</option>
                                                            <option value="4">Embibe</option>
                                                        </select> --}}
                                                <th><b>Generated By</b></th>
                                                <th><b>Start Date</b></th>
                                                <th><b>Expiry Date</b></th>
                                                <th><b>School</b></th>
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
                                                        <hr class="formdivider">

                                                        <input type="checkbox" class="row-checkbox"
                                                            value="{{ $item->id }}">
                                                    </td>
                                                    <td>{{ $loop->iteration }}.</td>
                                                    <td>{{ \Illuminate\Support\Str::limit($item->access_code ?? 'NA', 10) }}
                                                    </td>
                                                    <td>{{ $item->bookSeries->name ?? 'N/A' }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>{{ $item->start_date }}</td>
                                                    <td>{{ $item->end_date }}</td>
                                                    <td>{{ $item->school->name ?? 'N/A' }}</td>
                                                    <td>{{ ucwords($item->status) }}</td>
                                                    <td>{{ $item->usedBy->name ?? '-' }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @isPermission('access.code.activate')
                                                            <a class="btn btn-sm statusBtn {{ $item->is_active == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                href="javascript:void(0);"
                                                                onclick="confirmStatus('{{ route('access.code.activate', $item->id) }}', {{ $item->is_active }})">
                                                                {{ $item->is_active == 1 ? 'Active' : 'Inactive' }}
                                                            </a>

                                                            @endisPermission
                                                            @isPermission('access.code.edit')
                                                            <a class="btn
                                                                btn-sm btn-warning"
                                                                href="{{ route('access.code.edit', $item->id) }}"><i
                                                                    class="fa fa-pencil"></i></a>
                                                            @endisPermission
                                                            <button class="btn btn-sm btn-primary"
                                                                data-id="{{ $item->id }}" id="accessCodeInfo">
                                                                Info
                                                            </button>
                                                            {{-- @livewire('school-access-code', ['infoId' => $item->id]) --}}
                                                            {{-- @isPermission('access.code.delete')
                                                                <a class="btn btn-danger btn-sm me-2"
                                                                    href="javascript:void(0);"
                                                                    onclick="confirmDelete('{{ route('access.code.delete', $item->id) }}')">
                                                                    <i class="fa fa-trash"></i></a>
                                                            @endisPermission --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- <div class="d-flex justify-content-right text-right">
                                    {!! $accessCode->appends(request()->query())->links('pagination::bootstrap-4') !!}
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <div class="modal fade" id="accessCodeInfoModal" tabindex="-1" aria-labelledby="accessCodeInfoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accessCodeInfoModalLabel">Access Code Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        // Open print page in a new window
                        const printUrl =
                            `{{ route('access.code.print') }}?ids=${Array.from(selectedIds).join(',')}`;
                        window.open(printUrl, '_blank');
                    } else {
                        // Handle other export options
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



        // document.addEventListener('DOMContentLoaded', function() {
        //     const selectAllCheckbox = document.getElementById('selectAll');
        //     const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        //     const exportDropdownContainer = document.getElementById('exportDropdownContainer');
        //     const exportForm = document.getElementById('exportForm');
        //     const selectedIdsInput = document.getElementById('selectedIds');
        //     const exportTypeInput = document.getElementById('exportType');

        //     let selectedIds = new Set(); // Use a Set to store selected IDs across pages.

        //     // Load previously selected IDs from the hidden input (if available)
        //     const existingIds = selectedIdsInput.value.split(',');
        //     existingIds.forEach(id => {
        //         if (id) selectedIds.add(id);
        //     });

        //     // Toggle "Select All" functionality
        //     selectAllCheckbox.addEventListener('change', function() {
        //         const isChecked = this.checked;

        //         rowCheckboxes.forEach(checkbox => {
        //             checkbox.checked = isChecked;
        //             if (isChecked) {
        //                 selectedIds.add(checkbox.value); // Add ID to the set.
        //             } else {
        //                 selectedIds.delete(checkbox.value); // Remove ID from the set.
        //             }
        //         });

        //         updateSelectedIdsInput();
        //         toggleExportDropdown();
        //     });

        //     // Handle individual checkbox changes
        //     rowCheckboxes.forEach(checkbox => {
        //         checkbox.addEventListener('change', function() {
        //             if (this.checked) {
        //                 selectedIds.add(this.value);
        //             } else {
        //                 selectedIds.delete(this.value);
        //             }

        //             updateSelectAllState();
        //             updateSelectedIdsInput();
        //             toggleExportDropdown();
        //         });

        //         // Restore previously selected checkboxes
        //         if (selectedIds.has(checkbox.value)) {
        //             checkbox.checked = true;
        //         }
        //     });

        //     // Handle export option click
        //     document.querySelectorAll('.export-option').forEach(option => {
        //         option.addEventListener('click', function() {
        //             if (selectedIds.size === 0) {
        //                 alert('Please select at least one access code.');
        //                 return;
        //             }

        //             exportTypeInput.value = this.getAttribute('data-type');
        //             exportForm.submit();
        //         });
        //     });

        //     // Function to update the hidden input with selected IDs
        //     function updateSelectedIdsInput() {
        //         selectedIdsInput.value = Array.from(selectedIds).join(',');
        //     }

        //     // Function to update the state of the "Select All" checkbox
        //     function updateSelectAllState() {
        //         const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
        //         const noneChecked = Array.from(rowCheckboxes).every(checkbox => !checkbox.checked);

        //         if (allChecked) {
        //             selectAllCheckbox.checked = true;
        //             selectAllCheckbox.indeterminate = false;
        //         } else if (noneChecked) {
        //             selectAllCheckbox.checked = false;
        //             selectAllCheckbox.indeterminate = false;
        //         } else {
        //             selectAllCheckbox.indeterminate = true;
        //         }
        //     }

        //     // Function to toggle the visibility of the export dropdown
        //     function toggleExportDropdown() {
        //         if (selectedIds.size > 0) {
        //             exportDropdownContainer.classList.remove('d-none');
        //         } else {
        //             exportDropdownContainer.classList.add('d-none');
        //         }
        //     }
        // });
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
                                <p><strong>Access Code:</strong> ${data.code}</p>
                                <p><strong>School Name:</strong> ${data.school}</p>
                                <p><strong>Medium:</strong> ${data.medium}</p>
                                <p><strong>Created By:</strong> ${data.generated_by}</p>
                                <p><strong>Is Activated ? :</strong> No
                                </p>
                                <p><strong>Start Date:</strong> ${data.start_date}</p>
                                <p><strong>Used By:</strong> ${data.usedBy}</p>

                            </div>
                            <div class="col-md-6">
                                <p><strong>Book Series Name:</strong>
                                   Lumalearn
                                </p>
                                <p><strong>Board:</strong> ${data.board}</p>
                                <p><strong>Class:</strong>${data.class}</p>
                                <p><strong>Status:</strong>  ${data.generated_by}</p>
                                <p><strong>Expired Date:</strong>  ${data.expiry_date}</p>
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
@endsection
