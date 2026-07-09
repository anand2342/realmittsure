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
            <div class="col-md-8">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">Login Access Details</h5>
                    <p> Below are the login credentials and access details for the users. Only school administrators
                        can view this information to manage user access securely.
                    </p>
                    @if ($userType == 'teachers')
                        <a href="{{ route('sp.teacher.manager') }}"
                            class="btn btn-primary-gradient rounded-1 addBtn ">Back</a>
                    @else
                        <a href="{{ route('sp.student.manager') }}"
                            class="btn btn-primary-gradient rounded-1 addBtn ">Back</a>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="teacherRighr position-relative">
                    <img src="{{ asset('frontend/images/teacher-manager-img.svg') }}" alt="" class="teacherImg">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="teacherTable">
                <div class="headerTbl">
                    <h6 class="m-0">All Users</h6>
                    <div class="teacherrightTable">
                        <div class="tableSearch">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Name">
                        </div>
                        @if ($userType == 'students')
                        <div class="col">
                            <form method="GET" action="{{ url()->current() }}">
                                {{-- You can also use the action method to specify a route if needed --}}
                                <select name="class" class="form-select" onchange="this.form.submit()">
                                    <option value="">Search by Class</option>
                                    @foreach ($classes as $id => $class)
                                        <option value="{{ $id }}" {{ $id == request('class') ? 'selected' : '' }}>
                                            {{ $class }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @endif
                    

                        <div class="dropdown">
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
                        </div>
                        <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Filter">
                                    <img src="{{ asset('frontend/images/filter-icon.svg') }}" alt="">
                                </span>
                            </button>
                            <ul class="dropdown-menu">
                                {{--  <li><a class="dropdown-item" href="#">Subject</a></li>
                                    <li><a class="dropdown-item" href="#">Class</a></li>  --}}
                                <li><a class="dropdown-item" href="#" id="active">Active Students</a></li>
                                <li><a class="dropdown-item" href="#" id="inactive">Inactive Students</a>
                                <li><a class="dropdown-item" href="{{ route('sp.student.manager') }}">All Students</a>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('login.access.details.export', $userType) }}" class="bg-transparent border-0 p-0">
                            <button class="bg-transparent border-0 p-0" type="button">
                                <span>
                                    <img src="{{ asset('frontend/images/download-icon.svg') }}" alt="Download"
                                        title="Download">
                                </span>
                            </button>
                        </a>

                    </div>
                </div>
                <div class="px-3 py-2">
                    <div class="table-responsive tbleDiv">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    @if ($userType == 'students')
                                        <th>Class</th>
                                    @endif
                                    <th>Login Email</th>
                                    <th>Login Mob. No.</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name ?? '' }} </td>
                                        @if ($userType == 'students')
                                            <td>{{ App\Models\SchoolClass::where('id', $user->studentDetails->class)->value('name') }}
                                            </td>
                                        @endif
                                        <td>{{ $user->email ?? '' }} </td>
                                        <td>{{ $user->mobile_no ?? '' }}</td>
                                        <td>{{ $user->validate_string ?? '' }}</td>
                                        <td>
                                            <span class="{{ $user->status == 1 ? 'activeTxt' : 'deactiveTxt' }}">
                                                {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="customPagination mt-4">
                        <ul class="pagination">
                            <li class="page-item {{ $users->onFirstPage() ? 'disabled' : '' }} previous-item">
                                <a class="page-link" href="{{ $users->previousPageUrl() }}">
                                    <span><img src="{{ asset('frontend/images/arrowprw.svg') }}" width="6"></span>
                                </a>
                            </li>

                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                <li class="page-item {{ $page == $users->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <li class="page-item {{ $users->hasMorePages() ? '' : 'disabled' }} next-item">
                                <a class="page-link" href="{{ $users->nextPageUrl() }}">
                                    <span><img src="{{ asset('frontend/images/arrownxt.svg') }}" width="6"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterStudents(status) {
            const url = new URL(window.location.href);

            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');

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


            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get("status");

            if (status === 1) {
                document.getElementById("active").classList.add('active');
            } else if (status === 0) {
                document.getElementById("inactive").classList.add('active');
            }

            document.getElementById('active').addEventListener('click', function() {
                filterStudents(1);
            });

            document.getElementById('inactive').addEventListener('click', function() {
                filterStudents(0);
            });
        });
    </script>
    <script>
        function sortUsers(order) {
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
                sortUsers('asc');
            });

            document.getElementById('sortDesc').addEventListener('click', function(event) {
                event.preventDefault();
                sortUsers('desc');
            });
        });

    </script>
@endsection
