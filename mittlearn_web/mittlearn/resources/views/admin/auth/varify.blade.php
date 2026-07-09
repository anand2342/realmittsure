@include('admin.layouts.head-links')
<main>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                <!-- <img src="{{ asset(path: 'images/mittlearn-logo.svg') }}"> -->
                                <span class="d-none d-lg-block">Mittlearn</span>
                            </a>
                        </div><!-- End Logo -->
                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Verify Your Data</h5>
                                    <p class="text-center small">Choose Mail Link Or Mobile OTP</p>
                                </div>
                                <!-- Display a success message -->
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <!-- Display an error message -->
                                @if (session('error'))
                                    <span>
                                        <label class="error">{{ session('error') }}</label>
                                    </span>
                                @endif
                                <div class="mb-3">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="email-tab" data-bs-toggle="tab"
                                                href="#email" role="tab" aria-controls="email"
                                                aria-selected="true">Mail Link</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="otp-tab" data-bs-toggle="tab" href="#otp"
                                                role="tab" aria-controls="otp" aria-selected="false">Mobile OTP</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="email" role="tabpanel"
                                        aria-labelledby="email-tab">

                                        <form method="POST" action="{{ route('admin.reset-password.mail') }}"
                                            class="row g-3" novalidate>
                                            @csrf
                                            <div class="col-12">
                                                <label for="yourUsername" class="form-label">Enter your email
                                                    address:</label>
                                                <div class="input-group has-validation">
                                                    <input id="email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" required
                                                        autocomplete="email" autofocus>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100" type="submit">Send Password Reset
                                                    Link</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="otp" role="tabpanel" aria-labelledby="otp-tab">
                                        <form method="POST" action="{{ route('admin.reset-password.otp') }}"
                                            class="row g-3" novalidate>
                                            @csrf
                                            <div class="col-12">
                                                <label for="mobile" class="form-label">Enter your mobile
                                                    number:</label>
                                                <div class="input-group has-validation">
                                                    <input id="mobile" type="text"
                                                        class="form-control @error('mobile') is-invalid @enderror"
                                                        name="mobile" value="{{ old('mobile') }}" required
                                                        autocomplete="mobile" autofocus>
                                                    @error('mobile')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100" type="submit">Send OTP</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>

</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

@include('admin.layouts.footer-links')
