@extends('admin.layouts.master')
@section('content')
    <div>
        <style>
            .course-chip {
                display: inline-block;
                border: 1px solid #00438C;
                border-radius: 8px;
                padding: 2px 5px;
                margin: 2px 2px;
            }
        </style>
        <div class="pagetitle">
            <h1>Courses Purchase Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Purchase Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">



                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <h5 class="card-title">Courses Purchase Report</h5>
                                </div>
                                <div class="col-sm-6 text-end">
                                    <div class="course-chip">
                                        Total <i class="bi bi-currency-rupee"></i>: &nbsp;{{ $totalAmountAll ?? 0 }}

                                    </div>
                                </div>
                            </div>
                            <hr class="fromdivider">


                            <div class="table-responsive tbleDiv ">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Total Amount</th>
                                            <th>User Name</th>
                                            <th>User Mobile</th>
                                            <th>TXN Id</th>
                                            <th>Payment Id</th>
                                            <th>Date</th>
                                            <th>Purchage Courses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datalist as $k => $val)
                                            <tr>
                                                <td>{{ $k + $datalist->firstItem() }}.</td>
                                                <td>{{ $val->total_amount ?? 'NA' }}</td>
                                                <td>{{ $val->userDetail->name }}</td>
                                                <td>{{ $val->userDetail->mobile_no }}</td>
                                                <td>{{ $val->txn_id ?? 'NA' }}</td>
                                                <td>{{ $val->payment_id ?? 'NA' }}</td>
                                                <td>{{ $val->created_at ? $val->created_at->format('d-m-Y') : 'NA' }}</td>
                                                <td>
                                                    @php
                                                        $courseIds = json_decode($val->cart, true); // converts JSON string to array
                                                        $courses = [];
                                                        if ($courseIds && count($courseIds)) {
                                                            $courses = \App\Models\Course::whereIn('id', $courseIds)
                                                                ->pluck('course_name')
                                                                ->toArray();
                                                        }
                                                    @endphp

                                                    @if (!empty($courses))
                                                        @foreach ($courses as $course)
                                                            <span class="course-chip">{{ $course }}</span>
                                                        @endforeach
                                                    @else
                                                        NA
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $datalist->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
