@extends('schoolPortal.layouts.master')
@section('content')
@include('admin.layouts.flash-messages')
    @php
        $flag = 0;
        $heading = 'Add Student';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'View/Edit Student Details';
        }
    @endphp
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">Manage Unverified Students</h5>
                    <p>Easily manage unverified students with tools designed to streamline verification and administrative
                        processes.</p>
                </div>
                <a href="{{ route('sp.student.manager') }}" class="btn btn-primary-gradient rounded-1 addBtn ">Back</a>
            </div>
            <div class="col-md-4">
                {{--  <div class="teacherRighr position-relative">
                    <img src="{{ asset('frontend/images/student-manager-img.svg') }}" alt=""
                        class="teacherImg studentImg">
                </div>  --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="teacherTable">
                <div class="headerTbl">
                    <h6 class="m-0">Unverified Students Manager</h6>
                    <div class="teacherrightTable">
                        <div class="tableSearch">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Name">
                        </div>
                        {{--  <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Sort">
                                    <img src="{{ asset('frontend/images/sort-icon.svg') }}" alt="">
                                </span>
                            </button>
                            <ul class="dropdown-menu" id="sortDropdown">
                                <li><a class="dropdown-item" href="#" id="sortAsc">Sort A to Z</a></li>
                                <li><a class="dropdown-item" href="#" id="sortDesc">Sort Z to A</a></li>
                            </ul>
                        </div>  --}}
                        {{--  <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Filter">
                                    <img src="{{ asset('frontend/images/filter-icon.svg') }}" alt="">
                                </span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Subject</a></li>
                                    <li><a class="dropdown-item" href="#">Class</a></li>
                                <li><a class="dropdown-item" href="#" id="activeStudents">Active Students</a></li>
                                <li><a class="dropdown-item" href="#" id="inactiveStudents">Inactive Students</a>
                                <li><a class="dropdown-item" href="{{ route('sp.student.manager') }}">All Students</a>
                                </li>
                            </ul>
                        </div>  --}}
                        {{--  <a href="{{ route('export.students') }}" class="bg-transparent border-0 p-0">
                            <button class="bg-transparent border-0 p-0" type="button">
                                <span>
                                    <img src="{{ asset('frontend/images/download-icon.svg') }}" alt="Download"
                                        title="Download">
                                </span>
                            </button>
                        </a>  --}}
                    </div>
                </div>
                <div class="px-3 py-2">
                    <div class="table-responsive tbleDiv">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    @if (config('COURSES_FILTER_BY_ACCESS_CODE') == 1)
                                        <th>Access Code</th>
                                    @endif
                                    <th>Parent's Mob. No.</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                @foreach ($students as $student)
                                    <tr data-id="{{ $student->id }}"
                                        data-admission_no="{{ $student->userAdditionalDetail->admission_no }}"
                                        data-name="{{ $student->name }}" data-email="{{ $student->email }}"
                                        data-parent_name="{{ $student->studentDetails->parent_name }}"
                                        data-admission_date="{{ \Carbon\Carbon::parse($student->studentDetails->doj)->format('Y-m-d') }}"
                                        data-class="{{ $student->studentDetails->class }}"
                                        data-section="{{ $student->studentDetails->section }}"
                                        data-parent_mobile_no="{{ $student->mobile_no }}"
                                        data-status="{{ $student->status == 1 ? 'active' : 'inactive' }}"
                                        data-dob="{{ $student->studentDetails->dob }}">
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <span class="nameTbl student-name"> <img
                                                    src="{{ $student->image ? Storage::url('uploads/user/profile_image/' . $student->image) : asset('frontend/images/default-image.jpg') }}"
                                                    alt="">{{ $student->name }}
                                            </span>
                                        </td>
                                        <td>{{ App\Models\SchoolClass::where('id', $student->studentDetails->class)->value('name') }}
                                        </td>
                                        @if (config('COURSES_FILTER_BY_ACCESS_CODE') == 1)
                                            <td> {{ $student->userAccessCode->access_code ?? '' }}</td>
                                        @endif
                                        <td>{{ $student->mobile_no }}</td>
                                        <td>
                                            <div class="d-inline-block">
                                                <button class="btn btn-primary btn-sm px-2 py-1 edit-student-btn"
                                                    id="edit-student-btn" data-bs-toggle="offcanvas"
                                                    data-bs-target="#addStudent" data-id="{{ $student->id }}"
                                                    type="button">
                                                    Verified
                                                </button>
                                                {{--  <a class="dropdown-item viewEditStudent edit-student-btn"
                                                    id="edit-student-btn" href="#addStudent" data-bs-toggle="offcanvas"
                                                    data-id="{{ $student->id }}">View/Edit Student
                                                    Details</a>  --}}
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="customPagination mt-4">
                        <ul class="pagination">
                            <li class="page-item {{ $students->onFirstPage() ? 'disabled' : '' }} previous-item">
                                <a class="page-link" href="{{ $students->previousPageUrl() }}">
                                    <span><img src="{{ asset('frontend/images/arrowprw.svg') }}" width="6"></span>
                                </a>
                            </li>

                            @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $students->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <li class="page-item {{ $students->hasMorePages() ? '' : 'disabled' }} next-item">
                                <a class="page-link" href="{{ $students->nextPageUrl() }}">
                                    <span><img src="{{ asset('frontend/images/arrownxt.svg') }}" width="6"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" id="logsView">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fs-6 fw-semibold">View Active/Inactive Logs</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="logsUl" id="logsList">
                <!-- Logs will be dynamically added here -->
            </ul>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <button type="button" class="btn btn-secondary px-5 rounded-1" data-bs-dismiss="offcanvas">Back</button>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end {{ $errors->any() ? 'show' : '' }}" id="addStudent" tabindex="-1">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-heading fs-6 fw-semibold">Add Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <h6 class="">Bulk upload</h6>
            @livewire('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_student'])
            <hr class="form-divider">

            {{ Form::open(['url' => route('sp.un-verfired.student.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
            {{ Form::hidden('role', 'school_student') }}
            {{ Form::hidden('id', '', ['id' => 'student_id_field']) }}

            <div class="formPanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('name', 'Name') !!} <b>*</b>
                            {!! Form::text('name', old('name', $userData->name ?? null), [
                                'class' => 'form-control ' . ($errors->has('name') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                                'readonly' => 'readonly',
                            ]) !!}
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('parent_mobile_no', 'Parent/Guardian Mobile No.') !!} <b>*</b>
                            {!! Form::text(
                                'parent_mobile_no',
                                old('parent_mobile_no', $userData->studentDetails->emergency_contact_phone ?? null),
                                [
                                    'class' => 'form-control mobile ' . ($errors->has('parent_mobile_no') ? 'is-invalid' : ''),
                                    'placeholder' => 'Enter here',
                                    'readonly' => 'readonly',
                                ],
                            ) !!}
                            @error('parent_mobile_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if (getUserRoles() == 'school_teacher')
                        <div class="col-md-6">
                            <div class="form-group bginput mb-3">
                                {!! Form::label('class', 'Select Class') !!} <b>*</b>
                                {!! Form::select('class', $teacherClasses, old('class', $userData->studentDetails->class ?? null), [
                                    'class' => 'form-select ' . ($errors->has('class') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ]) !!}
                                @error('class')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <div class="col-md-6">
                            <div class="form-group bginput mb-3">
                                {!! Form::label('class', 'Select Class') !!} <b>*</b>
                                {!! Form::select('class', $classes, old('class', $userData->studentDetails->class ?? null), [
                                    'class' => 'form-select ' . ($errors->has('class') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ]) !!}
                                @error('class')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('section', 'Select Section') !!}
                            {!! Form::select(
                                'section',
                                config('constants.SECTION'),
                                old('section', $userData->studentDetails->section ?? null),
                                [
                                    'class' => 'form-select ' . ($errors->has('section') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ],
                            ) !!}
                            @error('section')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('admission_no', 'Admission No.') !!} <b>*</b>
                            {!! Form::text('admission_no', old('admission_no', $userData->userAdditionalDetail->admission_no ?? null), [
                                'class' => 'form-control qualification ' . ($errors->has('admission_no') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('admission_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            {!! Form::label('admission_date', 'Admission Date') !!} <b>*</b>
                            {!! Form::date('admission_date', $userData->studentDetails->admission_date ?? null, [
                                'class' => 'form-control dateInput' . ($errors->has('admission_date') ? 'is-invalid' : ''),
                            ]) !!}
                            @error('admission_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('parent_name', 'Parent Name') !!} <b>*</b>
                            {!! Form::text('parent_name', old('parent_name', $userData->studentDetails->parent_name ?? null), [
                                'class' => 'form-control ' . ($errors->has('parent_name') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('parent_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            {!! Form::label('email', 'Email') !!}
                            {!! Form::text('email', old('email', $userData->email ?? null), [
                                'class' => 'form-control ' . ($errors->has('email') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]) !!}
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            {!! Form::label('dob', 'DOB') !!} <b>*</b>
                            {!! Form::date('dob', old('dob', $userData->studentDetails->dob ?? null), [
                                'class' => 'form-control dateInput ' . ($errors->has('dob') ? 'is-invalid' : ''),
                                'id' => 'date-input',
                                'placeholder' => 'Select date',
                            ]) !!}
                            @error('dob')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <button type="button" class="btn backbtn" data-bs-dismiss="offcanvas">Back</button>
                <button type="submit" class="btn btn-primary-gradient rounded-1">Submit</button>
            </div>
        </div>
        {{ Form::close() }}
    </div>

    <div class="modal fade" id="statusMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center">
                        <lottie-player src="{{ asset('frontend/images/study-idea.json') }}" loop=""
                            autoplay="" style="width: 130px;height: 130px;margin: auto;"
                            background="transparent"></lottie-player>
                        <h6 class="fw-semibold">Are you sure !</h6>
                        <p id="statusText"></p>
                        <button type="button" class="btn btn-primary-gradient rounded-1"
                            id="confirmChangeStatus">Yes</button>
                        <div>
                            <button type="button" class="btn btnNo" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="studentInactive">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 align-items-baseline">
                    <div>
                        <h6 class="modal-title fw-semibold">Inactive Student</h6>
                        <p>Enter inactive date for changing the status of student from active to Inactive.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="">
                        <div class="formPanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group bginput mb-3">
                                        <label>Enter Date</label>
                                        <input type="text" class="form-control dateBirth" value="Select date">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary-gradient rounded-1">Submit</button>
                                <div>
                                    <button type="button" class="btn btnNo">Back</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const statusModal = document.getElementById('statusMdl');
                    const statusText = document.getElementById('statusText');
                    let studentId = null;

                    // Handle opening modal and setting data
                    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
                        button.addEventListener('click', function() {
                            studentId = this.getAttribute('data-id');
                            const status = this.getAttribute('data-status');
                            const name = this.getAttribute('data-name');
                            const fromStatus = status == 1 ? 'Activate' : 'Inactivate';
                            const toStatus = status == 1 ? 'Inactivate' : 'Activate';

                            statusText.textContent =
                                `Do you want to update the student status of ${name} from ${fromStatus} to ${toStatus}?`;
                        });
                    });

                    // Handle status change confirmation
                    {{--  document.getElementById('confirmChangeStatus').addEventListener('click', function() {
                if (studentId) {
                    var url = '{{ route(
                        '
                                    user.toggle.status ',
                        ': id ',
                    ) }}'.replace(':id',
                        studentId);
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                location.reload();
                            } else {
                                alert('Failed to update status. Please try again.');
                            }
                        },
                        error: function() {
                            alert('Error occurred while updating status.');
                        }
                    });
                }
            });
        });  --}}

                    // To display active/inactive logs for a user from the UserLog table
                    document.addEventListener('DOMContentLoaded', function() {
                        const logsList = document.getElementById('logsList');

                        document.addEventListener('click', function(event) {
                            if (event.target && event.target.matches('[data-bs-toggle="offcanvas"]')) {
                                const userId = event.target.getAttribute('data-id');

                                if (!userId) {
                                    console.error('User ID is missing!');
                                    return;
                                }

                                // Clear existing logs and show loading state
                                logsList.innerHTML = '<li>Loading logs...</li>';

                                // Fetch logs from the backend
                                fetch(`/school-portal/user/logs/${userId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        // Clear the loading message
                                        logsList.innerHTML = '';

                                        if (!Array.isArray(data) || data.length === 0) {
                                            logsList.innerHTML =
                                                '<li>No logs found for this user.</li>';
                                            return;
                                        }

                                        // Render logs dynamically
                                        data.forEach(log => {
                                            const logType = log.action_as === 'user_active' ?
                                                'activated' :
                                                'deactivated';
                                            const logIcon = log.action_as === 'user_active' ?
                                                '{{ asset('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                frontend / images / activated - icon.svg ') }}' :
                                                '{{ asset('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                frontend / images / deactivated - icon.svg ') }}';

                                            logsList.innerHTML += `
                            <li>
                                <div class="logsInner ${logType}">
                                    <figure class="m-0">
                                        <img src="${logIcon}" alt="" width="36">
                                    </figure>
                                    <div>
                                        <span>${log.title}</span>
                                        <strong>
                                            <img src="{{ asset('frontend/images/time-date-icon.svg') }}" alt="">
                                            ${new Date(log.log_date).toLocaleDateString()} 
                                            <b class="fw">${new Date(log.log_date).toLocaleTimeString()}</b>
                                        </strong>
                                    </div>
                                </div>
                            </li>
                        `;
                                        });
                                    })
                                    .catch(error => {
                                        console.error('Error fetching logs:', error);
                                        logsList.innerHTML =
                                            '<li>Error loading logs. Please try again later.</li>';
                                    });
                            }
                        });
                    });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-student-btn');
            const addButton = document.querySelector('.addBtn');
            const offcanvasTitle = document.querySelector('.offcanvas-heading');
            const form = document.getElementById('add-plan-form');
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#studentTableBody tr');

            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    const studentRow = event.target.closest('tr');
                    const studentId = studentRow.getAttribute('data-id');

                    offcanvasTitle.textContent = 'View/Edit Student';

                    document.getElementById('student_id_field').value = studentId;
                    document.querySelector('input[name="admission_no"]').value = studentRow.dataset
                        .admission_no || '';
                    document.querySelector('input[name="name"]').value = studentRow.dataset.name ||
                        '';
                    document.querySelector('input[name="email"]').value = studentRow.dataset
                        .email ||
                        '';
                    document.querySelector('input[name="parent_name"]').value = studentRow.dataset
                        .parent_name ||
                        '';
                    document.querySelector('input[name="admission_date"]').value = studentRow
                        .dataset.admission_date || '';
                    document.querySelector('input[name="dob"]').value = studentRow.dataset.dob ||
                        '';
                    document.querySelector('select[name="class"]').value = studentRow.dataset
                        .class || '';
                    document.querySelector('select[name="section"]').value = studentRow.dataset
                        .section || '';
                    document.querySelector('input[name="parent_mobile_no"]').value = studentRow
                        .dataset.parent_mobile_no || '';

                    document.querySelector('input[name="student_id"]').value = studentRow.dataset
                        .id || '';
                });
            });

            if (addButton) {
                addButton.addEventListener('click', function() {
                    offcanvasTitle.textContent = 'Add Student';
                    form.reset();
                    document.getElementById('student_id_field').value = '';
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    tableRows.forEach(row => {
                        const title = row.getAttribute('data-name').toLowerCase();

                        if (title.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>


    <script>
        function filterStudents(status) {
            const url = new URL(window.location.href);

            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get("status");

            if (status === 1) {
                document.getElementById("activeStudents").classList.add('active');
            } else if (status === 0) {
                document.getElementById("inactiveStudents").classList.add('active');
            }

            document.getElementById('activeStudents').addEventListener('click', function() {
                filterStudents(1);
            });

            document.getElementById('inactiveStudents').addEventListener('click', function() {
                filterStudents(0);
            });
        });
    </script>
    <script>
        function sortStudents(order) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', order);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sortOrder = urlParams.get("sort");

            if (sortOrder === 'asc') {
                document.getElementById("sortAsc").classList.add('active');
            } else if (sortOrder === 'desc') {
                document.getElementById("sortDesc").classList.add('active');
            }

            document.getElementById('sortAsc').addEventListener('click', function(event) {
                event.preventDefault();
                sortStudents('asc');
            });

            document.getElementById('sortDesc').addEventListener('click', function(event) {
                event.preventDefault();
                sortStudents('desc');
            });
        });
    </script>
    {{--
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('.offcanvas.show')) {
                const offcanvasBackdrop = document.createElement('div');
                offcanvasBackdrop.classList.add('offcanvas-backdrop', 'fade', 'show');
                document.body.appendChild(offcanvasBackdrop);

                // Ensure the offcanvas-body is scrollable
                const offcanvasBody = document.querySelector('.offcanvas-body');
                if (offcanvasBody) {
                    offcanvasBody.style.overflowY = 'auto';
                }
            }
        });
    </script> --}}
@endsection
