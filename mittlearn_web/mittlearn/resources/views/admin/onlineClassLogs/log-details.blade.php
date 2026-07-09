@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <div class="pagetitle">
            <h1>Online Class Details</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item">Online Class</li>
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
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#additional-Details"> Additional Details</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                    <h5 class="card-title">Online Class Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Title</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->title ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Status</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->status ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Instructor Name</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->instructor->name ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Class</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->class->name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Subject</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->subject->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Class Date</div>
                                        <div class="col-lg-9 col-md-8">{{ $data->class_date ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Start Time </div>
                                        <div class="col-lg-9 col-md-8">{{ $data->start_time ?? 'N/A' }}
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
