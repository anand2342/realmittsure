@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <div class="pagetitle">
            <h1>Profile</h1>
        </div>
        <!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            @if (
                                (isset($data->image) && !empty($data->image)))
                                <img src="{{ Storage::url('uploads/user/profile_image/' . $data->image) }}"
                                    alt="Profile Image" class="rounded-circle">
                            @else
                                <img src="{{ asset('frontend/images/default-image.jpg') }}" alt="Default Image"
                                    class="rounded-circle">
                            @endif
                            <h2>{{ $data->name ?? 'N/A' }}</h2>
                            <h3>{{ $data->userRole->role->role_name ?? 'N/A' }}</h3>

                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>
                                @if (
                                    (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_admin') ||
                                        $data->userRole->role_slug === 'school_teacher' ||
                                        $data->userRole->role_slug === 'school_student')
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#Bank-additional-Details"> Additional Details</button>
                                    </li>
                                @endif

                                @if (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_admin')
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Bank-Details">Bank
                                            Details
                                        </button>
                                    </li>
                                @endif
                                @if (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'b2c_student')
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#Address-Details">Address
                                        </button>
                                    </li>
                                @endif
                                @if (
                                    (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'leaders') )
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Social_media"> Social
                                            Media Links</button>
                                    </li>
                                @endif
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    @if (isset($data->userAdditionalDetail->about))
                                        <h5 class="card-title">About</h5>
                                        <p class="small fst-italic">{{ $data->userAdditionalDetail->about ?? 'N/A' }}</p>
                                    @endif

                                    <h5 class="card-title mt-2">Profile Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label mt-2">Full Name</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->name ?? 'N/A' }}</div>
                                    </div>
                                    @if ($data->userRole->role_slug === 'salesman')
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Employee ID</div>
                                            <div class="col-lg-9 col-md-8">
                                                {{ $data->userAdditionalDetail->employee_id ?? 'N/A' }}</div>
                                        </div>
                                    @elseif($data->userRole->role_slug === 'distributors')
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Distributor ID</div>
                                            <div class="col-lg-9 col-md-8">
                                                {{ $data->userAdditionalDetail->distributor_id ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Role Name</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->userRole->role->role_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">
                                            {{ $data->userRole->role_slug === 'school_student' ? 'Parent Contact Number' : 'Contact Number' }}
                                        </div>
                                        <div class="col-lg-9 col-md-8">
                                            {{ $data->userRole->role->role_slug === 'school_admin'
                                                ? $data->userAdditionalDetail->decision_maker_mobile_no ?? 'N/A'
                                                : $data->mobile_no ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->email ?? 'N/A' }}</div>
                                    </div>

                                    @if ($data->userRole->role_slug != 'school_student' && $data->userRole->role_slug != 'b2c_student')
                                        @if ($data->userRole->role_slug != 'salesman' && $data->userRole->role_slug != 'instructor' && $data->userRole->role_slug != 'distributors')
                                            @if ($data->userRole->role_slug != 'school_admin')
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Gender</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        {{ $data->userAdditionalDetail->gender ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Age</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        {{ $data->userAdditionalDetail->age ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Designation</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {{ $data->userAdditionalDetail->designation ?? 'N/A' }}
                                                </div>
                                            </div>
                                        @endif

                                        {{-- @dd($data->userRole->role_slug) --}}
                                        @if ($data->userRole->role_slug != 'leaders')
                                            @if ($data->userRole->role_slug != 'salesman' && $data->userRole->role_slug != 'distributors')
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Country</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        {{ isset($data->userAdditionalDetail->country) ? ucwords($data->userAdditionalDetail->country) : null ?? 'India' }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">State</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {{ $data->userAdditionalDetail->State->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">City</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {{ $data->userAdditionalDetail->City->city ?? 'N/A' }}
                                                </div>
                                            </div>
                                            @if (
                                                $data->userRole->role_slug != 'school_teacher' &&
                                                    $data->userRole->role_slug != 'salesman' && $data->userRole->role_slug != 'instructor' && 
                                                    $data->userRole->role_slug != 'distributors')
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Pin Code</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        {{ $data->schoolDetails->postal_code ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Address</div>
                                                <div class="col-lg-9 col-md-8">
                                                    {{ $data->userAdditionalDetail->address ?? 'N/A' }}
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                </div>

                                <div class="tab-pane fade Bank-additional-Details view pt-3" id="Bank-additional-Details">
                                    <h5 class="card-title">Additional Details</h5>
                                    @if (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_admin')
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Parent School Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->parent_school_name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Website Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                @if (!empty($data->userAdditionalDetail->website))
                                                    <a href="{{ $data->userAdditionalDetail->website }}" target="_blank"
                                                        rel="noopener noreferrer">
                                                        {{ $data->userAdditionalDetail->website }}
                                                    </a>
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Decision Maker Role</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->roleName->role_name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Decision Mobile Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->decision_maker_mobile_no ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">School Registration
                                                Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->school_registration_no ?? 'N/A' }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_student')
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Admission Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->admission_no ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Admission Date</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->studentDetails->doj ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Data of Birth</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->studentDetails->dob ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Class</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->studentDetails->studentClass->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Section</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->studentDetails->section ?? 'N/A' }}
                                            </div>
                                        </div>
                                    @endif
                                    @if (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_teacher')
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Qualification</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->qualification ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Year of Experience</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{ $data->userAdditionalDetail->experience ?? 'N/A' }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="tab-pane fade Bank-Details view pt-3" id="Bank-Details">
                                    <h5 class="card-title">Bank Details</h5>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->bank_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank Holder Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->acc_holder_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank Account Number</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->acc_no ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank IFSC code</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->ifsc_code ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade Address-Details view pt-3" id="Address-Details">
                                    <h5 class="card-title">Address Details</h5>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Pin code</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->postal_code ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">State</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->state ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">City</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->city ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            {{ $data->userAdditionalDetail->address ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade Social_media view pt-3" id="Social_media">
                                    <h5 class="card-title">Social Media Links Details</h5>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Twitter</label>
                                        <div class="col-md-8 col-lg-9">
                                            @if (!empty($data->userAdditionalDetail->twitter))
                                                <a href="{{ $data->userAdditionalDetail->twitter }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    {{ $data->userAdditionalDetail->twitter }}
                                                </a>
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Instagram</label>
                                        <div class="col-md-8 col-lg-9">
                                            @if (!empty($data->userAdditionalDetail->instagram))
                                                <a href="{{ $data->userAdditionalDetail->instagram }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    {{ $data->userAdditionalDetail->instagram }}
                                                </a>
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Facebook</label>
                                        <div class="col-md-8 col-lg-9">
                                            @if (!empty($data->userAdditionalDetail->facebook))
                                                <a href="{{ $data->userAdditionalDetail->facebook }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    {{ $data->userAdditionalDetail->facebook }}
                                                </a>
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">LinkedIn</label>
                                        <div class="col-md-8 col-lg-9">
                                            @if (!empty($data->userAdditionalDetail->linkedin))
                                                <a href="{{ $data->userAdditionalDetail->linkedin }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    {{ $data->userAdditionalDetail->linkedin }}
                                                </a>
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>
    <!-- End #div -->
@endsection
