@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">
        <div class="d-flex flex-wrap">
            <div class="leftpanel">
                <div class="helloSection">
                    <div class=" pe-md-5">
                        <h2><b>Download</b> Mittlearn App</h2>
                        <p> Get the best learning experience on your mobile device. Download the app from your preferred
                            store
                            below.</p>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        @php
                            $student = session('student_class');
                        @endphp
                        @if ($student)
                            <span class="badge">{{ $student['class'] }}</span>
                        @endif

                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    </div>
                </div>
                <div class="rightpanel">
                    @include('mittBunny.layouts.profile-header')
                </div>
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
