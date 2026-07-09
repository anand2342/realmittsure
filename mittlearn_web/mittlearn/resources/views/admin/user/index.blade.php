@extends('admin.layouts.master')

@section('content')

    <div>
        <div class="pagetitle">
            <h1> Users</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('user.index') }}">
                                <div class="d-flex flex-wrap align-items-center mt-4">
                                    <div class="col-md-3 me-2 mb-2">
                                        <input type="text" class="form-control" placeholder="Search by Name"
                                            name="name" value="{{ request('name') }}">
                                    </div>
                                    <div class="col-md-3 me-2 mb-2">
                                        <input type="text" class="form-control" placeholder="Search by Email"
                                            name="email" value="{{ request('email') }}">
                                    </div>
                                    <div class="col-md-3 me-2 mb-2">
                                        <input type="text" class="form-control" placeholder="Search by Mobile No."
                                            name="mobile_no" value="{{ request('mobile_no') }}">
                                    </div>

                                    @if ($role == 'school_student' || $role == 'school_teacher')
                                        <div class="col-md-2 me-2 mb-2">
                                            <input type="text" class="form-control" placeholder="Search by School"
                                                name="school_name" value="{{ request('school_name') }}">
                                        </div>
                                    @endif
                                    @if ($role == 'd2c_user')
                                        <div class="col-md-2 me-2 mb-2">
                                            <input type="text" class="form-control" placeholder="Search by School"
                                                name="d2c_user_school_name" value="{{ request('d2c_user_school_name') }}">
                                        </div>
                                    @endif
                                    @if ($role == 'd2c_user')
                                        <div class="col-md-2 me-2 mb-2">
                                            <select class="form-control" name="category">
                                                <option value="">Search by Category</option>
                                                @foreach ($categories as $key => $item)
                                                    <option value=" {{ $key }}"
                                                        {{ request('category') == $key ? 'selected' : '' }}>
                                                        {{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if ($role == 'school_admin')
                                        <div class="col-md-2 me-2 mb-2">
                                            <select class="form-control" name="school_type">
                                                <option value="">Search by School Type</option>
                                                @foreach (config('constants.SCHOOL_TYPES_FOR_FILTER') as $key => $item)
                                                    <option value=" {{ $key }}"
                                                        {{ request('school_type') == $key ? 'selected' : '' }}>
                                                        {{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="col-md-2 me-2 mb-2">
                                        <input type="hidden" class="form-control" placeholder="Search by Email"
                                            name="role" value="{{ request('role') }}">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <h5 class="card-title mb-0">
                                    @if (request()->has('role'))
                                        @php
                                            $roleName = App\Models\Role::where('role_slug', request('role'))->value(
                                                'role_name',
                                            );
                                        @endphp
                                        All {{ $roleName }}
                                    @else
                                        All Users
                                    @endif
                                </h5>

                                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                    <label for="paginationSelectOnpage" class="me-2 mb-0 text-nowrap">Per Page
                                        Records:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                        style="width: 80px;">
                                        <option value="" disabled
                                            {{ session('per_page_records') ? '' : 'selected' }}>
                                            --Select--</option>
                                        @foreach ([10, 20, 30, 40, 50] as $option)
                                            <option value="{{ $option }}"
                                                {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="exportDropdownContainer" class="d-inline-block d-none">
                                        <button class="btn btn-info btn-sm" type="button" id="exportDropdown">Send
                                            Credentials via SMS</button>
                                    </div>
                                    @isPermission('user.create')
                                        <a href="{{ route('user.create') }}" class="btn btn-success btn-sm text-nowrap">Add
                                            New</a>
                                    @endisPermission

                                    <div class="me-2 mb-2 mb-md-0">
                                        @if (request('role') !== 'super_admin')
                                            <button type="button" onclick="exportWithFilters()"
                                                class="btn btn-success btn-sm text-nowrap">
                                                <i class="fas fa-file-excel"></i> Export @if (request()->has('role'))
                                                    @php
                                                        $roleName = App\Models\Role::where(
                                                            'role_slug',
                                                            request('role'),
                                                        )->value('role_name');
                                                    @endphp
                                                    {{ $roleName }}
                                                @else
                                                    Users
                                                @endif
                                            </button>
                                        @endif

                                    </div>



                                    {{-- @endisPermission  --}}
                                </div>
                            </div>
                            <hr class="form-divider">
                            <ul class="nav nav-tabs nav-tabs-bordered" id="BookTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab === 'Active' ? 'active' : '' }}" id="Active-tab"
                                        data-bs-toggle="tab" data-bs-target="#Active-users" type="button" role="tab"
                                        aria-controls="Active-users"
                                        aria-selected="{{ $activeTab === 'Active' ? 'true' : 'false' }}">
                                        Active
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab === 'InActive' ? 'active' : '' }}"
                                        id="InActive-tab" data-bs-toggle="tab" data-bs-target="#InActive-users"
                                        type="button" role="tab" aria-controls="InActive-users"
                                        aria-selected="{{ $activeTab === 'InActive' ? 'true' : 'false' }}">
                                        Inactive
                                    </button>
                                </li>
                                {{-- @if ($role == 'school_admin')
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $activeTab === 'CrmSchools' ? 'active' : '' }}"
                                            id="crm-tab" data-bs-toggle="tab" data-bs-target="#crm-users"
                                            type="button" role="tab" aria-controls="crm-users"
                                            aria-selected="{{ $activeTab === 'CrmSchools' ? 'true' : 'false' }}">
                                            Schools From CRM
                                        </button>
                                    </li>
                                @endif --}}
                                @if ($role == 'b2c_student')
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $activeTab === 'guest' ? 'active' : '' }}"
                                            id="guest-tab" data-bs-toggle="tab" data-bs-target="#guest-users"
                                            type="button" role="tab" aria-controls="guest-users"
                                            aria-selected="{{ $activeTab === 'guest' ? 'true' : 'false' }}">
                                            System Generated Users (iOS)
                                        </button>
                                    </li>
                                @endif
                            </ul>


                            <div class="tab-content pt-2" id="BookTabContent">
                                <form id="exportForm" method="POST" action="{{ route('send.sms.user') }}">
                                    @csrf
                                    <input type="hidden" name="ids" id="selectedIds" value="">
                                </form>
                                <div class="tab-pane fade {{ $activeTab === 'Active' ? 'show active' : '' }}"
                                    id="Active-users" role="tabpanel" aria-labelledby="Active-tab">
                                    <div class="table-responsive tbleDiv ">
                                        <table class="table table-striped table-bordered mt-4">
                                            <thead>
                                                <tr>
                                                    @if ($role == 'school_student' || $role == 'b2c_student' || $role == 'd2c_user')
                                                        <th></th>
                                                    @endif
                                                    <th>S.No</th>
                                                    @if ($role == 'school_admin')
                                                        <th>Unique ID</th>
                                                    @elseif($role == 'salesman')
                                                        <th>Employee ID</th>
                                                    @elseif($role == 'distributors')
                                                        <th>Distributor ID</th>
                                                    @endif
                                                    <th>Full Name</th>
                                                    @if ($role == 'school_admin')
                                                        <th>Username</th>
                                                    @endif
                                                    <th>Email/Mobile No.</th>
                                                    @if ($role == 'd2c_user')
                                                        <th>Category</th>
                                                        <th>Class</th>
                                                    @endif
                                                    @if ($role == 'school_student' || $role == 'school_teacher')
                                                        <th>School ID</th>
                                                        <th>School</th>
                                                    @endif
                                                    @if ($role == 'b2c_student')
                                                        <th>Courses</th>
                                                    @endif
                                                    @if ($role !== 'distributors' && $role !== 'salesman' && $role !== 'instructor' && getUserRoles() == 'super_admin')
                                                        <th>Password</th>
                                                    @endif
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($activeUsers) && $activeUsers->count())
                                                    @foreach ($activeUsers as $data)
                                                        {{-- @dump($data) --}}
                                                        <tr>
                                                            @if ($role == 'school_student' || $role == 'b2c_student' || $role == 'd2c_user')
                                                                <td>
                                                                    @if ($loop->first)
                                                                        <input type="checkbox" id="selectAll"
                                                                            style="margin-top: -61px; display: block">All
                                                                    @endif
                                                                    <hr class="formdivider"
                                                                        style="color: #ffffff !important;">
                                                                    <input type="checkbox" class="row-checkbox"
                                                                        value="{{ $data->id }}">
                                                                </td>
                                                            @endif
                                                            <td>{{ $activeUsers->currentPage() * $activeUsers->perPage() - $activeUsers->perPage() + $loop->iteration . '.' }}
                                                            </td>
                                                            @if ($role == 'school_admin')
                                                                <td>{{ $data->additionalDetails->school->unique_id ?? '' }}
                                                                @elseif($role == 'salesman')
                                                                <td>{{ $data->additionalDetails->employee_id ?? '' }}
                                                                @elseif($role == 'distributors')
                                                                <td>{{ $data->additionalDetails->distributor_id ?? '' }}
                                                            @endif
                                                            <td>
                                                                {{ $data->name }}

                                                                @if ($role === 'school_admin' && !empty($data->soid))
                                                                <br>
                                                                    <span class="badge text-bg-success text-white mt-1">
                                                                        CRM Fetched
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            </td>
                                                            @if ($role == 'school_admin')
                                                                <td>{{ $data->username ?? '' }}</td>
                                                            @endif
                                                            <td>
                                                                @if (!empty($data->email))
                                                                    {{ $data->email ?? '' }}
                                                                @endif
                                                                @if (!empty($data->email) && !empty($data->mobile_no))
                                                                    <br>
                                                                @endif
                                                                @if (!empty($data->mobile_no))
                                                                    {{ $data->mobile_no }}
                                                                @endif
                                                            </td>
                                                            @if ($role == 'd2c_user')
                                                                <td>{{ $data->userClass->category->name ?? '' }}
                                                                <td>{{ $data->studentDetails->className->name ?? '' }}
                                                                </td>
                                                            @endif
                                                            @if ($role == 'school_student' || $role == 'school_teacher')
                                                                <td>{{ $data->additionalDetails->school->unique_id ?? '' }}
                                                                <td>{{ $data->additionalDetails->school->name ?? '' }}
                                                                </td>
                                                            @endif
                                                            @if ($role == 'b2c_student')
                                                                <td>
                                                                    @if (!empty($data->assigned_courses))
                                                                        @foreach (explode(',', $data->assigned_courses) as $course)
                                                                            <span class="assignedCourseB2C">
                                                                                {{ trim($course) }}
                                                                            </span>
                                                                        @endforeach
                                                                    @else
                                                                        No courses
                                                                    @endif
                                                                </td>
                                                            @endif
                                                            @if ($role !== 'distributors' && $role !== 'salesman' && $role !== 'instructor' && getUserRoles() == 'super_admin')
                                                                <td>{{ $data->validate_string ?? 'N/A' }}</td>
                                                            @endif
                                                            <td>
                                                                @if ($role != 'qd_developer')
                                                                    @isPermission('user.view')
                                                                        <a class="btn btn-sm btn-info me-1"
                                                                            href="{{ route('user.view', $data->id) }}">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                    @endisPermission
                                                                    @isPermission('user.edit')
                                                                        <a class="btn btn-sm btn-warning"
                                                                            href="{{ route('user.edit', $data->id) }}">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    @endisPermission
                                                                    {{-- @isPermission('user.delete')
                                                                    <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                        onclick="confirmDelete('{{ route('user.delete', $data->id) }}')">
                                                    <i class="fa fa-trash"></i></a>
                                                    @endisPermission --}}
                                                                    @isPermission('user.active.inactive')
                                                                        <a class="btn btn-sm statusBtn {{ $data->status == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                            onclick="confirmStatus('{{ route('user.active.inactive', $data->id) }}')">
                                                                            {{ $data->status == 1 ? 'Active' : 'Inactive' }}
                                                                        </a>
                                                                    @endisPermission
                                                                    {{-- @isPermission('superadmin.loginAsUser') --}}
                                                                    @if ($role == 'school_admin')
                                                                        <a href="{{ route('school.assign.digital.content', $data->id) }}"
                                                                            class="btn btn-primary">
                                                                            Assign Content
                                                                        </a>
                                                                    @endif
                                                                    {{-- @dd(Auth::user()->role ); --}}
                                                                    {{-- @endisPermission --}}
                                                                    @isPermission('superadmin.loginAsUser')
                                                                        @if ($role == 'school_admin' && (Auth::user()->role == 'super_admin' || Auth::user()->role == 'qd_developer'))
                                                                            <a href="{{ route('superadmin.loginAsUser', $data->id) }}"
                                                                                class="btn btn-primary">
                                                                                Login as school
                                                                            </a>
                                                                        @endif
                                                                    @endisPermission
                                                                    {{-- // super admin can chnaged role for students --}}
                                                                    @if (in_array($role, ['school_student', 'b2c_student']) && in_array(Auth::user()->role, ['super_admin', 'qd_developer']))
                                                                        <button type="button" class="btn btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#changeRoleModal"
                                                                            data-user-id="{{ $data->id }}">
                                                                            Change Type
                                                                        </button>
                                                                    @endif

                                                                    <!-- Modal -->
                                                                    <div class="modal fade" id="changeRoleModal"
                                                                        tabindex="-1"
                                                                        aria-labelledby="changeRoleModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <form id="changeRoleForm">
                                                                                @csrf
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title"
                                                                                            id="changeRoleModalLabel">
                                                                                            Change
                                                                                            User Role</h5>
                                                                                        <button type="button"
                                                                                            class="btn-close"
                                                                                            data-bs-dismiss="modal"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <input type="hidden"
                                                                                            id="user_id" name="user_id">
                                                                                        <div class="mb-3">
                                                                                            <label for="role"
                                                                                                class="form-label">Select
                                                                                                Role</label>
                                                                                            <select name="role"
                                                                                                id="role"
                                                                                                class="form-select"
                                                                                                required>
                                                                                                <option value="">
                                                                                                    Select
                                                                                                    Role</option>
                                                                                                <option value="d2c_user">
                                                                                                    D2C User</option>
                                                                                                <option
                                                                                                    value="b2c_student">B2C
                                                                                                    User</option>
                                                                                                <option
                                                                                                    value="school_student">
                                                                                                    School Student</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            data-bs-dismiss="modal">Cancel</button>
                                                                                        <button type="submit"
                                                                                            class="btn btn-success">Save
                                                                                            Changes</button>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center">There is no data</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="d-flex justify-content-right text-right">
                                        {!! $activeUsers->appends(array_merge(request()->query(), ['active_tab' => 'Active']))->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                                <div class="tab-pane fade {{ $activeTab === 'InActive' ? 'show active' : '' }}"
                                    id="InActive-users" role="tabpanel" aria-labelledby="InActive-tab">
                                    <div class="table-responsive tbleDiv ">
                                        <table class="table table-striped table-bordered mt-4">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Name</th>
                                                    <th>Email/Mobile No.</th>
                                                    @if ($role == 'school_student' || $role == 'school_teacher')
                                                        <th>School</th>
                                                    @endif
                                                    @if ($role == 'd2c_user')
                                                        <th>Category</th>
                                                        <th>Class</th>
                                                    @endif
                                                    @if ($role == 'b2c_student')
                                                        <th>Courses</th>
                                                    @endif
                                                    @if ($role !== 'distributors' && $role !== 'salesman' && $role !== 'instructor' && getUserRoles() == 'super_admin')
                                                        <th>Password</th>
                                                    @endif
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($inActiveUsers) && $inActiveUsers->count())
                                                    @foreach ($inActiveUsers as $data)
                                                        <tr>
                                                            <td>{{ $inActiveUsers->currentPage() * $inActiveUsers->perPage() - $inActiveUsers->perPage() + $loop->iteration . '.' }}
                                                            </td>
                                                            <td>{{ $data->name }}</td>
                                                            <td>
                                                                @if (!empty($data->email))
                                                                    {{ $data->email }}
                                                                @endif
                                                                @if (!empty($data->email) && !empty($data->mobile_no))
                                                                    <br>
                                                                @endif
                                                                @if (!empty($data->mobile_no))
                                                                    {{ $data->mobile_no }}
                                                                @endif
                                                            </td>
                                                            @if ($role == 'd2c_user')
                                                                <td>{{ $data->userClass->category->name ?? '' }}
                                                                <td>{{ $data->studentDetails->className->name ?? '' }}
                                                                </td>
                                                            @endif
                                                            @if ($role == 'school_student' || $role == 'school_teacher')
                                                                <td>{{ $data->additionalDetails->school->name ?? 'N/A' }}
                                                                </td>
                                                            @endif
                                                            @if ($role == 'b2c_student')
                                                                <td>{{ $data->assigned_courses ?? 'No courses' }}
                                                                </td>
                                                            @endif
                                                            @if ($role !== 'distributors' && $role !== 'salesman' && $role !== 'instructor' && getUserRoles() == 'super_admin')
                                                                <td>{{ $data->validate_string ?? 'N/A' }}</td>
                                                            @endif
                                                            <td>
                                                                @isPermission('user.view')
                                                                    <a class="btn btn-sm btn-info me-1"
                                                                        href="{{ route('user.view', $data->id) }}">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                @endisPermission
                                                                @isPermission('user.edit')
                                                                    <a class="btn btn-sm btn-warning"
                                                                        href="{{ route('user.edit', $data->id) }}">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                                @endisPermission
                                                                {{-- @isPermission('user.delete')
                                                            <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                onclick="confirmDelete('{{ route('user.delete', $data->id) }}')">
                                                    <i class="fa fa-trash"></i></a>
                                                    @endisPermission --}}
                                                                @isPermission('user.active.inactive')
                                                                    <a class="btn btn-sm statusBtn {{ $data->status == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                        onclick="confirmStatus('{{ route('user.active.inactive', $data->id) }}')">
                                                                        {{ $data->status == 1 ? 'Active' : 'Inactive' }}
                                                                    </a>
                                                                @endisPermission
                                                                {{-- @isPermission('superadmin.loginAsUser') --}}
                                                                {{-- @if ($role == 'school_admin')
                                                                    <a href="{{ route('school.assign.digital.content', $data->id) }}"
                                                    class="btn btn-primary">
                                                    Assign Digital Content
                                                    </a>
                                                    @endif --}}
                                                                {{-- @endisPermission --}}

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center">There is no data</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-right text-right">
                                        {!! $inActiveUsers->appends(array_merge(request()->query(), ['active_tab' => 'InActive']))->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>
                                @if ($role == 'b2c_student')
                                    <div class="tab-pane fade {{ $activeTab === 'guest' ? 'show active' : '' }}"
                                        id="guest-users" role="tabpanel" aria-labelledby="guest-tab">
                                        <div class="table-responsive tbleDiv">
                                            <table class="table table-striped table-bordered mt-4">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Name</th>
                                                        <th>Email/Mobile No.</th>
                                                        <th>Courses</th>
                                                        <th>Password</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($iosGuestUsers) && $iosGuestUsers->count())
                                                        @foreach ($iosGuestUsers as $data)
                                                            <tr>
                                                                <td>{{ $iosGuestUsers->currentPage() * $iosGuestUsers->perPage() - $iosGuestUsers->perPage() + $loop->iteration . '.' }}
                                                                </td>
                                                                <td>{{ $data->name }}</td>
                                                                <td>
                                                                    @if (!empty($data->email))
                                                                        {{ $data->email }}
                                                                    @endif
                                                                    @if (!empty($data->email) && !empty($data->mobile_no))
                                                                        <br>
                                                                    @endif
                                                                    @if (!empty($data->mobile_no))
                                                                        {{ $data->mobile_no }}
                                                                    @endif
                                                                </td>
                                                                @if ($role == 'd2c_user')
                                                                    <td>{{ $data->userClass->category->name ?? '' }}
                                                                    <td>{{ $data->studentDetails->className->name ?? '' }}
                                                                    </td>
                                                                @endif
                                                                @if ($role == 'school_student' || $role == 'school_teacher')
                                                                    <td>{{ $data->additionalDetails->school->name ?? 'N/A' }}
                                                                    </td>
                                                                @endif
                                                                @if ($role == 'b2c_student')
                                                                    <td>{{ $data->assigned_courses ?? 'No courses' }}
                                                                    </td>
                                                                @endif
                                                                @if ($role !== 'distributors' && $role !== 'salesman' && $role !== 'instructor' && getUserRoles() == 'super_admin')
                                                                    <td>{{ $data->validate_string ?? 'N/A' }}</td>
                                                                @endif
                                                                <td>
                                                                    @isPermission('user.view')
                                                                        <a class="btn btn-sm btn-info me-1"
                                                                            href="{{ route('user.view', $data->id) }}">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                    @endisPermission
                                                                    @isPermission('user.edit')
                                                                        <a class="btn btn-sm btn-warning"
                                                                            href="{{ route('user.edit', $data->id) }}">
                                                                            <i class="fa fa-pencil"></i>
                                                                        </a>
                                                                    @endisPermission
                                                                    {{-- @isPermission('user.delete')
                                                            <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                onclick="confirmDelete('{{ route('user.delete', $data->id) }}')">
                                                    <i class="fa fa-trash"></i></a>
                                                    @endisPermission --}}
                                                                    @isPermission('user.active.inactive')
                                                                        <a class="btn btn-sm statusBtn {{ $data->status == 1 ? 'btn-success' : 'btn-danger' }}"
                                                                            onclick="confirmStatus('{{ route('user.active.inactive', $data->id) }}')">
                                                                            {{ $data->status == 1 ? 'Active' : 'Inactive' }}
                                                                        </a>
                                                                    @endisPermission
                                                                    {{-- @isPermission('superadmin.loginAsUser') --}}
                                                                    {{-- @if ($role == 'school_admin')
                                                                    <a href="{{ route('school.assign.digital.content', $data->id) }}"
                                                    class="btn btn-primary">
                                                    Assign Digital Content
                                                    </a>
                                                    @endif --}}
                                                                    {{-- @endisPermission --}}

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="6" class="text-center">There is no data</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="d-flex justify-content-right text-right">
                                            {!! $iosGuestUsers->appends(array_merge(request()->query(), ['active_tab' => 'guest']))->links('pagination::bootstrap-4') !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="tab-pane fade {{ $activeTab === 'CrmSchools' ? 'show active' : '' }}"
                                    id="crm-users" role="tabpanel" aria-labelledby="crm-tab">
                                    <div class="table-responsive tbleDiv">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th><b>SO Id</b></th>
                                                    <th><b>Name</b></th>
                                                    <th><b>Email</b></th>
                                                    <th><b>Mobile</b></th>
                                                    <th><b>RM Name</b></th>
                                                    <th><b>RM Mobile</b></th>
                                                    <!-- <th><b>Status</b></th> -->
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($crmSchools) && $crmSchools->count() > 0)
                                                    @foreach ($crmSchools as $item)
                                                        @php
                                                            $rmDetail = \App\Models\User::where(
                                                                'id',
                                                                $item->user_additional_details->assign_to,
                                                            )->first();
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $crmSchools->currentPage() * $crmSchools->perPage() - $crmSchools->perPage() + $loop->iteration . '.' }}
                                                            </td>
                                                            <td>{{ $item->user->soid ?? '' }}</td>
                                                            <td>{{ $item->name ?? '' }}</td>
                                                            <td>{{ $item->user->email ?? '' }}</td>
                                                            <td>{{ $item->user->mobile_no ?? '' }}</td>
                                                            <td>{{ $rmDetail->name ?? '' }}</td>
                                                            <td>{{ $rmDetail->mobile_no ?? '' }}</td>

                                                            <td>
                                                                @if (
                                                                    $item->user &&
                                                                        ((filled($item->user->email) && $item->user->email !== 'N/A') ||
                                                                            (filled($item->user->mobile_no) && $item->user->mobile_no !== 'N/A')))
                                                                    {{-- Has contact info: show Verify button with confirm modal --}}
                                                                    <button class="btn btn-sm btn-success me-1"
                                                                        onclick="confirmVerifySchool(
                                                                            '{{ route('crm.school.verify', $item->id) }}',
                                                                            '{{ addslashes($item->name) }}'
                                                                        )">
                                                                        Activate
                                                                    </button>
                                                                @else
                                                                    {{-- No contact info: show Verify (edit page) + Send SMS to RM --}}
                                                                    <a class="btn btn-sm btn-primary me-1"
                                                                        href="{{ route('user.edit', ['id' => $item->user_id, 'verify' => 'verifySchool']) }}"
                                                                        title="Verify">
                                                                        Verify
                                                                    </a>
                                                                    <button disabled class="btn btn-sm btn-warning"
                                                                        onclick="confirmSmsToRM(
                                                                            '{{ route('crm.school.sms.rm', $item->id) }}',
                                                                            '{{ addslashes($item->name) }}'
                                                                        )">
                                                                        Send SMS to RM
                                                                    </button>
                                                                @endif
                                                                @if (Auth::user()->is_admin = 2)
                                                                    <button class="btn btn-sm btn-danger"
                                                                        onclick="openRemoveModal({{ $item->id }},'{{ addslashes($item->name) }}')">
                                                                        Remove
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center">No CRM Schools found</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-right text-right">
                                        {!! $crmSchools->appends(array_merge(request()->query(), ['active_tab' => 'CrmSchools']))->links('pagination::bootstrap-4') !!}
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
    {{-- ══════════════════════════════════════════════════════════════════════
     Remove Modal  –  asks for a remark (min 4 words) before soft-delete
     ══════════════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="removeCrmSchoolModal" tabindex="-1" aria-labelledby="removeCrmSchoolModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="removeCrmSchoolModalLabel">
                        <i class="fas fa-trash-alt me-2"></i>Remove School
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-1 text-muted" style="font-size:13px;">You are about to remove:</p>
                    <p class="fw-semibold mb-3" id="removeSchoolNameLabel" style="font-size:15px;"></p>

                    <label for="removeRemark" class="form-label" style="font-size:13px;font-weight:500;">
                        Reason for removal <span class="text-danger">*</span>
                        <small class="text-muted fw-normal ms-1">(minimum 4 words)</small>
                    </label>
                    <textarea id="removeRemark" class="form-control" rows="3" maxlength="500"
                        placeholder="e.g. Duplicate entry created by CRM team by mistake" oninput="crmRemoveValidate()"
                        style="font-size:13px;resize:vertical;"></textarea>

                    <div class="d-flex justify-content-between mt-1">
                        <span id="remarkWordError" class="text-danger" style="font-size:12px;display:none;">
                            Please enter at least 4 words.
                        </span>
                        <span id="remarkWordCount" class="text-muted ms-auto" style="font-size:11px;">0 words</span>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-danger" id="confirmRemoveBtn" disabled
                        onclick="submitCrmRemove()">
                        <span id="confirmRemoveBtnText">Confirm Remove</span>
                        <span id="confirmRemoveSpinner" class="spinner-border spinner-border-sm ms-1 d-none"
                            role="status"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>


    <script>
        (function() {
            // ── state ────────────────────────────────────────────────────────────────
            let _schoolId = null;
            let _schoolName = '';
            let _modalInst = null;

            // ── open ─────────────────────────────────────────────────────────────────
            window.openRemoveModal = function(schoolId, schoolName) {
                _schoolId = schoolId;
                _schoolName = schoolName;

                document.getElementById('removeSchoolNameLabel').textContent = schoolName;
                document.getElementById('removeRemark').value = '';
                document.getElementById('remarkWordError').style.display = 'none';
                document.getElementById('remarkWordCount').textContent = '0 words';
                document.getElementById('confirmRemoveBtn').disabled = true;
                document.getElementById('confirmRemoveBtnText').textContent = 'Confirm Remove';
                document.getElementById('confirmRemoveSpinner').classList.add('d-none');

                _modalInst = new bootstrap.Modal(document.getElementById('removeCrmSchoolModal'));
                _modalInst.show();
            };

            // ── live validation ───────────────────────────────────────────────────────
            window.crmRemoveValidate = function() {
                const raw = document.getElementById('removeRemark').value.trim();
                const words = raw === '' ? [] : raw.split(/\s+/).filter(w => w.length > 0);
                const wordCount = words.length;
                const valid = wordCount >= 4;

                document.getElementById('remarkWordCount').textContent = wordCount + ' word' + (wordCount === 1 ?
                    '' : 's');
                document.getElementById('confirmRemoveBtn').disabled = !valid;
                document.getElementById('remarkWordError').style.display = (raw.length > 0 && !valid) ? 'block' :
                    'none';
            };

            // ── submit ────────────────────────────────────────────────────────────────
            window.submitCrmRemove = function() {
                const remark = document.getElementById('removeRemark').value.trim();
                const wordCount = remark === '' ? 0 : remark.split(/\s+/).filter(w => w.length > 0).length;

                if (wordCount < 4) {
                    document.getElementById('remarkWordError').style.display = 'block';
                    return;
                }

                // Show spinner, disable button
                document.getElementById('confirmRemoveBtn').disabled = true;
                document.getElementById('confirmRemoveBtnText').textContent = 'Removing…';
                document.getElementById('confirmRemoveSpinner').classList.remove('d-none');

                fetch('{{ route('crm.school.remove') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            school_id: _schoolId,
                            remark: remark
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status) {
                            _modalInst.hide();
                            const row = document.getElementById('crm-school-row-' + _schoolId);
                            if (row) {
                                row.style.transition = 'opacity .3s';
                                row.style.opacity = '0';
                            }
                            // Reload after fade, landing on CrmSchools tab
                            setTimeout(() => {
                                const url = new URL(window.location.href);
                                url.searchParams.set('active_tab', 'CrmSchools');
                                url.searchParams.set('crm_removed', '1');
                                window.location.href = url.toString();
                            }, 200);
                        } else {
                            alert(data.message ?? 'Something went wrong. Please try again.');
                            document.getElementById('confirmRemoveBtn').disabled = false;
                            document.getElementById('confirmRemoveBtnText').textContent = 'Confirm Remove';
                            document.getElementById('confirmRemoveSpinner').classList.add('d-none');
                        }
                    })
                    .catch(() => {
                        alert('Request failed. Please check your connection and try again.');
                        document.getElementById('confirmRemoveBtn').disabled = false;
                        document.getElementById('confirmRemoveBtnText').textContent = 'Confirm Remove';
                        document.getElementById('confirmRemoveSpinner').classList.add('d-none');
                    });
            };
        })();
    </script>
    <script>
        function confirmVerifySchool(url, schoolName) {
            if (confirm('Are you sure you want to verify "' + schoolName + '"? This will mark the school as verified')) {
                window.location.href = url;
            }
        }

        function confirmSmsToRM(url, schoolName) {
            if (confirm('Send SMS to RM for "' + schoolName + '"? The RM will be notified to provide contact details.')) {
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'SMS sent to RM successfully.');
                        } else {
                            alert(data.message || 'Failed to send SMS.');
                        }
                    })
                    .catch(() => alert('Something went wrong. Please try again.'));
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set user_id in modal
            const modal = document.getElementById('changeRoleModal');
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                document.getElementById('user_id').value = userId;
            });

            // Submit form via AJAX
            $('#changeRoleForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('superadmin.changeUserRole') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        $('#changeRoleModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
    <script>
        function exportWithFilters() {
            // Get all form inputs
            const form = document.querySelector('form[method="GET"]');
            const formData = new FormData(form);

            // Convert form data to URL params
            const params = new URLSearchParams();
            for (const [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            // Add roleSlug
            params.append('roleSlug', '{{ request('role ') }}');

            // Redirect to download endpoint with filters
            window.location.href = `{{ route('download.users.data') }}?${params.toString()}`;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-checkbox');
            const exportDropdownContainer = document.getElementById('exportDropdownContainer');
            const exportDropdownButton = document.getElementById('exportDropdown');
            const selectedIdsInput = document.getElementById('selectedIds');

            let selectedIds = new Set();

            // Toggle "Select All" functionality
            if (selectAllCheckbox) {
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
                    updateUI();
                });
            }

            // Individual checkbox change handler
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        selectedIds.add(this.value);
                    } else {
                        selectedIds.delete(this.value);
                    }
                    updateSelectAllState();
                    updateUI();
                });
            });

            // Update Select All checkbox state (checked, unchecked, indeterminate)
            function updateSelectAllState() {
                if (!selectAllCheckbox) return;

                const allChecked = Array.from(rowCheckboxes).every(chk => chk.checked);
                const noneChecked = Array.from(rowCheckboxes).every(chk => !chk.checked);

                if (allChecked) {
                    selectAllCheckbox.checked = true;
                    selectAllCheckbox.indeterminate = false;
                } else if (noneChecked) {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                } else {
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = true;
                }
            }

            // Update hidden input and toggle Send SMS button visibility
            function updateUI() {
                selectedIdsInput.value = Array.from(selectedIds).join(',');
                if (selectedIds.size > 0) {
                    exportDropdownContainer.classList.remove('d-none');
                } else {
                    exportDropdownContainer.classList.add('d-none');
                }
            }

            // On Send SMS button click, show confirmation modal
            exportDropdownButton.addEventListener('click', function() {
                if (selectedIds.size === 0) {
                    Swal.fire('No users selected', 'Please select at least one user to send SMS.',
                        'warning');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send credentials via SMS to the selected users?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        document.getElementById('exportForm').submit();
                    }
                });
            });
        });
    </script>
@endsection
