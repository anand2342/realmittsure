@extends('frontend.layouts.master')

@section('content')
    <div class="shoppingMain">
        <div class="offeringsBanner">
            <div class="lottieSquare">
                <lottie-player src="{{ asset('frontend/images/square-shape-loading.json') }}" autoPlay loop
                    style="width: 120px; height: 120px;"></lottie-player>
            </div>
            <img src="{{ asset('frontend/images/blue-square.svg') }}" alt="" width="80" class="squareImg">
            <div class="container">
                <div class="bannerTxt">
                    <h1>Download <b> Mittlearn App</b></h1>
                    <p>Get the best learning experience on your mobile device. Download the app from your preferred store
                        below.</p>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="dashboardMain p-4">
            <div class="row">
                <div class="col-lg-12 col-md-12 pe-md-1 mb-3 mb-lg-0">
                    <div class="cardBox">
                        <div class="row justify-content-center">
                            <!-- Play Store Section -->
                            <div class="col-md-6 mb-4 mt-2">

                                <div class="card shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="headingBx d-block d-md-flex">
                                            <h4 class="fs-5 mb-2 mb-md-0">{{ $setting['play_heading'] ?? 'N/A' }}</h4>
                                            <div class="d-flex align-items-center gap-2">
                                                @if (isset($setting['play_logo']))
                                                    <img src="{{ Storage::url('uploads/logo/' . $setting['play_logo']) }}"
                                                        alt="Google Play" style="height: 50px;" class="mb-3">
                                                @endif
                                            </div>
                                        </div>

                                        <p class="mt-2">{{ $setting['play_description'] ?? 'N/A' }}</p>
                                        @if (isset($setting['play_image']))
                                            <div class="mb-3">
                                                <img src="{{ Storage::url('uploads/logo/' . $setting['play_image']) }}"
                                                    alt="App Store QR Code" class="img-fluid" style="max-height: 150px;">
                                            </div>
                                        @endif

                                        <a href="{{ $setting['play_link'] ?? 'N/A' }}" target="_blank"
                                            class="btn btn-success px-4">
                                            Download on Play Store
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- App Store Section -->
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="headingBx d-block d-md-flex">
                                            <h4 class="fs-5 mb-2 mb-md-0">{{ $setting['app_heading'] ?? 'N/A' }}</h4>
                                            <div class="d-flex align-items-center gap-2">
                                                @if (isset($setting['app_logo']))
                                                    <img src="{{ Storage::url('uploads/logo/' . $setting['app_logo']) }}"
                                                        alt="Google Play" style="height: 50px;" class="mb-3">
                                                @endif
                                            </div>
                                        </div>
                                        <p class="mt-2">{{ $setting['app_description'] ?? 'N/A' }}</p>
                                        @if (isset($setting['app_image']))
                                            <div class="mb-3">
                                                <img src="{{ Storage::url('uploads/logo/' . $setting['app_image']) }}"
                                                    alt="App Store QR Code" class="img-fluid" style="max-height: 150px;">
                                            </div>
                                        @endif

                                        <a href="{{ $setting['app_link'] ?? 'N/A' }}" target="_blank"
                                            class="btn btn-primary px-4">
                                            Download on App Store
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
