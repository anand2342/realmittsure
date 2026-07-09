@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <div class="pagetitle">
            <h1>School User Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">School User</li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <section class="section profile">
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#additional-Details">
                                        Additional Details</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h5 class="card-title">Online Class Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Name</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Email</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->email ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Mobile No.</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->mobile_no ?? 'N/A' }}</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Class</div>
                                        @if ($data->userAdditionalDetail->role == 'school_teacher')
                                            <div class="col-lg-9 col-md-8">
                                                {{ $data->userAdditionalDetail->class_names ?? 'N/A' }}</div>
                                        @else
                                            <div class="col-lg-9 col-md-8">{{ $data->studentDetails->className->name ?? 'N/A' }}
                                            </div>
                                        @endif
                                    </div>
                                    @if ($data->userAdditionalDetail->role == 'school_teacher')
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Subject</div>
                                            <div class="col-lg-9 col-md-8">
                                                {{ $data->userAdditionalDetail->subject_names ?? 'N/A' }}</div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Gender</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->userAdditionalDetail->gender ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">DOB </div>
                                        <div class="col-lg-9 col-md-8">{{ \Carbon\Carbon::parse($data->userAdditionalDetail->dob)->format('d-m-Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">End Time</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->end_time ?? 'N/A' }}
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade show additional-Details" id="additional-Details">
                                    <h5 class="card-title">Additional Details</h5>

                                </div>


                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>
@endsection
