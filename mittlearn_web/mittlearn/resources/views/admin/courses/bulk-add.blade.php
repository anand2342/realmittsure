@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <div class="pagetitle">
                    <h1>Bulk Book/Course</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item">Book/Courses</li>
                            <li class="breadcrumb-item active">Bulk Book/Courses</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-0">Bulk Upload Book/Courses</h5>
                            <hr class="form-divider">

                            @livewire('courses-bulk-upload')

                            <!-- Bulk Upload Status/Feedback -->
                            @if (session()->has('errorMsg'))
                                <div class="alert alert-danger mt-3">
                                    {{ session('errorMsg')[0] }}
                                </div>
                            @elseif (session()->has('successMsg'))
                                <div class="alert alert-success mt-3">
                                    {{ session('successMsg') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
