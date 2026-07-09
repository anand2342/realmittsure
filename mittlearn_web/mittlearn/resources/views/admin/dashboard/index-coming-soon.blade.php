@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">

                        <!-- Sales Card -->
                        <div class="col-xxl-12 col-md-12">
                            <div class="card info-card sales-card">

                                <div class="card-body">
                                    <h5 class="card-title">Dashboard Data Coming Soon...</h5>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->



                </div><!-- End Right side columns -->

            </div>
        </section>

    </div>

@section('javascript')
    <script src="{{ asset('admin/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/chart.js') }}/chart.umd.js') }}"></script>
    <script src="{{ asset('admin/vendor/echarts/echarts.min.js') }}"></script>
@endsection
@endsection
